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
    <title>Alumni | Resume Builder</title>
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

    <!-- Header -->
    <div class="header">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="css/admin_img/logo-orange.png" width="65" height="60">
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

        <label id="greettxt" style="color: #FFEFBD; font-weight: 600; font-size: 14px; margin-bottom: 5px;">Hi there, CvSU Alumnus<span style="color: #FBD25B; font-size: 20px;">👋</span></label>
        <a href="Alumni-Inbox.php"><i class='bx bxs-message'></i></a>
    </div>

    <div class="user-img">
        <?php

                $query = "SELECT * FROM tbl_alumni WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

        <img src="css/img/<?php echo $row['Image']; ?>" width="50" height="50">

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
                <a href="Home.php" class="link">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="name">Dashboard</span>
                </a>
                <!-- <i class='bx bxs-chevron-down' ></i> -->
            </div>

            <div class="submenu">
                <a href="Home.php" class="link submenu-title">Dashboard</a>
            </div>

        </li>

       <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title">
                <a href="#" class="link">
                    <i class='bx bxs-briefcase' ></i>
                    <span class="name">Jobs</span>
                </a>
                <i class='bx bxs-chevron-down' ></i>
            </div>

            <div class="submenu">
                <a href="#" class="link submenu-title">All Settings</a>
                <a href="Alumni-Jobs.php" class="link">Job Opening</a>
                <a href="Alumni-Applications.php" class="link">Applications</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing4">
                <a href="Events.php" class="link">
                    <i class='bx bx-calendar-event'></i>
                    <span class="name">Events</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Events.php" class="link submenu-title">Events</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing5">
                <a href="Alumni-Account.php" class="link">
                    <i class='bx bxs-user' ></i>
                    <span class="name">Account</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Alumni-Account.php" class="link submenu-title">Account</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown" id="signoutbtn">
            <div class="title" id="landing6">
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
        <a href="Alumni-Account.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Account</a>
        <div class="profile_content">
        <h5>Update Resume<span><i class='bx bxs-edit' ></i></span></h5>
        <form action="Alumni-Profile.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <div class="row justify-content-center">

            <div class="col-lg-12" style="margin-top: 20px; font-size: 13px; text-align: justify;">
                <div class="alert alert-warning" role="alert">
                    <label><b>Reminder:</b> To preview or generate your resume, please complete your profile details first. Kindly click the link below to update your profile.</label><br><br>

                    <a href="Alumni-Profile.php" style="color: #0D6EFD;">Click here to complete your Profile</a>
                </div>
            </div>


            <div class="col-lg-12">

             <?php

                $query = "SELECT * FROM tbl_alumni WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

            <img src="css/img/<?php echo $row['Image']; ?>" width="150" height="150" id="user_img" style="display: none;">

              <div class="form-group" style="margin-top: 10px;">
                <label>Full Name:</label>
                <input type="text" name="atn" id="atn" class="form-control" value="<?php echo $row['First_Name']; ?> <?php echo $row['Last_Name']; ?>" disabled>
              </div>
            </div>

             <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Contact Number:</label>
                <input type="text" name="contact_num" id="contact_num" class="form-control" value="<?php echo $row['Contact_Number']; ?>" disabled>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Email Address:</label>
                <input type="text" name="alumni_email" id="alumni_email" class="form-control" value="<?php echo $row['Email']; ?>" disabled>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>City & State:</label>
                <input type="text" name="city" id="city" class="form-control" value="<?php echo $row['City']; ?>, <?php echo $row['State']; ?>" disabled>
              </div>
            </div>

             <?php

                  }
              }

            ?>

            </div>

            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-12" id="resume_details">
        <div class="row justify-content-center">

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5 style="display: inline;">Personal Information:</h5>
                 <button class="preview" id="generate-pdf"><i class="fa fa-eye" aria-hidden="true"></i> Preview</button>

                 <?php

                $query = "SELECT * FROM tbl_alumni_citizen WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                $row = mysqli_fetch_assoc($query_run);
            } else {
                // Set default empty values if no data is available for this ID
                $row = [
                    'Citizenship' => '',
                ];
            }

            ?>
                <div class="resume_content">
                    <h6 id="Citizenship">Citizenship: <span style="font-weight: 100;"><?php echo $row['Citizenship']; ?></span>

                        <span class="update_resume"><a href="Alumni-Resume-Citizen.php"><i class='bx bxs-pencil' ></i></a></span></h6>
                </div>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Work Experience: <span class="add_resume"><a href="Alumni-Resume-AddWork.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_work WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content" id="work_sec">
                    <input type="hidden" name="work_id" id="work_id" value="<?php echo $row['ID']; ?>">
                    <h6 id="Job_Title"><?php echo $row['Job_Title']; ?><span class="update_resume"><a href="Alumni-Resume-EditWork.php?id=<?php echo $row['ID']; ?>"><i class='bx bxs-pencil' ></i></a></span></h6>

                    <p id="Company_Name"><?php echo $row['Company_Name']; ?></p>
                    <p id="job_date"><?php echo $row['From_Month']; ?> <?php echo $row['From_Year']; ?> - <?php echo $row['To_Month']; ?> <?php echo $row['To_Year']; ?></p>
                    <!-- <p id="job_description"><?php echo $row['Description']; ?></p> -->
                    <div id="job_description">
                        <?php echo $row['Description']; ?>
                    </div>
                </div>

                 <?php

                  }
              }

            ?>
              </div>
            </div>

             <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Education Attainment: <span class="add_resume"><a href="Alumni-Resume-AddEducation.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_education WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content" id="educ_sec">
                     <input type="hidden" name="educ_id" id="educ_id" value="<?php echo $row['ID']; ?>">
                    <h6 id="level"><?php echo $row['Level']; ?> <?php echo $row['Field']; ?><span class="update_resume"><a href="Alumni-Resume-EditEducation.php?id=<?php echo $row['ID']; ?>"><i class='bx bxs-pencil' ></i></a></span></h6>

                    <p id="school"><?php echo $row['School_Name']; ?> - <?php echo $row['City']; ?></p>
                    <p id="school_date"><?php echo $row['From_Month']; ?> <?php echo $row['From_Year']; ?> - <?php echo $row['To_Month']; ?> <?php echo $row['To_Year']; ?></p>
                </div>

             <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Skills: <span class="add_resume"><a href="Alumni-Resume-AddSkill.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_skill WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content" id="skill_sec">
                    <input type="hidden" name="skill_id" id="skill_id" value="<?php echo $row['ID']; ?>">
                    <h6 id="skill"><?php echo $row['Skill_Name']; ?> <span style="font-weight: 100;">- <?php echo $row['Esperience']; ?></span><span class="update_resume">
                        <a href="Alumni-Resume-EditSkill.php?id=<?php echo $row['ID']; ?>"><i class='bx bxs-pencil' ></i></a></span></h6>
                </div>

             <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Languages: <span class="add_resume"><a href="Alumni-Resume-AddLanguage.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                 <?php

                $query = "SELECT * FROM tbl_alumni_language WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content" id="lang_sec">
                    <input type="hidden" name="lang_id" id="lang_id" value="<?php echo $row['ID']; ?>">
                    <h6 id="lang"><?php echo $row['Language']; ?> <span style="font-weight: 100;">- <?php echo $row['Proficiency']; ?></span><span class="update_resume">
                        <a href="Alumni-Resume-EditLanguage.php?id=<?php echo $row['ID']; ?>"><i class='bx bxs-pencil' ></i></a></span></h6>
                </div>

             <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Links: <span class="add_resume"><a href="Alumni-Resume-AddLink.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_link WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content" id="link_sec">
                    <input type="hidden" name="link_id" id="link_id" value="<?php echo $row['ID']; ?>">
                    <h6 id="link_name"><a href="<?php echo $row['Link']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $row['Link']; ?></a><span class="update_resume">
                        <a href="Alumni-Resume-EditLink.php?id=<?php echo $row['ID']; ?>"><i class='bx bxs-pencil' ></i></a></span></h6>
                </div>

                <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Certifications and Licenses: <span class="add_resume"><a href="Alumni-Resume-AddCerti.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_certification WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content" id="certi_sec">
                    <input type="hidden" name="certi_id" id="certi_id" value="<?php echo $row['ID']; ?>">
                    <h6 id="certi"><?php echo $row['Certi_Name']; ?><span class="update_resume"><a href="Alumni-Resume-EditCerti.php?id=<?php echo $row['ID']; ?>"><i class='bx bxs-pencil' ></i></a></span></h6>

                    <p id="certi_date"><?php echo $row['From_Month']; ?> <?php echo $row['From_Year']; ?> - <?php echo $row['To_Month']; ?> <?php echo $row['To_Year']; ?></p>
                    <div id="certi_description">
                        <?php echo $row['Description']; ?>
                    </div>
                </div>

                 <?php

                  }
              }


            mysqli_close($con);

            ?>
              </div>
            </div>
           
            </div>
        </form>
    </div>
</div>
</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>

<!-- Sidebar Landing Page (JS FUNCTION) -->
<script>
    // Add a click event listener to redirect on click
    document.getElementById('landing1').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Home.php'; // Replace with your desired URL
    });

    document.getElementById('landing4').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Events.php'; // Replace with your desired URL
    });

    document.getElementById('landing5').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Alumni-Account.php'; // Replace with your desired URL
    });

    document.getElementById('landing6').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'logout-user.php'; // Replace with your desired URL
    });
</script>

<!-- GENERATE PDF (JS FUNCTION) -->

<script>
document.getElementById("generate-pdf").addEventListener("click", function () {
    // Get values from input fields for the header section
    const fullName = document.getElementById("atn").value;
    const contactNumber = document.getElementById("contact_num").value;
    const email = document.getElementById("alumni_email").value;
    const cityState = document.getElementById("city").value;

    // Initialize jsPDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const pageHeight = doc.internal.pageSize.height;
    const pageWidth = doc.internal.pageSize.width;
    const bottomPadding = 30; // Add 20 units of padding at the bottom
    let yPosition = 20;

    // Helper function to handle page breaks with padding
    function checkPageBreak(doc, yPosition) {
        if (yPosition + bottomPadding >= pageHeight) {
            doc.addPage();
            return 20; // Reset Y position for new page
        }
        return yPosition;
    }


    // Function to get the image data URL
    function getImageDataURL(image) {
        const canvas = document.createElement("canvas");
        canvas.width = image.width;
        canvas.height = image.height;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(image, 0, 0, image.width, image.height);
        return canvas.toDataURL("image/jpeg");
    }

    // Reference to the image in the HTML
    const imgElement = document.getElementById("user_img");

    // Check if the image exists and is loaded
    if (imgElement) {
        imgElement.onload = function () {
            const imageDataURL = getImageDataURL(imgElement);

            // Add the image to the top right
            doc.addImage(imageDataURL, 'JPEG', pageWidth - 55, 15, 37, 40); // Adjust size and position as needed

            // Set PDF title and header information
            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.text(fullName, 10, yPosition);
            yPosition += 10;

            doc.setFont("helvetica", "normal");
            doc.setFontSize(10);
            doc.text(cityState, 10, yPosition);
            yPosition += 6;

            doc.setTextColor(0, 0, 255); 
            doc.text(email, 10, yPosition);
            yPosition += 6;

            doc.setTextColor(0, 0, 0); 
            doc.text(contactNumber, 10, yPosition);
            yPosition += 20;

            // Section: Personal Details
            yPosition = checkPageBreak(doc, yPosition);
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Personal Details", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;

            const citizenText = document.getElementById("Citizenship").innerText;
            doc.setFont("helvetica", "normal");
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0); // Reset to black
            doc.text(`${citizenText}`, 15, yPosition);
            yPosition += 15;

            // Section: Work Experience
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Work Experience", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 13;

            document.querySelectorAll(".resume_content[id^='work_sec']").forEach(workSection => {
                yPosition = checkPageBreak(doc, yPosition);
                doc.setFont("helvetica", "bold");
                doc.setFontSize(13);
                doc.setTextColor(0, 0, 0); // Reset to black
                doc.text(workSection.querySelector("#Job_Title").innerText, 15, yPosition);
                yPosition += 7;

                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.setTextColor(128, 128, 128); // Gray color
                doc.text(workSection.querySelector("#Company_Name").innerText, 15, yPosition);
                yPosition += 5;

                doc.text(workSection.querySelector("#job_date").innerText, 15, yPosition);
                yPosition += 10;

                doc.setTextColor(0, 0, 0); // Reset to black
                const jobDescription = workSection.querySelector("#job_description").innerText;
                const splitDesc = doc.splitTextToSize(jobDescription, 180);
                splitDesc.forEach(line => {
                    yPosition = checkPageBreak(doc, yPosition);
                    doc.text(line, 15, yPosition);
                    yPosition += 6;
                });
                yPosition += 10;
            });

            // Section: Education
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Education Attainment", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;

            document.querySelectorAll(".resume_content[id^='educ_sec']").forEach(educationSection => {
                yPosition = checkPageBreak(doc, yPosition);
                doc.setFont("helvetica", "bold");
                doc.setFontSize(13);
                doc.setTextColor(0, 0, 0); // Reset to black
                doc.text(educationSection.querySelector("#level").innerText, 15, yPosition);
                yPosition += 7;

                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.text(educationSection.querySelector("#school").innerText, 15, yPosition);
                yPosition += 5;

                doc.setTextColor(128, 128, 128); // Gray color
                doc.text(educationSection.querySelector("#school_date").innerText, 15, yPosition);
                yPosition += 15;
            });

            // Section: Skills
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Skills", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;

            document.querySelectorAll(".resume_content[id^='skill_sec']").forEach(skillSection => {
                yPosition = checkPageBreak(doc, yPosition);
                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0); // Reset to black
                doc.text(`• ${skillSection.querySelector("#skill").innerText}`, 15, yPosition);
                yPosition += 7;
            });
            yPosition += 10;

            // Section: Languages
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Languages", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;

            document.querySelectorAll(".resume_content[id^='lang_sec']").forEach(languageSection => {
                yPosition = checkPageBreak(doc, yPosition);
                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0); // Reset to black
                doc.text(`• ${languageSection.querySelector("#lang").innerText}`, 15, yPosition);
                yPosition += 7;
            });
            yPosition += 10;

            // Section: Links
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Links", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;

            document.querySelectorAll(".resume_content[id^='link_sec']").forEach(linkSection => {
                yPosition = checkPageBreak(doc, yPosition);
                doc.setFont("helvetica", "normal");
                doc.setTextColor(0, 0, 255); // Blue for links
                doc.setFontSize(10);
                doc.text(linkSection.querySelector("#link_name").innerText, 15, yPosition);
                yPosition += 7;
            });
            yPosition += 10;

            // Section: Certifications and Licenses
            doc.setFont("helvetica", "bold");
            doc.setFontSize(14);
            doc.setTextColor(128, 128, 128); // Gray color
            doc.text("Certifications And Licenses", 10, yPosition);
            yPosition += 6;

            // Draw a horizontal line
            doc.setDrawColor(128, 128, 128); // Gray color
            doc.setLineWidth(0.3);
            doc.line(10, yPosition, 200, yPosition);
            yPosition += 10;

            document.querySelectorAll(".resume_content[id^='certi_sec']").forEach(certiSection => {
                yPosition = checkPageBreak(doc, yPosition);
                doc.setFont("helvetica", "bold");
                doc.setFontSize(13);
                doc.setTextColor(0, 0, 0); // Reset to black
                doc.text(certiSection.querySelector("#certi").innerText, 15, yPosition);
                yPosition += 7;

                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.setTextColor(128, 128, 128); // Gray color
                doc.text(certiSection.querySelector("#certi_date").innerText, 15, yPosition);
                yPosition += 10;

                doc.setTextColor(0, 0, 0); // Reset to black
                const certiDescription = certiSection.querySelector("#certi_description").innerText;
                const splitCertiDesc = doc.splitTextToSize(certiDescription, 180);
                splitCertiDesc.forEach(line => {
                    yPosition = checkPageBreak(doc, yPosition);
                    doc.text(line, 15, yPosition);
                    yPosition += 6;
                });
                yPosition += 10;
            });

            // Open the generated PDF in a new tab
            window.open(doc.output('bloburl'));
        };

        // Trigger the image loading if not already loaded
        if (imgElement.complete) {
            imgElement.onload();
        }
    }
});
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