<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Confirmation</title>
</head>
<body>

  <div class="container">
     <p>Hi Onesphore,<br>
     {{ $name }} has recently placed an order regarding <b>{{ $service }}</p>
     <hr>
     <div>
         <h3>Order Information</h3>
         <p>
        <b>Requested Service: </b> {{ $service }}<br>
        <b>Link: </b> {{ $link }}<br>
        <b>Quantity: </b> {{ $quantity }}<br>
        <b>Charge: </b> {{ $charge }}<br>
         </p>
     </div>
     <div>
         <a style="background-color:blue; color:white; padding:4px; border-radius: 4px; margin:2px;" href="https://vibeandburn.com/clientOrders">Check an order</a>
     </div>
  </div>
  
  <footer>
      <p>Copyright {{date('Y')}} © VibeAndBurn, All right reserved.</p>
  </footer>
</body>
</html>