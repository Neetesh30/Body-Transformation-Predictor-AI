<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glenmark Mudra Campaign</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #0c101b;
      font-family: "Montserrat", sans-serif;
      color: #fff;
    }

    .single-film {
      position: relative;
      cursor: pointer;
      overflow: hidden;
      border-radius: 8px;
      transition: transform 0.3s ease;
      margin-top: 40px;
    }

    .single-film:hover {
      transform: scale(1.03);
    }

    .single-film .box {
      width: 100%;
      position: relative;
    }

    .single-film .box__hover {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.2);
      display: flex;
      justify-content: center;
      align-items: center;
      opacity: 1;
      transition: 0.3s;
    }

  
    .play-movie-icon {
       display: flex;
      justify-content: center;
      align-items: center;
      font-size: 50px;
      color: #fff;
      position: relative;
    }

    .film-info{
      background-color: rgba(224, 48, 48, 0.945);
    }
    
    .pulse {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      animation: pulse-animation 2s infinite;
}

@keyframes pulse-animation {
  0% {
    box-shadow: 0 0 0 0px rgba(138, 21, 21, 0.2);
  }
  100% {
    box-shadow: 0 0 0 20px rgba(0, 0, 0, 0);
  }
}


    .film-info {
      text-align: center;
      padding: 10px 0;
    }

    /* Fullscreen popup */
    .video-popup {
      display: none;
      position: fixed;
      z-index: 9999;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.95);
      justify-content: center;
      align-items: center;
      transition: opacity 0.5s ease;
    }

    .video-popup.active {
      display: flex;
      opacity: 1;
    }

    .video-popup video {
      width: 90%;
      max-width: 900px;
      height: auto;
      border-radius: 8px;
      animation: zoomIn 0.5s ease;
    }

    .video-popup .close-popup {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 32px;
      color: #fff;
      cursor: pointer;
      z-index: 10;
    }

    @keyframes zoomIn {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row g-4">
    <!-- Video 1 -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="single-film" data-video-src="{{ asset('glenmark/videos/mudra-1.mp4') }}">
        <div class="box">
           <img src="{{ asset('glenmark/images/mudra.png') }}" class="img-responsive w-100">
        </div>
        <div class="box__hover">
          <i class="play-movie-icon ion-ios-play-outline pulse"></i>
        </div>
        <div class="film-info">
          <h4>Mudra 1</h4>
        </div>
      </div>
    </div>

    <!-- Video 2 -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="single-film" data-video-src="{{ asset('glenmark/videos/mudra-1.mp4') }}">
        <div class="box">
           <img src="{{ asset('glenmark/images/mudra.png') }}" class="img-responsive w-100">
        </div>
        <div class="box__hover">
          <i class="play-movie-icon ion-ios-play-outline pulse"></i>
        </div>
        <div class="film-info">
          <h4>Mudra 2</h4>
        </div>
      </div>
    </div>

    <!-- Video 3 -->
    <div class="col-lg-12 col-md-6 col-sm-12">
      <div class="single-film" data-video-src="{{ asset('glenmark/videos/mudra-1.mp4') }}">
        <div class="box">
           <img src="{{ asset('glenmark/images/mudra.png') }}" class="img-responsive w-100">
        </div>
        <div class="box__hover">
          <i class="play-movie-icon ion-ios-play-outline pulse"></i>
        </div>
        <div class="film-info">
          <h4>Mudra 3</h4>
        </div>
      </div>
    </div>


     <!-- Video 4 -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="single-film" data-video-src="{{ asset('glenmark/videos/mudra-1.mp4') }}">
        <div class="box">
           <img src="{{ asset('glenmark/images/mudra.png') }}" class="img-responsive w-100">
        </div>
        <div class="box__hover">
          <i class="play-movie-icon ion-ios-play-outline pulse"></i>
        </div>
        <div class="film-info">
          <h4>Mudra 4</h4>
        </div>
      </div>
    </div>


     <!-- Video 5 -->
    <div class="col-lg-6 col-md-6 col-sm-12">
      <div class="single-film" data-video-src="{{ asset('glenmark/videos/mudra-1.mp4') }}">
        <div class="box">
           <img src="{{ asset('glenmark/images/mudra.png') }}" class="img-responsive w-100">
        </div>
        <div class="box__hover">
          <i class="play-movie-icon ion-ios-play-outline pulse"></i>
        </div>
        <div class="film-info">
          <h4>Mudra 5</h4>
        </div>
      </div>
    </div>

  </div>
</div>


<!-- Fullscreen Video Popup -->
<div class="video-popup">
  <span class="close-popup">&times;</span>
  <video id="popupVideo" controls></video>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
  $('.single-film').click(function(){
    var videoSrc = $(this).data('video-src');
    $('#popupVideo').attr('src', videoSrc);
    $('.video-popup').addClass('active');
    $('#popupVideo')[0].play();
  });

  $('.close-popup').click(function(){
    $('#popupVideo')[0].pause();
    $('.video-popup').removeClass('active');
  });

  // Close when clicking outside video
  $('.video-popup').click(function(e){
    if(!$(e.target).is('video')) {
      $('#popupVideo')[0].pause();
      $('.video-popup').removeClass('active');
    }
  });
});
</script>

</body>
</html>
