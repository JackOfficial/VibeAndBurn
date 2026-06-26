<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Broadcast Message</title>
</head>
<body>
    <div>
        <img src="{{ url('front/images/logo.png') }}" style="width: 100px; height:auto; display:block; margin: 0 auto;" alt="VibeAndBurn Logo">
    </div>  
    
    <div>
        <p>Hi {{ $name }},</p>
        
        <div>
            {!! $body !!}
        </div>
        
        <p style="font-size: 12px; color: #666;">
            This email was sent to {{ $email }}. If this was sent to you by mistake, kindly disregard it.
        </p>
    </div>
    
    <div style="margin-top: 20px;">
        <b>Vibe&Burn Team</b><br>
        Best regards
    </div>
    
    <footer style="margin-top: 30px;">
        <p class="text-white text-center mb-0">{{ date('Y') }} © VibeAndBurn</p>
    </footer>
</body>
</html>