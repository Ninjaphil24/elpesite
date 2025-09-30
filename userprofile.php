<?php
session_start();
require "connection.php";
if(!empty($_COOKIE["email"])){
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];
    
}
else{
$email = $_SESSION['email'];
$password = $_SESSION['password'];
}
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
    }
}
if (isset($_POST['insert'])) {
$photo = $_FILES['photo']['name'];
$target = "profilepics/".basename($photo);
$sql = "UPDATE usertable SET profilePic = 'profilepics/$photo' WHERE email = '$email'";
move_uploaded_file($_FILES['photo']['tmp_name'], $target);
$result = mysqli_query($con,$sql);
header ("Location: userprofile.php");

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/callas.jpg">
    <link rel="stylesheet" href="userprofile.css">
    <title>My Profile</title>
</head>
<body>
<section>
  <div class="crop">
  <a href="userprofile.php">
    <img src="<?php echo isset($fetch_info['profilePic']) ? $fetch_info['profilePic']: './profilepics/beard.png';?>" alt=""> 
</div>
<div class="user">
    <h3>ΧΑΙΡΕ <?php echo $fetch_info['firstName']?></h3>
  </a>
  </div>
</section>
    <nav>
        <ul>
            <li><a href="home.php">ΧΩΡΟΣ_ΜΕΛΩΝ</a></li>
            <?php if($fetch_info['usertype']=='admin'){
        echo '<li><a href="boardmembers.php">ΧΩΡΟΣ_ΔΣ</a></li>';}?>
            <li><a href="new-password.php">ΑΛΛΑΓΗ_ΚΩΔΙΚΟΥ</a></li>
        </ul>
    </nav>
    <br>
    <?php if(isset($fetch_info['profilePic']))echo ' 
    <div class="profilePicCheck">
    <img src=" '.$fetch_info['profilePic'].'
        " alt="">
    </div>';
    else echo "";?>
    <br>
    <form method="POST" action="userprofile.php" enctype="multipart/form-data">
        <div class="upload_image">
            <div class="chooseButton">
                <input type="hidden" name="size" value="1000000"> 
                <label><?php if(isset($fetch_info['profilePic']))echo 'ΑΝΕΒΑΣΤΕ ΑΛΛΗ ΦΩΤΟΓΡΑΦΙΑ';
                else echo 'ΑΝΕΒΑΣΤΕ ΜΙΑ ΦΩΤΟΓΡΑΦΙΑ';
                ?><br></label>
                <input style="color:#ccc;" type="file" name="photo" required="">
            </div> <br>
            <!-- <h4>(ΔΥΟ ΦΟΡΕΣ ΚΛΙΚ ΑΡΓΑ ΓΙΑ ΥΠΟΒΟΛΗ)</h4> -->
            <div class="uploadButton">
                <input type="submit" name="insert" value="ΥΠΟΒΟΛΗ">
            </div>
            <br> <br>
            <?php if(isset($fetch_info['profilePic']))echo "
            <div class='deleteButton'>            
                <a href='deleteprofilepic.php'>ΔΙΑΓΡΑΦΗ ΦΩΤΟΓΡΑΦΙΑΣ</a>
            </div>";
            else echo "";?>
            <br> <br>
            <div class="deleteButton">
                <a href="deleteprofile.php" class='confirmation' onclick="return confirm('Are you sure?');">ΔΙΑΓΡΑΦΗ ΠΡΟΦΙΛ</a>
            </div>
        </div>
    </form>


</body>
</html>