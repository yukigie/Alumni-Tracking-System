<?php require_once "controllerUserData.php"; 
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="login_style.css">
    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form" style="margin-top: -10%;">
                <form action="signup-user.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Create Account</h2>
                    <p class="text-center">Create New Account Here</p>
                    <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="Full Name" required value="<?php echo $name ?>">
                         <input class="form-control" type="hidden" name="sender" value="gsample219@gmail.com">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Email Address" required value="<?php echo $email ?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm password" required>
                    </div>

                     <div class="form-group">
                        <input class="form-control" type="text" name="position" placeholder="Enter Job Position" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control" type="hidden" name="user" value="Admin" required>
                    </div>

                    <div class="form-group">
                        <input class="form-control button" type="submit" id="signup" name="signup" value="Signup">
                    </div>
                    <div class="link login-link text-center">Already have an Account? <a href="login-user.php">Login here</a></div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>