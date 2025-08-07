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
    <title>Alumni | Print Placement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <!-- Header -->
    <div class="header">

    <!-- Logo -->
    <div class="logo_content">
        <a href="home.php" class="logo-box">
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


<!-- Home Section -->

<section class="home" style="left: 50px; width: 95%;">

<div class="row">
    <div class="col-md-12">
    <div id="personal_details" style="padding-top: 20px;">
        <a href="Report.php" style="margin-top: -10px; color: #C2AE44"><span><i class='bx bx-chevron-left'></i></span> Return to Report</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Print Table <span><i class="fa fa-print" aria-hidden="true"></i></span></h5>

        <div class="row justify-content-center">

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <textarea name="textarea" id="default" class="form-control" rows="3">
                    
                <div class="header-print" style="text-align: center;">
    <img src="admin_img/cvsu-logo.png" width="60" height="60" style="margin-top: 20px; margin-left: -23rem;">
    <h4 style="margin-top: -50px;">Cavite State University - Imus Campus</h4>
    <p>Cavite Civic Center Palico IV, Imus, Cavite <br>
        (046) 436 6584 / (046) 436 6584 / (046) 436 6584</p>
    <a href="www.cvsu.edu.ph">www.cvsu.edu.ph</a>
    <h3>Alumni Tracer System</h3>
    <div class="quote" style="width: 500px; font-size: 14px; color: #555; margin: 0 auto;">
        <p><i>Cavite State University - Imus Campus Alumni Tracer System, where we help our alumni stay connected, celebrate their successes, and contribute to our community's growth and development.</i></p>
    </div>
    

<h2>Tracked Alumni Placement Overview</h2>


    <table id="alumni-tbl" style="border-collapse: collapse; width: 100%; margin-top: 20px;">
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">No.</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Alumni Tracking Number</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Name</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Job Title</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Company Name</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Applied Date</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Status</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Hired Date</th>
            <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Job Type</th>

        </tr>
        <?php
            require 'connection.php';

            // Query to fetch data from tbl_employer_hired and tbl_employer_joblist
            $sql = "
                SELECT 
                    e_h.Alumni_ID AS tracking_number, 
                    e_h.Applicant_Name AS name, 
                    e_h.Job_Title AS job_title, 
                    e_j.Company_Name AS company_name, 
                    e_h.Applied_Date AS applied_date, 
                    e_h.Status AS status, 
                    e_h.Hired_Date AS hired_date, 
                    e_j.Job_Type AS job_type
                FROM 
                    tbl_employer_hired e_h
                INNER JOIN 
                    tbl_employer_joblist e_j ON e_h.Job_ID = e_j.ID
                ORDER BY 
                    e_h.Applied_Date DESC";

            $result = $con->query($sql);

            if ($result && $result->num_rows > 0) {
                $no = 1; // Row counter
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'>$no</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['tracking_number']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['name']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['job_title']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['company_name']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['applied_date']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['status']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['hired_date']}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$row['job_type']}</td>
                    </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='9' style='border: 1px solid #ddd; padding: 8px; text-align: center;'>No records found</td></tr>";
            }

            $con->close();
            ?>
    </table>
    <p style="text-align: left; font-weight: 600; margin-top: 20px;">Prepared By. </p>
</div>

                </textarea>
                
              </div>
            </div>

        </div>

            </div>

        </div>
    </div>


            </div>
    </div>
</div>
</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="tinymce/tinymce.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    
<!-- // Rich textbox Editor For Content (JS FUNCTION) -->
<script>
tinymce.init({
    selector: '#default', // Replace with your TinyMCE selector
    height: 1000,
    plugins: 'lists link image advtemplate print',
    toolbar: 'undo redo | bold italic | bullist numlist | link image | advtemplate | print',
    advtemplate_templates: [
        {
            title: 'Custom Template 1',
            description: 'Description of Custom Template 1',
            content: '<p>Your content here</p>'
        },
        {
            title: 'Custom Template 2',
            description: 'Description of Custom Template 2',
            content: '<p>Another template content here</p>'
        }
    ],
    setup: function (editor) {
        editor.on('init', function () {
            formatCourseAndJobColumns(); // Call the course formatting function on init
            addPrintCSS(); // Inject print CSS when the editor initializes
        });
    }
});

// Function to format course columns by removing text before/after the hyphen
function formatCourseAndJobColumns() {
    const editorContent = tinymce.get('default').getContent();
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = editorContent;

    // Process .course-column cells to display text after the hyphen
    tempDiv.querySelectorAll('.course-column').forEach(cell => {
        const fullText = cell.textContent;
        const hyphenIndex = fullText.indexOf('-');

        // Check if there's a hyphen and set text after it
        if (hyphenIndex !== -1) {
            cell.textContent = fullText.substring(hyphenIndex + 1).trim();
        }
    });

    // Set the updated content back to the editor
    tinymce.get('default').setContent(tempDiv.innerHTML);
}

// Function to dynamically add print-specific CSS
function addPrintCSS() {
    const style = document.createElement('style');
    style.type = 'text/css';
    style.innerHTML = `
        @media print {
            table {
                page-break-inside: auto;
                border-collapse: collapse;
                width: 100%;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
            
            tfoot {
                display: table-footer-group;
            }
            
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            
            th {
                background-color: green;
                color: white;
                text-align: left;
            }
        }
    `;
    document.head.appendChild(style); // Append the CSS to the <head>
}
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