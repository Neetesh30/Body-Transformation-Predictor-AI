@extends('layouts.guestapp')

@section('content')
<!-- Loading Modal -->
<!-- Fullscreen BMI Loading Modal -->
<div id="bmiLoadingModal" class="bmi-loading-modal justify-content-center align-items-center">
    <div class="bmi-loading-content text-center">
        <lottie-player 
            src="{{ asset('glenmark/animation/5BmzrKr7O7.json') }}"  
            background="transparent"  
            speed="1"  
            style="width: 200px; height: 200px;"  
            loop  
            autoplay>
        </lottie-player>

        <p class="bmi-pulse-text mt-3">Your BMI is getting calculated, please wait...</p>
    </div>
</div>

<style>
/* Modal Overlay */
/* Make the modal cover the whole screen */
.bmi-loading-modal {
    display: none; /* ✅ Hide on page load */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1050;
}


/* Optional: style content */
.bmi-loading-content {
    background: #fff; /* optional white box */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.bmi-loading-content.text-center {
    text-align: -webkit-center !important;
}

/* Pulse text */
.bmi-pulse-text {
    animation: pulse 1.5s infinite;
    font-weight: bold;
    color: #ff6600;
}

@keyframes pulse {
    0% { opacity: 0.3; }
    50% { opacity: 1; }
    100% { opacity: 0.3; }
}

.bmi-countdown-text {
    color: #007bff;
    font-size: 1.1rem;
    animation: fadeInOut 0.8s ease-in-out infinite;
}

@keyframes fadeInOut {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}

</style>

<div class="container-fluid p-5">
    <div class="row">
        <div class="col-12">
            <div class="banner">
                <img src="{{ asset('glenmark/images/diabetesday.jpeg') }}" class="img-fluid w-100" alt="Diabetes Day">
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h2 class="mb-4">Find a Doctor</h2>

            <form id="doctorForm" method="POST" action="{{ route('search.doctor') }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" name="state" required>
                            <option value="">Select State</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="city" name="city" required>
                            <option value="">Select City</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="doctor" class="form-label">Doctor</label>
                        <select class="form-select" id="doctor" name="doctor" required>
                            <option value="">Select Doctor</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Search Doctor</button>
                </div>
            </form>

            <div class="mt-4" id="result"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mt-4" id="doctorVideoContainer" style="display:none;">
                <video id="doctorVideo" width="100%" controls autoplay muted>
                    <source src="" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>

                <div id="noVideoMessage" class="alert alert-warning mt-2" style="display:none;">
                    Sorry, No video found for this doctor.
                </div>
            </div>

            <div class="mt-4 text-center" id="bmiButtonContainer" style="display:none;">
                <button class="btn btn-success" id="checkBmiBtn">Check Your BMI Now</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mt-5" id="bmiFormSection" style="display:none;">
                <h3>Calculate Your BMI</h3>
                 <form id="bmiForm" method="post" action="{{ route('calculate.bmi') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" name="age" id="age" required>
                        </div>
                        <div class="col-md-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" name="gender" id="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" required>
                            <small>Enter your whats app no</small>
                        </div>

                        <div class="col-md-3">
                            <label for="height_feet" class="form-label">Height (feet)</label>
                            <input type="number" class="form-control" name="height_feet" id="height_feet" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label for="height_inches" class="form-label">Height (inches)</label>
                            <input type="number" class="form-control" name="height_inches" id="height_inches" min="0" max="11" required>
                        </div>


                        <div class="col-md-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" name="weight" id="weight" required>
                        </div>

                        <div class="col-md-6">
                            <label for="diet" class="form-label">Food Diet</label>
                            <select class="form-select" name="diet" id="diet" required>
                                <option value="">Select Diet</option>
                                <option value="Veg">Veg</option>
                                <option value="Nonveg">Nonveg</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="lifestyle" class="form-label">Current Lifestyle</label>
                            <select class="form-select" name="lifestyle" id="lifestyle" required>
                                <option value="">Select Lifestyle / Occupation</option>
                                <option value="Office Job">Office Job / Desk Job</option>
                                <option value="Remote Work">Remote Work / Work from Home</option>
                                <option value="Student">Student</option>
                                <option value="Retired">Retired</option>
                                <option value="Healthcare Worker">Healthcare Worker</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Delivery Worker">Delivery / Field Worker</option>
                                <option value="Construction">Construction / Labor</option>
                                <option value="Sportsman">Sportsman / Athlete</option>
                                <option value="Gym Trainer">Gym Trainer / Fitness Coach</option>
                                <option value="Business Owner">Business Owner / Entrepreneur</option>
                                <option value="Freelancer">Freelancer / Consultant</option>
                                <option value="Homemaker">Homemaker / Stay-at-Home</option>
                                <option value="Military">Military / Defense Personnel</option>
                                <option value="Driver">Driver / Transport Worker</option>
                                <option value="Other">Other</option>
                            </select>

                        </div>

                        <div class="col-md-6">
                            <!-- Label -->
                            <label class="form-label d-block">Upload your current photo</label>

                            <!-- Image Preview -->
                            <div class="mb-2 position-relative d-inline-block">
                                <img id="imagePreview"
                                    src="{{ asset('glenmark/images/dummy-avatar.jpg') }}"
                                    alt="Upload Image"
                                    class="img-thumbnail"
                                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 1px solid #ccc; cursor: pointer;">
                            
                                    <!-- Remove Icon -->
                                <span id="removeImage" 
                                    style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; 
                                            width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; 
                                            cursor: pointer; font-weight: bold; display: none;">×</span>
                            
                            </div>

                            <!-- Hidden File Input -->
                            <input type="file" class="form-control d-none" name="image" id="image" accept="image/*">

                            <!-- Browse Button -->
                            <button type="button" class="d-block btn btn-secondary btn-sm" id="browseBtn">Browse</button>
                        </div>

                        <!-- T&C Checkbox -->
                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="tncCheckbox" checked onclick="return false;">
                                <label class="form-check-label" for="tncCheckbox" style="cursor:pointer;">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#tncModal"><u>Terms & Conditions</u></a>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Submit BMI</button>
                        </div>
                    </div>
                </form>

                <!-- T&C Modal -->
                <div class="modal fade" id="tncModal" tabindex="-1" aria-labelledby="tncModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tncModalLabel">Terms & Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>This is dummy content for Terms & Conditions. You can replace this text with your actual T&C content.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel risus vitae lorem facilisis.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>

                <!-- BMI Result Section -->
                <div class="mt-4" id="bmiResultContainer" style="display:none;">
                    <h5>Your BMI Result:</h5>
                    <div id="bmiResult" class="alert text-center fw-bold" role="alert"></div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Make sure jQuery is loaded -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    // Load states on page load
    $.ajax({
        url: "{{ url('/get-states') }}",
        type: "POST",
        data: {},
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(data) {
            $('#state').html('<option value="">Select State</option>');
            $.each(data, function(i, state){
                $('#state').append('<option value="'+state+'">'+state+'</option>');
            });
        }
    });

    // On state change -> get cities
    $('#state').change(function(){
        var state = $(this).val();
        $('#city').html('<option>Loading...</option>');
        $('#doctor').html('<option value="">Select Doctor</option>');

        $.ajax({
            url: "{{ url('/get-cities') }}",
            type: "POST",
            data: { state: state },
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(data){
                $('#city').html('<option value="">Select City</option>');
                $.each(data, function(i, city){
                    $('#city').append('<option value="'+city+'">'+city+'</option>');
                });
            }
        });
    });

    // On city change -> get doctors
    $('#city').change(function(){
        var state = $('#state').val();
        var city = $(this).val();
        $('#doctor').html('<option>Loading...</option>');

        $.ajax({
            url: "{{ url('/get-doctors') }}",
            type: "POST",
            data: { state: state, city: city },
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(data){
                $('#doctor').html('<option value="">Select Doctor</option>');
                $.each(data, function(i, doctor){
                    $('#doctor').append('<option value="'+doctor+'">'+doctor+'</option>');
                });
            }
        });
    });

    // Form submit
   $('#doctorForm').submit(function (e) {
    e.preventDefault();

    var state = $('#state').val();
    var city = $('#city').val();
    var doctor = $('#doctor').val();

    $.ajax({
        url: "{{ route('search.doctor') }}",
        type: "POST",
        data: { state: state, city: city, doctor: doctor },
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function (data) {
            $('#result').html('<div class="alert alert-success">' + data + '</div>');

            $.ajax({
                url: "{{ url('/get-doctor-video') }}",
                type: "POST",
                data: { doctor: doctor },
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function (res) {

                    if (res.video) {
                        // Set video
                        $('#doctorVideo source').attr('src', '{{ asset('') }}' + res.video);
                        $('#doctorVideo')[0].load();
                        $('#doctorVideo')[0].play();
                        $('#doctorVideoContainer').show();
                        $('#noVideoMessage').hide();

                        // Initially disable BMI button
                        $('#bmiButtonContainer').fadeIn();
                        $('#checkBmiBtn').prop('disabled', true)
                                         .text('Please wait... (15s)');
                                         
                        $('#doctorVideo').removeAttr('controls'); // 🚫 Disable controls

                        // Scroll to video container
                        $('html, body').animate({
                            scrollTop: $('#doctorVideoContainer').offset().top - 0
                        }, 800);

                        // ✅ After 15 seconds, enable BMI button
                        
                        let secondsLeft = 15;
                        $('#checkBmiBtn').text('Please wait... (' + secondsLeft + 's)');

                        let countdown = setInterval(function() {
                            secondsLeft--;
                            $('#checkBmiBtn').text('Please wait... (' + secondsLeft + 's)');
                            if (secondsLeft <= 0) clearInterval(countdown);
                        }, 1000);
                        setTimeout(function () {
                            $('#checkBmiBtn').prop('disabled', false)
                                             .removeClass('btn-secondary')
                                             .addClass('btn-success')
                                             .text('Check Your BMI Now');
                            $('#doctorVideo').attr('controls', true); // ✅ Re-enable controls


                            // Optionally pause or hide video
                            // $('#doctorVideo')[0].pause();
                            // $('#doctorVideoContainer').fadeOut(); // uncomment to hide
                        }, 15000);

                        // ✅ Also enable when video ends before 15s
                        $('#doctorVideo')[0].onended = function () {
                            $('#checkBmiBtn').prop('disabled', false)
                                             .removeClass('btn-secondary')
                                             .addClass('btn-success')
                                             .text('Check Your BMI Now');
                        $('#doctorVideo').attr('controls', true); // ✅ Restore controls early

                        };

                        // When BMI button clicked
                        $('#checkBmiBtn').off('click').on('click', function () {
                            if (!$(this).prop('disabled')) {
                                $('#bmiFormSection').slideDown(800, function () {
                                    $('html, body').animate({
                                        scrollTop: $('#bmiFormSection').offset().top
                                    }, 800);
                                });
                            }
                            $('#doctorVideo')[0].pause();
                        });

                    } else {
                        // ❌ No video found — directly show BMI form
                        $('#doctorVideoContainer').hide();
                        $('#noVideoMessage').show();
                        $('#bmiButtonContainer').hide();

                        $('#bmiFormSection').fadeIn(800, function () {
                            $('html, body').animate({
                                scrollTop: $('#bmiFormSection').offset().top - 20
                            }, 800);
                        });
                    }
                },
                error: function () {
                    $('#result').html('<div class="alert alert-danger">Error fetching doctor video!</div>');
                }
            });
        },
        error: function () {
            $('#result').html('<div class="alert alert-danger">Something went wrong!</div>');
        }
    });
});


});
</script>

<script>
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const browseBtn = document.getElementById('browseBtn');
    const removeImage = document.getElementById('removeImage');
    const defaultImage = "{{ asset('glenmark/images/dummy-avatar.jpg') }}";

    // Click on image or browse button opens file input
    imagePreview.addEventListener('click', () => imageInput.click());
    browseBtn.addEventListener('click', () => imageInput.click());

    // Show preview when file selected
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                removeImage.style.display = 'flex'; // Show remove icon
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = defaultImage;
            removeImage.style.display = 'none'; // Hide remove icon
        }
    });

    // Remove image on clicking the cross icon
    removeImage.addEventListener('click', function () {
        imagePreview.src = defaultImage;
        imageInput.value = ''; // Clear file input
        removeImage.style.display = 'none'; // Hide remove icon
    });
</script>

<script>
    $(document).ready(function() {

    $('#bmiForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        // Show loading modal
        $('#bmiLoadingModal').addClass('d-flex').fadeIn();

        $.ajax({
            url: "{{ route('calculate.bmi') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                // --- Countdown animation before hiding modal ---
                let countdown = 3;
                let messageEl = $('<p class="bmi-countdown-text mt-2 fw-bold"></p>').appendTo('.bmi-loading-content');

                function updateCountdown() {
                    messageEl.text(`Calculation complete in ${countdown}...`);
                    countdown--;

                    if (countdown >= 0) {
                        setTimeout(updateCountdown, 800);
                    } else {
                        // Fade out modal smoothly
                        $('#bmiLoadingModal').fadeOut(800, function() {
                            $('#bmiLoadingModal').removeClass('d-flex');
                            messageEl.remove(); // clean up
                        });

                        // Now show results
                        showBMIResult(response);
                    }
                }

                updateCountdown(); // Start countdown
            },
            //error: function(xhr) {
              //  $('#bmiLoadingModal').fadeOut(500).removeClass('d-flex');
              //  alert("Something went wrong! Please try again.");
            //}
            error: function(xhr) {
                $('#bmiLoadingModal').fadeOut(500).removeClass('d-flex');

                if (xhr.status === 422) { // Validation error
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = [];

                    $.each(errors, function(key, messages) {
                        // messages is an array for each field
                        errorMessages.push(messages[0]); // Show only first message per field
                    });

                    // Show errors (you can customize how to display)
                    alert(errorMessages.join("\n"));
                } else {
                    alert("Something went wrong! Please try again.");
                }
            }

        });
    });

    // Function to show BMI result (to keep code clean)
    function showBMIResult(response) {
        let resultContainer = $('#bmiResultContainer');
        let resultBox = $('#bmiResult');

        resultContainer.show();

        let alertClass = '';
        if (response.category === 'Underweight') {
            alertClass = 'alert-warning';
        } else if (response.category === 'Normal weight') {
            alertClass = 'alert-success';
        } else if (response.category === 'Overweight') {
            alertClass = 'alert-info';
        } else if (response.category === 'Obese') {
            alertClass = 'alert-danger';
        }

        resultBox
            .removeClass('alert-warning alert-success alert-info alert-danger')
            .addClass(alertClass)
            .text(`Your BMI is ${response.bmi} (${response.category})`);

        if (response.image) {
            $('#bmiResult').append(
                `<div class="mt-3 text-center">
                    <img src="{{ asset('storage/') }}/${response.image}" 
                         class="img-fluid mt-3 rounded shadow-sm" 
                         width="500" alt="Uploaded Image">
                </div>`
            );
        }

        $('#bmiResultContainer').slideDown(800, function() {
            $('html, body').animate({
                scrollTop: $('#bmiResultContainer').offset().top
            }, 800);
        });
    }
});

</script>

@endsection
