<!-- https://youtu.be/tYdy4272IEs -->
<?php require_once "controllerUserData.php"; ?>
<!javascript:document.forms['EditForm'].state.value='browse'; document.forms['EditForm'].state2.value='main'; document.forms['EditForm'].submit();DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="shortcut icon" type="image/png" href="img/callas.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <form action="index.php" method="POST" autocomplete="">
                    <h2 class="text-center">Είσοδος Μελών ΕΛΠΕTest</h2>
                    <p class="text-center">Login with your email and password.</p>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <div>
                            <input type="checkbox" name="remember" id="remember"
                                <?php if(isset($_COOKIE["login"])) { ?> checked
                                <?php } ?> /> <label for="remember-me">Remember me</label>
                        </div>
                    </div>
                    <!-- <div class="link forget-pass text-left"><a href="forgot-password.php">Forgot password?</a></div> -->
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="login" value="Login">
                    </div>
      <div class="link login-link text-center">Not yet a member? <a href="signup-user.php">Signup now</a></div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>		