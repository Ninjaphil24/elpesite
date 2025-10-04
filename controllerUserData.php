<?php
session_start();
require "connection.php";
$email     = "";
$firstName = "";
$lastName  = "";
$errors    = [];

//if user signup button
if (isset($_POST['signup'])) {
    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $lastName  = mysqli_real_escape_string($con, $_POST['lastName']);
    $email     = mysqli_real_escape_string($con, $_POST['email']);
    $password  = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res         = mysqli_query($con, $email_check);
    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = "Email that you have entered already exists!";
    }
    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        // $code = rand(999999, 111111); use only with email vericiation
        $code = 0;
        // $status = "notverified"; use only with email verification
        $status      = "verified";
        $insert_data = "INSERT INTO usertable (firstName, lastName, email, password, code, status,registerDate)VALUES('$firstName', '$lastName', '$email', '$encpass', '$code', '$status',NOW())";
        $data_check  = mysqli_query($con, $insert_data);
        if ($data_check) { //use only without email verification
            $_SESSION['email']    = $email;
            $_SESSION['password'] = $password;
            header('location: home.php');
            exit();
        }
        //Use only with email verification
        //     $subject = "Email Verification Code";
        //     $message = "Your verification code is $code";
        //     $sender = "From: speggs@gmail.com";
        //     if(mail($email, $subject, $message, $sender)){
        //         $info = "We've sent a verification code to your email - $email";
        //         $_SESSION['info'] = $info;
        //         $_SESSION['email'] = $email;
        //         $_SESSION['password'] = $password;
        //         header('location: user-otp.php');
        //         exit();
        //     }else{
        //         $errors['otp-error'] = "Failed while sending code!";
        //     }
        // }else{
        //     $errors['db-error'] = "Failed while inserting data into database!";
        // }
    }

}
//if user click verification code submit button
if (isset($_POST['check'])) {
    $_SESSION['info'] = "";
    $otp_code         = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code       = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res         = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email      = $fetch_data['email'];
        $code       = 0;
        $status     = 'verified';
        $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($con, $update_otp);
        if ($update_res) {
            $_SESSION['name']  = $name;
            $_SESSION['email'] = $email;
            header('location: home.php');
            exit();
        } else {
            $errors['otp-error'] = "Failed while updating code!";
        }
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if cookies exist
// if (! empty($_COOKIE["email"]) && $_COOKIE["usertype"] == "member") {
//     header("Location: home.php");
//     exit();
// } else if (! empty($_COOKIE["email"]) && $_COOKIE["usertype"] == "admin") {
//     header("Location: boardmembers.php");
//     exit();
// }
if (! empty($_COOKIE["email"])) {
    header("Location: home.php");
    exit();
}
//if user click login button
if (isset($_POST['login'])) {
    $email       = mysqli_real_escape_string($con, $_POST['email']);
    $password    = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res         = mysqli_query($con, $check_email);
    if (mysqli_num_rows($res) > 0) {
        $fetch      = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $status            = $fetch['status'];
            if ($status == 'verified') {
                $_SESSION['email']    = $email;
                $_SESSION['password'] = $password;
                header('location: home.php');

                // if ($status == 'verified' && $fetch['usertype'] == 'member') {
                //     $_SESSION['email']    = $email;
                //     $_SESSION['password'] = $password;
                //     header('location: home.php');
                // } else if ($status == 'verified' && $fetch['usertype'] == 'admin') {
                //     $_SESSION['email']    = $email;
                //     $_SESSION['password'] = $password;
                //     header('location: boardmembers.php');
            } else {
                $info             = "Παρακαλώ επιβεβαιώστε το email σας - $email";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
    }
}

if (! empty($_POST['remember'])) {
    $oneYear = time() + (365 * 24 * 60 * 60); // 1 year from now
    setcookie("email", $email, $oneYear);
    setcookie("password", $password, $oneYear);
    setcookie("usertype", $fetch['usertype'], $oneYear);
}

//if user click continue button in forgot password form
if (isset($_POST['check-email'])) {
    $email       = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql     = mysqli_query($con, $check_email);
    if (mysqli_num_rows($run_sql) > 0) {
        $code        = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
        $run_query   = mysqli_query($con, $insert_code);
        if ($run_query) {
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender  = "From: speggs@gmail.com";
            if (mail($email, $subject, $message, $sender)) {
                $info              = "We've sent a password reset otp to your email - $email";
                $_SESSION['info']  = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}

//if user click check reset otp button
if (isset($_POST['check-reset-otp'])) {
    $_SESSION['info'] = "";
    $otp_code         = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code       = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res         = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data        = mysqli_fetch_assoc($code_res);
        $email             = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info              = "Please create a new password that you don't use on any other site.";
        $_SESSION['info']  = $info;
        header('location: new-password.php');
        exit();
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if (isset($_POST['change-password'])) {
    //Original line next
    //$_SESSION['info'] = "";
    isset($_COOKIE["email"]) ? $email = $_COOKIE["email"] : $_SESSION['info'] = "";
    $password                         = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword                        = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    } else {
        $code = 0;
        //Original line next
        //$email = $_SESSION['email']; //getting this email using session
        $email       = isset($_COOKIE["email"]) ? $_COOKIE["email"] : $_SESSION['email']       = "";
        $encpass     = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
        $run_query   = mysqli_query($con, $update_pass);
        if ($run_query) {
            $info             = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: password-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

//if login now button click
if (isset($_POST['login-now'])) {
    header('Location: index.php');
}
