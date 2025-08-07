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

// Handle the AJAX request
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Query to fetch the job data based on the job ID
    $query = "SELECT * FROM tbl_employer_joblist WHERE ID = '$id'";
    $query_run = mysqli_query($con, $query);

    // Query to fetch the skill data based on the job ID
    $query1 = "SELECT * FROM tbl_employer_skill WHERE Job_ID = '$id'";
    $query_run1 = mysqli_query($con, $query1);

    // Query to fetch the Job sched data based on the job ID
    $query2 = "SELECT * FROM tbl_employer_jobsched WHERE Job_ID = '$id'";
    $query_run2 = mysqli_query($con, $query2);

    if (mysqli_num_rows($query_run) > 0 && mysqli_num_rows($query_run2) > 0) {
        $row = mysqli_fetch_assoc($query_run);  // Job data
        $row2 = mysqli_fetch_assoc($query_run2); // Job schedule data

        // Fetch all skills into an array
        $skills = [];
        while ($row1 = mysqli_fetch_assoc($query_run1)) {
            $skills[] = $row1['Skill_Name']; // Add each skill to the array
        }

        // Combine job data with skills and job schedule
        $combined_data = array_merge($row, $row2);  // Merge job data and job schedule data
        $combined_data['skills'] = $skills;         // Add the skills array to the combined data

        // Return the combined data as JSON
        echo json_encode($combined_data);
    } else {
        // Return an empty JSON if no data found
        echo json_encode([]);
    }

    exit; // Ensure no further processing happens after the AJAX response
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alumni | Applications</title>
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

// Insert or Update Data
if (isset($_POST['applybtn'])) {
    $id_int = $_POST['id_int'];
    $job_name_int = $_POST['job_name_int'];
    $atn = $_POST['atn'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $alumni_state = $_POST['alumni_state'];
    $alumni_city = $_POST['alumni_city'];
    $postal = $_POST['postal'];
    $about = $_POST['about'];
    $emp_email = $_POST['emp_email'];
    $alumni_con = $_POST['alumni_con'];
    $alumni_img = $_POST['alumni_img'];

    // Insert application record
    $query = "INSERT INTO tbl_employer_application (Alumni_ID, Job_ID, Applicant_Name, Contact_Number, Applied_Date, Job_Title, Status, Address, Description, Email_Employer, Email_Alumni, Image) 
              VALUES ('{$atn}', '{$id_int}', CONCAT('$fname', ' ', '$lname'), '{$alumni_con}', NOW(), '{$job_name_int}', 'Applied', CONCAT('$alumni_state', ', ', '$alumni_city', ', ', '$postal'), '{$about}', '{$emp_email}', '{$email}', '{$alumni_img}')";

    if (mysqli_query($con, $query)) {
        // Update Available_Positions and Job_Applicants in tbl_employer_joblist
        $updateQuery = "UPDATE tbl_employer_joblist 
                        SET Available_Positions = Available_Positions - 1, 
                            Job_Applicants = Job_Applicants + 1 
                        WHERE ID = '{$id_int}'";
        
        if (mysqli_query($con, $updateQuery)) {
            setAlert("Successfully Apply!", "Check your Applications for the Status", "success");
        } else {
            setAlert("Update Error!", "Failed to update job listing positions.", "error");
        }
    } else {
        setAlert("Data Not Updated!", "Failed to update Your Resume Details.", "error");
    }

    header("Location: Alumni-Jobs.php");
    exit;
}

// Delete Data
if (isset($_POST['cancelbtn'])) {
    $ID = $_POST['id_int'];

    $sql = "DELETE FROM tbl_employer_application WHERE Job_ID=$ID AND Email_Alumni = '$email'";
    
    if ($con->query($sql)) {
        
        $updateQuery = "UPDATE tbl_employer_joblist 
                        SET Available_Positions = Available_Positions + 1, 
                            Job_Applicants = Job_Applicants - 1 
                        WHERE ID = '{$ID}'";
        
        if (mysqli_query($con, $updateQuery)) {
            setAlert("Application Cancelled!", "Your Application has been successfully removed!", "success");
        } else {
            setAlert("Update Error!", "Failed to update job listing positions.", "error");
        }
    } else {
        setAlert("Data Not Deleted!", "Failed to delete the Job.", "error");
    }

    header("Location: Alumni-Jobs.php");
    exit;
}

?>


<!-- Job Modal -->
<div class="modal fade" id="JobModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 15px;">
        <div class="modal-header">
            <img id="Image" src="">
      </div>
      <div class="modal-body">
        <form action="Alumni-Jobs.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <input type="hidden" name="id_int" id="id_int" class="form-control">
        <input type="hidden" name="emp_email" id="emp_email">
        <input type="hidden" name="job_name_int" id="job_name_int">
        <input type="hidden" name="job_position_int" id="job_position_int">
        <input type="hidden" name="job_closedate_int" id="job_closedate_int">

        <?php

                $query = "SELECT * FROM tbl_employer_application WHERE Email_Alumni ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {

            ?>

            <input type="hidden" name="Job_ID_Stats[]" value="<?php echo $row['Job_ID']; ?>">
            <input type="hidden" name="Job_Email_Stats[]" value="<?php echo $row['Email_Alumni']; ?>">
            <input type="hidden" name="Job_Stats[]" value="<?php echo $row['Status']; ?>">

            <?php

                  }
              }

            ?>

        <?php

                $query = "SELECT * FROM tbl_alumni WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {

            ?>

            <input type="hidden" name="atn" id="atn" value="<?php echo $row['Alumni_ID']; ?>">
            <input type="hidden" name="fname" id="fname" value="<?php echo $row['First_Name']; ?>">
            <input type="hidden" name="lname" id="lname" value="<?php echo $row['Last_Name']; ?>">
            <input type="hidden" name="alumni_state" id="alumni_state" value="<?php echo $row['State']; ?>">
            <input type="hidden" name="alumni_city" id="alumni_city" value="<?php echo $row['City']; ?>">
            <input type="hidden" name="postal" id="postal" value="<?php echo $row['Postal']; ?>">
            <input type="hidden" name="about" id="about" value="<?php echo $row['About']; ?>">
            <input type="hidden" name="alumni_con" id="alumni_con" value="<?php echo $row['Contact_Number']; ?>">
            <input type="hidden" name="alumni_Email" id="alumni_Email" value="<?php echo $row['Email']; ?>">
            <input type="hidden" name="alumni_img" id="alumni_img" value="<?php echo $row['Image']; ?>">

            <?php

                  }
              }

            ?>

        <div class="row">
            <div class="col-lg-6">
                <h4 name="job_name" id="job_name"></h4>
                <p>By. <b><span name="com_name" id="com_name"></span></b></p>
                <p>Located In. <span name="city" id="city"></span>, <b><span name="state" id="state"></b></span></p>
                <b><p style="opacity: 0.7;">PHP <span name="salary" id="salary"></span></p></b>
                <p class="jobsched" name="day" id="day"></p>
                <p class="jobsched" name="shift" id="shift"></p>
                <p class="jobsched" name="type" id="type"></p>
            </div>

            <div class="col-lg-6" id="coltbtn">
                <button class="applybtn" type="submit" name="applybtn">APPLY NOW</button>

                <button class="cancelbtn" type="submit" name="cancelbtn" style="margin-top: 10px; background-color: gray; display: none;">Cancel Application</button>

                <p class="appclose" style="display: none; margin-top: 5px;"></p>
            </div>

            <div class="col-lg-12" style="padding-left: 0px; margin-bottom: 10px;">
                <hr>
            </div>

            <div class="col-lg-6">
                
                <h5><i class="fa fa-money" aria-hidden="true"></i> Salary</h5>
                <p class="jobdescript">PHP <span name="salary1" id="salary1"></span></p>

                <h5><i class="fa fa-briefcase" aria-hidden="true"></i> Job Type</h5>
                <p name="type1" id="type1" class="jobdescript"></p>
            </div>

            <div class="col-lg-6">
                
                <h5><i class="fa fa-users" aria-hidden="true"></i> Available Position</h5>
                <p class="jobdescript"><span name="positions" id="positions"></span></p>
                <p name="applicants" id="applicants" style="display: none;"></p>

                <h5><i class="fa fa-clock-o" aria-hidden="true"></i> Application Close Date</h5>
                <p class="jobdescript" name="closedate" id="closedate"></p>

            </div>

            <div class="col-lg-12" style="margin-top: 20px; padding-right: 20px;">
                <h5>Job Description</h5>
                <p name="description" id="description"></p>
            </div>

            <div class="col-lg-12" style="margin-top: 20px;">
                <h5>Skills Required</h5>
                <p name="SkillsDescript" id="SkillsDescript"></p>
                <ul id="skills"></ul>
            </div>

        </div>
      </div>
  </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


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

<div class="col-lg-12">


    <a href="Alumni-Archive.php"><button class="hirebtn" style="margin-top: 20px; background-color: #9B2626;"  
          onmouseover="this.style.backgroundColor='#781D1D';" 
          onmouseout="this.style.backgroundColor='#9B2626';">Archive <span><i class="fa fa-archive" aria-hidden="true" style="color: #FFBEBE;"></i></span></button></a>


    <div class="dropdown1">
    <button class="dropdown-toggle1" id="statusFilterButton">Show All &emsp;▼</button>
    <div class="dropdown-content1">
        <div class="dropdown-item1" data-status="All">Show All</div>
        <div class="dropdown-item1" data-status="Status: Applied">Applied <span class="status-dot" style="color: #65A67B; font-size: 20px;">◉</span></div>
        <div class="dropdown-item1" data-status="Status: Screening">Screening <span class="status-dot" style="color: #FBDD42; font-size: 20px;">◉</span></div>
        <div class="dropdown-item1" data-status="Status: Interview">Interview <span class="status-dot" style="color: #4A9FBE; font-size: 20px;">◉</span></div>
        <div class="dropdown-item1" data-status="Status: Hired">Hired <span class="status-dot" style="color: #125428; font-size: 20px;">◉</span></div>
    </div>
</div>

    <div class="jobtabs">
        <div class="tab-content" >
            <p>Check your application status</p>
            <div class="row">
        <?php
            $query = "SELECT tbl_employer_joblist.*, tbl_employer_application.Status,
                        tbl_employer_application.Interview_DateTime,
                        tbl_employer_application.Interview_Link,
                        tbl_employer_application.Interview_Note
                      FROM tbl_employer_joblist
                      INNER JOIN tbl_employer_application
                      ON tbl_employer_joblist.ID = tbl_employer_application.Job_ID
                      AND tbl_employer_joblist.Email = tbl_employer_application.Email_Employer
                      AND tbl_employer_application.Email_Alumni = '{$email}'
                      ORDER BY tbl_employer_joblist.ID DESC";

            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                while ($row = mysqli_fetch_assoc($query_run)) {

                    $statushired = $row['Status'];

                    // Check if interview-related fields are filled
                    $hasNote = !empty($row['Interview_DateTime']) || !empty($row['Interview_Link']) || !empty($row['Interview_Note']);

                    $displayStyle = 'none';

                    if ($statushired == 'Interview') {
                        if ($hasNote) {
                            $displayStyle = 'block';
                        }

                        $originalDateTime = $row['Interview_DateTime'];
                        if (!empty($originalDateTime)) {
                            $formattedDateTime = date("F j, Y, g:i A", strtotime($originalDateTime));
                        } else {
                            $formattedDateTime = '';  
                        }

                    } else {
                        $displayStyle = 'none';

                        if ($hasNote) {
                            $displayStyle = 'none';
                        }
                        $originalDateTime = $row['Interview_DateTime'];
                        if (!empty($originalDateTime)) {
                            $formattedDateTime = date("F j, Y, g:i A", strtotime($originalDateTime));
                        } else {
                            $formattedDateTime = '';  
                        }
                    }

        ?>
                <div class="col-lg-4">
                    <div class="card">
                      <div class="card-body">
                        <input type="hidden" name="job_id" id="job_id" value="<?php echo $row['ID']; ?>">

                        <h5 class="card-title"><?php echo $row['Job_Title']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['Company_Name']; ?></h6>
                        <p class="card-subtitle mb-2 text-muted"><?php echo $row['City']; ?>, <?php echo $row['State']; ?></p>

                        <div class="details"><?php echo $row['Sched_Day']; ?></div>
                        <div class="details"><?php echo $row['Salary']; ?> Salary</div>

                        <p class="card-text" id="descript-text1"><?php echo $row['Description']; ?></p>

                        <p class="postedate text-muted" data-postdate="<?php echo date('Y-m-d', strtotime($row['Job_Start_Date'])); ?>"></p>

                         <p class="status-text"><span>Status: </span><?php echo $row['Status']; ?></p>


                      </div>
                    </div>

                <div class="div-note" style="padding: 10px; background-color: #93D7F0; border-radius: 10px; border: 1px solid #555; display: <?php echo $displayStyle; ?>;">
                    <p style="font-weight: 600;">Interview Schedule: </p>
                    <p style="font-size: 13px; margin-top: -5px;">
                            <i class="fa fa-clock-o" aria-hidden="true"></i> 
                            <?php echo htmlspecialchars($formattedDateTime); ?>
                    </p>
                    <a href="<?php echo htmlspecialchars($row['Interview_Link']); ?>"><p style="font-size: 13px; margin-top: -10px; color: #333;">
                            <i class="fa fa-link" aria-hidden="true"></i> 
                            <?php echo htmlspecialchars($row['Interview_Link']); ?>
                    </p></a>
                    <i>
                        <p style="font-size: 14px; margin-top: -10px;">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> 
                            <?php echo htmlspecialchars($row['Interview_Note']); ?>
                        </p>
                    </i>
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

<!-- JavaScript for Filtering Status Card -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const statusFilterButton = document.getElementById("statusFilterButton");
    const dropdownItems = document.querySelectorAll(".dropdown-item1");
    const jobCards = document.querySelectorAll(".card");

    // Event listener for dropdown items
    dropdownItems.forEach((item) => {
        item.addEventListener("click", function () {
            const selectedStatus = this.getAttribute("data-status");

            // Update button text
            statusFilterButton.innerText = selectedStatus + " ▼";

            // Filter job listings based on status
            jobCards.forEach((card) => {
                const jobStatus = card.querySelector(".status-text").innerText.trim();

                if (selectedStatus === "All" || jobStatus === selectedStatus) {
                    card.parentElement.style.display = "block"; // Show the card's column
                } else {
                    card.parentElement.style.display = "none"; // Hide the card's column
                }
            });
        });
    });
});

</script>

<!-- Define color map based on status value (JS FUNCTION) -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const statusTexts = document.querySelectorAll(".status-text"); // Select all elements with the class .status-text

    // Define color map based on status value
    const statusColors = {
        "Status: Applied": "#65A67B",
        "Status: Screening": "#BEAB4A",
        "Status: Interview": "#4A9FBE",
        "Status: Hired": "#125428"
    };

    // Loop through each status-text element
    statusTexts.forEach(statusText => {
        const statusValue = statusText.textContent.trim(); // Get and trim the text content

        // Set the background color if a match is found
        if (statusColors[statusValue]) {
            statusText.style.backgroundColor = statusColors[statusValue];
            statusText.style.color = "#FFFFFF"; // Set text color to white for readability
        }
    });
});
</script>


<!-- GET MODAL DISPLAY (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const jobModal = document.getElementById('JobModal');
    
    jobModal.addEventListener('hide.bs.modal', function () {
        const jobId = document.getElementById('id_int').value; // Example: Fetch job ID from a hidden input field
        
        // Fetch the updated content for the target section
        fetch(`Alumni-Jobs-Update.php?job_id=${jobId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('coltbtn').innerHTML = html;
            })
            .catch(error => console.error('Error fetching content:', error));
    });
});

</script>

<!-- TEXT DESCRIPTION DISPLAY (JS FUNCTION) -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const maxLength = 200; // Adjust the max length of characters to display before truncating
    
    // Select all elements with ids `descript-text` and `descript-text1`
    const descriptionElements = document.querySelectorAll("#descript-text, #descript-text1");

    descriptionElements.forEach(descriptionElement => {
        const fullText = descriptionElement.textContent;

        if (fullText.length > maxLength) {
            const truncatedText = fullText.substring(0, maxLength) + "... ";
            
            // Update the element's text to truncated version
            descriptionElement.textContent = truncatedText;
            
            // Create "See More" link
            const seeMoreLink = document.createElement("a");
            seeMoreLink.href = "#";
            seeMoreLink.textContent = "See More";
            seeMoreLink.style.color = "darkgreen"; // Optional: add style to the link
            seeMoreLink.style.cursor = "pointer";
            
            // Append "See More" link to the description element
            descriptionElement.appendChild(seeMoreLink);
            
        }
    });
});

</script>

<!-- JOB POP UP FORM (JS FUNCTION) -->
<script>
    $(document).ready(function () {
        // Function to calculate days between two dates
        function calculateDaysToClose(closeDateStr) {
            var closeDate = new Date(closeDateStr);
            var today = new Date();
            
            today.setHours(0, 0, 0, 0);
            closeDate.setHours(0, 0, 0, 0);

            var timeDifference = closeDate.getTime() - today.getTime();
            var daysRemaining = Math.ceil(timeDifference / (1000 * 3600 * 24));

            if (daysRemaining > 0) {
                return `Application Closed in ${daysRemaining} day/s`;
            } else if (daysRemaining === 0) {
                return `Application Closed today`;
            } else {
                return `Application Closed`;
            }
        }

// Function to disable the APPLY NOW button based on conditions
function checkApplicationAvailability(availablePositions, closeDateStatus) {
            var applyButton = $('.applybtn');
            var applyClose = $('.appclose');
            var cancelButton = $('.cancelbtn');
            var jobIdStatsElements = document.querySelectorAll("input[name='Job_ID_Stats[]']");
            var jobEmailStatsElements = document.querySelectorAll("input[name='Job_Email_Stats[]']");
            var jobStatsElements = document.querySelectorAll("input[name='Job_Stats[]']");
            var SelJobId = document.getElementById("id_int").value;
            var SelEmailName = document.getElementById("alumni_Email").value;

            
            if (availablePositions === '0' || closeDateStatus === 'Application Closed' || closeDateStatus === 'Application Closed today') {
        applyButton.prop('disabled', true).css('background-color', 'gray');
        applyButton.css('cursor', 'default');

                if (availablePositions === '0' && closeDateStatus === 'Application Closed' || closeDateStatus === 'Application Closed today') {

                    applyClose.css('display', 'block');
                    applyClose.text('Job is Not Available');
                    applyButton.css('cursor', 'default');
                }

                else if (availablePositions === '0') {

                    applyClose.css('display', 'block');
                    applyClose.text('Application List is Already Full');
                }

                else if (closeDateStatus === 'Application Closed' || closeDateStatus === 'Application Closed today') {

                    applyClose.css('display', 'block');
                    applyClose.text('Application is Closed');
                    applyButton.css('cursor', 'default');
                }

                else {
                    applyClose.css('display', 'none');
                }

            } else {
                applyButton.prop('disabled', false);
            }

jobIdStatsElements.forEach(function(jobIdElement, index) { 
    // Get the corresponding job email and status elements by the same index
    var jobEmailElement = jobEmailStatsElements[index];
    var jobStatusElement = jobStatsElements[index];

    if (jobIdElement.value === SelJobId && jobEmailElement.value === SelEmailName) {
        if (jobStatusElement.value === 'Applied') {
            applyButton.prop('disabled', true).css('background-color', '#C2AE44').text('Application Submitted');
            applyButton.css('cursor', 'default');
            cancelButton.css('display', 'inline');
        } else if (jobStatusElement.value === 'Screening') {
            applyButton.prop('disabled', true).css('background-color', '#C2AE44').text('For Screening');
            applyButton.css('cursor', 'default');
            cancelButton.css('display', 'none');
            applyClose.css('display', 'block');
            applyClose.text('Your Application is Up for Screening!');
        } else if (jobStatusElement.value === 'Interview') {
            applyButton.prop('disabled', true).css('background-color', '#4A9FBE').text('For Interview');
            applyButton.css('cursor', 'default');
            cancelButton.css('display', 'none');
            applyClose.css('display', 'block');
            applyClose.text('You are Set for this Job Interview!');
        } else if (jobStatusElement.value === 'Hired') {
            applyButton.prop('disabled', true).css('background-color', '#125428').text('Job Accepted!');
            applyButton.css('cursor', 'default');
            cancelButton.css('display', 'none');
            applyClose.css('display', 'block');
            applyClose.text('You are Hired for this Job!');
        } else {
            applyButton.prop('disabled', false).css('background-color', '').text('APPLY NOW');
            applyButton.css('cursor', '');
            cancelButton.css('display', 'none');
        }

        // Stop the loop once a match is found
        return;
    }
});

        }

        // When any card is clicked
        $('.card').on('click', function () {
            $('#JobModal').modal('show');
            var jobId = $(this).find('#job_id').val();

            $.ajax({
                url: '', 
                type: 'POST',
                data: { id: jobId },
                success: function (response) {
                    console.log("Response:", response);
                    try {
                        var jobData = JSON.parse(response);

                        if (jobData && jobData.ID) {
                            $('#id_int').val(jobData.Job_ID);
                            
                            if (jobData.Image) {
                                $('#Image').attr('src', 'css/img/' + jobData.Image).show();
                            } else {
                                $('#Image').attr('src', 'css/img/default.png');
                            }

                            $('#emp_email').val(jobData.Email);
                            $('#job_name').text(jobData.Job_Title);
                            $('#job_name_int').val(jobData.Job_Title);
                            $('#com_name').text(jobData.Company_Name);
                            $('#city').text(jobData.City);
                            $('#state').text(jobData.State);
                            $('#salary').text(jobData.Salary);
                            $('#salary1').text(jobData.Salary);
                            $('#type').text(jobData.Job_Type);
                            $('#type1').text(jobData.Job_Type);

                            var availablePositions = jobData.Available_Positions;
                            $('#positions').text(availablePositions + " Available Position(s)");
                            $('#job_position_int').val(availablePositions);

                            $('#applicants').text(jobData.Job_Applicants);

                            $('#description').text(jobData.Description);
                            $('#day').text(jobData.Day);
                            $('#shift').text(jobData.Shift);
                            $('#type').text(jobData.Type);
                            $('#SkillsDescript').text(jobData.Skills);

                            var closeDateStr = jobData.Job_Close_Date;
                            var result = calculateDaysToClose(closeDateStr);
                            $('#closedate').text(result);
                            $('#job_closedate_int').val(result);

                            checkApplicationAvailability(availablePositions, result); // Call the disable function

                            $('#skills').empty();
                            if (jobData.skills && jobData.skills.length > 0) {
                                jobData.skills.forEach(function(skill) {
                                    $('#skills').append('<li>' + skill + '</li>');
                                });
                            } else {
                                $('#skills').append('<li>No skill set available</li>');
                            }

                        } else {
                            console.log('No valid data returned from server.');
                        }
                    } catch (error) {
                        console.log('Error parsing JSON:', error);
                    }
                },
                error: function (xhr, status, error) {
                    console.log('AJAX Error:', error);
                }
            });
        });
    });
</script>


<!-- Function to calculate the days difference and display -->
<script>
    function calculateDaysAgo() {
        const postDateElements = document.querySelectorAll('.postedate');
        
        postDateElements.forEach(function(element) {
            const postDateString = element.getAttribute('data-postdate'); // Get the posted date from the data attribute
            const postDate = new Date(postDateString); // Convert it to a Date object
            const currentDate = new Date(); // Get the current date

            // Calculate the difference in milliseconds
            const diffTime = Math.abs(currentDate - postDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Convert milliseconds to days

            // Update the content with the number of days ago
            if (diffDays === 0) {
                element.innerHTML = "Posted Today";
            } else if (diffDays === 1) {
                element.innerHTML = "Posted 1 Day Ago";
            } else {
                element.innerHTML = `Posted ${diffDays} Days Ago`;
            }
        });
    }

    // Run the function when the page loads
    window.onload = calculateDaysAgo;
</script>


 <!-- // DROPDOWN FOR SEARCH INPUT (JS FUNCTION) -->
<script>
     $(document).ready(function(e){
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).attr("href").replace("#","");
        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);
        $('.input-group #search_param').val(param);
        });
        });
            var a = document.getElementByTagName('a').item(0);
            $(a).on('keyup', function(evt){
              console.log(evt);
              if(evt.keycode === 13){
                
                alert('search?');
              } 
            }); 
</script>

 <!-- // BUTTON ACTIVE FOR TABS (JS FUNCTION) -->
<script>
    // JavaScript to toggle the 'active' class on clicked buttons
    function setActive(button) {
        // Remove the 'active' class from all buttons
        var buttons = document.querySelectorAll('.jobtabs ul li button');
        buttons.forEach(function(btn) {
            btn.classList.remove('active');
        });

        // Add the 'active' class to the clicked button
        button.classList.add('active');
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