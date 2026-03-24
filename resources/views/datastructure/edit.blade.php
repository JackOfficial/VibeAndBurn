<?php
session_start();
$servername = "localhost";
$username = "vibeduet_Jack";
$password = "Vibe&Burn600456";
$database = "vibeduet_datastructure";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['update'])){
    $photo = $_FILES['photo'];
    $regno = $_POST['regno'];
    ////////

    if(!empty($photo)){
           $target_dir = "data structure/";
$target_file = $target_dir . basename($_FILES["photo"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["photo"]["tmp_name"]);
  if($check !== false) {
    // Check if file already exists
if (!file_exists($target_file)) {
  // Allow certain file formats
if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg"
|| $imageFileType == "gif") {
 move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file); 
}
  else{
      $error = "image type is not allowed. you should upload jpg, png jpeg or gif";
  }
}
else{
    $error = "File already exist";
}
  } else {
    $error = "File is not an image.";
  } 
    }
    else{
        $error = "Please insert your photo"; 
    }

    //////
    if(!empty($photo) and !empty($regno)){
        $id = $_GET['update'];
     $sql = "UPDATE students SET regno = $regno , photo = $target_file WHERE id = $id";

if ($conn->query($sql) === TRUE) {
  $_SESSION['success'] = "New student updated successfully";
  ?>
  <script>window.location.href = "/datastructure/work3";</script>
  <?php
} else {
  $error = "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
    }
    else{
        $error = "Fill out all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
<div class="row">
    <div class="col-md-6 col-sm-12">
        <?php if(isset($_POST['register']) && isset($success)){ ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
        <?php } ?>
        
         <?php if(isset($_POST['register']) && isset($error)){ ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
          <?php } ?>
         <div class="card">
     <div class="card-header bg-primary text-white">
         REGISTER STUDENT
     </div>
     <div class="card-body">
         <?php
         $studentId = $_GET['id'];
         $sql = "SELECT * FROM students WHERE id=$studentId";
         $result = $conn->query($sql);
         if ($result) {
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    $regno = $row['regno'];
                      $id = $row['id'];
                }
            }
            else{
             echo "Not found";
         }
         }
         else{
             echo "Not found";
         }
         $conn->close();
         ?>
         <form method="GET" action="/datastructure/edit?update=<?php echo $id ?>" enctype="multipart/form-data">
  <div class="form-group">
    <label for="regno">Regitration Number:</label>
    <input type="text" class="form-control" name="regno" value="<?php echo $regno; ?>" placeholder="Enter your Reg Number" id="regno" required />
  </div>
   <div class="form-group">
    <label for="photo">Passport photo</label>
    <input type="file" class="form-control" name="photo" id="photo" required />
  </div>
  <button type="submit" name="update" class="btn btn-primary">Update</button>
</form>
     </div>
 </div>
    </div>
</div>
</div>

</body>
</html>