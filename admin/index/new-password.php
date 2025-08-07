<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="login_style.css">
    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off" onsubmit="return validateFirstPage()">
                    <h2 class="text-center">New Password</h2>
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
                    <div class="form-group">
                        <input class="form-control" type="password" id="password" name="password" placeholder="Create new password" required>
                    </div>
                    <div class="errormsg" style="margin-left: -10px;">
                      <label id="password_error" style="font-size: 12px; color: #F47777; padding-top: 10px; padding-bottom: 10px; display: none;"></label>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" id="cpassword" name="cpassword" placeholder="Confirm your password" required>
                    </div>
                     <div class="errormsg" style="margin: -10px;">
                      <label id="cpassword_error" style="font-size: 12px; color: #F47777;"></label>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    
function validateFirstPage() {
  let isValid = true;

  // Clear previous errors
  document.querySelectorAll('.errormsg label').forEach(label => label.textContent = '');
  document.querySelectorAll('#first-page input').forEach(element => element.style.border = '');

  // Validate Password
  const password = document.getElementById('password').value.trim();
  if (!validatePassword(password)) {
    document.getElementById('password_error').textContent = 'Password must be at least 8 characters long, contain at least one number, one uppercase letter, and one special character.';
    document.getElementById('password_error').style.display = 'block';
    document.getElementById('password_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('password').style.border = '1px solid red';
    isValid = false;
  }

  // Validate Confirm Password
  const confirmPassword = document.getElementById('cpassword').value.trim();
  if (confirmPassword === "") {
    document.getElementById('cpassword_error').textContent = 'Confirm Password is required';
    document.getElementById('cpassword_error').style.margin = '-18px 0px 0px 0px';
    document.getElementById('cpassword').style.border = '1px solid red';
    isValid = false;
  } else if (confirmPassword !== password) {
    document.getElementById('cpassword_error').textContent = 'Passwords do not Match';
    document.getElementById('cpassword_error').style.margin = '-18px 0px 0px 20px';
    document.getElementById('cpassword').style.border = '1px solid red';
    isValid = false;
  }

  return isValid;
}

// Validate password format
function validatePassword(password) {
  const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return passwordPattern.test(password);
}


// Reset error message and input border on typing
document.querySelectorAll('input').forEach(input => {
  input.addEventListener('input', function() {
    this.style.border = ''; // Reset the border to default
    const errorLabelId = `${this.name}_error`;
    const errorLabel = document.getElementById(errorLabelId);
    if (errorLabel) {
      errorLabel.textContent = ''; // Clear the error message
      errorLabel.style.margin = '0px 0px 0px 0px';;
    }
  });
});

// Disable copy-paste for the confirm password field
document.getElementById('cpassword').addEventListener('paste', function(event) {
  event.preventDefault();
  alert('Copy-paste is disabled for security reasons.');
});

</script>   
    
</body>
</html>