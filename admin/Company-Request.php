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
    <title>Admin | Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">
    <link rel="stylesheet" href="style.css">
    
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
    $emp_name1 = $_POST['emp_name1'];
    $comp_name1 = $_POST['comp_name1'];
    $comp_int1 = $_POST['comp_int1'];
    $comp_email1 = $_POST['comp_email1'];
    $comp_site1 = $_POST['comp_site1'];
    $description1 = $_POST['description1'];

    $sql = "DELETE FROM tbl_employer WHERE Employer_ID=$ID AND Account='notverified'";
    
    if ($con->query($sql)) {

    // Insert declined application record
    $query = "INSERT INTO tbl_account_declined (Employer_ID, Full_Name, Website_Link, Company_Name, Industry, Description, Email, Account) 
              VALUES ('{$ID}', '{$emp_name1}', '{$comp_site1}', '{$comp_name1}', '{$comp_int1}', '{$description1}', '{$comp_email1}', 'Declined')";

    $updateQuery = "DELETE FROM usertable WHERE email='$comp_email1' AND user='Employer'";

        if (mysqli_query($con, $query) && mysqli_query($con, $updateQuery)) {
            setAlert("Request was Declined!", "Now Check the Declined Tab", "success");
            header("Location: Company-Request.php");
            exit;

            } else {
            setAlert("Request is not Set!", "Failed to decline the Account.", "error");
            header("Location: Company-Request.php");
            exit;
        }
    } else {
        setAlert("Request is not Set!", "Failed to decline the Account.", "error");
        header("Location: Company-Request.php");
        exit;
    }
}

// Update Data
if (isset($_POST['approvebtn'])) {
    $ID = $_POST['view_id1'];
    $comp_email = $_POST['comp_email'];

    // First query to update usertable
    $sql1 = "UPDATE usertable SET status='verified' WHERE code=0 AND email='{$comp_email}'";

    // Second query to update tbl_employer
    $sql2 = "UPDATE tbl_employer SET Account='verified' WHERE Employer_ID=$ID AND Email='{$comp_email}'";

    // Execute first query
    $result1 = $con->query($sql1);
    if (!$result1) {
        setAlert("Request is not Set!", "Error updating usertable: " . $con->error, "warning");
        header("Location: Company-Request.php");
        exit;
    }

    // Execute second query
    $result2 = $con->query($sql2);
    if (!$result2) {
        setAlert("Request is not Set!", "Error updating tbl_employer: " . $con->error, "warning");
        header("Location: Company-Request.php");
        exit;
    }

    // If both succeed
    if ($result1 && $result2) {
        setAlert("Account Approved!", "Another Company has been Added", "success");
        header("Location: Company-Request.php");
        exit;
    } else {
        setAlert("Request is not Set!", "Unknown error occurred", "warning");
        header("Location: Company-Request.php");
        exit;
    }
}

?>


<!-- View Modal -->
<div class="modal fade ViewModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #5E90AB;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; justify-content: space-between; display: flex; width: 95%;">View Account <span id="status" class="status" style="font-size: 18px;">Not Verified</span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Account Details</h5>

      <form action="Company-Request.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body" style="margin-top: -40px;">
          <input type="hidden" name="view_id1" id="view_id1">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Employer Name:</label>
                <input type="text" name="emp_name" id="emp_name" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Name:</label>
                <input type="text" name="comp_name" id="comp_name" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Industry:</label>
                <input type="text" name="comp_int" id="comp_int" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="comp_email" id="comp_email" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Website:</label>
                <input type="text" name="comp_site" id="comp_site" class="form-control" readonly required>
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

          <button type="button" class="btn btn-danger deletebtn" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target=".DeleteModal1" id="deletebtn">Decline</button>

          <button type="submit" name="approvebtn" id="approvebtn" class="btn btn-primary upbtn" onclick="submitNotifForm()" style="background-color: #5E90AB;" 
          onmouseover="this.style.backgroundColor='#416679';" 
          onmouseout="this.style.backgroundColor='#5E90AB';">Approve</button>
          
        </div>
    </form>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade ViewModal1" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; justify-content: space-between; display: flex; width: 95%;">View Account <span id="status2" class="status2" style="font-size: 18px;"></span></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Account Details</h5>

      <form action="Company-Request.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body" style="margin-top: -40px;">
          <input type="hidden" name="view_id2" id="view_id2">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Employer Name:</label>
                <input type="text" name="emp_name2" id="emp_name2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Name:</label>
                <input type="text" name="comp_name2" id="comp_name2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Industry:</label>
                <input type="text" name="comp_int2" id="comp_int2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="comp_email2" id="comp_email2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Company Website:</label>
                <input type="text" name="comp_site2" id="comp_site2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="description2" id="description2" class="form-control" rows="5" readonly required></textarea>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-secondary upbtn" data-bs-dismiss="modal">Done</button>
          
        </div>
    </form>
    </div>
  </div>
</div>


<!-- Declined Modal -->
<div class="modal fade DeleteModal1" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Decline Request</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="Company-Request.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id">

          <h4>Are you sure you want to Decline this Account?</h4>

            <input type="text" name="req_comp" id="req_comp" class="form-control" readonly required>

            <input type="hidden" name="emp_name1" id="emp_name1" class="form-control" readonly required>

            <input type="hidden" name="comp_name1" id="comp_name1" class="form-control" readonly required>

            <input type="hidden" name="comp_int1" id="comp_int1" class="form-control" readonly required>

            <input type="hidden" name="comp_email1" id="comp_email1" class="form-control" readonly required>

            <input type="hidden" name="comp_site1" id="comp_site1" class="form-control" readonly required>

            <textarea name="description1" id="description1" class="form-control" rows="5" readonly style="display: none;"></textarea>
              
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" name="deletedata" onclick="submitNotifForm1()" class="btn btn-primary delbtn">Yes, Decline Request</button>
        </div>
      </form>
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
                <a href="Report.php" class="link">
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
            <h2>Account Approval</h2>
            <p><a href="home.php">Dashboard</a> / <a href="AllUsers.php">All Users</a> / Check Request</p>
        </div>

        <form action="Admin-Approve.php" method="POST" id="notif-form" class="notif-form">

            <input type="hidden" name="sender_mail" id="sender_mail" class="form-control" value="gsample219@gmail.com">

            <input type="hidden" name="rec_email" class="rec_email">
    
        </form>

        <form action="Admin-Declined.php" method="POST" id="notif-form1" class="notif-form">

            <input type="hidden" name="sender_mail" id="sender_mail" class="form-control" value="gsample219@gmail.com">

            <input type="hidden" name="rec_email" class="rec_email">
    
        </form>

        <form action="Employer-HiredPage.php" method="POST" id="resume_form" autocomplete="off">
            <input type="hidden" name="get_email" class="get_email">
        </form>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#Applied">
                    <i class="fa fa-question-circle" style="font-size:20px; color: #E0B418;"></i> Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#Screening">
                     <i class="fa fa-times-circle" style="font-size:20px; color: #9B2626;"></i> Declined</a>
            </li>
        </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="Applied">
            <div class="row border g-0 rounded shadow-sm">
                <div class="col p-4">
                        
    <div class="container_my-5" style="width: 100%; box-shadow: 0 0 2px 0 #222; margin-top: 10px; margin-bottom: 10px;">

    <div class="table-responsive">
        <table class="table table-bordered" id="myTable" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Employer Name</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company Industry</th>
                    <th scope="col">Status</th>  
                    <th scope="col" style="display: none;">Company Website</th>
                    <th scope="col" style="display: none;">Description</th>
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer WHERE Account = 'notverified';";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                
                  $industry = $row['Industry'];
                  $industry_parts = explode('-', $industry);
                  $industry_display = trim($industry_parts[0]);

                    echo "
                    <tr>
                        <td>$row[Employer_ID]</td>
                        <td>$row[First_Name] $row[Last_Name]</td>
                        <td>$row[Company_Name]</td>
                        <td>$row[Email]</td>
                        <td>$industry_display</td>
                        <td>$row[Account]</td>
                        <td style='display: none;'>$row[Website_Link]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn1' style='padding: 8px 15px;'>
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
                    </div>
                </div>
            </div>

        <div class="tab-pane" id="Screening">
            <div class="row border g-0 rounded shadow-sm">
                <div class="col p-4">
                        

        <div class="container_my-5" style="width: 100%; box-shadow: 0 0 2px 0 #222; margin-top: 10px; margin-bottom: 10px;">

             <div class="table-responsive">
        <table class="table table-bordered" id="myTable1" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Employer Name</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company Industry</th> 
                    <th scope="col">Status</th> 
                    <th scope="col" style="display: none;">Company Website</th>
                    <th scope="col" style="display: none;">Description</th>
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_account_declined;";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    
                  $industry = $row['Industry'];
                  $industry_parts = explode('-', $industry);
                  $industry_display = trim($industry_parts[0]);

                    echo "
                    <tr>
                        <td>$row[Employer_ID]</td>
                        <td>$row[Full_Name]</td>
                        <td>$row[Company_Name]</td>
                        <td>$row[Email]</td>
                        <td>$industry_display</td>
                        <td>$row[Account]</td>
                        <td style='display: none;'>$row[Website_Link]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn2' style='padding: 8px 15px;'>
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

<!-- Email Notification (JS FUNCTION) -->
<script>
    function submitNotifForm() {
    const form = document.getElementById('notif-form');
    const formData = new FormData(form);

    fetch('Admin-Approve.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(result => {
            alert('Request Update was sent to the Employer mail.');
            console.log('Result:', result);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Request Update was sent to the Employer mail.');
        });
}
</script>

<script>
    function submitNotifForm1() {
    const form = document.getElementById('notif-form1');
    const formData = new FormData(form);

    fetch('Admin-Declined.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(result => {
            alert('Request Update was sent to the Employer mail.');
            console.log('Result:', result);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Request Update was sent to the Employer mail.');
        });
}
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

<!-- PASS THE INPUT VALUE (JS FUNCTION) -->

<script>
    // Select input elements
    const viewIdInput = document.getElementById("view_id1");
    const deleteIdInput = document.getElementById("delete_id");
    const emp_name = document.getElementById("emp_name");
    const comp_int = document.getElementById("comp_int");
    const comp_email = document.getElementById("comp_email");
    const comp_site = document.getElementById("comp_site");
    const description = document.getElementById("description");
    const comp_name = document.getElementById("comp_name");

    const req_comp = document.getElementById("req_comp");
    const emp_name1 = document.getElementById("emp_name1");
    const comp_name1 = document.getElementById("comp_name1");
    const comp_int1 = document.getElementById("comp_int1");
    const comp_email1 = document.getElementById("comp_email1");
    const comp_site1 = document.getElementById("comp_site1");
    const description1 = document.getElementById("description1");
    const deleteBtn = document.getElementById("deletebtn");

    // Copy function
    deleteBtn.onclick = function () {
        deleteIdInput.value = viewIdInput.value;
        req_comp.value = comp_name.value;
        emp_name1.value = emp_name.value;
        comp_name1.value = comp_name.value;
        comp_int1.value = comp_int.value;
        comp_email1.value = comp_email.value;
        comp_site1.value = comp_site.value;
        description1.value = description.value;
    };
</script>

<!-- Datatable Searchbox (JS FUNCTION) -->

<!-- VIEW POP UP FORM (JS FUNCTION) -->

<script>
    $(document).ready(function () {
        // Initialize #myTable for ApplyModal
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

        // Use event delegation for ApplyModal
        $('#myTable').on('click', '.editbtn1', function () {
            $('.ViewModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            const industryFullText = data[4];
            const industryText = industryFullText.includes('-') 
                ? industryFullText.substring(0, industryFullText.indexOf('-')).trim() 
                : industryFullText;

            $('#view_id1').val(data[0]);
            $('#emp_name').val(data[1]);
            $('#comp_name').val(data[2]);
            $('#comp_email').val(data[3]);
            $('.rec_email').val(data[3]);
            $('#comp_int').val(industryText);
            $('#comp_site').val(data[6]);
            $('#description').val(data[7]);
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Initialize #myTable1 for ScreeningModal
        $('#myTable1').DataTable({
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

        // Use event delegation for ScreeningModal
        $('#myTable1').on('click', '.editbtn2', function () {
            $('.ViewModal1').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            const industryFullText = data[4];
            const industryText = industryFullText.includes('-') 
                ? industryFullText.substring(0, industryFullText.indexOf('-')).trim() 
                : industryFullText;

            $('#view_id2').val(data[0]);
            $('#emp_name2').val(data[1]);
            $('#comp_name2').val(data[2]);
            $('#comp_email2').val(data[3]);
            $('#comp_int2').val(industryText);
            $('#comp_site2').val(data[6]);
            $('#description2').val(data[7]);
            $('#status2').text(data[5]);
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