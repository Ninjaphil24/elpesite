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

$photo = mysqli_query($con,"SELECT profilePic FROM usertable WHERE email = '$email'");

$path = $photo;

$del = mysqli_query($con,"UPDATE usertable SET profilePic = NULL WHERE email = '$email'"); // delete query


if($del)
{
    unlink($path); 
    mysqli_close($con); // Close connection
    header("location:userprofile.php"); // redirects to all records page
    exit;	
}
else
{
    echo "Error deleting record"; // display error message if not delete
}
?>