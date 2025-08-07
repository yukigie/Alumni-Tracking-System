<?php 
require_once "controllerUserData.php"; 

if (!isset($_SESSION['email'], $_SESSION['password'])) {
    header('Location: login-user.php');
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM usertable WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$fetch_info = $result->fetch_assoc();

if ($fetch_info) {
    if ($fetch_info['status'] !== "verified") {
        header('Location: user-otp.php');
        exit;
    }

    if ($fetch_info['code'] != 0) {
        header('Location: reset-code.php');
        exit;
    }
}

// Define the setAlert function to store SweetAlert message data
function setAlert($title, $text, $icon, $button = "Done") {
    $_SESSION['alert'] = [
        'title' => $title,
        'text' => $text,
        'icon' => $icon,
        'button' => $button
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employer | Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"crossorigin="anonymous">
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';

// Show the alert if it's set in the session
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo "<script>swal({
        title: '{$alert['title']}',
        text: '{$alert['text']}',
        icon: '{$alert['icon']}',
        button: '{$alert['button']}',
        closeOnClickOutside: true,
    });</script>";

    // Clear the alert from the session so it doesn't display again
    unset($_SESSION['alert']);
}

function validateImage($image, $allowedExtensions, $maxSize) {
    $fileName = $image["name"];
    $fileSize = $image["size"];
    $tmpName = $image["tmp_name"];
    $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($imageExtension, $allowedExtensions)) {
        setAlert("Invalid Image Extension", "Please upload a JPG, JPEG, or PNG image.", "warning");
        return false;
    } elseif ($fileSize > $maxSize) {
        setAlert("Image Size Too Large", "Please upload an image smaller than 10MB.", "warning");
        return false;
    }

    $newImageName = uniqid() . '.' . $imageExtension;
    move_uploaded_file($tmpName, 'css/img/' . $newImageName);

    return $newImageName;
}

// Update Data
if (isset($_POST['submit'])) {
    $employ_id = $_POST['employ_id'];
    $contact_num = $_POST['contact_num'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $link = $_POST['link'];
    $com_name = $_POST['com_name'];
    $industry = $_POST['industry'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $postal = $_POST['postal'];
    $address = $_POST['address'];
    $description = $_POST['description'];

    $newImageName = $_FILES["image"]["error"] === 4 ? mysqli_fetch_assoc(mysqli_query($con, "SELECT Image FROM tbl_employer WHERE Employer_ID  = '{$employ_id}'"))['Image'] : validateImage($_FILES["image"], ['jpg', 'jpeg', 'png'], 10000000);

if ($newImageName) {
    
    $query = "UPDATE tbl_employer 
    SET First_Name = '{$fname}', 
    Middle_Name = '{$mname}', 
    Last_Name = '{$lname}', 
    Contact_Number = '{$contact_num}', 
    Website_Link = '{$link}', 
    Company_Name = '{$com_name}', 
    Industry = '{$industry}', 
    State = '{$state}', 
    City = '{$city}', 
    Postal_Code = '{$postal}', 
    Address = '{$address}', 
    Description = '{$description}',
    Image = '{$newImageName}' 
WHERE 
    Email = '{$email}' AND Employer_ID = '{$employ_id}'";

    $query1 = "UPDATE usertable SET name = CONCAT('$fname', ' ', '$lname') WHERE Email = '$email'";

    $query2 = "UPDATE tbl_employer_joblist SET Image = '{$newImageName}', Company_Name = '{$com_name}', State = '{$state}', City = '{$city}', Postal = '{$postal}' WHERE Email = '$email'";
    
    if (mysqli_query($con, $query) && mysqli_query($con, $query1) && mysqli_query($con, $query2)) {
        setAlert("Successfully Updated!", "Your Profile Details has been successfully updated!", "success");
        header("Location: Employer-Profile.php");
        exit;
    } else {
        setAlert("Data Not Updated!", "Failed to update the Your Profile Details.", "error");
        header("Location: Employer-Profile.php");
        exit;
        }
    }   
}

?>

<!-- Create Admin Modal -->
<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Change Password</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="AllUsers.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body">

          <h4>This will Redirect you to Another Page and will Automatically Sign you out.</h4>

          <h6 style="margin-top: 20px;">Would you still like to Proceed?</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="new-password.php"><button type="button" class="btn btn-primary upbtn">Proceed</button></a>
        </div>
      </form>
    </div>
  </div>
</div>

      <!-- Header -->
    <div class="header" style="background-color: #85847B;">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="css/admin_img/logo-white.png" width="65" height="60">
            <div class="logo" style="font-size: 23px; font-weight: 800;">C<span style="color: #FBD25B; font-weight: 800; font-size: 23px;">v</span>SU 
                <span style="font-size: 15px; font-weight: 600;">Imus Campus</span>
                <p><span>Alumni Tracker<img src="css/admin_img/focus1.png" width="25" height="25"></span></p>
            </div>

            <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        </a>
    </div>

<div class="side-nav1">
    <div class="side-nav">

        <label id="greettxt" style="color: #fff; font-weight: 600; font-size: 14px; margin-bottom: 5px;">Hi there, Valued Partner!<span style="color: #FBD25B; font-size: 20px;">👋</span></label>
        <a href="Employer-Inbox.php"><i class='bx bxs-message'></i></a>
    </div>

    <div class="user-img">
        <?php

                $query = "SELECT * FROM tbl_employer WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

        <img src="css/img/<?php echo $row['Image']; ?>" width="50" height="50" style="border: 2px solid white;">

        <?php

                  }
              }


            ?>
    </div>
</div>

    </div>


    <!-- Sidebar -->
<div class="sidebar">

<!-- List Menu -->
    <ul class="sidebar-list">
<!-- Non Dropdown List Item -->
        <li>
            <div class="title" id="landing1">
                <a href="home-Employer.php" class="link">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="name">Dashboard</span>
                </a>
                <!-- <i class='bx bxs-chevron-down' ></i> -->
            </div>

            <div class="submenu">
                <a href="home-Employer.php" class="link submenu-title">Dashboard</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing2">
                <a href="Employer-JobReport.php" class="link">
                    <i class='bx bx-bar-chart'></i>
                    <span class="name">Job Report</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-JobReport.php" class="link submenu-title">Job Report</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing3">
                <a href="Employer-Applications.php" class="link">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span class="name">Application</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Applications.php" class="link submenu-title">Application</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing4">
                <a href="Employer-Applicant.php" class="link">
                    <i class='bx bx-calendar-event'></i>
                    <span class="name">Applicant List</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Applicant.php" class="link submenu-title">Applicant List</a>
            </div>

        </li>

         <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing5">
                <a href="Employer-Inbox.php" class="link">
                    <i class='bx bxs-message'></i>
                    <span class="name">Inbox</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Inbox.php" class="link submenu-title">Inbox</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing6">
                <a href="Employer-Profile.php" class="link">
                    <i class='bx bxs-user' ></i>
                    <span class="name">Account</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Profile.php" class="link submenu-title">Account</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown" id="signoutbtnemp">
            <div class="title" id="landing7">
                <a href="logout-user.php" class="link">
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class="name">Sign Out</span>
                </a>
            </div>

        </li>
    </ul>

</div>

<!-- Home Section -->

<section class="home">

<div class="row">
    <div class="col-lg-4 col-md-12">
    <div class="profile_info1">
        <a href="home-Employer.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Dashboard</a>
        <div class="profile_content">
        <h5>Edit Profile<span><i class='bx bxs-edit' ></i></span></h5>
        <form action="Employer-Profile.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <div class="row justify-content-center">
            <div class="col-lg-12">

             <?php

                $query = "SELECT * FROM tbl_employer WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {

                $industry = $row['Industry'];
                 // Split the string by the hyphen, and take the first part (before the hyphen)
                $industry_parts = explode('-', $industry);
                $industry_first_part = isset($industry_parts[0]) && trim($industry_parts[0]) !== '' ? trim($industry_parts[0]) : "Select Industry";
            ?>

        <input type="hidden" name="employ_id" id="employ_id" class="form-control" value="<?php echo $row['Employer_ID']; ?>">

        <div class="form-group" style="margin-top: 20px; position: relative; text-align: center;">
            <div class="profile-pic">
                <img src="css/img/<?php echo $row['Image']; ?>" id="photo" name="photo">
            </div>


        <!-- File input styled as an icon -->
                <label for="file" class="file-label">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                </label>

                <input type="file" name="image" id="file" style="display: none;" accept=".jpg, .jpeg, .png">
            </div>
    </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Email Address:</label>
                <input type="text" name="alumni_email" id="alumni_email" class="form-control" value="<?php echo $row['Email']; ?>" required disabled>
              </div>
            </div>
          
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
                <label>Contact Number:</label>
                <input type="text" name="contact_num" id="contact_num" class="form-control" value="<?php echo $row['Contact_Number']; ?>" required>
              </div>
            </div>

            <a href="" class="jobstatus" data-bs-toggle="modal" data-bs-target="#EditModal">Change Password</a>

            </div>

            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-12" id="personal_details">
        <h5>Company Details</h5>
        <p style="font-size: 12px; color: red;">*Please Fill Out All Fields</p>
        <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>First Name:</label>
                <input input type="text" name="fname" id="fname" class="form-control" value="<?php echo $row['First_Name']; ?>" required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Middle Name:</label>
                <input type="text" name="mname" id="mname" class="form-control" value="<?php echo $row['Middle_Name']; ?>" required>
              </div>
            </div>
          
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Last Name:</label>
                <input type="text" name="lname" id="lname" class="form-control" value="<?php echo $row['Last_Name']; ?>" required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Company Name:</label>
                <input type="text" name="com_name" id="com_name" class="form-control" value="<?php echo $row['Company_Name']; ?>" required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Company Industry:</label>
               <select name="industry" id="industry" class="form-control" required>
                  <option hidden selected value="<?php echo $row['Industry']; ?>"><?php echo $industry_first_part; ?></option>
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

                <label style="margin-top: 10px; display: none;" id="otherIndustryLabel">(Type Industry Name if Others. was Selected, N/A if Not Applicable):</label>
                <input type="text" name="otherindustry" id="otherindustry" class="form-control" style="display: none;">
              </div>
            </div>

             <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Company State:</label>
                <input type="text" name="state" id="state" class="form-control" value="<?php echo $row['State']; ?>" required>
              </div>
            </div>

             <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Company City:</label>
                <input type="text" name="city" id="city" class="form-control" value="<?php echo $row['City']; ?>" required>
              </div>
            </div>

             <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Postal Code:</label>
                <input type="text" name="postal" id="postal" class="form-control" value="<?php echo $row['Postal_Code']; ?>" required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Website Link:</label>
                <input type="text" name="link" id="link" class="form-control" value="<?php echo $row['Website_Link']; ?>">
              </div>
            </div>

             <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Company Address:</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo $row['Address']; ?>" required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Company Description:</label>
                <textarea type="text" name="description" id="description" class="form-control" required rows="3"><?php echo $row['Description']; ?></textarea>
              </div>
            </div>

            <?php

                  }
              }


            mysqli_close($con);

            ?>

            <button class="submit-btn" type="submit" id="submit" name="submit" form="form_body">Save Changes</button>

            </div>
        </form>
    </div>
</div>
</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    document.getElementById("form_body").addEventListener("submit", function (event) {
        // Fetch all required inputs
        const requiredFields = document.querySelectorAll("#form_body [required]");
        let isValid = true;
        let message = "";

        // Loop through required fields to check for empty or invalid inputs
        requiredFields.forEach((field) => {
            if (!field.value.trim()) {
                isValid = false;
                message += `- ${field.previousElementSibling.textContent.trim()} is required.\n`;
            }
        });

        // Check if postal code has a value of 0
        const postalField = document.getElementById("postal");
        if (postalField && postalField.value.trim() === "0") {
            isValid = false;
            message += "Postal Code cannot be 0.\n";
        }

        // Validate contact number for at least 10 digits
        const contactField = document.getElementById("contact_num");
        if (contactField && contactField.value.trim().length < 10) {
            isValid = false;
            message += "Contact Number must have at least 10 digits.\n";
        }

        // Validate profile picture (check if the <img> src attribute is empty)
        const photoField = document.getElementById("photo");
        if (photoField && (!photoField.src || photoField.src.endsWith("css/img/"))) {
            isValid = false;
            message += "Please upload a profile picture.\n";
        }

        // Prevent form submission and show alert if validation fails
        if (!isValid) {
            event.preventDefault();
            alert("Please correct the following field:\n\n" + message);
        }
    });
</script>


<!-- Sidebar Landing Page (JS FUNCTION) -->
<script>
    // Add a click event listener to redirect on click
    document.getElementById('landing1').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'home-Employer.php'; // Replace with your desired URL
    });

    document.getElementById('landing2').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-JobReport.php'; // Replace with your desired URL
    });

    document.getElementById('landing3').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Applications.php'; // Replace with your desired URL
    });

    document.getElementById('landing4').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Applicant.php'; // Replace with your desired URL
    });

    document.getElementById('landing5').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Inbox.php'; // Replace with your desired URL
    });

    document.getElementById('landing6').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Profile.php'; // Replace with your desired URL
    });

    document.getElementById('landing7').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'logout-user.php'; // Replace with your desired URL
    });
</script>

<script>
    document.getElementById('industry').addEventListener('change', function() {
        var selectedValue = this.value;
        var otherIndustryInput = document.getElementById('otherindustry');
        var otherIndustryLabel = document.getElementById('otherIndustryLabel');

        if (selectedValue === "Others.") {
            otherIndustryInput.style.display = 'block';
            otherIndustryLabel.style.display = 'block';

            otherIndustryInput.required = true;
            // Exchange the id and name between the select and input element
            otherIndustryInput.name = "industry";
            otherIndustryInput.id = "industry";
            this.name = "otherindustry";
            this.id = "otherindustry";
        } else {
            otherIndustryInput.style.display = 'none';
            otherIndustryLabel.style.display = 'none';

            otherIndustryInput.required = false;
            // Revert the id and name back to the original
            otherIndustryInput.name = "otherindustry";
            otherIndustryInput.id = "otherindustry";
            this.name = "industry";
            this.id = "industry";
            this.style.display = 'block';
        }
    });
</script>

<!-- CHANGE USER IMAGE (JS FUNCTION) -->

<script>
    document.getElementById('file').addEventListener('change', function (event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('photo').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
});

</script>

<!-- Prevent any non-numeric characters in the input (JS FUNCTION) -->
<script>
        // Prevent any non-numeric characters in the input
    document.getElementById('postal').addEventListener('input', function (e) {
      const value = e.target.value;
      e.target.value = value.replace(/\D/g, ''); // Replace any non-numeric character with an empty string
    });


    document.getElementById('contact_num').addEventListener('input', function (e) {
    const value = e.target.value;

    // Allow only digits, spaces, and plus symbol
    e.target.value = value.replace(/[^\d+\s]/g, '');

    // Automatically format the number to Philippine format as the user types
        const formattedValue = formatPhilippineNumber(e.target.value);
        e.target.value = formattedValue;
    });

    function formatPhilippineNumber(value) {
        // Remove any non-digit or non-plus characters (retain plus and digits)
        value = value.replace(/[^\d+]/g, '');

        if (value.startsWith('+63')) {
            // Format as +63 XXX XXX XXXX
            value = value.replace(/(\+63)(\d{3})(\d{3})(\d{4})/, '$1 $2 $3 $4');
        } else if (value.startsWith('63')) {

            value = value.replace(/(63)(\d{3})(\d{3})(\d{4})/, '+$1 $2 $3 $4');
        } else {
            // In case the user doesn't start with +63 or 63, ensure it's corrected to +63
            value = '+63 ' + value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
        }

        return value;
    }


    </script>


    <!-- // DROPDOWN FOR HEADER SUBMENU (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleSidebar = document.querySelector('.user-img');
        const sideNav = document.querySelector('.side-nav');
        const navLinks = sideNav.querySelectorAll('a');
        const sidebar = document.querySelector('.sidebar'); // Reference to sidebar

        // Mapping icons to text
        const iconTextMap = {
            'bx bxs-message': 'Messages'
        };

        // Function to check default sidebar state
        function checkDefaultSidebarState() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add("close"); // Ensure sidebar is closed on smaller screens
            } else {
                sidebar.classList.remove("close"); // Open sidebar for larger screens
            }
        }

        // Toggle dropdown and switch icons to text
        toggleSidebar.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sideNav.classList.toggle('show');
                navLinks.forEach(link => {
                    const iconClass = link.querySelector('i')?.className;
                    if (sideNav.classList.contains('show')) {
                        if (iconTextMap[iconClass]) {
                            link.innerHTML = iconTextMap[iconClass];
                        }
                    } else {
                        if (iconTextMap[iconClass]) {
                            link.innerHTML = `<i class="${iconClass}"></i>`;
                        }
                    }
                });
            }
        });

        // Reset when resizing back to larger screens
        function checkScreenSize() {
            if (window.innerWidth > 768) {
                sideNav.classList.remove('show');
                navLinks.forEach(link => {
                    const iconClass = link.querySelector('i')?.className;
                    if (iconTextMap[iconClass]) {
                        link.innerHTML = `<i class="${iconClass}"></i>`;
                    }
                });
                toggleSidebar.style.pointerEvents = 'none'; // Make the toggle unclickable
            } else {
                toggleSidebar.style.pointerEvents = 'auto'; // Make the toggle clickable
                document.getElementById('greettxt').style.color = '#333';
            }
        }

        // Initial state check on page load
        checkDefaultSidebarState();
        checkScreenSize();

        // Adjust sidebar state dynamically on window resize
        window.addEventListener("resize", () => {
            checkDefaultSidebarState();
            checkScreenSize();
        });

        // TOGGLE SIDEBAR
        const sidebarToggle = document.querySelector(".toggle-sidebar");
        const logo = document.querySelector(".logo-box");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        logo.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    });
</script>
    
</body>
</html>