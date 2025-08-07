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
    <title>Admin | System Users</title>
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


// Insert Hired Data
if (isset($_POST['updatedata2'])) {
    $ID = $_POST['view_id3'];
    $admin_name = $_POST['admin_name'];
    $admin_mail = $_POST['admin_mail'];
    $position = $_POST['position'];

    $sql = "UPDATE tbl_admin 
              SET Name = '{$admin_name}', Position = '{$position}'
              WHERE Admin_ID = '{$ID}'";
    
    if ($con->query($sql)) {

    // Insert declined application record
    $query = "UPDATE usertable 
              SET name = '{$admin_name}'
              WHERE email = '{$admin_mail}' AND user= 'Admin'";

        if (mysqli_query($con, $query)) {
            setAlert("Successfully Updated!", "Admin Details has been successfully updated!", "success");
            header("Location: AllUsers.php");
            exit;

            } else {
            setAlert("Data Not Updated!", "Failed to update the admin details.", "error");
            header("Location: AllUsers.php");
            exit;
        }
    } else {
        setAlert("Data Not Updated!", "Failed to update the admin details.", "error");
        header("Location: AllUsers.php");
        exit;
    }
}


// Delete Data
if (isset($_POST['deletedata'])) {
    $ID = $_POST['delete_id'];
    $admin_delmailemail = $_POST['admin_delmail'];

    $sql = "DELETE FROM tbl_admin WHERE Admin_ID=$ID";
    
    if ($con->query($sql)) {

    // Insert declined application record
    $query = "DELETE FROM usertable WHERE email='$admin_delmailemail'";

        if (mysqli_query($con, $query)) {
            setAlert("Successfully Deleted!", "Admin Details has been successfully deleted!", "success");
            header("Location: login-user.php");
            exit;

            } else {
            setAlert("Data Not Updated!", "Failed to delete the admin details.", "error");
            header("Location: login-user.php");
            exit;
        }
    } else {
        setAlert("Data Not Updated!", "Failed to delete the admin details.", "error");
        header("Location: login-user.php");
        exit;
    }
}


?>

<!-- View Modal for Alumni -->
<div class="modal fade ViewModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #2E6D43;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; display: block;">Alumni Details</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="#" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id1" id="view_id1">

          <div class="row justify-content-center">
            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 20px; text-align: center;">
            <div class="profile-pic">
                <img src="" id="alumni_img">
            </div>
                <label style="margin-top: 10px; font-size: 23px; font-weight: 600;" id="applicant_name"></label>
                
              </div>
            </div>

            <div class="col-lg-8">
              <div class="form-group alumni_labels" style="margin-top: 30px;">
                <div class="row">

            <div class="col-lg-4">
                <label>Account: </label>
            </div>

            <div class="col-lg-8">
                <span class="account"></span> <span class="status-icon"><i class="" aria-hidden="true"></i></span>
            </div>

            <div class="col-lg-4">
                <label>Alumni Tracking Number: </label>
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
          <button type="button" data-bs-dismiss="modal" class="btn btn-secondary" style="background-color: #2E6D43;" 
          onmouseover="this.style.backgroundColor='#1C4B2C';" 
          onmouseout="this.style.backgroundColor='#2E6D43';">Done</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- View Modal for Employer -->
<div class="modal fade ViewModal1" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #5E90AB;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600; display: block;">Employer Details</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="#" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id2" id="view_id2">

          <div class="row justify-content-center">
            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 20px; text-align: center;">
            <div class="profile-pic">
                <img src="" id="emp_img">
            </div>
                <label style="margin-top: 10px; font-size: 23px; font-weight: 600;" id="emp_name"></label>
                
              </div>
            </div>

            <div class="col-lg-8">
              <div class="form-group alumni_labels" style="margin-top: 30px;">
                <div class="row">

            <div class="col-lg-4">
                <label>Reference ID.: </label>
            </div>

            <div class="col-lg-8">
                <span id="ref_id"></span>
            </div>

            <div class="col-lg-4">
                <label>Employer Name: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="emp_name1"></span>
            </div>

            <div class="col-lg-4">
                <label>Email Address: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="emp_mail"></span>
            </div>

            <div class="col-lg-4">
                <label>Contact Number: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="emp_num"></span>
            </div>

            <div class="col-lg-4">
                <label>Company Name: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="con_name"></span>
            </div>

            <div class="col-lg-4">
                <label>Company Industry: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="industry"></span>
            </div>

            <div class="col-lg-4">
                <label>Company State: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="com_state"></span>
            </div>

            <div class="col-lg-4">
                <label>Complete Address: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="com_add"></span>
            </div>

            <div class="col-lg-4">
                <label>Company Website: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <a href="" id="com_link" target="_blank" rel="noopener noreferrer"></a>
            </div>

            <div class="col-lg-4">
                <label>Description: </label>
            </div>

            <div class="col-lg-8 alumni-span" >
                <span id="com_description"></span>
            </div>
              </div>
          </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" data-bs-dismiss="modal" class="btn btn-primary" style="background-color: #5E90AB;" 
          onmouseover="this.style.backgroundColor='#416679';" 
          onmouseout="this.style.backgroundColor='#5E90AB';">Done</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Interview Modal -->
<div class="modal fade ViewModal2" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Update Admin</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Admin Details</h5>

      <form action="AllUsers.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id3" id="view_id3">

          <div class="row justify-content-center">
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Full Name:</label>
                <input type="text" name="admin_name" id="admin_name" class="form-control" required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="admin_mail" id="admin_mail" class="form-control" readonly required style="margin-bottom: 10px;">
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Position:</label>
                <input type="text" name="position" id="position" class="form-control" required>
              </div>
            </div>

        <div class="modal-footer" style="margin-top: 20px;">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updatedata2" class="btn btn-primary upbtn">Update</button>
        </div>
      </div>
    </div>
      </form>
    </div>
  </div>
</div>

<!-- Declined Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Delete Admin</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="AllUsers.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id">

          <h4>Are you sure you want to Delete this Account?</h4>

          <p>This will Automatically Sign you out.</p>

            <input type="text" name="admin_del" id="admin_del" class="form-control" readonly required>

            <input type="hidden" name="admin_delmail" id="admin_delmail" class="form-control" readonly required>
              
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" name="deletedata" class="btn btn-primary delbtn">Yes, Delete Account</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Create Admin Modal -->
<div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Add Admin</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="AllUsers.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body">

          <h4>This will Redirect you to Another Page and will Automatically Sign you out.</h4>

          <h6 style="margin-top: 20px;">Would you still like to Proceed?</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="signup-user.php"><button type="button" class="btn btn-primary addbtn">Proceed</button></a>
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
            <h2>System Users</h2>
            <p><a href="home.php">Dashboard</a> / All Users</p>

            <a href="Company-Request.php"><button class="hirebtn">Check Request <span><i class="fa fa-repeat" aria-hidden="true"></i></span></button></a>
        </div>

        <form action="Employer-HiredPage.php" method="POST" id="resume_form" autocomplete="off">
            <input type="hidden" name="get_email" class="get_email">
        </form>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#Applied">
                    <i class="fa fa-graduation-cap" style="font-size:20px; color: #267B44;"></i> Alumni</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#Screening">
                     <i class="fa fa-briefcase" style="font-size:20px; color: #267B44;"></i> Employer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#Interview">
                    <i class="fa fa-user-circle" style="font-size:20px; color: #E0B418;"></i> Admin</a>
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
                    <th scope="col">Alumni. ID</th>
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
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_alumni";
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
                        <td><img src='index/css/img/$row[Image]' width='50' height='50'></td>
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
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn' style='padding: 8px 15px;' onclick='transferText()'>
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

        <a href="Print-Company.php"><button type="button" class="btn btn-primary" id="edit" style="margin-bottom: 10px;">
        <i class="fa fa-print" aria-hidden="true"></i><span>PRINT</span>
         </button></a>

             <div class="table-responsive">
        <table class="table table-bordered" id="myTable1" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Employer Name</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company State</th>
                    <th scope="col" style="display: none;">Contact Number</th>
                    <th scope="col" style="display: none;">Company Industry</th> 
                    <th scope="col" style="display: none;">Company Website</th>
                    <th scope="col" style="display: none;">Address</th>
                    <th scope="col" style="display: none;">Description</th>
                    <th scope="col" style="display: none;">ImageText</th>     
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer WHERE Account = 'verified';";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[Employer_ID]</td>
                        <td>$row[First_Name] $row[Last_Name]</td>
                        <td>$row[Company_Name]</td>
                        <td><img src='index/css/img/$row[Image]' width='50' height='50'></td>
                        <td>$row[Email]</td>
                        <td>$row[State]</td>
                        <td style='display: none;'>$row[Contact_Number]</td>
                        <td style='display: none;'>$row[Industry]</td>
                        <td style='display: none;'>$row[Website_Link]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td style='display: none;'>$row[Image]</td>
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
        <div class="tab-pane" id="Interview">
            <div class="row border g-0 rounded shadow-sm">
                <div class="col p-4">

        <div class="container_my-5" style="width: 100%; box-shadow: 0 0 2px 0 #222; margin-top: 10px; margin-bottom: 10px;">

    <button type="button" class="btn btn-primary" id="new" data-bs-toggle="modal" data-bs-target="#AddModal" style="margin-bottom: 20px;">
        <i class="fa fa-plus-circle" aria-hidden="true"></i><span>NEW</span>
    </button>
    
    <a href="new-password.php" style="float: right;">Change Password</a>

             <div class="table-responsive">
        <table class="table table-bordered" id="myTable2" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Position</th>
                    <th scope="col" style="width: 100px;">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_admin";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                $rowCount = 0; // Initialize a counter for rows

                while ($row = $result->fetch_assoc()) {
                    $rowCount++;

                    // Check if it's the first row and apply a special class for the delete button
                    $deleteButtonClass = ($rowCount == 1) ? 'no-delete' : '';

                    echo "
                    <tr>
                        <td>$row[Admin_ID]</td>
                        <td>$row[Name]</td>
                        <td>$row[Email]</td>
                        <td>$row[Status]</td>
                        <td>$row[Position]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn2' id='edit'>
                                <i class='fa fa-pencil' aria-hidden='true'></i><span>EDIT</span>
                            </button>

                            <button type='button' class='btn btn-danger btn-sm deletebtn $deleteButtonClass' id='delete'>
                                <i class='fa fa-minus-circle' aria-hidden='true'></i><span>DELETE</span>
                            </button>
                        </td>
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

    // Hide the delete button for the first row
    document.querySelectorAll('.no-delete').forEach(function(button) {
        button.style.display = 'none';
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
        // Initialize DataTable
        const table = $('#myTable3').DataTable({
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

        // Event delegation for .editbtn3 within #myTable3 to support pagination
        $('#myTable3').on('click', '.editbtn3', function () {
            // Show the DeclinedModal
            $('.DeclinedModal').modal('show');

            // Get data from the closest row
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            console.log(data);

            // Populate modal fields with data from the selected row
            $('#view_id4').val(data[0]);
            $('#applicant_name4').val(data[1]);
            $('#alumni_mail4').val(data[2]);
            $('.get_email').val(data[2]);
            $('#alumni_num4').val(data[4]);
            $('#alumni_job4').val(data[5]);
            $('#alumni_status4').val(data[6]);
            $('#alumni_applied4').val(data[7]);
            $('#alumni_address4').val(data[8]);
            $('#alumni_desc4').val(data[9]);
        });
    });
</script>

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
        $('#myTable').on('click', '.editbtn', function () {
            $('.ViewModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            const imageFileName = data[15]; 
            // Full image path
            const imgSrc = `index/css/img/${imageFileName}`;

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
        $('#myTable1').on('click', '.editbtn1', function () {
            $('.ViewModal1').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            const imageFileName = data[11]; 
            // Full image path
            const imgSrc = `index/css/img/${imageFileName}`;

            const link = data[8];
            
            const industryFullText = data[7];
            const industryText = industryFullText.includes('-') 
                ? industryFullText.substring(0, industryFullText.indexOf('-')).trim() 
                : industryFullText;

            $('#view_id2').val(data[0]);
            $('#ref_id').text(data[0]);
            $('#emp_img').attr('src', imgSrc);
            $('#emp_name').text(data[1]);
            $('#emp_name1').text(data[1]);
            $('#emp_mail').text(data[4]);
            $('#emp_num').text(data[6]);
            $('#con_name').text(data[2]);
            $('#industry').text(industryText);
            $('#com_state').text(data[5]);
            $('#com_add').text(data[9]);
            $('#com_link').attr('href', link).text("Click to Visit");
            $('#com_description').text(data[10]);
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Initialize #myTable2 for InterviewModal
        $('#myTable2').DataTable({
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

        // Use event delegation for InterviewModal
        $('#myTable2').on('click', '.editbtn2', function () {
            $('.ViewModal2').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            $('#view_id3').val(data[0]);
            $('#admin_name').val(data[1]);
            $('#admin_mail').val(data[2]);
            $('#position').val(data[4]);
        });
    });
</script>

 <script>
        $(document).ready(function () {

            $('.deletebtn').on('click', function () {

                $('#DeleteModal').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                console.log(data);

                $('#delete_id').val(data[0]);
                $('#admin_del').val(data[1]);
                $('#admin_delmail').val(data[2]);

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