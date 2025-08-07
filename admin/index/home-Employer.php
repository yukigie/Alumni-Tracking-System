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
    <title>Employer | Home</title>
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

    <section class="home" style="max-height: 100vh; height: 90vh;">

        <div class="alumnihead-title">

            <h2><span style="color: #555;">Welcome to the</span> <b>C<span>v</span>SU Alumni Tracer!</b> 
                <span style="color: #555;"><?php echo $fetch_info['name'] ?></span></h2>

            <h5>Empowering your <span style="color: #D2A31E; font-weight: 700;">Company</span> with the best <span style="font-weight: 700;">CvSUeño</span> Professionals.</h5>

            <div class="reminder" style="padding-bottom: 0px;">
                <h6>Stay ahead by keeping your <a href="Employer-Profile.php"><u><span>Company Profile</span></u></a> and <a href="Employer-JobReport.php"><u><span>Job Listings</span></u></a>. Discover top CvSUeño talents today!</h6>
            </div>
        </div>

        <div class="dashboard-items">
            <div class="row">
                <div class="col-lg-3">
                    <a href="Employer-JobReport.php"><div class="item1">
                        <div class="db-icon">
                            <i class="fa fa-briefcase" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>No. of Posted Jobs</p>
                        <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_joblist WHERE Email='$email'";
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
                    <a href="Employer-Applications.php"><div class="item2">
                        <div class="db-icon">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>No. of Applicants</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_application WHERE Email_Employer ='$email'";
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
                    <a href="Employer-HiredPage.php"><div class="item3">
                        <div class="db-icon">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Hired Applicants</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_hired WHERE Email_Employer ='$email'";
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
                    <a href="Employer-Applications.php"><div class="item4">
                        <div class="db-icon">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Declined Applicants</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_declined WHERE Status = 'Declined' AND Email_Employer ='$email'";
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

            <div class="dashboard-btmitems">
            <div class="row">
            <div class="col-lg-6">
                <div class="details1">
            <div class="tblPlacement">
                <div class="cardHeader">
                    <h5 style="color: darkgreen;">Job Listing</h5>
                    <a href="Employer-JobReport.php"><button class="btn btn-warning">See More</button></a>
                </div>

                <table style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th scope="col">Ref. ID</th>
                            <th scope="col">Job Title</th>
                            <th scope="col">Job Applicants</th>
                            <th scope="col" style="text-align: center;">Status</th>
                            <th scope="col" style="display: none;">Close Date</th>
                        </tr>
                    </thead>

                    <tbody>
            <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_joblist WHERE Email='$email'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Job_Title]</td>
                        <td class='applicant-col'>$row[Job_Applicants]</td>
                        <td class='status-col'></td>
                        <td class='close-col' style='display: none;'>$row[Job_Close_Date]</td>
                        <td>
                    </tr>
                    ";
                }

                    mysqli_close($con);
                ?>
                    </tbody>
                </table>
            </div>
        </div>
                </div>


        <div class="col-lg-6">
        <div class="details1">
            <div class="tblPlacement">
                <div class="cardHeader">
                    <h5 style="color: darkgreen;">Application Report</h5>
                    <a href="Employer-Applications.php"><button class="btn btn-warning">See More</button></a>
                </div>

                <table style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th scope="col">Ref. ID</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Job Title</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>

                    <tbody>
            <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_application WHERE Email_Employer='$email'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Job_Title]</td>
                        <td class='status-text'>$row[Status]</td>
                    </tr>
                    ";
                }

                    mysqli_close($con);
                ?>
                    </tbody>
                </table>
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

<!-- Define color map based on status value (JS FUNCTION) -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const statusTexts = document.querySelectorAll(".status-text"); // Select all elements with the class .status-text

    // Define color map based on status value
    const statusColors = {
        "Applied": "green",
        "Screening": "#8B7818",
        "Interview": "blue",
        "Hired": "#125428"
    };

    // Loop through each status-text element
    statusTexts.forEach(statusText => {
        const statusValue = statusText.textContent.trim(); // Get and trim the text content

        // Set the background color if a match is found
        if (statusColors[statusValue]) {
            statusText.style.color = statusColors[statusValue];
        }
    });
});
</script>

<script>
$(document).ready(function() {
    // Iterate over each row to check the job close date
    $('table tbody tr').each(function() {
        // Get the close date, status cell, and icon for each row
        const closeDateText = $(this).find('.close-col').text();
        const statusCol = $(this).find('.status-col');

        // Parse the close date
        const closeDate = new Date(closeDateText);
        const currentDate = new Date();

        // Determine if the job is active or inactive
        if (closeDate >= currentDate) {
            statusCol.text('Active').css('color', 'green'); // Job is active
            statusCol.css('font-weight', '600'); 
        } else {
            statusCol.text('Inactive').css('color', 'red'); // Job is inactive
            statusCol.css('font-weight', '600');
        }

    });
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