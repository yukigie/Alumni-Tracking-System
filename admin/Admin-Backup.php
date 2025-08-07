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
    <title>Admin | Backup & Recovery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">
    <link rel="stylesheet" href="style.css">

    <style>
        .backupdiv {
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        .backupdiv .backupbtn {
            padding: 50px;
        }
        .backupdiv .backupbtn button {
            padding: 55px;
            font-size: 20px;
            background-color: white;
            border: 2px lightgray solid;
            color: #888;
            border-radius: 10px;
            transition: 0.2s;
            width: 100%;
        }
        .backupdiv .backupbtn button:hover {
            cursor: pointer;
            background-color: lightgray;
            border: 2px #666 solid;
            color: #333;
        }
        .backupdiv .backupbtn button i {
            display: block;
            margin-bottom: 20px;
            font-size: 45px;
            color: #2E6D43;
        }
    </style>
    
</head>
<body>

    <!-- Modal for Recover data -->
            <div class="modal fade recoverModal" id="recoverModal" tabindex="-1" aria-labelledby="recoverModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Recover your Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="recover-backup.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Backup File:</label>
                                    <input type="file" name="backupFile" id="file" class="form-control" accept=".sql" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Restore</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Modal for BACKUP -->
            <div class="modal fade backupModal" id="backupModal" tabindex="-1" aria-labelledby="backupModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="backupModalLabel">Backup Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="backupForm" action="export-backup-db.php" method="post">


                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="backupSQL" id="backupSQL">
                                    <label class="form-check-label" for="backupSQL">Database Backup in (.sql) File Type</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="backup[]" value="database" id="backupDatabase">
                                    <label class="form-check-label" for="backupDatabase">Database Backup in (.csv) File Type</label>
                                </div>

                                <div class="selectdb" style="display: none;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="select_all" id="selectAll">
                                        <label class="form-check-label" for="selectAll">Select All</label>
                                    </div>

                                    <!-- Add table checkboxes dynamically -->
                                    <div id="tableCheckboxes">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="messages" id="messages">
                                            <label class="form-check-label" for="messages">messages</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_account_declined" id="tbl_account_declined">
                                            <label class="form-check-label" for="tbl_account_declined">tbl_account_declined</label>
                                        </div>
                                         <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_admin" id="tbl_admin">
                                            <label class="form-check-label" for="tbl_admin">tbl_admin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni" id="tbl_alumni">
                                            <label class="form-check-label" for="tbl_alumni">tbl_alumni</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_certification" id="tbl_alumni_certification">
                                            <label class="form-check-label" for="tbl_alumni_certification">tbl_alumni_certification</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_citizen" id="tbl_alumni_citizen">
                                            <label class="form-check-label" for="tbl_alumni_citizen">tbl_alumni_citizen</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_education" id="tbl_alumni_education">
                                            <label class="form-check-label" for="tbl_alumni_education">tbl_alumni_education</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_jobprefdays" id="tbl_alumni_jobprefdays">
                                            <label class="form-check-label" for="tbl_alumni_jobprefdays">tbl_alumni_jobprefdays</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_jobprefsalary" id="tbl_alumni_jobprefsalary">
                                            <label class="form-check-label" for="tbl_alumni_jobprefsalary"> tbl_alumni_jobprefsalary</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_jobprefsched" id="tbl_alumni_jobprefsched">
                                            <label class="form-check-label" for="tbl_alumni_jobprefsched">  tbl_alumni_jobprefsched</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_jobprefshift" id="tbl_alumni_jobprefshift">
                                            <label class="form-check-label" for="tbl_alumni_jobprefshift">tbl_alumni_jobprefshift</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_jobpreftype" id="tbl_alumni_jobpreftype">
                                            <label class="form-check-label" for="tbl_alumni_jobpreftype">tbl_alumni_jobpreftype</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_jobstatus" id="tbl_alumni_jobstatus">
                                            <label class="form-check-label" for="tbl_alumni_jobstatus">tbl_alumni_jobstatus</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_language" id="tbl_alumni_language">
                                            <label class="form-check-label" for="tbl_alumni_language">tbl_alumni_language</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_link" id="tbl_alumni_link">
                                            <label class="form-check-label" for="tbl_alumni_link">tbl_alumni_link</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_skill" id="tbl_alumni_skill">
                                            <label class="form-check-label" for="tbl_alumni_skill">tbl_alumni_skill</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_alumni_work" id="tbl_alumni_work">
                                            <label class="form-check-label" for="tbl_alumni_work">tbl_alumni_work</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_atn" id="tbl_atn">
                                            <label class="form-check-label" for="tbl_atn">tbl_atn</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_courses" id="tbl_courses">
                                            <label class="form-check-label" for="tbl_courses">tbl_courses</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer" id="tbl_employer">
                                            <label class="form-check-label" for="tbl_employer">tbl_employer</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer_application" id="tbl_employer_application">
                                            <label class="form-check-label" for="tbl_employer_application">tbl_employer_application</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer_declined" id="tbl_employer_declined">
                                            <label class="form-check-label" for="tbl_employer_declined">tbl_employer_declined</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer_hired" id="tbl_employer_hired">
                                            <label class="form-check-label" for="tbl_employer_hired">tbl_employer_hired</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer_joblist" id="tbl_employer_joblist">
                                            <label class="form-check-label" for="tbl_employer_joblist">tbl_employer_joblist</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer_jobsched" id="tbl_employer_jobsched">
                                            <label class="form-check-label" for="tbl_employer_jobsched">tbl_employer_jobsched</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_employer_skill" id="tbl_employer_skill">
                                            <label class="form-check-label" for="tbl_employer_skill">tbl_employer_skill</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_event" id="tbl_event">
                                            <label class="form-check-label" for="tbl_event">tbl_event</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="tbl_gallery" id="tbl_gallery">
                                            <label class="form-check-label" for="tbl_gallery">tbl_gallery</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tables[]" value="usertable" id="usertable">
                                            <label class="form-check-label" for="usertable">usertable</label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" data-bs-dismiss="modal" class="btn btn-primary" style="margin-top: 20px;">Start Backup</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Header -->
    <div class="header">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="admin_img/logo-white.png" width="65" height="60">
            <div class="logo" style="font-size: 23px; font-weight: 800;">C<span style="color: #FBD25B; font-weight: 800; font-size: 23px;">v</span>SU 
                <span style="font-size: 15px; font-weight: 600;">Imus Campus</span>
                <p><span>Alumni Tracker<img src="admin_img/focus.png" width="25" height="25"></span></p>
            </div>

            <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        </a>
    </div>

<div class="side-nav1">
    <div class="side-nav">
        <p style="font-size: 16px; color: #fff; font-weight: 600;">ADMIN <span style="font-size: 30px; color: lightgreen;">•</span></p>
    </div>

    <div class="user-img" style="margin-top: 20px;">
        <img src="admin_img/cvsu-logo1.png" width="50" height="50">
    </div>
</div>

    </div>


    <!-- Sidebar -->
<div class="sidebar">

<!-- List Menu -->
    <ul class="sidebar-list">
<!-- Non Dropdown List Item -->
        <li>
            <div class="title"  id="landing1">
                <a href="home.php" class="link">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="name">Dashboard</span>
                </a>
                <!-- <i class='bx bxs-chevron-down' ></i> -->
            </div>
        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing3">
                <a href="#" class="link">
                    <i class="fa fa-pie-chart" aria-hidden="true"></i>
                    <span class="name">Report</span>
                </a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing2">
                <a href="Alumni.php" class="link">
                    <i class='bx bxs-graduation' ></i>
                    <span class="name">Alumni</span>
                </a>
            </div>
        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing4">
                <a href="Jobs.php" class="link">
                    <i class='bx bxs-briefcase' ></i>
                    <span class="name">Jobs</span>
                </a>
            </div>
        </li>


        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing5">
                <a href="AllUsers.php" class="link">
                    <i class='bx bxs-user' ></i>
                    <span class="name">All Users</span>
                </a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <label>General Setting</label>
        <li class="dropdown">
            <div class="title">
                <a href="#" class="link">
                    <i class='bx bxs-cog' ></i>
                    <span class="name">All Settings</span>
                </a>
                <i class='bx bxs-chevron-down' ></i>
            </div>

            <div class="submenu">
                <a href="#" class="link submenu-title">All Settings</a>
                <a href="Gallery.php" class="link">Add Gallery</a>
                <a href="Course.php" class="link">Add Courses</a>
                <a href="Events.php" class="link">Add Event</a>
                <a href="Admin-Setting.php" class="link">System Settings</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown" style="margin-top: 80%;">
            <div class="title">
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

    <div class="head-title">
        <h2>Backup Data</h2>
        <p><a href="home.php">Dashboard</a> / <a href="Admin-Setting.php">Alumni Setting</a> / Backup Data</p>
    </div>

    <div class="backupdiv">
        <div class="row">
            <div class="col-lg-6">
                <div class="backupbtn">
                    <button data-bs-toggle="modal" data-bs-target=".backupModal"><i class="fa fa-refresh" aria-hidden="true"></i>Back up System Data in Filetype</button>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="backupbtn">
                    <button data-bs-toggle="modal" data-bs-target=".recoverModal"><i class="fa fa-undo" aria-hidden="true"></i>Recover Previous System Data</button>
                </div>
            </div>
        </div>
    </div>

</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Toggle the display of selectdb when backupDatabase is checked/unchecked
    document.getElementById('backupDatabase').addEventListener('change', function () {
        const selectdb = document.querySelector('.selectdb');
        selectdb.style.display = this.checked ? 'block' : 'none';

        // If unchecked, uncheck all checkboxes under selectdb
        if (!this.checked) {
            const tableCheckboxes = document.querySelectorAll('#tableCheckboxes .form-check-input');
            tableCheckboxes.forEach(checkbox => checkbox.checked = false);

            // Also uncheck "Select All"
            const selectAll = document.getElementById('selectAll');
            selectAll.checked = false;
        }
    });

    // Handle "Select All" functionality for table checkboxes
    document.getElementById('selectAll').addEventListener('change', function () {
        const tableCheckboxes = document.querySelectorAll('#tableCheckboxes .form-check-input');
        tableCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Ensure "Select All" is checked/unchecked based on other table checkboxes
    document.querySelectorAll('#tableCheckboxes .form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const selectAll = document.getElementById('selectAll');
            const allChecked = Array.from(document.querySelectorAll('#tableCheckboxes .form-check-input')).every(cb => cb.checked);
            selectAll.checked = allChecked;
        });
    });

    // Handle form submission for exporting selected tables
    document.getElementById('backupForm').addEventListener('submit', function (e) {
        const selectedTables = Array.from(document.querySelectorAll('#tableCheckboxes .form-check-input:checked')).map(cb => cb.value);
        const isSelectAll = document.getElementById('selectAll').checked;

        if (isSelectAll) {
            console.log("Exporting the entire database...");
        } else if (selectedTables.length > 0) {
            console.log("Exporting selected tables: ", selectedTables);
        } else {
            console.log("No tables selected for export.");
        }
    });
</script>

<!-- Sidebar Landing Page (JS FUNCTION) -->
<script>
    // Add a click event listener to redirect on click
    document.getElementById('landing1').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'home.php'; // Replace with your desired URL
    });

    document.getElementById('landing2').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Alumni.php'; // Replace with your desired URL
    });

    document.getElementById('landing3').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Report.php'; // Replace with your desired URL
    });

    document.getElementById('landing4').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Jobs.php'; // Replace with your desired URL
    });

    document.getElementById('landing5').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'AllUsers.php'; // Replace with your desired URL
    });
</script>


   <!-- // DROPDOWN FOR HEADER SUBMENU (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const toggleSidebar = document.querySelector('.user-img');
    const sideNav = document.querySelector('.side-nav');
    const navLinks = sideNav.querySelectorAll('a');
    
    // Mapping icons to text
    const iconTextMap = {
        'bx bxs-message': 'Messages',
        'bx bxs-bell': 'Notifications'
    };

    // Toggle dropdown and switch icons to text
    toggleSidebar.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            sideNav.classList.toggle('show');
            navLinks.forEach(link => {
                const iconClass = link.querySelector('i').className;
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
                const iconClass = link.querySelector('i').className;
                if (iconTextMap[iconClass]) {
                    link.innerHTML = `<i class="${iconClass}"></i>`;
                }
            });
            toggleSidebar.style.pointerEvents = 'none'; // Make the toggle unclickable
        } else {
            toggleSidebar.style.pointerEvents = 'auto'; // Make the toggle clickable
        }
    }

    window.addEventListener('resize', checkScreenSize);

    // Initial check
    checkScreenSize();
});


</script>


   <!-- // DROPDOWN FOR SUBMENU (JS FUNCTION) -->
<script>

    // DROPDOWN FOR SUBMENU
    const listItems = document.querySelectorAll(".sidebar-list li");

    listItems.forEach(item => {
        item.addEventListener("click", () => {
            let isActive = item.classList.contains("active");

            listItems.forEach((el) => {
                el.classList.remove("active")
            });

            if (isActive) item.classList.remove("active");
            else item.classList.add("active");
        });
    });
    
    // TOGGLE SIDEBAR
    const toggleSidebar = document.querySelector(".toggle-sidebar");
    const logo = document.querySelector(".logo-box");
    const sidebar = document.querySelector(".sidebar");

    toggleSidebar.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });

    logo.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });
</script>
    
</body>
</html>