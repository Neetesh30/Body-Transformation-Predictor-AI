<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;

// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Str;

use TCPDF;


class DoctorController extends Controller
{
    // Dummy data for demonstration (replace with DB later)
    private $data = [
        'Maharashtra' => [
            'Mumbai' => ['Dr. A', 'Dr. B'],
            'Pune' => ['Dr. C']
        ],
        'Karnataka' => [
            'Bangalore' => ['Dr. D', 'Dr. E'],
            'Mysore' => ['Dr. F']
        ],
        'Delhi' => [
            'New Delhi' => ['Dr. G', 'Dr. H']
        ],
    ];

    private $videos = [
        'Dr. A' => 'glenmark/videos/dr_a.mp4',
        'Dr. B' => 'glenmark/videos/dr_b.mp4',
        'Dr. C' => 'glenmark/videos/dr_c.mp4',
        'Dr. D' => 'glenmark/videos/dr_d.mp4',
        // 'Dr. E' => 'glenmark/videos/dr_e.mp4',
        // 'Dr. F' => 'glenmark/videos/dr_f.mp4',
        // 'Dr. G' => 'glenmark/videos/dr_g.mp4',
        // 'Dr. H' => 'glenmark/videos/dr_h.mp4',
    ];

    // Load page
    public function index()
    {
        return view('find-doctor'); // no need to send $states, $cities, $doctors
    }

    // Get all states dynamically
    public function getStates()
    {
        $states = array_keys($this->data);
        return response()->json($states);
    }

    // Get cities for selected state
    public function getCities(Request $request)
    {
        $state = $request->state;
        $cities = isset($this->data[$state]) ? array_keys($this->data[$state]) : [];
        return response()->json($cities);
    }

    // Get doctors for selected city
    public function getDoctors(Request $request)
    {
        $state = $request->state;
        $city = $request->city;
        $doctors = isset($this->data[$state][$city]) ? $this->data[$state][$city] : [];
        return response()->json($doctors);
    }

    // Search doctor (AJAX)
    public function search(Request $request)
    {
        $state = $request->state;
        $city = $request->city;
        $doctor = $request->doctor;

        return "Searching for <strong>$doctor</strong> in <strong>$city, $state</strong>";
    }

    // Add a method to get video for a doctor
    public function getDoctorVideo(Request $request)
    {
        $doctor = $request->doctor;
        $video = $this->videos[$doctor] ?? null;
        return response()->json(['video' => $video]);
    }

    public function store__old(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'diet' => 'required|string',
            'lifestyle' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        // BMI Calculation
        $heightInMeters = $request->height / 100;
        $bmi = $request->weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 1);

        // Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bmi_images', 'public');
        }

        // Determine category
        if ($bmi < 18.5) {
            $category = "Underweight";
        } elseif ($bmi < 24.9) {
            $category = "Normal weight";
        } elseif ($bmi < 29.9) {
            $category = "Overweight";
        } else {
            $category = "Obese";
        }

        return response()->json([
            'bmi' => $bmi,
            'category' => $category,
            'image' => $imagePath,
        ]);
    }

    public function store__V1(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'height_feet' => 'required|numeric|min:1',
            'height_inches' => 'required|numeric|min:0|max:11',            
            'weight' => 'required|numeric',
            'diet' => 'required|string',
            'lifestyle' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        // Convert height to centimeters
        $heightInCm = ($request->height_feet * 30.48) + ($request->height_inches * 2.54);
        $heightInMeters = $heightInCm / 100;

        // BMI Calculation
        $bmi = $request->weight / ($heightInMeters * $heightInMeters);
        $bmi = round($bmi, 1);

        // Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bmi_images', 'public');
        }

       // Prepare DALL·E prompt
        $prompt = "Generate a realistic illustration of a {$request->gender} who 
        follows a {$request->diet} diet, and has a {$request->lifestyle} lifestyle. 
        The image should look healthy, vibrant, and motivational.";

        // Generate AI Image
        if ($imagePath) {
            // Variation of user-uploaded image
            $imageResult = OpenAI::images()->create([
                'model' => 'gpt-image-1',
                'image' => fopen(storage_path('app/public/' . $imagePath), 'r'),
                'n' => 1,
                'size' => '512x512',
                'prompt' => $prompt,
            ]);
        } else {
            // Generate from scratch using prompt
            $imageResult = OpenAI::images()->create([
                'model' => 'gpt-image-1',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '512x512',
            ]);
        }


        // Decode the generated image
        $generatedImageBase64 = $imageResult->data[0]->b64_json;
        $generatedImage = base64_decode($generatedImageBase64);
        $aiImagePath = 'public/ai_images/' . uniqid() . '.png';
        Storage::put($aiImagePath, $generatedImage);
        

        // Determine category
        if ($bmi < 18.5) {
            $category = "Underweight";
            
        } elseif ($bmi < 24.9) {
            $category = "Normal weight";
        } elseif ($bmi < 29.9) {
            $category = "Overweight";
        } else {
            $category = "Obese";
        }

        return response()->json([
            'bmi' => $bmi,
            'category' => $category,
            'image' => $imagePath,
        ]);
    }

  
     public function store__V2_Working_AI_Image(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'height_feet' => 'required|numeric|min:1',
            'height_inches' => 'required|numeric|min:0|max:11',            
            'weight' => 'required|numeric',
            'diet' => 'required|string',
            'lifestyle' => 'required|string',
            'image' => 'nullable|image|max:5120' // 5 MB
        ]);

        // Convert height to meters
        $heightInCm = ($request->height_feet * 30.48) + ($request->height_inches * 2.54);
        $heightInMeters = $heightInCm / 100;

        // BMI Calculation
        $bmi = round($request->weight / ($heightInMeters * $heightInMeters), 1);

        // Determine category
        if ($bmi < 18.5) {
            $category = "Underweight";
        } elseif ($bmi < 24.9) {
            $category = "Normal weight";
        } elseif ($bmi < 29.9) {
            $category = "Overweight";
        } else {
            $category = "Obese";
        }

        $uploadedImagePath = null;
        $aiImagePath = null;

        if ($request->hasFile('image')) {
            // Store uploaded image
            $uploadedImagePath = $request->file('image')->store('bmi_images', 'public');
            $uploadedImageFullPath = storage_path('app/public/' . $uploadedImagePath);

            // Prepare prompt using uploaded image info
            $prompt = "Generate a healthy, vibrant, motivational illustration of a {$request->gender} who follows a {$request->diet} diet and has a {$request->lifestyle} lifestyle. The person looks similar to the uploaded image.";

          $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])
            ->attach('image', fopen($uploadedImageFullPath, 'r'), 'image.png')
            ->post('https://api.openai.com/v1/images/edits', [
                'model' => 'gpt-image-1',
                'prompt' => 'Make the person look more fit and healthy while keeping the same background.',
                'size' => '1024x1024',
                'quality' => 'low',
                'output_format'=> 'jpeg',           
                ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to generate AI image',
                    'details' => $response->body()
                ], 500);
            }

            $data = $response->json();

            if (!isset($data['data'][0]['b64_json'])) {
                return response()->json([
                    'error' => 'AI image generation failed',
                    'details' => $data,
                ], 500);
            }

            // Decode and save the image locally
            $base64Image = $data['data'][0]['b64_json'];
            $aiImageContents = base64_decode($base64Image);
            $aiImagePath = 'ai_images/' . uniqid() . '.png';
            Storage::disk('public')->put($aiImagePath, $aiImageContents);

        }

        return response()->json([
            'bmi' => $bmi,
            'category' => $category,
            'uploaded_image' => $uploadedImagePath ? Storage::url($uploadedImagePath) : null,
            'ai_image' => $aiImagePath ? Storage::url($aiImagePath) : null,
        ]);
    }
     public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'height_feet' => 'required|numeric|min:1',
            'height_inches' => 'required|numeric|min:0|max:11',            
            'weight' => 'required|numeric',
            'diet' => 'required|string',
            'lifestyle' => 'required|string',
            'image' => 'nullable|image|max:5120' // 5 MB
        ]);

        // Convert height to meters
        $heightInCm = ($request->height_feet * 30.48) + ($request->height_inches * 2.54);
        $heightInMeters = $heightInCm / 100;

        // BMI Calculation
        $bmi = round($request->weight / ($heightInMeters * $heightInMeters), 1);

        $category = '';

        // Determine category
        if ($bmi < 18.5) {
            $category = "Underweight";
        } elseif ($bmi < 24.9) {
            $category = "Normal weight";
        } elseif ($bmi < 29.9) {
            $category = "Overweight";
        } else {
            $category = "Obese";
        }

        $uploadedImagePath = null;
        $aiImagePath = null;
        $diet_plan_html = null;

        if ($request->hasFile('image')) {
            // Store uploaded image
                $uploadedImage = $request->file('image');
                $uploadedImageName = uniqid('bmi_') . '.jpeg'; // filename only
                $uploadedImagePath = 'bmi_images/' . $uploadedImageName;
                $uploadedImageFullPath = storage_path('app/public/' . $uploadedImagePath);

                // Get original image info
                $imageInfo = getimagesize($uploadedImage->getRealPath());
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                $mime = $imageInfo['mime'];

                // Create image resource based on type
                switch ($mime) {
                    case 'image/jpeg':
                        $img = imagecreatefromjpeg($uploadedImage->getRealPath());
                        break;
                    case 'image/png':
                        $img = imagecreatefrompng($uploadedImage->getRealPath());
                        break;
                    case 'image/gif':
                        $img = imagecreatefromgif($uploadedImage->getRealPath());
                        break;
                    default:
                        throw new \Exception('Unsupported image type');
                }

                // Create true color thumbnail
                $thumbWidth = 300;
                $thumbHeight = 300;
                $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

                // Fill background with white (removes transparency)
                $white = imagecolorallocate($thumb, 255, 255, 255);
                imagefill($thumb, 0, 0, $white);

                // Resize
                imagecopyresampled($thumb, $img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

                // Save as JPEG
                imagejpeg($thumb, $uploadedImageFullPath, 100);

                // Free memory
                imagedestroy($img);
                imagedestroy($thumb);

            // Prepare prompt using uploaded image info
            $prompt = "Generate a healthy, vibrant, motivational illustration of a {$request->gender} who follows a {$request->diet} diet and has a {$request->lifestyle} lifestyle. The person looks similar to the uploaded image.";

          $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])
            ->attach('image', fopen($uploadedImageFullPath, 'r'), 'image.png')
            ->post('https://api.openai.com/v1/images/edits', [
                'model' => 'gpt-image-1',
                'prompt' => 'Make the person look more fit and healthy while keeping the same background. The person looks similar to the uploaded image.',
                'size' => '1024x1024',
                'quality' => 'low',
                'output_format'=> 'jpeg',           
                ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Failed to generate AI image',
                    'details' => $response->body()
                ], 500);
            }

            $data = $response->json();


            // Decode and save the image locally
            $base64Image = $data['data'][0]['b64_json'];
            $aiImageContents = base64_decode($base64Image);
            $aiImagePath = 'ai_images/ai_'.$request->phone.'_' . uniqid() . '.jpeg';
            $ai_image_success = Storage::disk('public')->put($aiImagePath, $aiImageContents);

            if ($ai_image_success) {
                 if($category != 'Normal weight'){
                    // 🧩 Step 2: Generate custom plan using ChatGPT
                        $chatPrompt = "
                        Generate a detailed 30-day personalized diet and exercise plan for:
                        Name: {$request->name}
                        Age: {$request->age}
                        Gender: {$request->gender}
                        BMI: {$bmi} ({$category})
                        Diet Type: {$request->diet}
                        Lifestyle: {$request->lifestyle}
                        
                        Make it motivational, simple, and specific to improve BMI naturally. Include:
                        - Morning routine
                        - Diet plan (breakfast, lunch, dinner)
                        - Exercise recommendations
                        ";

                        $planResponse = Http::withHeaders([
                            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                            'Content-Type' => 'application/json',
                        ])->post('https://api.openai.com/v1/chat/completions', [
                            'model' => 'gpt-4o-mini',
                            'messages' => [
                                ['role' => 'system', 'content' => 'You are a certified nutrition and fitness expert.'],
                                ['role' => 'user', 'content' => $chatPrompt]
                            ],
                            'temperature' => 0.8,
                        ]);

                        if ($planResponse->successful()) {
                            $dietPlan = $planResponse['choices'][0]['message']['content'] ?? 'Plan not generated.';

                            $diet_plan_html = Str::of($dietPlan)
                            ->replaceMatches('/^### (.*)$/m', '<h3>$1</h3>')
                            ->replaceMatches('/^#### (.*)$/m', '<h4>$1</h4>')
                            ->nl2br();
                        }
                }
                else{
                    // 🧩 Step 2: Generate custom plan using ChatGPT
                        
                        $planResponse = "'Great job! Your BMI is normal — maintain your healthy habits!'";

                        if ($planResponse) {
                            $dietPlan = $planResponse;
                        }
                }   
            }


        }else{

            if($category != 'Normal weight'){
                // 🧩 Step 2: Generate custom plan using ChatGPT
                    $chatPrompt = "
                    Generate a detailed 30-day personalized diet and exercise plan for:
                    Name: {$request->name}
                    Age: {$request->age}
                    Gender: {$request->gender}
                    BMI: {$bmi} ({$category})
                    Diet Type: {$request->diet}
                    Lifestyle: {$request->lifestyle}
                    
                    Make it motivational, simple, and specific to improve BMI naturally. Include:
                    - Morning routine
                    - Diet plan (breakfast, lunch, dinner)
                    - Exercise recommendations
                    ";

                    $planResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a certified nutrition and fitness expert.'],
                            ['role' => 'user', 'content' => $chatPrompt]
                        ],
                        'temperature' => 0.8,
                    ]);

                    if ($planResponse->successful()) {
                        $dietPlan = $planResponse['choices'][0]['message']['content'] ?? 'Plan not generated.';
                    }
            }
            else{
                // 🧩 Step 2: Generate custom plan using ChatGPT
                    
                    $planResponse = "'Great job! Your BMI is normal — maintain your healthy habits!'";

                    if ($planResponse) {
                        $dietPlan = $planResponse;
                    }
            }

        }

        // Get only the filenames
        $uploadedImageName = $uploadedImagePath ? basename($uploadedImagePath) : null;
        $aiImageName = $aiImagePath ? basename($aiImagePath) : null;
        

        $pdfData = [
            'name' => $request->name,
            'age' => $request->age,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'height' => "{$request->height_feet}' {$request->height_inches}\"",
            'weight' => "{$request->weight} kg",
            'bmi' => $bmi,
            'category' => $category,
            'diet' => $request->diet,
            'lifestyle' => $request->lifestyle,
            // 'uploaded_image' => $uploadedImagePath ? Storage::url($uploadedImagePath) : null,
            // 'ai_image' => $aiImagePath ? Storage::url($aiImagePath) : null,
            'uploaded_image' => $uploadedImageName,
            'ai_image' => $aiImageName,
            'diet_plan' => $dietPlan,
        ];

        // Create and store PDF
     
         // 1️⃣ Render Blade view to HTML using TCPDF
        $html = View::make('pdf.bmi_report', $pdfData)->render();

        // 2️⃣ Initialize TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('YourAppName');
        $pdf->SetAuthor('YourAppName');
        $pdf->SetTitle('BMI Report');
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 20);
        $pdf->AddPage();

        // 3️⃣ Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // 4️⃣ Save PDF to storage
        $pdfFileName = 'bmi_reports/' . $request->phone . '_' . uniqid('bmi_report_') . '.pdf';
        $pdf->Output(storage_path('app/public/' . $pdfFileName), 'F');

        $pdfUrl = Storage::url($pdfFileName);

        return response()->json([
            'bmi' => $bmi,
            'category' => $category,
            'uploaded_image' => $uploadedImagePath ? Storage::url($uploadedImagePath) : null,
            'ai_image' => $aiImagePath ? Storage::url($aiImagePath) : null,
            'diet_plan' => $diet_plan_html,
            'pdf_report' => $pdfUrl,
        ]);
    }

    
}
