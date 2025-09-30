<!-- https://www.mysqltutorial.org/mysql-delete-join/ -->
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
$del = mysqli_query($con,"DELETE FROM usertable WHERE email = '$email'"); // delete query
// AND DELETE FROM comments WHERE entryID = '$eid'
if($del)
{
    mysqli_close($con); // Close connection
    header("location:index.php"); // redirects to all records page
    exit;	
}
else
{
    echo "Error deleting record"; // display error message if not delete
}
?>