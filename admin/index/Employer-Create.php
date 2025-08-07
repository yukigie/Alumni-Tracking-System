<?php require_once "controllerUserData.php"; ?>

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
    <a href="UserType.php" id="first-page"><p>< Return to User Type</p></a>
         <div class="container" id="first-page1">
            <div class="main">

              <!-- First Form Page -->

                <div class="content">
                    <h2>Sign Up as <span>Employer</span> <i class="fa fa-briefcase" aria-hidden="true"></i></h2>
                    <hr>
                   <div class="form">

                     <form action="Employer-Create.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off" onsubmit="return validateSecondPage()">

                      <input class="form-control" type="hidden" name="sender" value="gsample219@gmail.com">
                      <input type="hidden" name="user" value="Employer">

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

                       <label>Email Address *</label>
                       <input type="text" name="email" id="email" value="<?php echo $email ?>">
                       <div class="errormsg">
                        <label id="email_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Password *</label>
                       <input type="password" name="password" id="password">
                       <div class="errormsg">
                        <label id="password_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Confirm Password *</label>
                       <input type="password" name="cpassword" id="cpassword">
                       <div class="errormsg">
                        <label id="cpassword_error" style="font-size: 11px;"></label>
                      </div>

                       <p><b>Note:</b> Please note that the employer account will first be reviewed and approved by the system administrator for verification purposes. Once your account has been approved, you will receive a confirmation email and be able to log in. This process helps ensure the security and authenticity of all users.</p>

                   </div>
                </div>
                
                <div class="form-img-white">
                    <img src="css/admin_img/logo-shadow.png">
                    <h2>Cavite State University 
                    <span style="font-size: 12px; color: #333; display: block;">IMUS CAMPUS</span>
                    <span>Alumni Tracker</span></h2>
                    <i><p class="qoute">“Staying <span>Connected</span>, Strengthening <span>Bonds</span>”</p></i>

                    <button class="next-btn">Next ></button>
                </div>
            </div>
    </div>

    <!-- Second Form Page -->

    <p id="second-page">< Back</p>
         <div class="container" id="second-page1">
            <div class="main">
                <div class="content">
                    <h2>Sign Up as <span>Employer</span> <i class="fa fa-briefcase" aria-hidden="true"></i></h2>
                    <hr>
                   <div class="form">

                    <div class="form-content">

                       <label>First Name *</label>
                       <input type="text" name="name" id="name" value="<?php echo $name ?>">
                       <div class="errormsg">
                        <label id="name_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Middle Name *</label>
                       <input type="text" name="mname" id="mname">
                       <div class="errormsg">
                        <label id="mname_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Last Name *</label>
                       <input type="text" name="lname" id="lname">
                       <div class="errormsg">
                        <label id="lname_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Company Website *</label>
                       <input type="text" name="comlink" id="comlink">
                       <div class="errormsg">
                        <label id="comlink_error" style="font-size: 11px;"></label>
                      </div>

                   </div>
                 </div>
                </div>
                
                <div class="form-img-white">
                    
                  <div class="content" id="content1" style="margin-top: 1em;">
                   <div class="form">

                     <div class="form-content">

                       <label>Company Name *</label>
                       <input type="text" name="comname" id="comname">
                       <div class="errormsg">
                        <label id="comname_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Company Industry *</label>
                       <select name="industry" id="industry" class="form-control" required>
                      <option hidden selected>Select Industry</option>
                      <option value="Information Technology - Computer Science">Information Technology</option>
                      <option value="Finance & Banking - Business Administration">Finance & Banking</option>
                      <option value="Healthcare & Pharmaceuticals - Psychology">Healthcare & Pharmaceuticals</option>
                      <option value="Hotel & Restaurant Management - Hospitality Management">Hotel & Restaurant Management</option>
                      <option value="Manufacturing - Business Administration">Manufacturing</option>
                      <option value="Retail & Wholesale - Entrepreneurship">Retail & Wholesale</option>
                      <option value="Telecommunications - Information Technology">Telecommunications</option>
                      <option value="Education & Training - Education">Education & Training</option>
                      <option value="Construction & Real Estate - Business Administration">Construction & Real Estate</option>
                      <option value="Transportation & Logistics - Business Administration">Transportation & Logistics</option>
                      <option value="Hospitality & Tourism - Hospitality Management">Hospitality & Tourism</option>
                      <option value="Food & Beverage - Hospitality Management">Food & Beverage</option>
                      <option value="Media & Entertainment - Journalism">Media & Entertainment</option>
                      <option value="Automotive - Business Administration">Automotive</option>
                      <option value="Energy & Utilities - Business Administration">Energy & Utilities</option>
                      <option value="Legal & Consulting - Business Administration">Legal & Consulting</option>
                      <option value="Marketing & Advertising - Business Administration">Marketing & Advertising</option>
                      <option value="Aerospace & Defense - Business Administration">Aerospace & Defense</option>
                      <option value="Agriculture & Farming - Business Administration">Agriculture & Farming</option>
                      <option value="Non-Profit & Social Services - Psychology">Non-Profit & Social Services</option>
                      <option value="Fashion & Apparel - Business Administration">Fashion & Apparel</option>
                      <option value="Beauty & Cosmetics - Business Administration">Beauty & Cosmetics</option>
                      <option value="Environmental Services - Business Administration">Environmental Services</option>
                      <option value="Insurance - Business Administration">Insurance</option>
                      <option value="Human Resources & Recruitment - Business Administration">Human Resources & Recruitment</option>
                      <option value="Chemical Industry - Business Administration">Chemical Industry</option>
                      <option value="Consumer Goods - Entrepreneurship">Consumer Goods</option>
                      <option value="Mining & Metals - Business Administration">Mining & Metals</option>
                      <option value="Public Administration & Government - Office Administration">Public Administration & Government</option>
                      <option value="Sports & Recreation - Business Administration">Sports & Recreation</option>
                      <option value="Arts & Crafts - Journalism">Arts & Crafts</option>
                      <option value="Others.">Others.</option>
                    </select>

                      <div class="errormsg">
                        <label id="industry_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Company State Address*</label>
                       <input type="text" name="state" id="state"></input>
                       <div class="errormsg">
                        <label id="state_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Company Description *</label>
                       <input type="text" name="description" id="description">
                       <div class="errormsg">
                        <label id="description_error" style="font-size: 11px;"></label>
                      </div>

                      <div class="consent">

                         <input type="checkbox" id="consentCheckbox" name="consent">

                       <small class="consent-text">By checking this checkbox, you Acknowledge and give your consent to the collection and use of the information you have provided in this form.</small>

                       </div>


                    <button class="submit-btn" type="submit" id="signup1" name="signup1" form="form_body">Submit</button>

                  </div>
                     </form>
                   </div>
                </div>
                </div>
            </div>
    </div>
</div> 



<!--  JS SECTION AND LINKS -->
<script src="js/employer_validation.js"></script>


<!-- function to enable or disable the button (JS Function) -->
   
<script>
    const consentCheckbox = document.getElementById("consentCheckbox");
        const submitButton = document.getElementById("signup1");

        consentCheckbox.addEventListener("change", function () {
            if (consentCheckbox.checked) {
                submitButton.disabled = false;
                submitButton.classList.add("enabled"); // Add the 'enabled' class
            } else {
                submitButton.disabled = true;
                submitButton.classList.remove("enabled"); // Remove the 'enabled' class
            }
        });
</script>


<!-- Page Transition (JS Function) -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
      const links = document.querySelectorAll("a");

      links.forEach(link => {
        link.addEventListener("click", function(event) {
          event.preventDefault();
          document.body.classList.add("slide-out-right"); // or slide-out-right
          setTimeout(() => {
            window.location.href = this.href;
          }, 300); // Matches the CSS transition duration
        });
      });
    });
  </script>


<!-- Class Transition (JS Function) -->

  <script>
   document.addEventListener("DOMContentLoaded", function() {
    const nextButton = document.querySelector(".next-btn");
    const backButton = document.querySelector("#second-page");

    const firstPage = document.querySelector("#first-page");
    const firstPage1 = document.querySelector("#first-page1");
    const secondPage = document.querySelector("#second-page");
    const secondPage1 = document.querySelector("#second-page1");

    // Slide in/out effects using CSS classes
    function showElement(element) {
        element.style.display = "block";
        element.classList.add("slide-in");
        element.classList.remove("slide-out");
    }

    function hideElement(element) {
        element.classList.add("slide-out");
        element.classList.remove("slide-in");
        setTimeout(function() {
            element.style.display = "none";
        }, 500); // Matches the transition duration
    }

     // Handle next button click (show second page)
    nextButton.addEventListener("click", function(event) {
      event.preventDefault();
      if (validateFirstPage()) {
        hideElement(firstPage);
        hideElement(firstPage1);
        showElement(secondPage);
        showElement(secondPage1);
      }
    });

    // Handle back button click (show first page)
    backButton.addEventListener("click", function(event) {
        event.preventDefault();
        hideElement(secondPage);
        hideElement(secondPage1);
        showElement(firstPage);
        showElement(firstPage1);
    });
});

  </script>
   
</body>
</html>