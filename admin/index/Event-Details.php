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
    <title>Alumni | Event Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

<?php

require 'connection.php';
// Retrieve the ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

?>
    <!-- Header -->
    <div class="header">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="css/admin_img/cvsu-logo.png" width="65" height="60">
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

<div id="personal_details" style="padding-top: 20px;">
    <a href="Events.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Events</a>
</div>

<div class="event-content">

    <?php

        if ($id) {
            // Fetch specific row data based on the ID
            $query = "SELECT * FROM tbl_event WHERE Event_ID ='$id'";
            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                $row = mysqli_fetch_assoc($query_run);
            } else {
                // Set default empty values if no data is available for this ID
                $row = [
                    'Event_Name' => '',
                    'BatchYear' => '',
                    'Schedule' => '',
                    'Description' => '',
                ];
            }
        } else {
            // If no ID is provided, redirect back to the resume list or show an error
            echo "<script>alert('No Events Available Yet!'); window.location.href = 'Event-Details.php';</script>";
            exit;
        }
        ?>

                    <div class="event-item1">
                        <div class="event-details">
                            <input type="hidden" name="event_id" id="event_id" value="<?php echo $row['Event_ID']; ?>">
                            <h3><?php echo $row['Event_Name']; ?></h3>
    
                            <p class="event-batchyear"><?php echo $row['BatchYear']; ?> Alumni</p>
                            <!-- Add a span to hold the schedule -->
                            <small>Date & Time: <span class="schedule" data-schedule="<?php echo $row['Schedule']; ?>"></span></small>
                            <div class="event-descript">
                                <p><?php echo $row['Description']; ?></p>
                            </div>
                        </div>
                    </div>
<?php
mysqli_close($con);
?>
</div>

</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- FORMATTING OF SCHED DATE AND TIME -->
<script>
    // Get all elements with the class "schedule"
    document.querySelectorAll('.schedule').forEach(function (element) {
        // Retrieve the original datetime from the data attribute
        const originalDateTime = element.getAttribute('data-schedule');
        
        if (originalDateTime) {
            // Convert the date string into a Date object
            const date = new Date(originalDateTime);

            // Format the date
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };

            // Convert to the desired format
            const formattedDateTime = date.toLocaleString('en-US', options);

            // Set the formatted date-time in the HTML
            element.textContent = formattedDateTime;
        }
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