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
                    <h2>Sign Up as <span>Alumni</span> <i class="fa fa-graduation-cap" aria-hidden="true"></i></h2>
                    <hr>
                   <div class="form">

                     <form action="Alumni-Create.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off" onsubmit="return validateSecondPage()">

                      <input class="form-control" type="hidden" name="sender" value="gsample219@gmail.com">
                      <input type="hidden" name="user" value="Alumni">

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

                <label>Alumni Tracking Number *</label>
                <input type="text" name="tracking_number" id="tracking_number" style="display: inline;">

                <label id="tracking_number_error1" style="font-size: 15px; color: red; position: absolute; margin-top: -50px; text-align: left; transition: 0.2s;"></label>

                <div class="errormsg">
                  <label id="tracking_number_error" style="font-size: 11px;"></i></label>
                </div>

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
                    <h2>Sign Up as <span>Alumni</span> <i class="fa fa-graduation-cap" aria-hidden="true"></i></h2>
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

                       <label>Date of Birth *</label>
                       <input type="Date" name="birthday" id="birthday">
                       <div class="errormsg">
                        <label id="birthday_error" style="font-size: 11px;"></label>
                      </div>

                    </div>

                   </div>
                </div>
                
                <div class="form-img-white">
                    
                  <div class="content" id="content1">
                   <div class="form">

                     <div class="form-content" style="margin-top: -5px;">

                <?php

               $sql = "SELECT * FROM tbl_courses WHERE Course_ID";

                  if ($result = mysqli_query($con, $sql)) {
                      if (mysqli_num_rows($result) > 0) {

                          echo "
                          <div class='col-lg-6'>
                              <div class='form-group' style='margin-top: 20px;'>
                                  <label>Graduated Course:</label>
                                  <select name='course' id='course' class='form-control'>";

                          // Loop through rows for each course
                          while ($rows1 = mysqli_fetch_assoc($result)) {
                              // Split course name to display only the part after hyphen
                              $course = $rows1['Course_Name'];
                              $course_parts = explode('-', $course);
                              $course_display = trim($course_parts[0]);

                              // Set the first option as selected and hidden
                              echo "

                              <option selected hidden value=''>Select Course</option>

                              <option value='{$course}'>$course_display</option>";
                          }

                          echo "</select>
                              </div>
                          </div>";
                      }
                  }

                ?>

                       <div class="errormsg">
                        <label id="course_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Graduated Year *</label>
                       <select name="batch_year" id="batch_year">
                        <option hidden value="">Select Year</option>
                        <!-- dynamically generate this list using server-side logic if needed -->
                        <?php
                          $currentYear = date("Y");
                          for ($year = $currentYear; $year >= 1950; $year--) {
                            echo "<option value='$year'>$year</option>";
                          }
                        ?>
                      </select>
                      <div class="errormsg">
                        <label id="batch_year_error" style="font-size: 11px;"></label>
                      </div>

                       <label>Gender *</label>
                       <select name="gender" id="gender">
                        <option selected hidden value="">Select Gender</option>
                        <option>Female</option>
                        <option>Male</option>
                      </select>
                      <div class="errormsg">
                        <label id="gender_error" style="font-size: 11px;"></label>
                      </div>

                       <label>About You *</label>
                       <input type="text" name="about" id="about">
                       <div class="errormsg">
                        <label id="about_error" style="font-size: 11px;"></label>
                      </div>

                      <div class="consent">

                         <input type="checkbox" id="consentCheckbox" name="consent">

                       <small class="consent-text">By checking this checkbox, you Acknowledge and give your consent to the collection and use of the information you have provided in this form.</small>

                       </div>


                    <button class="submit-btn" type="submit" id="signup" name="signup" form="form_body" disabled>Submit</button>

                  </div>
                     </form>
                   </div>
                </div>
                </div>
            </div>
    </div>
</div> 



<!--  JS SECTION AND LINKS -->
<script src="js/alumni_validation.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function () {
    $("#tracking_number").on("input", function () {
      let trackingNumber = $(this).val();

      // Check if input is not empty
      if (trackingNumber.length > 0) {
        // Send AJAX request
        $.ajax({
          url: "validate_tracking.php", // PHP file for validation
          method: "POST",
          data: { tracking_number: trackingNumber },
          success: function (response) {
            $("#tracking_number_error1").html(response);
          },
          error: function () {
            $("#tracking_number_error1").html("An error occurred. Please try again.");
          },
        });
      } else {
        $("#tracking_number_error1").html(""); // Clear error message if input is empty
      }
    });
  });
</script>


<!-- function to enable or disable the button (JS Function) -->
        
<script>
    const consentCheckbox = document.getElementById("consentCheckbox");
        const submitButton = document.getElementById("signup");

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