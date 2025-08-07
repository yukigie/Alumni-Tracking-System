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
    <title>Admin | Job Report</title>
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
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; justify-content: space-between; display: flex; width: 78%;">View Job <span id="applicants"></span></h4>
        <span style="font-size: 14px;">Applicant/s for this Job <i class="fa fa-user-plus" aria-hidden="true"></i></span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Job Details</h5>
      <p class="status-text">
        <span class="status"></span> <span class="status-icon"><i class="" aria-hidden="true"></i></span>
    </p>

        <div class="modal-body">
          <input type="hidden" name="view_id1" id="view_id1">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Title:</label>
                <input type="text" name="job_title" id="job_title" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Name:</label>
                <input type="text" name="com_name" id="com_name" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Type:</label>
                <input type="text" name="job_type" id="job_type" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Posted Date:</label>
                <input type="date" name="posted_date" id="posted_date" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">No. of Available Position/s:</label>
                <input type="text" name="position" id="position" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Close Date:</label>
                <input type="date" name="close_date" id="close_date" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Image/Logo:</label>
                <img src="" id="alumni_img" style="width: 200px; height: 200px; border-radius: 20px; display: block; margin-left: 10px;">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <textarea name="address" id="address" class="form-control" rows="5" readonly></textarea> 
              </div>
            </div>

           <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Skills Required:</label>
                <input type="text" name="skills" id="skills" class="form-control" readonly required>
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
        <h2>Job Report</h2>
        <p><a href="home.php">Dashboard</a> / Job Report</p>
    </div>

    <div class="container_my-5">

    <input type="hidden" name="edit_id" id="edit_id">

    <br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Job Title</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Job Posted Date</th>
                    <th scope="col">Job Applicants</th> 
                    <th scope="col">Job Status</th>
                    <th scope="col" style="display: none;">Job Type</th>
                    <th scope="col" style="display: none;">Positions</th> 
                    <th scope="col" style="display: none;">Job Close Date</th> 
                    <th scope="col" style="display: none;">Address</th> 
                    <th scope="col" style="display: none;">Image</th> 
                    <th scope="col" style="display: none;">Skills</th> 
                    <th scope="col" style="display: none;">Description</th>    
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_joblist";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Company_Name]</td>
                        <td>$row[Job_Start_Date]</td>
                        <td>$row[Job_Applicants]</td>
                        <td class='status-col'></td>
                        <td style='display: none;'>$row[Job_Type]</td>
                        <td style='display: none;'>$row[Available_Positions]</td>
                        <td class='close-col' style='display: none;'>$row[Job_Close_Date]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Image]</td>
                        <td style='display: none;'>$row[Skills]</td>
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


<!-- Job Status Check (JS FUNCTION) -->

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

<script>

function transferText() {
    setTimeout(function() {
        const spanText = document.querySelector('.status').textContent;

        $('.status').each(function() {
            const accountStatus = $(this).text().trim();
            const statusIcon = $(this).siblings('.status-icon').find('i');

                if (accountStatus === "Inactive") {

                    statusIcon.text("●");
                    statusIcon.css('color', 'red');
                } else {

                    statusIcon.text("●");
                    statusIcon.css('color', 'green');
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


            const imageFileName = data[10]; 
            // Full image path
            const imgSrc = `index/css/img/${imageFileName}`;

            $('#view_id1').val(data[0]);
            $('#job_title').val(data[1]);
            $('#com_name').val(data[2]);
            $('#job_type').val(data[6]);
            $('#posted_date').val(data[3]);
            $('#position').val(data[7]);
            $('#close_date').val(data[8]);
            $('#address').val(data[9]);
            $('#skills').val(data[11]);
            $('#description').val(data[12]);
            $('#applicants').text(data[4]);
            $('.status').text(data[5]);
            $('#alumni_img').attr('src', imgSrc);
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