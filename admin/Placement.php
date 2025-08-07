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
    <title>Admin | Placement List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

<!-- View Modal -->
<div class="modal fade ViewModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #5E90AB;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; justify-content: space-between; display: flex; width: 95%;">View Applicant <span id="status" class="status"></span></h4>
        <span style="font-size: 20px;" class="status-icon"><i class="" aria-hidden="true"></i></span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Placement Details</h5>

        <div class="modal-body" style="margin-top: -40px;">
          <input type="hidden" name="view_id1" id="view_id1">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applicant Name:</label>
                <input type="text" name="applicant_name" id="applicant_name" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Graduated Course:</label>
                <input type="text" name="grad_course" id="grad_course" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Title:</label>
                <input type="text" name="job_title" id="job_title" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applied Date:</label>
                <input type="date" name="applied_date" id="applied_date" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Contact Number:</label>
                <input type="text" name="con_num" id="con_num" class="form-control" readonly required>
              </div>
            </div>


           <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <input type="text" name="address" id="address" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="5" readonly required></textarea>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary upbtn" data-bs-dismiss="modal" style="background-color: #5E90AB;" 
          onmouseover="this.style.backgroundColor='#416679';" 
          onmouseout="this.style.backgroundColor='#5E90AB';">Done</button>
          
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
        <h2>Placement List</h2>
        <p><a href="home.php">Dashboard</a> / <a href="Alumni.php">Alumni List</a> / Check Placement</p>
    </div>

    <div class="container_my-5">
    <!-- Button trigger modal -->
    <!-- <a href="Print-Alumni.php"><button type="button" class="btn btn-primary" id="edit">
        <i class="fa fa-print" aria-hidden="true"></i><span>PRINT</span>
    </button></a> -->

    <input type="hidden" name="edit_id" id="edit_id">

    <br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Alumni. ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Job Title</th>
                    <th scope="col">Image</th>
                    <th scope="col">Applied Date</th>
                    <th scope="col">Course</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Contact_Number</th>
                    <th scope="col" style="display: none;">Address</th>
                    <th scope="col" style="display: none;">Description</th>
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT 
                tbl_employer_application.*,
                tbl_alumni.Alumni_ID,
                tbl_alumni.Course,
                tbl_alumni.Email
            FROM 
                tbl_employer_application
            JOIN 
                tbl_alumni ON (tbl_employer_application.Email_Alumni = tbl_alumni.Email AND 
                tbl_employer_application.Alumni_ID = tbl_alumni.Alumni_ID)";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[Alumni_ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Job_Title]</td>
                        <td><img src='index/css/img/$row[Image]' width='50' height='50'></td>
                        <td>$row[Applied_Date]</td>
                        <td>$row[Course]</td>
                        <td>$row[Status]</td>
                        <td style='display: none;'>$row[Contact_Number]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Description]</td>
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

<!-- Account Status Icon (JS FUNCTION) -->
<script>

function transferText() {
    setTimeout(function() {
        const spanText = document.querySelector('.status').textContent;

        $('.status').each(function() {
            const accountStatus = $(this).text().trim().toLowerCase();
            const statusIcon = $(this).siblings('.status-icon').find('i');

            // Check if the account status is 'notverified'
                if (accountStatus === "Hired") {
                // Change the icon to 'fa-times-circle' and color to red
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "green");
                accountStatus.css("color", "green");
            }

                else if (accountStatus === "Applied") {
                // Change the icon to 'fa-times-circle' and color to red
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "white");
                accountStatus.css("color", "white");

             }
             else if (accountStatus === "Screening") {
                // Change the icon to 'fa-times-circle' and color to red
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "yellow");
                accountStatus.css("color", "yellow");

             }
             else if (accountStatus === "Interview") {
                // Change the icon to 'fa-times-circle' and color to red
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "lightblue");
                accountStatus.css("color", "lightblue");

             } else {
                // Reset the icon to 'fa-check-circle' and color to green if verified
                statusIcon.addClass("fa fa-check-circle");
                statusIcon.css("color", "");
                accountStatus.css("color", "");
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
            $('.ViewModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();


            $('#view_id1').val(data[0]);
            $('#applicant_name').val(data[1]);
            $('#grad_course').val(data[5]);
            $('#job_title').val(data[2]);
            $('#applied_date').val(data[4]);
            $('#con_num').val(data[7]);
            $('#address').val(data[8]);
            $('#description').val(data[9]);
            $('#status').text(data[6]);
        });
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