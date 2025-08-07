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

// Define the setAlert function to store SweetAlert message data
function setAlert($title, $text, $icon, $button = "Done") {
    $_SESSION['alert'] = [
        'title' => $title,
        'text' => $text,
        'icon' => $icon,
        'button' => $button
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employer | Job Report</title>
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


    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';

// Show the alert if it's set in the session
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo "<script>swal({
        title: '{$alert['title']}',
        text: '{$alert['text']}',
        icon: '{$alert['icon']}',
        button: '{$alert['button']}',
        closeOnClickOutside: true,
    });</script>";

    // Clear the alert from the session so it doesn't display again
    unset($_SESSION['alert']);
}

// Delete Data
if (isset($_POST['deletedata'])) {
    $ID = $_POST['delete_id'];
    $sql = "DELETE FROM tbl_employer_joblist WHERE ID=$ID";
    $sql1 = "DELETE FROM tbl_employer_jobsched WHERE Job_ID=$ID";
    $sql2 = "DELETE FROM tbl_employer_skill WHERE Job_ID=$ID";
    
    if ($con->query($sql) && $con->query($sql1) && $con->query($sql2)) {
        setAlert("Successfully Deleted!", "Job Is Successfully Deleted!", "success");
        header("Location: Employer-JobReport.php");
        exit;
    } else {
        setAlert("Data Not Deleted!", "Failed to delete the Job.", "error");
        header("Location: Employer-JobReport.php");
        exit;
    }
}

mysqli_close($con);

?>


<!-- Delete Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Delete Job</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="Employer-JobReport.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id">
          <h4>Are you sure you want to Delete this?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" name="deletedata" class="btn btn-primary delbtn">Yes, Delete it</button>
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
        require 'connection.php';

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

    <!-- <section class="home">
        <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        <div class="head-title">
            <h2>Dashboard Page</h2>
        </div>
    </section> -->

    <section class="home">

        <div class="head-title">
            <h2>Job Report</h2>
            <p><a href="">Dashboard</a> / Job Report</p>
        </div>

        <div class="container_my-5">
    <!-- Button trigger modal -->
    <a href="Employer-AddJob.php"><button type="button" class="btn btn-primary" id="new">
        <i class="fa fa-plus-circle" aria-hidden="true"></i><span>NEW</span>
    </button></a>

    <input type="hidden" name="edit_id" id="edit_id">

    <br><br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Job Title</th>
                    <th scope="col">Job Type</th>
                    <th scope="col">Job Posted Date</th>
                    <th scope="col">Job Close Date</th>
                    <th scope="col">Job Applicants</th> 
                    <th scope="col">Status</th>   
                    <th scope="col" style="display: none;">Job Close Date</th>    
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_joblist WHERE Email = '{$email}'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Job_Type]</td>
                        <td>$row[Job_Start_Date]</td>
                        <td>$row[Job_Close_Date]</td>
                        <td>$row[Job_Applicants]</td>
                        <td class='status-col'></td>
                        <td class='close-col' style='display: none;'>$row[Job_Close_Date]</td>
                        <td>
                            <a href='Employer-EditJob.php?id=$row[ID]'<button type='button' class='btn btn-primary btn-sm editbtn' id='edit'>
                                <i class='fa fa-pencil' aria-hidden='true'></i><span>EDIT</span>
                            </button></a>
                            <button type='button' class='btn btn-danger btn-sm deletebtn' id='delete'>
                                <i class='fa fa-minus-circle' aria-hidden='true'></i><span>DELETE</span>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
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


<!-- EDIT POP UP FORM (JS FUNCTION) -->

<script>
    $(document).ready(function () {

        // Event delegation for .editbtn within #myTable to support pagination
        $('#myTable').on('click', '.editbtn', function () {

            // Get data from the closest row
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            console.log(data);

            // Populate modal fields with data from the selected row
            $('#edit_id').val(data[0]);
   
        });
    });
</script>


<!-- DELETE POP UP FORM (JS FUNCTION) -->

<script>
    $(document).ready(function () {
        // Initialize DataTable
        const table = $('#myTable').DataTable({
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

        // Event delegation for .deletebtn within #myTable to support pagination
        $('#myTable').on('click', '.deletebtn', function () {
            // Show the DeleteModal
            $('#DeleteModal').modal('show');

            // Get data from the closest row
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            console.log(data);

            // Populate modal fields with data from the selected row
            $('#delete_id').val(data[0]);
   
        });
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