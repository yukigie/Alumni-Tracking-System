<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
// if($email == false){
//   header('Location: login-user.php');
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form" style="margin-top: 10%;">
                <form action="user-otp.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
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
                    <div class="form-group" style="text-align: center; color: #fff;">
                        <h5>We will notify You if you're Account is Already Verified by the Admin.</h5>
                        <p>Thank You For Using CvSU Imus Alumni Tracking System!</p>
                    </div>
                    <div class="form-group">
                        <a href="login-user.php" style="text-decoration: none;"><input class="form-control button" type="button" name="check" value="Go Back"></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>