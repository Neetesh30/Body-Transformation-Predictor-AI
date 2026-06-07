<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BMI Report - {{ $name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 30px;
            color: #333;
        }
        h1, h2, h3 {
            text-align: center;
            color: #e67e22;
        }
        .section {
            margin-top: 25px;
            border-top: 2px solid #e67e22;
            padding-top: 15px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .images {
            text-align: center;
            margin-top: 20px;
        }
        .images img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            margin: 10px;
            border-radius: 10px;
        }
        .plan {
            white-space: pre-wrap;
            line-height: 1.5;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Personalized BMI Report</h1>
    <h3>{{ $name }}</h3>

    <div class="section">
        <h2>Basic Information</h2>
        <table class="details-table">
            <tr><td><strong>Age:</strong></td><td>{{ $age }}</td></tr>
            <tr><td><strong>Gender:</strong></td><td>{{ $gender }}</td></tr>
            <tr><td><strong>Phone:</strong></td><td>{{ $phone }}</td></tr>
            <tr><td><strong>Height:</strong></td><td>{{ $height }}</td></tr>
            <tr><td><strong>Weight:</strong></td><td>{{ $weight }}</td></tr>
            <tr><td><strong>Diet Type:</strong></td><td>{{ $diet }}</td></tr>
            <tr><td><strong>Lifestyle:</strong></td><td>{{ $lifestyle }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>BMI Result</h2>
        <p><strong>BMI:</strong> {{ $bmi }}</p>
        <p><strong>Category:</strong> {{ $category }}</p>
    </div>


        @if($uploaded_image || $ai_image)
        <div class="section">
            <h2 style="text-align:center; font-size:18px; color:#2c3e50;">Before & After — If You Follow the Plan</h2>
            <table width="100%" cellpadding="20">
                <tr>
                    @if($uploaded_image)
                    <td width="50%" align="center">
                        <p style="font-size:14px; color:#555;"><strong>Before : </strong></p>
                        <img src="{{ public_path('storage/bmi_images/' . $uploaded_image) }}" 
                            width="200" height="200" alt="Before Image">
                    </td>
                    @endif

                    @if($ai_image)
                    <td width="50%" align="center">
                        <p style="font-size:14px; color:#555;"><strong>After</strong></p>
                        {{-- <img src="{{ public_path('storage/ai_images/ai_9768300731_68e8cd220de50.png') }}" 
                                alt="AI Generated Image" width="200" height="200">  --}}

                        <img src="{{ public_path('storage/ai_images/' . $ai_image) }}" 
                            width="200" 
                            height="200" 
                            alt="After Image">
                    </td>
                    @endif
                </tr>
            </table>

            <p style="font-size:10px; color:#777; text-align:center; margin-top:5px;">
                *This is an AI-generated transformation visualization — follow the plan consistently for visible results.
            </p>
        </div>
        @endif


    {{-- <div class="section images" style="text-align: center; margin-top: 30px;">
            <h2 style="font-size: 20px; color: #2c3e50; text-align:center; margin-bottom: 10px;">
                Before & After — If You Follow the Plan
            </h2>

            <div style="display: flex; justify-content: center; align-items: center; gap: 40px; margin-top: 20px;">
                <div style="text-align: center;">
                    <h4 style="margin-bottom: 8px; font-size: 16px; color: #555;">Before</h4>
                        <img src="{{ public_path('storage/ai_images/ai_9768300731_68e8cd220de50.png') }}" 
                        alt="AI Generated Image" width="200" height="200"> 
                </div>
               
                <div style="text-align: center;">
                    <h4 style="margin-bottom: 8px; font-size: 16px; color: #555;">After</h4>
                    <img src="{{ public_path('storage/ai_images/ai_9768300731_68e8cd220de50.png') }}" 
                        alt="AI Generated Image" width="200" height="200">                
                </div>
               
            </div>

            <p style="font-size: 12px; color: #777; margin-top: 10px;">
                *This is an AI-generated transformation visualization — follow the plan consistently for visible results.
            </p>
        </div> --}}

    <div class="section">
        <h2>Personalized Plan</h2>
        <div class="plan" style="white-space: pre-wrap; line-height:1.5; font-size:12px;">
            <div class="plan">{!! $diet_plan !!}</div>
        </div>
    </div>
</body>
</html>
