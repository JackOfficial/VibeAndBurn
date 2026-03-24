<?php
$servername = "localhost";
$username = "vibeduet_Jack";
$password = "Vibe&Burn600456";
$database = "vibeduet_vibeandburn";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
else{
 echo "Connected successfully";   
}

?>