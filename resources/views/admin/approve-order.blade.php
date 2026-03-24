<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fame</title>
</head>
<body>
    <p>Hello {{ $name }},</p>
    <p>
      Your request regarding {{ $service }} with an ID of {{ $name }} has been proccessed successfuly.
    </p> 
    
    <p>
        Thank you for being part of what we do!
    </p>
    
    <p>
        This email was sent to {{$email}}.
        If it is not your, kindly disregard it.
    </p>
    <p><a href="{{$link}}">Check My Video</a></p>
    <p>Best Regards,</p>
    <p>The Fame Team</p>
</body>
<footer>
   Fame © {{ date('Y') }}. All Rights Reserved.
</footer>
</html>