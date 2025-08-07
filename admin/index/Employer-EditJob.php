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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employer | Edit Job</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';
// Retrieve the ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

function alert($title, $text, $icon, $button = "Done") {
    echo "<script>swal({
        title: '{$title}',
        text: '{$text}',
        icon: '{$icon}',
        button: '{$button}',
        closeOnClickOutside: true,
    });</script>";
}


// Insert or Update Data
if (isset($_POST['submit'])) {
     // Sanitize input data to prevent SQL injection
    $job_id = mysqli_real_escape_string($con, $_POST['job_id']);
    $job_title = mysqli_real_escape_string($con, $_POST['job_title']);
    $positions = mysqli_real_escape_string($con, $_POST['positions']);
    $close_date = mysqli_real_escape_string($con, $_POST['close_date']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $job_type = mysqli_real_escape_string($con, $_POST['job_type']);
    $salary = mysqli_real_escape_string($con, $_POST['salary']);
    $skill = mysqli_real_escape_string($con, $_POST['skill']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $days = mysqli_real_escape_string($con, $_POST['days']);
    $shift = mysqli_real_escape_string($con, $_POST['shift']);
    $sched = mysqli_real_escape_string($con, $_POST['sched']); 

    $query = "UPDATE tbl_employer_joblist 
              SET Job_Title = '{$job_title}', 
              Available_Positions = '{$positions}', 
              Job_Close_Date = '{$close_date}', 
              Address = '{$address}', 
              Job_Type = '{$job_type}', 
              Salary = '{$salary}',
              Skills = '{$skill}', 
              Description = '{$description}',
              Sched_Day = '{$days}' 
              WHERE ID = '{$job_id}' AND Email = '{$email}'";

    $query1 = "UPDATE tbl_employer_jobsched 
              SET Job_Title = '{$job_title}', 
              Day = '{$days}', 
              Shift = '{$shift}', 
              Type = '{$sched}' 
              WHERE Job_ID ='{$job_id}' AND Email = '{$email}'";

    // Execute the query
    if (mysqli_query($con, $query) && mysqli_query($con, $query1)) {
        header('location: Employer-EditJob.php');
    } else {
        alert("Data Not Updated!", "Failed to update Your Job Details.", "error");
        }
    }


?>


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
    <div class="col-md-12">
   <div id="personal_details" style="padding-top: 0px;">
        <a href="Employer-JobReport.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Job Report</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Update Job<span><i class='bx bxs-edit' ></i></span></h5>
        <p style="font-size: 13px; color: red;">*Please Fill Out All Fields</p>
        <form action="Employer-EditJob.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <div class="row justify-content-center">
      <?php

        if ($id) {
            // Fetch specific row data based on the ID
            $query = "SELECT * FROM tbl_employer_joblist WHERE ID ='$id' AND Email ='$email'";
            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                $row = mysqli_fetch_assoc($query_run);
            } else {
                // Set default empty values if no data is available for this ID
                $row = [
                    'Job_Title' => '',
                    'Available_Positions' => '',
                    'Job_Close_Date' => '',
                    'Address' => '',
                    'Job_Type' => '',
                    'Salary' => '',
                    'Skills' => '',
                    'Description' => '',
                ];
            }
        } else {
            // If no ID is provided, redirect back to the resume list or show an error
            echo "<script>alert('Your Job Details has been Successfully Updated.');
                 window.location.href = 'Employer-JobReport.php';</script>";
            exit;
        }
        ?>

        <div class="row justify-content-center">

          <input type="hidden" name="job_id" id="job_id" class="form-control" value="<?php echo $row['ID']; ?>" required>

            <div class="col-lg-6">
                <div class="form-group" style="margin-top: 20px;">
                <label>Title*</label>
                 <input type="text" name="job_title" id="job_title"  class="form-control" value="<?php echo $row['Job_Title']; ?>" required>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group" style="margin-top: 20px;">
                <label>No. of Available Positions*</label>
                <input type="text" name="positions" id="positions" class="form-control" value="<?php echo $row['Available_Positions']; ?>" required>
                </div>
            </div>

             <div class="col-lg-6">
                <div class="form-group" style="margin-top: 20px;">
                <label>Close Date*</label>
                <input type="date" name="close_date" id="close_date" class="form-control" value="<?php echo $row['Job_Close_Date']; ?>" required>
                </div>
            </div>

             <div class="col-lg-6">
                <div class="form-group" style="margin-top: 20px;">
                <label>Resides In*</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo $row['Address']; ?>" required>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group" style="margin-top: 20px;">
                <label>Job Status*</label>
                <select name="job_type" id="job_type" class="form-control" required>
                      <option selected hidden><?php echo $row['Job_Type']; ?></option>
                      <option value="Full-Time">Full-Time</option>
                      <option value="Part-Time">Part-Time</option>
                      <option value="Contract-Based">Contract-Based</option>
                      <option value="Internship">Internship</option>
                      <option value="Freelance">Freelance</option>
                      <option value="Temporary">Temporary</option>
                    </select>
                </div>
            </div>

             <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Salary Range*</label>
                <select name="salary" id="salary" class="form-control" required>
                <option selected hidden><?php echo $row['Salary']; ?></option>
                <option value="Below 10,000">Below 10,000 PHP</option>
                <option value="10,000 - 20,000">10,000 - 20,000 PHP</option>
                <option value="21,000 - 40,000">21,000 - 40,000 PHP</option>
                <option value="41,000 - 60,000">41,000 - 60,000 PHP</option>
                <option value="More than 60,000">More than 60,000 PHP</option>
                </select>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h6>Schedule:</h6>
              </div>
            </div>

             <?php

                $query = "SELECT * FROM tbl_employer_jobsched WHERE Job_ID ='$id' AND Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 5px;">
                <label>Days*</label>
                <select name="days" id="days" class="form-control" required>
                  <option selected hidden><?php echo $row['Day']; ?></option>
                  <option value="Monday to Friday">Monday to Friday</option>
                  <option value="Full Week">Full Week</option>
                  <option value="Weekends">Weekends</option>
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 5px;">
                <label>Shift*</label>
                <select name="shift" id="shift" class="form-control" required>
                  <option selected hidden><?php echo $row['Shift']; ?></option>
                  <option value="Day Shift">Day Shift</option>
                  <option value="Afternoon Shift">Afternoon Shift</option>
                  <option value="Night Shift">Night Shift</option>
                  <option value="Evening Shift">Evening Shift</option>
                  <option value="Rotational Shift">Rotational Shift</option>
                  <option value="Fixed Shift">Fixed Shift</option>
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 5px;">
                <label>Type*</label>
                <select name="sched" id="sched" class="form-control" required>
                  <option selected hidden><?php echo $row['Type']; ?></option>
                  <option value="Flexible">Flexible</option>
                  <option value="Overtime">Overtime</option>
                  <option value="On Call">On Call</option>
                </select>
              </div>
            </div>

            <?php

                  }
              }

        if ($id) {
            // Fetch specific row data based on the ID
            $query = "SELECT * FROM tbl_employer_joblist WHERE ID ='$id' AND Email ='$email'";
            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                $row = mysqli_fetch_assoc($query_run);
            } else {
                // Set default empty values if no data is available for this ID
                $row = [
                    'Job_Title' => '',
                    'Available_Positions' => '',
                    'Job_Close_Date' => '',
                    'Address' => '',
                    'Job_Type' => '',
                    'Salary' => '',
                    'Skills' => '',
                    'Description' => '',
                ];
            }
        } else {
            // If no ID is provided, redirect back to the resume list or show an error
            echo "<script>alert('Your Job Details has been Successfully Updated.');
                 window.location.href = 'Employer-JobReport.php';</script>";
            exit;
        }
        ?>
            <div class="col-lg-12">
                <div class="form-group" style="margin-top: 20px;">
                <label>Skills Required</label>
                <textarea rows="2" name="skill" id="skill" class="form-control" rows="3"><?php echo $row['Skills']; ?></textarea>
            </div>

            <?php

                $query = "SELECT * FROM tbl_employer_skill WHERE Job_ID ='$id' AND Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

            <div class="col-lg-12">
              <div class="form-group">
                <div class="skill-wrapper">
                    <input type="text" name="skillset" id="skillset" class="form-control" value="<?php echo $row['Skill_Name']; ?>" disabled="true">
                </div>
             </div>
            </div>

              <?php

                  }
              }

        if ($id) {
            // Fetch specific row data based on the ID
            $query = "SELECT * FROM tbl_employer_joblist WHERE ID ='$id' AND Email ='$email'";
            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                $row = mysqli_fetch_assoc($query_run);
            } else {
                // Set default empty values if no data is available for this ID
                $row = [
                    'Job_Title' => '',
                    'Available_Positions' => '',
                    'Job_Close_Date' => '',
                    'Address' => '',
                    'Job_Type' => '',
                    'Salary' => '',
                    'Skills' => '',
                    'Description' => '',
                ];
            }
        } else {
            // If no ID is provided, redirect back to the resume list or show an error
            echo "<script>alert('Your Job Details has been Successfully Updated.');
                 window.location.href = 'Employer-JobReport.php';</script>";
            exit;
        }
        ?>

            <div class="col-lg-12">
                <div class="form-group" style="margin-top: 20px;">
                <label>Job Description</label>
                <textarea rows="3" name="description" id="description" class="form-control" rows="3"><?php echo $row['Description']; ?></textarea>
            </div>

              <button class="submit-btn1" type="submit" id="submit" name="submit" form="form_body">Save</button>

            </div>

        <?php
        mysqli_close($con);
        ?>


        </div>
        </form>

            </div>

        </div>
    </div>


            </div>
    </div>
</div>
</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

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

<!-- // INPUT VALIDATION FOR NO. OF POSITIONS AND CLOSE DATE (JS FUNCTION) -->

<script>
    // Prevent any non-numeric characters and 0 value in the input
document.getElementById('positions').addEventListener('input', function (e) {
    const value = e.target.value;
    
    // Replace non-numeric characters and remove leading zeros
    const cleanedValue = value.replace(/\D/g, ''); // Remove non-numeric characters
    e.target.value = cleanedValue.replace(/^0+/, ''); // Remove leading zeros
});

 // Get today's date
  const today = new Date();
  
  // Calculate tomorrow's date
  const tomorrow = new Date(today);
  tomorrow.setDate(today.getDate() + 1);
  
  // Convert tomorrow's date to 'YYYY-MM-DD' format
  const tomorrowDate = tomorrow.toISOString().split('T')[0];
  
  // Set the 'min' attribute of the date input to tomorrow's date
  document.getElementById('close_date').setAttribute('min', tomorrowDate);

</script>

<!-- JavaScript for pop-up warning -->
<script>
function validateForm() {
    var requiredFields = document.querySelectorAll('[required]');
    var isValid = true;
    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            isValid = false;
            alert('Please fill out the required fields.');
        }
    });
    return isValid;
}

document.querySelector('form').onsubmit = function() {
    return validateForm();
};
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