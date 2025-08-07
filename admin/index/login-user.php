<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login_style2.css">
    
    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">
</head>
<body>

    <div class="home-link">
        <a href="https://cvsu-alumni-tracker.com">< Return to Homepage</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12 form login-form">
                <form action="login-user.php" method="POST" autocomplete="off">

                <center>
                    <img src="css/admin_img/logo-orange.png">
                    <h3 class="text-center">Cavite State University</h3>
                    <h6>IMUS CAMPUS</h6>
                    <h3 class="text-center subtext">Alumni Tracker</h3>

                    <p class="text-center subtext1">“Staying <span style="color: #15622F; font-weight: 600;">Connected</span>, Strengthening <span style="color: #D19B00; font-weight: 600;">Bonds</span>”</p>
                </center>
                
                <label class="signin-label">Sign In</label>
                <hr>
                <label class="signin-label1">Enter your email and password to Sign in!</label>


                <label style="font-weight: 600;">Email <span class="req">*</span></label>
                <center><div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required autocomplete="off" value="<?php echo $email ?>">
                </div></center>

                <label style="margin-top: 20PX; font-weight: 600;">Password <span class="req">*</span></label>
                <center><div class="form-group">
                     <input type="password" id="password" name="password" placeholder="Password" required>
                     <span id="togglePassword">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                </div></center>

                    <div class="link forget-pass text-left"><a href="forgot-password.php">Forgot password?</a></div>
                   
                    <center><div class="form-group">
                        <input class="form-control button" type="submit" name="login" value="Sign In">
                    </div></center>

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
                    
                   <!--  <div class="link login-link text-center">Doesn't have an account yet? <a href="signup-user.php">Create here</a></div> -->
                </form>
            </div>

        </div>
    </div>
    

<!-- JAVASCRIPT CODES -->

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // toggle the eye icon
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>