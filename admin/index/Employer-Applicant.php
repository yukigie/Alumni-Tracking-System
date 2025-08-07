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
    <title>Employer | Applicat List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="#">

   <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- View Modal -->
<div class="modal fade ViewModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #5E90AB;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; display: block;">Alumni Details</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="#" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id1" id="view_id1">

          <div class="row justify-content-center">
            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 20px; text-align: center;">
            <div class="profile-pic1">
                <img src="" id="alumni_img" height="270" width="270">
            </div>
                <label style="margin-top: 10px; font-size: 23px; font-weight: 600;" id="applicant_name"></label>
                
              </div>
            </div>

            <div class="col-lg-8">
              <div class="form-group alumni_labels" style="margin-top: 30px;">
                <div class="row">

            <div class="col-lg-4">
                <label>Reference ID: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="atn"></span>
            </div>

            <div class="col-lg-4">
                <label>Full Name: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="fname"></span>
            </div>

            <div class="col-lg-4">
                <label>Email Address: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="alumni_email"></span>
            </div>

            <div class="col-lg-4">
                <label>Contact Number: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="con_num"></span>
            </div>

            <div class="col-lg-4">
                <label>Date of Birth: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="birthday"></span>
            </div>

            <div class="col-lg-4">
                <label>Gender: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="gender"></span>
            </div>

            <div class="col-lg-4">
                <label>Graduated Course: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="course"></span>
            </div>

            <div class="col-lg-4">
                <label>Graduated Year: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="gradyear"></span>
            </div>

            <div class="col-lg-4">
                <label>Current Status: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="jobstatus"></span>
            </div>

            <div class="col-lg-4">
                <label>Description: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="description"></span>
            </div>
              </div>
          </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>

          <a id="sendMessageLink4" href="#"><button type="button" class="btn btn-primary">Send Message</button></a>
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

<section class="home">

    <div class="head-title">
        <h2>Applicant List</h2>
        <p><a href="home-Employer.php">Dashboard</a> / Applicant List</p>

        <small style="display: block; margin-top: 10px; font-weight: 500; width: 90%;">List of Applicants that features a curated selection of talented <span style="color: darkgreen; font-weight: 700;">C<span style="color: #BB8C05;">v</span>SU</span> <b>Graduates</b> ready to join your team. Discover skilled professionals with the qualifications and potential to contribute to your organization’s success.</small>
    </div>

    <div class="container_my-5">
    <br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Graduated Year</th>
                    <th scope="col">Course</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Full Name</th>
                    <th scope="col" style="display: none;">Birthday</th> 
                    <th scope="col" style="display: none;">Gender</th> 
                    <th scope="col" style="display: none;">About</th> 
                    <th scope="col" style="display: none;">Course</th> 
                    <th scope="col" style="display: none;">Batch Year</th> 
                    <th scope="col" style="display: none;">Contact Number</th> 
                    <th scope="col" style="display: none;">Account</th>
                    <th scope="col" style="display: none;">ImageText</th>
                    <th scope="col" style="display: none;">Job Name</th> 
                    <th scope="col" style="display: none;">Date Hired</th> 
                    <th scope="col" style="display: none;">Company Name</th> 
                    <th scope="col" style="display: none;">Place</th> 
                    <th scope="col" style="display: none;">Job Type</th> 
                    <th scope="col" style="display: none;">Employment Type</th> 
                    <th scope="col" style="display: none;">Income</th>         
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT 
                        tbl_alumni.*,
                        tbl_alumni_jobstatus.ID,
                        tbl_alumni_jobstatus.Job_Name,
                        tbl_alumni_jobstatus.Date_Hired,
                        tbl_alumni_jobstatus.Company_Name,
                        tbl_alumni_jobstatus.Place,
                        tbl_alumni_jobstatus.Job_Type,
                        tbl_alumni_jobstatus.Employment_Type,
                        tbl_alumni_jobstatus.Income,
                        tbl_alumni_jobstatus.Email
                    FROM 
                        tbl_alumni
                    JOIN 
                        tbl_alumni_jobstatus ON (tbl_alumni.Email = tbl_alumni_jobstatus.Email AND 
                        tbl_alumni.Alumni_ID = tbl_alumni_jobstatus.ID);";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[Alumni_ID]</td>
                        <td>$row[First_Name] $row[Last_Name]</td>
                        <td>$row[Email]</td>
                        <td><img src='css/img/$row[Image]' width='50' height='50'></td>
                        <td>$row[Batch_Year]</td>
                        <td>$row[Course]</td>
                        <td>$row[Status]</td>
                        <td style='display: none;'>$row[First_Name] $row[Middle_Name] $row[Last_Name]</td>
                        <td style='display: none;'>$row[Birthday]</td>
                        <td style='display: none;'>$row[Gender]</td>
                        <td style='display: none;'>$row[About]</td>
                        <td style='display: none;'>$row[Course]</td>
                        <td style='display: none;'>$row[Batch_Year]</td>
                        <td style='display: none;'>$row[Contact_Number]</td>
                        <td style='display: none;'>$row[Account]</td>
                        <td style='display: none;'>$row[Image]</td>
                        <td style='display: none;'>$row[Job_Name]</td>
                        <td style='display: none;'>$row[Date_Hired]</td>
                        <td style='display: none;'>$row[Company_Name]</td>
                        <td style='display: none;'>$row[Place]</td>
                        <td style='display: none;'>$row[Job_Type]</td>
                        <td style='display: none;'>$row[Employment_Type]</td>
                        <td style='display: none;'>$row[Income]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn1' style='padding: 8px 15px;' onclick='transferText()'>
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

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

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

<!-- Account Status Icon (JS FUNCTION) -->
<script>

function transferText() {
    setTimeout(function() {
        const spanText = document.querySelector('.account').textContent;

        $('.account').each(function() {
            const accountStatus = $(this).text().trim().toLowerCase();
            const statusIcon = $(this).siblings('.status-icon').find('i');

            // Check if the account status is 'notverified'
                if (accountStatus === "notverified") {
                // Change the icon to 'fa-times-circle' and color to red
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "red");
             } else {
                // Reset the icon to 'fa-check-circle' and color to green if verified
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "");
            }
        });
    }, 900);
}

</script>

<!-- Datatable Searchbox (JS FUNCTION) -->
<script>
    $(document).ready(function () {
        // Initialize #myTable for ViewModal
        $('#myTable').DataTable({
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

        // Use event delegation for ViewModal
        $('#myTable').on('click', '.editbtn1', function () {
            $('#EditModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();


            const imageFileName = data[15]; 
            // Full image path
            const imgSrc = `css/img/${imageFileName}`;

            // Extract and format text for jobtype and course1
            const courseFullText = data[5];
            const courseText = courseFullText.includes('-') 
                ? courseFullText.substring(0, courseFullText.indexOf('-')).trim() 
                : courseFullText;

            $('#view_id1').val(data[0]);
            $('#atn').text(data[0]);
            $('#alumni_img').attr('src', imgSrc);
            $('#applicant_name').text(data[1]);
            $('#fname').text(data[7]);
            $('#alumni_email').text(data[2]);
            $('#con_num').text(data[13]);
            $('#birthday').text(data[8]);
            $('#gender').text(data[9]);
            $('#course').text(courseText);
            $('#gradyear').text(data[4]);
            $('#jobstatus').text(data[6]);
            $('#description').text(data[10]);
            $('.account').text(data[14]);
            $('#sendMessageLink4').attr('href', `Employer-Inbox.php?alumni_email=${$('#alumni_email').text()}`);
        });
    });
</script>

<script>
    $('#myTable').on('click', '.jobbtn', function () {
            $('#DeleteModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

        const imageFileName = data[15];
        // Full image path
        const imgSrc = `css/img/${imageFileName}`;

        // Extract and format text for jobtype and course1
        const jobTypeFullText = data[20];
        const courseFullText = data[5];
        const jobTypeText = jobTypeFullText.includes('-') 
            ? jobTypeFullText.substring(0, jobTypeFullText.indexOf('-')).trim() 
            : jobTypeFullText;
        const courseText = courseFullText.includes('-') 
            ? courseFullText.substring(0, courseFullText.indexOf('-')).trim() 
            : courseFullText;

        $('#view_id2').val(data[0]);
        $('#atn1').text(data[0]);
        $('#alumni_img1').attr('src', imgSrc);
        $('#applicant_name1').text(data[1]);
        $('#fname1').text(data[7]);
        $('#course1').text(courseText); // Display text before hyphen
        $('#jobstatus1').text(data[6]);
        $('#jobname').text(data[16]);
        $('#datehired').text(data[17]);
        $('#compname').text(data[18]);
        $('#place').text(data[19]);
        $('#jobtype').text(jobTypeText); // Display text after hyphen
        $('#emptype').text(data[21]);
        $('#income').text(data[22]);
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