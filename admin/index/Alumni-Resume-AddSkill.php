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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';

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
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];

    // Insert Data
    $query = "INSERT INTO tbl_alumni_skill (Skill_Name, Esperience, Email) 
              VALUES ('{$skills}', '{$experience}', '{$email}')";

    // Execute the query
    if (mysqli_query($con, $query)) {
        header('location: Alumni-Resume.php');
        exit;
    } else {
        alert("Data Not Updated!", "Failed to update Your Resume Details.", "error");
        exit;
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
    <div class="col-md-12">
    <div id="personal_details" style="padding-top: 20px;">
        <a href="Alumni-Resume.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Resume</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Skill<span><i class='bx bxs-edit' ></i></span></h5>
        <p style="font-size: 13px; color: red;">*Please Fill Out All Fields</p>
        <form action="Alumni-Resume-AddSkill.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body">

        <div class="row justify-content-center">


            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Skill Name*</label>
                <input type="text" id="skills" name="skills" class="form-control">
              </div>
            </div>

            
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 10px;">
                <label>Year of Experience*</label>
                <select name="experience" id="experience" class="form-control" required style="margin-top: 10px;">
                  <option selected hidden value="">Select Year</option>
                  <option value="Less than 1 Year">Less than 1 Year</option>
                  <option value="1 Year">1 Year</option>
                  <option value="2 Years">2 Years</option>
                  <option value="3 Years">3 Years</option>
                  <option value="4 Years">4 Years</option>
                  <option value="5 Years">5 Years</option>
                  <option value="6 Years">6 Years</option>
                  <option value="7 Years">7 Years</option>
                  <option value="8 Years">8 Years</option>
                  <option value="9 Years">9 Years</option>
                  <option value="10 Years">10 Years</option>
                  <option value="More than 10 Years">More than 10 Years</option>
                </select>
              </div>
            </div>

        </div>

              <button class="submit-btn1" type="submit" id="submit" name="submit" form="form_body">Save</button>

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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

<!-- Autofill List of Skills per course -->
<script>
    $(function() {
    var availableSkills = [
        // Bachelor Of Arts In Journalism
        "Writing",
        "Editing",
        "Proofreading",
        "Research",
        "Interviewing",
        "Media Ethics",
        "Content Creation",
        "Fact-Checking",
        "News Reporting",
        "Digital Journalism",

        // Bachelor Of Early Childhood Education
        "Classroom Management",
        "Child Development",
        "Lesson Planning",
        "Early Literacy",
        "Play-Based Learning",
        "Positive Reinforcement",
        "Parent Communication",
        "Curriculum Design",
        "Behavioral Management",
        "Special Needs Education",

        // Bachelor Of Elementary Education
        "Teaching",
        "Classroom Management",
        "Lesson Planning",
        "Child Development",
        "Curriculum Design",
        "Assessment Strategies",
        "Student Engagement",
        "Technology in Education",
        "Differentiated Instruction",
        "Classroom Communication",

        // Bachelor Of Science In Business Management
        "Leadership",
        "Project Management",
        "Financial Analysis",
        "Strategic Planning",
        "Marketing",
        "Decision Making",
        "Human Resources",
        "Operations Management",
        "Problem Solving",
        "Teamwork",

        // Bachelor Of Science In Computer Science
        "Programming",
        "Algorithms",
        "Data Structures",
        "Database Management",
        "Software Development",
        "Cybersecurity",
        "Operating Systems",
        "Artificial Intelligence",
        "Machine Learning",
        "Web Development",

        // Bachelor Of Science In Entrepreneurship
        "Business Planning",
        "Market Research",
        "Sales",
        "Marketing",
        "Financial Management",
        "Risk Management",
        "Innovation",
        "Networking",
        "Negotiation",
        "Business Strategy",

        // Bachelor Of Science In Hospitality Management
        "Customer Service",
        "Event Planning",
        "Hotel Management",
        "Food and Beverage Management",
        "Catering",
        "Tourism Management",
        "Team Leadership",
        "Marketing",
        "Crisis Management",
        "Problem Solving",

        // Bachelor Of Science In Information Technology
        "Network Management",
        "System Administration",
        "Technical Support",
        "Database Management",
        "Web Development",
        "Cybersecurity",
        "Cloud Computing",
        "Software Development",
        "IT Project Management",
        "Troubleshooting",

        // Bachelor Of Science In Office Administration
        "Office Management",
        "Record Keeping",
        "Administrative Support",
        "Microsoft Office",
        "Customer Service",
        "Communication",
        "Scheduling",
        "Time Management",
        "Document Management",
        "Event Coordination",

        // Bachelor Of Science In Psychology
        "Counseling",
        "Behavioral Analysis",
        "Research",
        "Interpersonal Skills",
        "Data Interpretation",
        "Report Writing",
        "Therapy Techniques",
        "Communication",
        "Conflict Resolution",
        "Mental Health Support",

        // Bachelor Of Secondary Education
        "Lesson Planning",
        "Subject Mastery",
        "Classroom Management",
        "Student Engagement",
        "Curriculum Design",
        "Assessment Strategies",
        "Technology in Education",
        "Differentiated Instruction",
        "Classroom Communication",
        "Special Needs Education"
    ];

    $("#skills").autocomplete({
        source: availableSkills
    });
});

</script>
    
</body>
</html>