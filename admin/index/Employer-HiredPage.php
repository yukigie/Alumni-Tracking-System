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
    <title>Employer | Hired</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
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

// Delete Data
if (isset($_POST['deletedata'])) {
    $ID = $_POST['delete_id'];
    $sql = "DELETE FROM tbl_employer_joblist WHERE ID=$ID";
    $sql1 = "DELETE FROM tbl_employer_jobsched WHERE Job_ID=$ID";
    $sql2 = "DELETE FROM tbl_employer_skill WHERE Job_ID=$ID";
    
    if ($con->query($sql) && $con->query($sql1) && $con->query($sql2)) {
        setAlert("Successfully Deleted!", "Job Is Successfully Deleted!", "success");
        header("Location: Employer-JobReport.php");
        exit;
    } else {
        setAlert("Data Not Deleted!", "Failed to delete the Job.", "error");
        header("Location: Employer-JobReport.php");
        exit;
    }
}

mysqli_close($con);

?>


<!-- View Modal -->
<div class="modal fade ViewModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2E6D43;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">View Applicant - Hired</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Applicant Details</h5>

      <form action="Employer-Applications.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id2" id="view_id2">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applicant Name:</label>
                <input type="text" name="applicant_name2" id="applicant_name2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="alumni_mail2" id="alumni_mail2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Con. Number:</label>
                <input type="text" name="alumni_num2" id="alumni_num2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applied Date:</label>
                <input type="date" name="alumni_applied2" id="alumni_applied2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Applied For:</label>
                <input type="text" name="alumni_job2" id="alumni_job2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Status:</label>
                <input type="text" name="alumni_status2" id="alumni_status2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <input type="text" name="alumni_address2" id="alumni_address2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px; display: block;">Applicant Resume:</label>
                  <button type="submit" class="preview" id="resumebtn" form="resume_form" style="float: left; margin-top: 10px; width: 70%;">Open Resume <i class="fa fa-file-text" aria-hidden="true"></i></button>
              </div>
            </div>

            <div class="col-lg-9">
              <div class="form-group" style="margin-top: 0px;">
                 <p style="font-size: 13px; width: 80%; margin-top: 50px;">Resumes generated by our system can be easily viewed in most web browsers (such as Chrome, Firefox, or Edge) or any PDF reader. Simply click on the button to open it. For security and privacy, please handle and store the document responsibly. Thank you!</p>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="alumni_desc2" id="alumni_desc2" class="form-control" readonly required></textarea>
              </div>
            </div>

            <input type="hidden" name="alumni_id2" id="alumni_id2" class="form-control" readonly required>

            <input type="hidden" name="alumni_jobid2" id="alumni_jobid2" class="form-control" readonly required>

            <input type="hidden" name="alumni_empmail2" id="alumni_empmail2" class="form-control" readonly required>

            <input type="hidden" name="alumni_img2" id="alumni_img2" class="form-control" readonly required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" name="" class="btn btn-primary upbtn" style="background-color: #2E6D43;" 
          onmouseover="this.style.backgroundColor='#1C4B2C';" 
          onmouseout="this.style.backgroundColor='#2E6D43';">Send Message</button>
          
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
        require 'connection.php';

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

    <!-- <section class="home">
        <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        <div class="head-title">
            <h2>Dashboard Page</h2>
        </div>
    </section> -->

    <section class="home">

        <div class="head-title">
            <h2>Hired Applicants</h2>
            <p><a href="">Dashboard</a> / <a href="Employer-Applications.php">Application Report</a> / Hired Applicants</p>
        </div>

        <div class="container_my-5">
    <!-- Button trigger modal -->
    <a href="Employer-Applicant.php" style="font-weight: 400;"><button type="button" class="btn btn-primary" id="new">
        <i class="fa fa-search" aria-hidden="true"></i><span>Search For Applicants</span>
    </button></a>

    <form action="Employer-HiredPage.php" method="POST" id="resume_form" autocomplete="off">
        <input type="hidden" name="get_email" id="get_email">
    </form>
    <br><br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Con. Number</th>
                    <th scope="col">Job Applied For</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Applied Date</th>
                    <th scope="col" style="display: none;">Address</th> 
                    <th scope="col" style="display: none;">Description</th>       
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_hired WHERE Email_Employer = '{$email}'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Email_Alumni]</td>
                        <td><img src='css/img/$row[Image]' width='60' height='60'></td>
                        <td>$row[Contact_Number]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Status] <i class='fa fa-check-circle' style='font-size:20px; color: #267B44;'></i></td>
                        <td style='display: none;'>$row[Applied_Date]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn' style='padding: 8px 15px;'>
                                <i class='fa fa-eye' aria-hidden='true'></i><span>VIEW</span>
                            </button>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</section>


<!-- RESUME DATA BASED ON THE SELECTED APPLICANT -->

<div class="divresume" style="display: none;">

<?php

if (isset($_POST['resumebtn'])) {
    $get_email = $_POST['get_email'];

    // Start output buffering to capture HTML
    ob_start();

    // Fetch data from tbl_alumni
    $query = "SELECT * FROM tbl_alumni WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    $check_data = mysqli_num_rows($query_run) > 0;

    if ($check_data) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            // Only output the content that needs to be displayed
            echo "<img src='css/img/{$row['Image']}' width='150' height='150' id='user_img'>";
            echo "<input type='text' id='atn' class='form-control' value='{$row['First_Name']} {$row['Last_Name']}' readonly>";
            echo "<input type='text' id='contact_num' class='form-control' value='{$row['Contact_Number']}' readonly>";
            echo "<input type='text' id='alumni_email' class='form-control' value='{$row['Email']}' readonly>";
            echo "<input type='text' id='city' class='form-control' value='{$row['City']}, {$row['State']}' readonly>";
        }
    }

    // Fetch data from tbl_alumni_citizen
    $query = "SELECT * FROM tbl_alumni_citizen WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        echo "<div class='resume_content'><span style='font-weight: 100;'>{$row['Citizenship']}</span></div>";
    }

    // Fetch data from tbl_alumni_work
    $query = "SELECT * FROM tbl_alumni_work WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='work_sec'>";
            echo "<h6 id='Job_Title'>{$row['Job_Title']}<span class='update_resume'></span></h6>";
            echo "<p id='Company_Name'>{$row['Company_Name']}</p>";
            echo "<p id='job_date'>{$row['From_Month']} {$row['From_Year']} - {$row['To_Month']} {$row['To_Year']}</p>";
            echo "<div id='job_description'>{$row['Description']}</div>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_education
    $query = "SELECT * FROM tbl_alumni_education WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='educ_sec'>";
            echo "<h6 id='level'>{$row['Level']} {$row['Field']}</h6>";
            echo "<p id='school'>{$row['School_Name']} - {$row['City']}</p>";
            echo "<p id='school_date'>{$row['From_Month']} {$row['From_Year']} - {$row['To_Month']} {$row['To_Year']}</p>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_skill
    $query = "SELECT * FROM tbl_alumni_skill WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='skill_sec'>";
            echo "<h6 id='skill'>{$row['Skill_Name']} <span style='font-weight: 100;'>- {$row['Esperience']}</span></h6>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_language
    $query = "SELECT * FROM tbl_alumni_language WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='lang_sec'>";
            echo "<h6 id='lang'>{$row['Language']} <span style='font-weight: 100;'>- {$row['Proficiency']}</span></h6>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_link
    $query = "SELECT * FROM tbl_alumni_link WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='link_sec'>";
            echo "<h6 id='link_name'><a href='{$row['Link']}' target='_blank' rel='noopener noreferrer'>{$row['Link']}</a></h6>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_certification
    $query = "SELECT * FROM tbl_alumni_certification WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='certi_sec'>";
            echo "<h6 id='certi'>{$row['Certi_Name']}<span class='update_resume'></span></h6>";
            echo "<p id='certi_date'>{$row['From_Month']} {$row['From_Year']} - {$row['To_Month']} {$row['To_Year']}</p>";
            echo "<div id='certi_description'>{$row['Description']}</div>";
            echo "</div>";
        }
    }

    // Closing tags for any additional sections
    // Close the database connection if needed
    mysqli_close($con);

    // Get the content and clean the output buffer
    $responseHtml = ob_get_clean();
    echo $responseHtml; // Only output the relevant HTML content
    exit; // Stop further execution
}
?>

</div>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>

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

<!-- GENERATE PDF (JS FUNCTION) -->
<script>
$(document).ready(function () {
    $("#resume_form").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        let postData = $(this).serializeArray();
        postData.push({ name: "resumebtn", value: "true" }); // Include the `resumebtn` field

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: postData,
            success: function (data) {
                $(".divresume").html(data); // Load response into .divresume
                generatePDF();

                // Reload the page after successful form submission and PDF generation
                setTimeout(function () {
                    location.reload();
                }, 1000); // Adjust delay if needed
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown);
            }
        });
    });
});

function generatePDF() {
    const fullName = document.getElementById("atn")?.value || "Full Name";
    const contactNumber = document.getElementById("contact_num")?.value || "Contact Number";
    const email = document.getElementById("alumni_email")?.value || "Email";
    const cityState = document.getElementById("city")?.value || "City, State";
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const pageWidth = doc.internal.pageSize.width;
    const marginLeft = 15;
    let yPosition = 20;
    const lineHeight = 7;

    // Helper function for page breaks
    function checkPageBreak() {
        if (yPosition > doc.internal.pageSize.height - 30) { // Account for bottom padding
            doc.addPage();
            yPosition = 20;
        }
    }

    // Add Header
    doc.setFontSize(18).setFont("helvetica", "bold");
    doc.text(fullName, marginLeft, yPosition);
    yPosition += 10;

    doc.setFontSize(10).setFont("helvetica", "normal");
    doc.text(cityState, marginLeft, yPosition);
    yPosition += 6;

    doc.setTextColor(0, 0, 255);
    doc.text(email, marginLeft, yPosition);
    yPosition += 6;

    doc.setTextColor(0, 0, 0);
    doc.text(contactNumber, marginLeft, yPosition);
    yPosition += 10;

    // Add Image
    const imgElement = document.getElementById("user_img");
    if (imgElement && imgElement.complete) {
        const imageDataURL = imgElement.src;
        const imgWidth = 37; // Adjust image size
        const imgHeight = 40;
        const imgX = pageWidth - 55; // Position to the right
        doc.addImage(imageDataURL, "JPEG", imgX, 15, imgWidth, imgHeight);
        yPosition += 10; // Adjust spacing
    }

    // Horizontal Line Styling
    function addSectionHeader(title) {
        checkPageBreak();
        doc.setFontSize(14).setFont("helvetica", "bold").setTextColor(128, 128, 128); // Gray
        doc.text(title, marginLeft, yPosition);
        yPosition += lineHeight;

        doc.setDrawColor(128, 128, 128); // Gray
        doc.setLineWidth(0.3);
        doc.line(marginLeft, yPosition, pageWidth - marginLeft, yPosition); // Horizontal line
        yPosition += 10;
    }

    // Add Personal Details Section
    addSectionHeader("Personal Details");

    doc.setFontSize(12).setFont("helvetica", "normal").setTextColor(0, 0, 0); // Black text
    const citizenText = document.querySelector(".resume_content").innerText || "Citizen Text";
    doc.text(`Citizenship: ${citizenText}`, marginLeft + 5, yPosition);
    yPosition += lineHeight + 10;

    // Add Work Experience Section
    addSectionHeader("Work Experience");

    document.querySelectorAll(".resume_content[id^='work_sec']").forEach(workSection => {
        checkPageBreak();
        doc.setFontSize(13).setFont("helvetica", "bold").setTextColor(0, 0, 0); // Black
        doc.text(workSection.querySelector("#Job_Title").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 2;

        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(128, 128, 128); // Gray color
        doc.text(workSection.querySelector("#Company_Name").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;

        doc.text(workSection.querySelector("#job_date").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 3;

        doc.setTextColor(0, 0, 0); // Black
        const jobDescription = workSection.querySelector("#job_description").innerText;
        const splitDesc = doc.splitTextToSize(jobDescription, pageWidth - 2 * marginLeft);
        splitDesc.forEach(line => {
            checkPageBreak();
            doc.text(line, marginLeft + 5, yPosition);
            yPosition += lineHeight;
        });
        yPosition += 10;
    });

    // Add Education Section
    addSectionHeader("Education Attainment");

    document.querySelectorAll(".resume_content[id^='educ_sec']").forEach(educationSection => {
        checkPageBreak();
        doc.setFontSize(13).setFont("helvetica", "bold").setTextColor(0, 0, 0); // Black
        doc.text(educationSection.querySelector("#level").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 2;

        doc.setFontSize(10).setFont("helvetica", "normal");
        doc.text(educationSection.querySelector("#school").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;

        doc.setTextColor(128, 128, 128); // Gray color
        doc.text(educationSection.querySelector("#school_date").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 10;
    });

    // Add Skills Section
    addSectionHeader("Skills");

    document.querySelectorAll(".resume_content[id^='skill_sec']").forEach(skillSection => {
        checkPageBreak();
        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(0, 0, 0); // Black
        doc.text(`• ${skillSection.querySelector("#skill").innerText}`, marginLeft + 5, yPosition);
        yPosition += lineHeight;
    });

    // Add Languages Section
    yPosition += 10;
    addSectionHeader("Languages");

    document.querySelectorAll(".resume_content[id^='lang_sec']").forEach(languageSection => {
        checkPageBreak();
        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(0, 0, 0); // Black
        doc.text(`• ${languageSection.querySelector("#lang").innerText}`, marginLeft + 5, yPosition);
        yPosition += lineHeight;
    });

    // Add Links Section
    yPosition += 10;
    addSectionHeader("Links");

    document.querySelectorAll(".resume_content[id^='link_sec']").forEach(linkSection => {
        checkPageBreak();
        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(0, 0, 255); // Blue for links
        doc.text(linkSection.querySelector("#link_name").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;
    });

    // Add Certifications Section
    yPosition += 10;
    addSectionHeader("Certifications And Licenses");

    document.querySelectorAll(".resume_content[id^='certi_sec']").forEach(certiSection => {
        checkPageBreak();
        doc.setFontSize(13).setFont("helvetica", "bold").setTextColor(0, 0, 0); // Black
        doc.text(certiSection.querySelector("#certi").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;

        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(128, 128, 128); // Gray color
        doc.text(certiSection.querySelector("#certi_date").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 3;

        doc.setTextColor(0, 0, 0); // Black
        const certiDescription = certiSection.querySelector("#certi_description").innerText;
        const splitDesc = doc.splitTextToSize(certiDescription, pageWidth - 2 * marginLeft);
        splitDesc.forEach(line => {
            checkPageBreak();
            doc.text(line, marginLeft + 5, yPosition);
            yPosition += lineHeight;
        });
        yPosition += 10;
    });

    // Open the generated PDF in a new tab
    window.open(doc.output("bloburl"));
}
</script>


<!-- Datatable Searchbox (JS FUNCTION) -->

<script>
    $(document).ready(function () {
        // Initialize DataTable
        const table = $('#myTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [5, 25, 50, -1],
                [5, 25, 50, "All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search here.....",
            }
        });

        // Event delegation for .editbtn within #myTable to support pagination
        $('#myTable').on('click', '.editbtn', function () {
            // Show the ViewModal
            $('.ViewModal').modal('show');

            // Get data from the closest row
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            console.log(data);

            // Populate modal fields with data from the selected row
            $('#view_id2').val(data[0]);
            $('#applicant_name2').val(data[1]);
            $('#alumni_mail2').val(data[2]);
            $('#get_email').val(data[2]);
            $('#alumni_num2').val(data[4]);
            $('#alumni_job2').val(data[5]);
            $('#alumni_status2').val(data[6]);
            $('#alumni_applied2').val(data[7]);
            $('#alumni_address2').val(data[8]);
            $('#alumni_desc2').val(data[9]);
   
        });
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