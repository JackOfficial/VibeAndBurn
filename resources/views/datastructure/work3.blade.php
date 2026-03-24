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

if(isset($_GET['del'])){
    $id = $_GET['del'];
    $sql = "DELETE FROM students WHERE id=$id";
    if($conn->query($sql)){
        $_SESSION['success'] = "Student was Deleted";
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
 <a href="/datastructure/register" class="btn btn-sm btn-primary mb-2">Add Student</a>
 <?php if(isset($_SESSION['success'])){  ?>
 <div class="alert alert-success">
            <?php echo $_SESSION['success']; ?>
        </div>
 <?php } ?>
  <table class="table">
    <thead>
        <tr>
            <th colspan="5" class="text-center">STUDENT'S INFORMATON</th>
        </tr>
      <tr>
        <th>#</th>
        <th>Photo</th>
        <th>Reg Number</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
        <?php 
        $sql = "SELECT * FROM students";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
      ?>
      <tr>
        <td><?php echo $row["id"]; ?></td>
        <td><?php echo $row["photo"]; ?></td>
        <td><?php echo $row["regno"]; ?></td>
         <td><?php echo $row["date"]; ?></td>
          <td>
              <a href="#" class="btn btn-sm btn-success">Edit</a>
              <a href="work3?del=<?php echo $row["id"]; ?>" class="btn btn-sm btn-danger">Delete</a>
              </td>
      </tr>
      <?php
  }
} else { ?>
   <tr>
        <td colspan="5" class="text-center">No students found!</td>
      </tr>
<?php }
$conn->close();
?>
    </tbody>
  </table>
</div>

</body>
</html>
