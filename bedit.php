<?php

// The session start works in conjuction with the logged in issue.
session_start();
require "connection.php";
$email = "";
$name = "";
$errors = array();
//require_once "controllerUserData.php";
?>
<?php 
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
      $status = $fetch_info['status'];
      $code = $fetch_info['code'];
      if($status == "verified"){
          if($code != 0){
              header('Location: reset-code.php');
          }
      }else{
          header('Location: user-otp.php');
      }
  }
}else{
  header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/eggfavicon.png">
    <title>Edit</title>
    <link rel="stylesheet" href="vote.css">
</head>
<body>
<div class='box'>
           <h3>Edit your comment</h3>
</div>
    <?php
   
     
        if (isset($_POST['editcomment'])) {
        $eid= mysqli_real_escape_string($con, $_GET['eid']);
        $reviewtype= mysqli_real_escape_string($con, $_GET['reviewtype']);
        $title = mysqli_real_escape_string($con, $_GET['title']);
        
        $cid = $con->real_escape_string($_POST['cid']);
        $comment= $con->real_escape_string($_POST['comment']);
    
        $sql = "UPDATE bcomments SET comment='$comment' WHERE bcomments.cid='$cid'";
        $result = $con->query($sql);
        if($result)header("Location: barticle.php?eid=$eid&reviewtype=$reviewtype&title=$title#comment");
        else echo "Problem";
        }


    ?>



</body>
</html>
