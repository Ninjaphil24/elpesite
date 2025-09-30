<?php
session_start();
session_unset();
session_destroy();
setcookie("email","",-8600);
setcookie("password","",-8600);
unset($_COOKIE['password']);
header('location: index.php');
?>