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
    <title>Alumni | Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="CSS/admin_img/cvsu-logo.png">

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

    <section class="home" style="max-height: 100vh; height: 90vh;">

        <div class="alumnihead-title">
            <h2><span>Welcome</span>, Proud C<span>v</span>SUeño! <?php echo $fetch_info['name'] ?></h2>
            <h5>Let’s <span>Connect</span> and <span style="color: #D2A31E;">Grow</span> together.</h5>

            <div class="reminder">
                <h6>Stay ahead by keeping your <span>Profile</span>, <span>Employment Status</span>, and <span>Job Preferences</span> updated. Your journey with CvSU continues!</h6>
                <div class="row">
                    <div class="col-lg-4">
                        <a href="Alumni-Profile.php"><div class="rmd-item">
                            <div class="rmd-icon">
                            <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <div class="rmd-text">
                                <p><span>Complete Your Alumni Profile:</span><br> Ensure your profile is updated with accurate information to stay connected with CvSU</p>
                            </div>
                        </div>
                        </div></a>
                    </div>

                    <div class="col-lg-4">
                        <a href="Alumni-JobStatus.php"><div class="rmd-item">
                            <div class="rmd-icon">
                            <i class="fa fa-briefcase" aria-hidden="true"></i>
                            <div class="rmd-text">
                                <p><span>Share Your Employment Status:</span><br> Let us know where you’re currently working or gain access to relevant job openings within the platform</p>
                            </div>
                        </div>
                        </div></a>
                    </div>

                    <div class="col-lg-4">
                        <a href="Alumni-JobPreference.php"><div class="rmd-item">
                            <div class="rmd-icon">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <div class="rmd-text">
                                <p><span>Define Your Job Preferences:</span><br> Help us match you with the right opportunities by filling out your job preference details today!</p>
                            </div>
                        </div>
                        </div></a>
                    </div>

                </div>
            </div>
        </div>

        <div class="dashboard-items1">
            <h6 style="margin-bottom: 10px;">📌 Your Application Status: </h6>
            <div class="row">
                <div class="col-lg-3">
                    <a href="Alumni-Applications.php"><div class="item1">
                        <div class="db-icon">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>No. of Applied Jobs</p>
                        <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_application WHERE Status = 'Applied' AND Email_Alumni ='$email'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>

                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Alumni-Applications.php"><div class="item2">
                        <div class="db-icon">
                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>No. of Screening</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_application WHERE Status = 'Screening' AND Email_Alumni ='$email'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Alumni-Applications.php"><div class="item3">
                        <div class="db-icon">
                            <i class="fa fa-comments" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>No. of Interview</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_application WHERE Status = 'Interview' AND Email_Alumni ='$email'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Alumni-Archive.php"><div class="item4">
                        <div class="db-icon">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Archived Jobs</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_declined WHERE Status = 'Declined' AND Email_Alumni ='$email'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

            </div>
        </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="rmd-resume">
                <h4>Build Your Resume with Ease <i class="fa fa-thumbs-up" aria-hidden="true"></i></h4>
                <p>Use our <b>CvSU Alumni Resume Builder</b> to create a Professional Resume in just a few steps!</p>
                <a href="Alumni-Resume.php" class="btn btn-warning">Click here to get Started!</a>
            </div>
        </div>
    </div>

    </section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- Rolling count effect  -->
<script>
    function animateCountByClass(className, duration) {
        const elements = document.querySelectorAll(`.${className}`);
        
        elements.forEach(element => {
            const targetCount = parseInt(element.getAttribute("data-count"), 10) || 0;
            const startCount = 0;
            const steps = duration / 50; // More steps for smoother animation
            const increment = targetCount / steps; // Smaller increments for smooth transition
            let currentCount = startCount;
            let currentStep = 0;

            const timer = setInterval(() => {
                currentStep++;
                currentCount += increment;

                if (currentStep >= steps || currentCount >= targetCount) {
                    currentCount = targetCount; // Final value to ensure precision
                    clearInterval(timer);
                }

                element.textContent = Math.floor(currentCount); // Update the element with the current count
            }, 50); // Update every 50ms
        });
    }

    // Example usage: animate numbers for elements with the class 'rowCountDisplay' over 3 seconds
    document.addEventListener("DOMContentLoaded", () => {
        animateCountByClass("rowCountDisplay", 2000); // 3000ms = 3 seconds
    });
</script>

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