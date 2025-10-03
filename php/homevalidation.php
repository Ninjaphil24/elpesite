<?php
session_start();
require "connection.php";
$email  = "";
$name   = "";
$errors = [];
//require_once "controllerUserData.php";

if (! empty($_COOKIE["email"])) {
    $email    = $_COOKIE['email'];
    $password = $_COOKIE['password'];

} else {
    $email    = $_SESSION['email'];
    $password = $_SESSION['password'];
}
if ($email != false && $password != false) {
    $sql     = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status     = $fetch_info['status'];
        $code       = $fetch_info['code'];
        if ($status == "verified") {
            if ($code != 0) {
                header('Location: reset-code.php');
            }
        } else {
            header('Location: user-otp.php');
        }
    }
} else {
    header('Location: index.php');
}
