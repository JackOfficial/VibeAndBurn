<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Broadcast Message</title>
</head>
<body>
  <div><img src="{{ asset('front/images/logo.png') }}" style="width: 100px; height:auto; text-align:center;"></div>  
  <div>
      <p>
          Hi {{ $name }},
          {!! $messages !!}
      </p>
      <p>
          This email was sent to {{$email}}. If it is not yours, kindly desregard it.
      </p>
      </div>
      <div>
          <b>Vibe&Burn Team,</b>
          Best regard
      </div>
      <footer>
          <p class="text-white text-center mb-0">{{date('Y')}} © VibeAndBurn</p>
      </footer>
  
</body>
</html>