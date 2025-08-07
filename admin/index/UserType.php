<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/homepage-style1.css">
    
</head>
<body>

<!-- background with gradient color -->
<div class="gradient-bg">
    <a href="https://cvsu-alumni-tracker.com"><p>< Return to Homepage</p></a>
         <div class="container">
            <div class="main">
                <div class="content">
                    <h2>Create Account</h2>
                    <hr>
                    <a href="Alumni-Create.php"><button class="user-btn"><i class="fa fa-graduation-cap" aria-hidden="true"></i> As Alumni</button></a>
                    
                    <a href="Employer-Create.php"><button class="user-btn"><i class="fa fa-briefcase" aria-hidden="true"></i> As Employer</button></a>

                    <p><b>Note:</b> Please note that the employer account will first be reviewed and approved by the system administrator for verification purposes. Once your account has been approved, you will receive a confirmation email and be able to log in. This process helps ensure the security and authenticity of all users.</p>
                </div>
                
                <div class="form-img">
                    <img src="css/admin_img/logo-orange.png">
                    <h2>Cavite State University 
                    <span style="font-size: 12px; color: #fff; display: block;">IMUS CAMPUS</span>
                    <span>Alumni Tracker</span></h2>
                    <i><p class="qoute">“Staying <span>Connected</span>, Strengthening <span>Bonds</span>”</p></i>
                </div>
            </div>
    </div>
</div> 



<!--  JS SECTION AND LINKS -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
      const links = document.querySelectorAll("a");

      links.forEach(link => {
        link.addEventListener("click", function(event) {
          event.preventDefault();
          document.body.classList.add("slide-out-left"); // or slide-out-right
          setTimeout(() => {
            window.location.href = this.href;
          }, 300); // Matches the CSS transition duration
        });
      });
    });
  </script>
   
</body>
</html>