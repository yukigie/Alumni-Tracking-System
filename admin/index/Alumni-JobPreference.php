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
    <title>Alumni | Job Preference</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"><script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';

// Delete Job Type
if ( isset($_POST['delete_type']) ){


        $type_id = $_POST['type_id'];


        $sql = "DELETE FROM tbl_alumni_jobpreftype WHERE ID=$type_id AND Email = '{$email}'";
        $result = $con->query($sql);

        if($result)
        {
        header('location: Alumni-JobPreference.php');
           
        }
        else
        {
        alert("Data Not Deleted!", "Failed to delete Your Job Preference Details.", "error");
        }

}

// Delete Days
if ( isset($_POST['delete_days']) ){


        $days_id = $_POST['days_id'];


        $sql = "DELETE FROM tbl_alumni_jobprefdays WHERE ID=$days_id AND Email = '{$email}'";
        $result = $con->query($sql);

        if($result)
        {
        header('location: Alumni-JobPreference.php');
           
        }
        else
        {
        alert("Data Not Deleted!", "Failed to delete Your Job Preference Details.", "error");
        }

}

// Delete Shift
if ( isset($_POST['delete_shift']) ){


        $shift_id = $_POST['shift_id'];


        $sql = "DELETE FROM tbl_alumni_jobprefshift WHERE ID=$shift_id AND Email = '{$email}'";
        $result = $con->query($sql);

        if($result)
        {
        header('location: Alumni-JobPreference.php');
           
        }
        else
        {
        alert("Data Not Deleted!", "Failed to delete Your Job Preference Details.", "error");
        }

}

// Delete Schedule
if ( isset($_POST['delete_sched']) ){


        $sched_id = $_POST['sched_id'];


        $sql = "DELETE FROM tbl_alumni_jobprefsched WHERE ID=$sched_id AND Email = '{$email}'";
        $result = $con->query($sql);

        if($result)
        {
        header('location: Alumni-JobPreference.php');
           
        }
        else
        {
        alert("Data Not Deleted!", "Failed to delete Your Job Preference Details.", "error");
        }

}

?>


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
        <h5>Job Preferences<span><i class='bx bxs-edit' ></i></span></h5>
        <form action="Alumni-JobPreference.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <div class="row justify-content-center">
            <div class="col-lg-12">

              <div class="form-group" style="margin-top: 20px;">
                <label>Tell us the job details you’re interested in to get better recommendations across the Platform.</label>
                <br><br>
                <label>This preferences may help our Alumni to find a suitable job without any hussle.</label>
               </div>

            </div>

            </div>

            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-12" id="resume_details">
        <div class="row justify-content-center">

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Job Status: <span class="add_resume"><a href="Alumni-JobPref-AddJobType.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_jobpreftype WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content">
                    <form action="Alumni-JobPreference.php" method="POST">
                    <input type="hidden" name="type_id" id="type_id" value="<?php echo $row['ID']; ?>">
                        <h6><?php echo $row['Job_Type']; ?>
                        <span class="update_resume">
                            <button type="submit" id="delete_type" name="delete_type" style="background: transparent; border: none;"><i class='bx bxs-trash-alt'></i></button></span></h6>
                    </form>
                </div>

                 <?php

                  }
              }

            ?>
              </div>
            </div>

             <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Work Schedules (Days): <span class="add_resume"><a href="Alumni-JobPref-AddDays.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_jobprefdays WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content">
                    <form action="Alumni-JobPreference.php" method="POST">
                    <input type="hidden" name="days_id" id="days_id" value="<?php echo $row['ID']; ?>">
                        <h6><?php echo $row['Days']; ?>
                        <span class="update_resume">
                            <button type="submit" id="delete_days" name="delete_days" style="background: transparent; border: none;"><i class='bx bxs-trash-alt'></i></button></span></h6>
                    </form>
                </div>

             <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Work Schedules (Shift): <span class="add_resume"><a href="Alumni-JobPref-AddShift.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_jobprefshift WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                <div class="resume_content">
                    <form action="Alumni-JobPreference.php" method="POST">
                    <input type="hidden" name="shift_id" id="shift_id" value="<?php echo $row['ID']; ?>">
                        <h6><?php echo $row['Shift_Name']; ?>
                        <span class="update_resume">
                            <button type="submit" id="delete_shift" name="delete_shift" style="background: transparent; border: none;"><i class='bx bxs-trash-alt'></i></button></span></h6>
                    </form>
                </div>

             <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Work Schedules: <span class="add_resume"><a href="Alumni-JobPref-AddSched.php"><i class='bx bx-plus-circle' ></i></a></span></h5>
                 <?php

                $query = "SELECT * FROM tbl_alumni_jobprefsched WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>
                 <div class="resume_content">
                    <form action="Alumni-JobPreference.php" method="POST">
                    <input type="hidden" name="sched_id" id="sched_id" value="<?php echo $row['ID']; ?>">
                        <h6><?php echo $row['Schedule']; ?>
                        <span class="update_resume">
                            <button type="submit" id="delete_sched" name="delete_sched" style="background: transparent; border: none;"><i class='bx bxs-trash-alt'></i></button></span></h6>
                    </form>
                </div>


             <?php

                  }
              }

            ?>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h5>Expected Salary Range:</h5>
                <?php

                $query = "SELECT * FROM tbl_alumni_jobprefsalary WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if ($check_data) {
                    $row = mysqli_fetch_assoc($query_run);
                } else {
                    // Set default empty values if no data is available for this ID
                    $row = [
                        'Salary' => '',
                    ];
                }

                ?>
                <div class="resume_content">
                    <form action="Alumni-JobPreference.php" method="POST">
                        <h6>Salary Range: <span style="font-weight: 100;"><?php echo $row['Salary']; ?></span>

                        <span class="update_resume">
                            <span class="update_resume"><a href="Alumni-JobPref-AddSalary.php"><i class='bx bxs-pencil'></i></a></span></h6>
                    </form>
                </div>

                <?php
            mysqli_close($con);
            ?>
              </div>
              
               <a href="Alumni-Jobs.php"><button style="width: 100%;" class="submit-btn1" type="button">Save Preference & View Job Recommendations</button></a>
               
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