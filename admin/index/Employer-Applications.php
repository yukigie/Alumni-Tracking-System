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
    <title>Employer | Applications</title>
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

// Insert or Update Data
if (isset($_POST['updatedata'])) {
    $view_id = $_POST['view_id'];
    $alumni_mail = $_POST['alumni_mail'];

    // Check if the Email is already in the table
    $query = "UPDATE tbl_employer_application 
              SET Status = 'Screening'
              WHERE ID = '{$view_id}' AND Email_Alumni = '{$alumni_mail}'";

    // Execute the query
    if (mysqli_query($con, $query)) {
        setAlert("Applicant Set for Screening!", "Now Check the Screening Tab", "success");
        header("Location: Employer-Applications.php");
        exit;
    } else {
        setAlert("Application is not Set!", "Failed to set the Applicant.", "error");
        header("Location: Employer-Applications.php");
        exit;
    }
}

if (isset($_POST['setsched'])) {
    $set_id = $_POST['set_id'];
    $set_mail = $_POST['set_mail'];
    $setdatetime = $_POST['setdatetime'];
    $setlink = $_POST['setlink'];
    $setmsg = $_POST['setmsg'];

    // Retrieve the current values from the database
    $existingQuery = mysqli_query($con, "SELECT Interview_DateTime, Interview_Link, Interview_Note FROM tbl_employer_application WHERE ID = '$set_id' AND Email_Alumni = '$set_mail'");
    $existingRow = mysqli_fetch_assoc($existingQuery);
    
    $existingDateTime = $existingRow['Interview_DateTime'];
    $existingLink = $existingRow['Interview_Link'];
    $existingMsg = $existingRow['Interview_Note'];

    // Initialize an empty array to store fields that need to be updated
    $updateFields = [];

    // Check each field for changes
    if ($existingDateTime !== $setdatetime) {
        // Only check for conflicts if Interview_DateTime has changed
        $check = mysqli_query($con, "SELECT * FROM tbl_employer_application WHERE Interview_DateTime = '$setdatetime'");
        if (mysqli_num_rows($check) > 0) {
            setAlert("Schedule Slot is Already Taken!", "Please Choose different Date or Time instead", "warning");
              header("Location: Employer-Applications.php");
            exit;   // Stop further processing if there's a conflict
        }
        // Add date field to update array
        $updateFields[] = "Interview_DateTime = '$setdatetime'";
    }

    // Add other fields to the update array if they've changed
    if ($existingLink !== $setlink) {
        $updateFields[] = "Interview_Link = '$setlink'";
    }
    if ($existingMsg !== $setmsg) {
        $updateFields[] = "Interview_Note = '$setmsg'";
    }

    // Proceed with the update only if there are fields that have changed
    if (!empty($updateFields)) {
        // Join the fields to create the update statement dynamically
        $updateQuery = "UPDATE tbl_employer_application 
                        SET Status = 'Interview', " . implode(", ", $updateFields) . " 
                        WHERE ID = '$set_id' AND Email_Alumni = '$set_mail'";

        // Execute the update query
        if (mysqli_query($con, $updateQuery)) {
            setAlert("Applicant Set for Interview!", "Now Check the Interview Tab", "success");
            header("Location: Employer-Applications.php");
            exit;
        } else {
            setAlert("Application is not Set!", "Failed to set the Applicant.", "error");
            header("Location: Employer-Applications.php");
            exit;
        }
    } else {
        // If no fields have changed, just redirect without updating
        header("Location: Employer-Applications.php");
        exit;
    }
}


// Insert Hired Data
if (isset($_POST['updatedata2'])) {
    $ID = $_POST['view_id2'];
    $applicant_name2 = $_POST['applicant_name2'];
    $alumni_mail2 = $_POST['alumni_mail2'];
    $alumni_img2 = $_POST['alumni_img2'];
    $alumni_num2 = $_POST['alumni_num2'];
    $alumni_applied2 = $_POST['alumni_applied2'];
    $alumni_job2 = $_POST['alumni_job2'];
    $alumni_address2 = $_POST['alumni_address2'];
    $alumni_desc2 = $_POST['alumni_desc2'];
    $alumni_id2 = $_POST['alumni_id2'];
    $alumni_jobid2 = $_POST['alumni_jobid2'];
    $alumni_empmail2 = $_POST['alumni_empmail2'];

    $sql = "UPDATE tbl_employer_application 
              SET Status = 'Hired'
              WHERE Job_ID = '{$alumni_jobid2}' AND Email_Alumni = '{$alumni_mail2}'";
    
    if ($con->query($sql)) {

    // Insert declined application record
    $query = "INSERT INTO tbl_employer_hired (Alumni_ID, Job_ID, Applicant_Name, Contact_Number, Applied_Date, Job_Title, Status, Address, Description, Email_Employer, Email_Alumni, Image, Hired_Date) 
              VALUES ('{$alumni_id2}', '{$alumni_jobid2}', '{$applicant_name2}', '{$alumni_num2}', '{$alumni_applied2}', '{$alumni_job2}', 'Hired', '{$alumni_address2}', '{$alumni_desc2}', '{$alumni_empmail2}', '{$alumni_mail2}', '{$alumni_img2}', NOW())";

        if (mysqli_query($con, $query)) {
            setAlert("Application Approved!", "Now Check the Hired Applicants List", "success");
            header("Location: Employer-Applications.php");
            exit;

            } else {
            setAlert("Application is not Set!", "Failed to set the Applicant.", "error");
            header("Location: Employer-Applications.php");
            exit;
        }
    } else {
        setAlert("Application is not Set!", "Failed to set the Applicant.", "error");
        header("Location: Employer-Applications.php");
        exit;
    }
}


// Delete Data
if (isset($_POST['deletedata'])) {
    $ID = $_POST['delete_id'];
    $applicant_name3 = $_POST['applicant_name3'];
    $alumni_mail3 = $_POST['alumni_mail3'];
    $alumni_img3 = $_POST['alumni_img3'];
    $alumni_num3 = $_POST['alumni_num3'];
    $alumni_applied3 = $_POST['alumni_applied3'];
    $alumni_job3 = $_POST['alumni_job3'];
    $alumni_address3 = $_POST['alumni_address3'];
    $alumni_desc3 = $_POST['alumni_desc3'];
    $alumni_id3 = $_POST['alumni_id3'];
    $alumni_jobid3 = $_POST['alumni_jobid3'];
    $alumni_empmail3 = $_POST['alumni_empmail3'];
    $emp_note = $_POST['emp_note'];

    $sql = "DELETE FROM tbl_employer_application WHERE ID=$ID";
    
    if ($con->query($sql)) {

    // Insert declined application record
    $query = "INSERT INTO tbl_employer_declined (Alumni_ID, Job_ID, Applicant_Name, Contact_Number, Applied_Date, Job_Title, Status, Address, Description, Email_Employer, Email_Alumni, Image, Note, Declined_Date) 
              VALUES ('{$alumni_id3}', '{$alumni_jobid3}', '{$applicant_name3}', '{$alumni_num3}', '{$alumni_applied3}', '{$alumni_job3}', 'Declined', '{$alumni_address3}', '{$alumni_desc3}', '{$alumni_empmail3}', '{$alumni_mail3}', '{$alumni_img3}', '{$emp_note}', NOW())";

    $updateQuery = "UPDATE tbl_employer_joblist 
                        SET Available_Positions = Available_Positions + 1, 
                            Job_Applicants = Job_Applicants - 1 
                        WHERE ID = '{$alumni_jobid3}'";

        if (mysqli_query($con, $query) && mysqli_query($con, $updateQuery)) {
            setAlert("Application was Declined!", "Now Check the Declined Tab", "success");
            header("Location: Employer-Applications.php");
            exit;

            } else {
            setAlert("Application is not Set!", "Failed to set the Applicant.", "error");
            header("Location: Employer-Applications.php");
            exit;
        }
    } else {
        setAlert("Application is not Set!", "Failed to set the Applicant.", "error");
        header("Location: Employer-Applications.php");
        exit;
    }
}


?>

<!-- Applied Modal -->
<div class="modal fade ApplyModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #427855;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">View Applicant - Applied</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Applicant Details</h5>

      <form action="Employer-Applications.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id" id="view_id">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applicant Name:</label>
                <input type="text" name="applicant_name" id="applicant_name" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="alumni_mail" id="alumni_mail" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Con. Number:</label>
                <input type="text" name="alumni_num" id="alumni_num" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applied Date:</label>
                <input type="date" name="alumni_applied" id="alumni_applied" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Applied For:</label>
                <input type="text" name="alumni_job" id="alumni_job" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Status:</label>
                <input type="text" name="alumni_status" id="alumni_status" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <input type="text" name="alumni_address" id="alumni_address" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px; display: block;">Applicant Resume:</label>
                  <button type="submit" class="preview" id="resumebtn" form="resume_form" style="float: left; margin-top: 10px; width: 70%;">Open Resume <i class="fa fa-file-text" aria-hidden="true"></i></button>
              </div>
            </div>

            <div class="col-lg-9">
              <div class="form-group" style="margin-top: 0px;">
                 <p style="font-size: 13px; width: 80%; margin-top: 50px;">Resumes generated by our system can be easily viewed in most web browsers (such as Chrome, Firefox, or Edge) or any PDF reader. Simply click on the button to open it. For security and privacy, please handle and store the document responsibly. Thank you!</p>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="alumni_desc" id="alumni_desc" class="form-control" readonly required></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
        <a id="sendMessageLink" href="#"><button type="button" class="btn btn-primary">Send Message</button></a>

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updatedata" class="btn btn-primary upbtn" onclick="submitNotifForm()" style="background-color: #427855;" 
          onmouseover="this.style.backgroundColor='#315A3F';" 
          onmouseout="this.style.backgroundColor='#427855';">Set For Screening</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Screening Modal -->
<div class="modal fade ScreeningModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #A79122;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">View Applicant - Screening</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Applicant Details</h5>

      <form action="Employer-Applications.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id1" id="view_id1">

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applicant Name:</label>
                <input type="text" name="applicant_name1" id="applicant_name1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="alumni_mail1" id="alumni_mail1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Con. Number:</label>
                <input type="text" name="alumni_num1" id="alumni_num1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applied Date:</label>
                <input type="date" name="alumni_applied1" id="alumni_applied1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Applied For:</label>
                <input type="text" name="alumni_job1" id="alumni_job1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Status:</label>
                <input type="text" name="alumni_status1" id="alumni_status1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <input type="text" name="alumni_address1" id="alumni_address1" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px; display: block;">Applicant Resume:</label>
                  <button type="submit" class="preview" id="resumebtn" form="resume_form" style="float: left; margin-top: 10px; width: 70%;">Open Resume <i class="fa fa-file-text" aria-hidden="true"></i></button>
              </div>
            </div>

            <div class="col-lg-9">
              <div class="form-group" style="margin-top: 0px;">
                 <p style="font-size: 13px; width: 80%; margin-top: 50px;">Resumes generated by our system can be easily viewed in most web browsers (such as Chrome, Firefox, or Edge) or any PDF reader. Simply click on the button to open it. For security and privacy, please handle and store the document responsibly. Thank you!</p>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="alumni_desc1" id="alumni_desc1" class="form-control" readonly required></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">

            <a id="sendMessageLink1" href="#"><button type="button" class="btn btn-primary">Send Message</button></a>

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" name="updatedata1" id="updatedata1" class="btn btn-secondary updatedata1" style="background-color: #A79122;" 
          onmouseover="this.style.backgroundColor='#786817';" 
          onmouseout="this.style.backgroundColor='#A79122';"
          data-bs-dismiss="modal">Set For Interview</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Interview Modal -->
<div class="modal fade InterviewModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #5E90AB;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">View Applicant - Interview</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Schedule Details</h5>

      <form action="Employer-Applications.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id2" id="view_id2">

          <div class="row justify-content-center">

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <h6 style="margin-bottom: 5px;">Date & Time:</h6>
                
                <h6 name="sched_time" id="sched_time" style="display: inline;"></h6>
                <button type="button" id="updatesched" class="updatesched" data-bs-dismiss="modal" style="display: inline; background-color: transparent; border: none; padding-left: 5px;"> <i class="fa fa-pencil" aria-hidden="true"></i></button>

                <h6 style="margin-top: 20px; margin-bottom: -1px;">Location/Link:</h6>
                <i><p name="sched_link" id="sched_link"></p></i>

              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                
                <h6 style="margin-bottom: 5px;">Additional Instructions:</h6>
                <p name="sched_msg" id="sched_msg"></p>

              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-left: -30px;">
                <h5>Applicant Details</h5>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applicant Name:</label>
                <input type="text" name="applicant_name2" id="applicant_name2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="alumni_mail2" id="alumni_mail2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Con. Number:</label>
                <input type="text" name="alumni_num2" id="alumni_num2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applied Date:</label>
                <input type="date" name="alumni_applied2" id="alumni_applied2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Applied For:</label>
                <input type="text" name="alumni_job2" id="alumni_job2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Status:</label>
                <input type="text" name="alumni_status2" id="alumni_status2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <input type="text" name="alumni_address2" id="alumni_address2" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px; display: block;">Applicant Resume:</label>
                  <button type="submit" class="preview" id="resumebtn" form="resume_form" style="float: left; margin-top: 10px; width: 70%;">Open Resume <i class="fa fa-file-text" aria-hidden="true"></i></button>
              </div>
            </div>

            <div class="col-lg-9">
              <div class="form-group" style="margin-top: 0px;">
                 <p style="font-size: 13px; width: 80%; margin-top: 50px;">Resumes generated by our system can be easily viewed in most web browsers (such as Chrome, Firefox, or Edge) or any PDF reader. Simply click on the button to open it. For security and privacy, please handle and store the document responsibly. Thank you!</p>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="alumni_desc2" id="alumni_desc2" class="form-control" readonly required></textarea>
              </div>
            </div>

            <input type="hidden" name="alumni_id2" id="alumni_id2" class="form-control" readonly required>

            <input type="hidden" name="alumni_jobid2" id="alumni_jobid2" class="form-control" readonly required>

            <input type="hidden" name="alumni_empmail2" id="alumni_empmail2" class="form-control" readonly required>

            <input type="hidden" name="alumni_img2" id="alumni_img2" class="form-control" readonly required>
          </div>
        </div>

        <div class="modal-footer">

        <a id="sendMessageLink2" href="#"><button type="button" class="btn btn-primary">Send Message</button></a>

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updatedata2" class="btn btn-primary upbtn" style="background-color: #2E6D43;" 
          onmouseover="this.style.backgroundColor='#1C4B2C';" 
          onmouseout="this.style.backgroundColor='#2E6D43';" onclick="submitNotifForm()">Hire Applicant</button>
          <button type="button" name="deletebtn" id="deletebtn" class="btn btn-danger deletebtn" data-bs-dismiss="modal">Decline Applicant</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Declined Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Decline Application</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="Employer-Applications.php" method="POST" class="form_body" autocomplete="off" style="margin-top: -20px;">
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id">

          <h4>Are you sure you want to take this action?</h4>

          <label style="margin-bottom: 5px; display: block;">*Note to Applicant:</label>

          <textarea name="emp_note" id="emp_note" class="form-control" rows="5" required></textarea>

            <input type="hidden" name="applicant_name3" id="applicant_name3" class="form-control" readonly required>

            <input type="hidden" name="alumni_mail3" id="alumni_mail3" class="form-control" readonly required>

            <input type="hidden" name="alumni_img3" id="alumni_img3" class="form-control" readonly required>
             
            <input type="hidden" name="alumni_num3" id="alumni_num3" class="form-control" readonly required>
             
            <input type="date" name="alumni_applied3" id="alumni_applied3" class="form-control" readonly required style="display: none;">
             
            <input type="hidden" name="alumni_job3" id="alumni_job3" class="form-control" readonly required>
            
            <input type="hidden" name="alumni_status3" id="alumni_status3" class="form-control" readonly required>
            
            <input type="hidden" name="alumni_address3" id="alumni_address3" class="form-control" readonly required>

            <textarea name="alumni_desc3" id="alumni_desc3" class="form-control" readonly required style="display: none;"></textarea>

            <input type="hidden" name="alumni_id3" id="alumni_id3" class="form-control" readonly required>

            <input type="hidden" name="alumni_jobid3" id="alumni_jobid3" class="form-control" readonly required>

            <input type="hidden" name="alumni_empmail3" id="alumni_empmail3" class="form-control" readonly required>
              
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" name="deletedata" class="btn btn-primary delbtn" onclick="submitNotifForm()">Yes, Decline Applicant</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Declined Modal -->
<div class="modal fade DeclinedModal" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">View Applicant - Declined</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Applicant Details</h5>

      <form action="Employer-Applications.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off" style="margin-top: -30px;">
        <div class="modal-body">
          <input type="hidden" name="view_id4" id="view_id4">

          <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px; display: flex; justify-content: space-between;">Note to Applicant: <i class="fa fa-clock-o" aria-hidden="true"></i><span name="decline_date" id="decline_date" style="font-size: 12px; margin-left: -660px;"></span></label>
                <i><p name="note_text" id="note_text" style="font-size: 14px;"></p></i>
              </div>
            </div>

          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applicant Name:</label>
                <input type="text" name="applicant_name4" id="applicant_name4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Email:</label>
                <input type="text" name="alumni_mail4" id="alumni_mail4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Con. Number:</label>
                <input type="text" name="alumni_num4" id="alumni_num4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Applied Date:</label>
                <input type="date" name="alumni_applied4" id="alumni_applied4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Job Applied For:</label>
                <input type="text" name="alumni_job4" id="alumni_job4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Status:</label>
                <input type="text" name="alumni_status4" id="alumni_status4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Address:</label>
                <input type="text" name="alumni_address4" id="alumni_address4" class="form-control" readonly required>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px; display: block;">Applicant Resume:</label>
                  <button type="submit" class="preview" id="resumebtn" form="resume_form" style="float: left; margin-top: 10px; width: 70%;">Open Resume <i class="fa fa-file-text" aria-hidden="true"></i></button>
              </div>
            </div>

            <div class="col-lg-9">
              <div class="form-group" style="margin-top: 0px;">
                 <p style="font-size: 13px; width: 80%; margin-top: 50px;">Resumes generated by our system can be easily viewed in most web browsers (such as Chrome, Firefox, or Edge) or any PDF reader. Simply click on the button to open it. For security and privacy, please handle and store the document responsibly. Thank you!</p>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Description:</label>
                <textarea name="alumni_desc4" id="alumni_desc4" class="form-control" readonly required></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          
        <a id="sendMessageLink4" href="#"><button type="button" class="btn btn-primary">Send Message</button></a>

        </div>
      </form>
    </div>
  </div>
</div>

<!-- Set Interview Modal -->
<div class="modal fade SetIntModal" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #5E90AB;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Set Interview</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Schedule Details</h5>

      <form action="Employer-Applications.php" method="POST" class="form_body" autocomplete="off" style="margin-top: -20px;">
        <div class="modal-body">
          <input type="hidden" name="set_id" id="set_id">
          <input type="hidden" name="set_mail" id="set_mail">

           <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">*Select Date & Time:</label>
                <input type="datetime-local" name="setdatetime" id="setdatetime" class="form-control" required>
              </div>
            </div>
            
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">Location/Link (Optional):</label>
                <input type="text" name="setlink" id="setlink" class="form-control">
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 5px;">*Additional Instructions:</label>
                <textarea name="setmsg" id="setmsg" class="form-control" rows="5" required></textarea>
              </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="setsched" id="setsched" class="btn btn-primary setsched" onclick="submitNotifForm()">Set Schedule</button>
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
            <h2>Application Report</h2>
            <p><a href="">Dashboard</a> / Application Report</p>

            <a href="Employer-HiredPage.php"><button class="hirebtn">Hired Applicants <span><i class="fa fa-check-circle" aria-hidden="true"></i></span></button></a>

        </div>

        <form action="Employer-Notif.php" method="POST" id="notif-form" class="notif-form">

            <input type="hidden" name="sender_mail" id="sender_mail" class="form-control" value="gsample219@gmail.com">

            <input type="hidden" name="rec_email" class="rec_email">

            <input type="hidden" name="mail_status" class="mail_status">

            <input type="hidden" name="mail_job" class="mail_job">
    
        </form>

        <form action="Employer-HiredPage.php" method="POST" id="resume_form" autocomplete="off">
            <input type="hidden" name="get_email" class="get_email">
        </form>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#Applied">
                    <i class="fa fa-user-circle-o" style="font-size:20px; color: #267B44;"></i> Applied</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#Screening">
                     <i class="fa fa-question-circle" style="font-size:20px; color: #E0B418;"></i> Screening</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#Interview">
                    <i class="fa fa-comments" style="font-size:20px; color: #4E9CEA;"></i> Interview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#Declined">
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
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Con. Number</th>
                    <th scope="col">Job Applied For</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Applied Date</th>
                    <th scope="col" style="display: none;">Address</th> 
                    <th scope="col" style="display: none;">Description</th>       
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_application WHERE Status = 'Applied' AND Email_Employer = '{$email}'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Email_Alumni]</td>
                        <td><img src='css/img/$row[Image]' width='60' height='60'></td>
                        <td>$row[Contact_Number]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Status] <i class='fa fa-user-circle-o' style='font-size:20px; color: #267B44;'></i></td>
                        <td style='display: none;'>$row[Applied_Date]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn' style='padding: 8px 15px;'>
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
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Con. Number</th>
                    <th scope="col">Job Applied For</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Applied Date</th>
                    <th scope="col" style="display: none;">Address</th> 
                    <th scope="col" style="display: none;">Description</th>     
                           
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_application WHERE Status = 'Screening' AND Email_Employer = '{$email}'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Email_Alumni]</td>
                        <td><img src='css/img/$row[Image]' width='50' height='50'></td>
                        <td>$row[Contact_Number]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Status] <i class='fa fa fa-question-circle' style='font-size:20px; color: #E0B418;'></i></td>
                        <td style='display: none;'>$row[Applied_Date]</td>
                        <td style='display: none;'>$row[Address]</td>
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
        <div class="tab-pane" id="Interview">
            <div class="row border g-0 rounded shadow-sm">
                <div class="col p-4">

        <div class="container_my-5" style="width: 100%; box-shadow: 0 0 2px 0 #222; margin-top: 10px; margin-bottom: 10px;">

             <div class="table-responsive">
        <table class="table table-bordered" id="myTable2" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Con. Number</th>
                    <th scope="col">Job Applied For</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Applied Date</th>
                    <th scope="col" style="display: none;">Address</th> 
                    <th scope="col" style="display: none;">Description</th>  
                    <th scope="col" style="display: none;">Alumni ID</th>  
                    <th scope="col" style="display: none;">Job ID</th>  
                    <th scope="col" style="display: none;">Email Employer</th> 
                    <th scope="col" style="display: none;">Imagetxt</th> 
                    <th scope="col" style="display: none;">Interview DateTime</th> 
                    <th scope="col" style="display: none;">Interview Link</th> 
                    <th scope="col" style="display: none;">Interview Note</th>       
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_application WHERE Status = 'Interview' AND Email_Employer = '{$email}'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Email_Alumni]</td>
                        <td><img src='css/img/$row[Image]' width='50' height='50'></td>
                        <td>$row[Contact_Number]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Status] <i class='fa fa-comments' style='font-size:20px; color: #4E9CEA;'></i></td>
                        <td style='display: none;'>$row[Applied_Date]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td style='display: none;'>$row[Alumni_ID]</td>
                        <td style='display: none;'>$row[Job_ID]</td>
                        <td style='display: none;'>$row[Email_Employer]</td>
                        <td style='display: none;'>$row[Image]</td>
                        <td style='display: none;'>$row[Interview_DateTime]</td>
                        <td style='display: none;'>$row[Interview_Link]</td>
                        <td style='display: none;'>$row[Interview_Note]</td>
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

 <div class="tab-pane" id="Declined">
            <div class="row border g-0 rounded shadow-sm">
                <div class="col p-4">

        <div class="container_my-5" style="width: 100%; box-shadow: 0 0 2px 0 #222; margin-top: 10px; margin-bottom: 10px;">

             <div class="table-responsive">
        <table class="table table-bordered" id="myTable3" style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th>
                    <th scope="col">Con. Number</th>
                    <th scope="col">Job Applied For</th> 
                    <th scope="col">Status</th>
                    <th scope="col" style="display: none;">Applied Date</th>
                    <th scope="col" style="display: none;">Address</th> 
                    <th scope="col" style="display: none;">Description</th> 
                    <th scope="col" style="display: none;">Note</th> 
                    <th scope="col" style="display: none;">Date Declined</th>       
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_declined WHERE Email_Employer = '{$email}'";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Email_Alumni]</td>
                        <td><img src='css/img/$row[Image]' width='50' height='50'></td>
                        <td>$row[Contact_Number]</td>
                        <td>$row[Job_Title]</td>
                        <td>$row[Status] <i class='fa fa-times-circle' style='font-size:20px; color: #9B2626;'></i></td>
                        <td style='display: none;'>$row[Applied_Date]</td>
                        <td style='display: none;'>$row[Address]</td>
                        <td style='display: none;'>$row[Description]</td>
                        <td style='display: none;'>$row[Note]</td>
                        <td style='display: none;'>$row[Declined_Date]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn3' style='padding: 8px 15px;'>
                                <i class='fa fa-eye' aria-hidden='true'></i><span>VIEW</span>
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

<!-- RESUME DATA BASED ON THE SELECTED APPLICANT -->

<div class="divresume" style="display: none;">

<?php

if (isset($_POST['resumebtn'])) {
    $get_email = $_POST['get_email'];

    // Start output buffering to capture HTML
    ob_start();

    // Fetch data from tbl_alumni
    $query = "SELECT * FROM tbl_alumni WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    $check_data = mysqli_num_rows($query_run) > 0;

    if ($check_data) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            // Only output the content that needs to be displayed
            echo "<img src='css/img/{$row['Image']}' width='150' height='150' id='user_img'>";
            echo "<input type='text' id='atn' class='form-control' value='{$row['First_Name']} {$row['Last_Name']}' readonly>";
            echo "<input type='text' id='contact_num' class='form-control' value='{$row['Contact_Number']}' readonly>";
            echo "<input type='text' id='alumni_email' class='form-control' value='{$row['Email']}' readonly>";
            echo "<input type='text' id='city' class='form-control' value='{$row['City']}, {$row['State']}' readonly>";
        }
    }

    // Fetch data from tbl_alumni_citizen
    $query = "SELECT * FROM tbl_alumni_citizen WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        echo "<div class='resume_content'><span style='font-weight: 100;'>{$row['Citizenship']}</span></div>";
    }

    // Fetch data from tbl_alumni_work
    $query = "SELECT * FROM tbl_alumni_work WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='work_sec'>";
            echo "<h6 id='Job_Title'>{$row['Job_Title']}<span class='update_resume'></span></h6>";
            echo "<p id='Company_Name'>{$row['Company_Name']}</p>";
            echo "<p id='job_date'>{$row['From_Month']} {$row['From_Year']} - {$row['To_Month']} {$row['To_Year']}</p>";
            echo "<div id='job_description'>{$row['Description']}</div>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_education
    $query = "SELECT * FROM tbl_alumni_education WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='educ_sec'>";
            echo "<h6 id='level'>{$row['Level']} {$row['Field']}</h6>";
            echo "<p id='school'>{$row['School_Name']} - {$row['City']}</p>";
            echo "<p id='school_date'>{$row['From_Month']} {$row['From_Year']} - {$row['To_Month']} {$row['To_Year']}</p>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_skill
    $query = "SELECT * FROM tbl_alumni_skill WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='skill_sec'>";
            echo "<h6 id='skill'>{$row['Skill_Name']} <span style='font-weight: 100;'>- {$row['Esperience']}</span></h6>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_language
    $query = "SELECT * FROM tbl_alumni_language WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='lang_sec'>";
            echo "<h6 id='lang'>{$row['Language']} <span style='font-weight: 100;'>- {$row['Proficiency']}</span></h6>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_link
    $query = "SELECT * FROM tbl_alumni_link WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='link_sec'>";
            echo "<h6 id='link_name'><a href='{$row['Link']}' target='_blank' rel='noopener noreferrer'>{$row['Link']}</a></h6>";
            echo "</div>";
        }
    }

    // Fetch data from tbl_alumni_certification
    $query = "SELECT * FROM tbl_alumni_certification WHERE Email ='$get_email'";
    $query_run = mysqli_query($con, $query);
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<div class='resume_content' id='certi_sec'>";
            echo "<h6 id='certi'>{$row['Certi_Name']}<span class='update_resume'></span></h6>";
            echo "<p id='certi_date'>{$row['From_Month']} {$row['From_Year']} - {$row['To_Month']} {$row['To_Year']}</p>";
            echo "<div id='certi_description'>{$row['Description']}</div>";
            echo "</div>";
        }
    }

    // Closing tags for any additional sections
    // Close the database connection if needed
    mysqli_close($con);

    // Get the content and clean the output buffer
    $responseHtml = ob_get_clean();
    echo $responseHtml; // Only output the relevant HTML content
    exit; // Stop further execution
}
?>

</div>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>

<!-- Email Notification (JS FUNCTION) -->
<script>
    function submitNotifForm() {
    const form = document.getElementById('notif-form');
    const formData = new FormData(form);

    fetch('Employer-Notif.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(result => {
            alert('Application Update was sent to the Applicant.');
            console.log('Result:', result);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Application Update was sent to the Applicant.');
        });
}
</script>

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

<!-- GENERATE PDF (JS FUNCTION) -->
<script>
$(document).ready(function () {
    $("#resume_form").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        let postData = $(this).serializeArray();
        postData.push({ name: "resumebtn", value: "true" }); // Include the `resumebtn` field

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: postData,
            success: function (data) {
                $(".divresume").html(data); // Load response into .divresume
                generatePDF();

                // Reload the page after successful form submission and PDF generation
                setTimeout(function () {
                    location.reload();
                }, 1000); // Adjust delay if needed
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown);
            }
        });
    });
});

function generatePDF() {
    const fullName = document.getElementById("atn")?.value || "Full Name";
    const contactNumber = document.getElementById("contact_num")?.value || "Contact Number";
    const email = document.getElementById("alumni_email")?.value || "Email";
    const cityState = document.getElementById("city")?.value || "City, State";
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const pageWidth = doc.internal.pageSize.width;
    const marginLeft = 15;
    let yPosition = 20;
    const lineHeight = 7;

    // Helper function for page breaks
    function checkPageBreak() {
        if (yPosition > doc.internal.pageSize.height - 30) { // Account for bottom padding
            doc.addPage();
            yPosition = 20;
        }
    }

    // Add Header
    doc.setFontSize(18).setFont("helvetica", "bold");
    doc.text(fullName, marginLeft, yPosition);
    yPosition += 10;

    doc.setFontSize(10).setFont("helvetica", "normal");
    doc.text(cityState, marginLeft, yPosition);
    yPosition += 6;

    doc.setTextColor(0, 0, 255);
    doc.text(email, marginLeft, yPosition);
    yPosition += 6;

    doc.setTextColor(0, 0, 0);
    doc.text(contactNumber, marginLeft, yPosition);
    yPosition += 10;

    // Add Image
    const imgElement = document.getElementById("user_img");
    if (imgElement && imgElement.complete) {
        const imageDataURL = imgElement.src;
        const imgWidth = 37; // Adjust image size
        const imgHeight = 40;
        const imgX = pageWidth - 55; // Position to the right
        doc.addImage(imageDataURL, "JPEG", imgX, 15, imgWidth, imgHeight);
        yPosition += 10; // Adjust spacing
    }

    // Horizontal Line Styling
    function addSectionHeader(title) {
        checkPageBreak();
        doc.setFontSize(14).setFont("helvetica", "bold").setTextColor(128, 128, 128); // Gray
        doc.text(title, marginLeft, yPosition);
        yPosition += lineHeight;

        doc.setDrawColor(128, 128, 128); // Gray
        doc.setLineWidth(0.3);
        doc.line(marginLeft, yPosition, pageWidth - marginLeft, yPosition); // Horizontal line
        yPosition += 10;
    }

    // Add Personal Details Section
    addSectionHeader("Personal Details");

    doc.setFontSize(12).setFont("helvetica", "normal").setTextColor(0, 0, 0); // Black text
    const citizenText = document.querySelector(".resume_content").innerText || "Citizen Text";
    doc.text(`Citizenship: ${citizenText}`, marginLeft + 5, yPosition);
    yPosition += lineHeight + 10;

    // Add Work Experience Section
    addSectionHeader("Work Experience");

    document.querySelectorAll(".resume_content[id^='work_sec']").forEach(workSection => {
        checkPageBreak();
        doc.setFontSize(13).setFont("helvetica", "bold").setTextColor(0, 0, 0); // Black
        doc.text(workSection.querySelector("#Job_Title").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 2;

        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(128, 128, 128); // Gray color
        doc.text(workSection.querySelector("#Company_Name").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;

        doc.text(workSection.querySelector("#job_date").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 3;

        doc.setTextColor(0, 0, 0); // Black
        const jobDescription = workSection.querySelector("#job_description").innerText;
        const splitDesc = doc.splitTextToSize(jobDescription, pageWidth - 2 * marginLeft);
        splitDesc.forEach(line => {
            checkPageBreak();
            doc.text(line, marginLeft + 5, yPosition);
            yPosition += lineHeight;
        });
        yPosition += 10;
    });

    // Add Education Section
    addSectionHeader("Education Attainment");

    document.querySelectorAll(".resume_content[id^='educ_sec']").forEach(educationSection => {
        checkPageBreak();
        doc.setFontSize(13).setFont("helvetica", "bold").setTextColor(0, 0, 0); // Black
        doc.text(educationSection.querySelector("#level").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 2;

        doc.setFontSize(10).setFont("helvetica", "normal");
        doc.text(educationSection.querySelector("#school").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;

        doc.setTextColor(128, 128, 128); // Gray color
        doc.text(educationSection.querySelector("#school_date").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 10;
    });

    // Add Skills Section
    addSectionHeader("Skills");

    document.querySelectorAll(".resume_content[id^='skill_sec']").forEach(skillSection => {
        checkPageBreak();
        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(0, 0, 0); // Black
        doc.text(`• ${skillSection.querySelector("#skill").innerText}`, marginLeft + 5, yPosition);
        yPosition += lineHeight;
    });

    // Add Languages Section
    yPosition += 10;
    addSectionHeader("Languages");

    document.querySelectorAll(".resume_content[id^='lang_sec']").forEach(languageSection => {
        checkPageBreak();
        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(0, 0, 0); // Black
        doc.text(`• ${languageSection.querySelector("#lang").innerText}`, marginLeft + 5, yPosition);
        yPosition += lineHeight;
    });

    // Add Links Section
    yPosition += 10;
    addSectionHeader("Links");

    document.querySelectorAll(".resume_content[id^='link_sec']").forEach(linkSection => {
        checkPageBreak();
        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(0, 0, 255); // Blue for links
        doc.text(linkSection.querySelector("#link_name").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;
    });

    // Add Certifications Section
    yPosition += 10;
    addSectionHeader("Certifications And Licenses");

    document.querySelectorAll(".resume_content[id^='certi_sec']").forEach(certiSection => {
        checkPageBreak();
        doc.setFontSize(13).setFont("helvetica", "bold").setTextColor(0, 0, 0); // Black
        doc.text(certiSection.querySelector("#certi").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight;

        doc.setFontSize(10).setFont("helvetica", "normal").setTextColor(128, 128, 128); // Gray color
        doc.text(certiSection.querySelector("#certi_date").innerText, marginLeft + 5, yPosition);
        yPosition += lineHeight + 3;

        doc.setTextColor(0, 0, 0); // Black
        const certiDescription = certiSection.querySelector("#certi_description").innerText;
        const splitDesc = doc.splitTextToSize(certiDescription, pageWidth - 2 * marginLeft);
        splitDesc.forEach(line => {
            checkPageBreak();
            doc.text(line, marginLeft + 5, yPosition);
            yPosition += lineHeight;
        });
        yPosition += 10;
    });

    // Open the generated PDF in a new tab
    window.open(doc.output("bloburl"));
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

            const originalDateTime = data[11];

            // Populate modal fields with data from the selected row
            $('#view_id4').val(data[0]);
            $('#applicant_name4').val(data[1]);
            $('#alumni_mail4').val(data[2]);
            $('.get_email').val(data[2]);
            $('.rec_email').val(data[2]);
            $('#alumni_num4').val(data[4]);
            $('#alumni_job4').val(data[5]);
            $('.mail_job').val(data[5]);
            $('#alumni_status4').val(data[6]);
            $('.mail_status').val(data[6]);
            $('#alumni_applied4').val(data[7]);
            $('#alumni_address4').val(data[8]);
            $('#alumni_desc4').val(data[9]);
            $('#note_text').text(data[10]);
            $('#sendMessageLink4').attr('href', `Employer-Inbox.php?alumni_email=${$('#alumni_mail4').val()}`);
            
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
              $('#decline_date').text(formattedDateTime);
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
            $('.ApplyModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            $('#view_id').val(data[0]);
            $('#applicant_name').val(data[1]);
            $('#alumni_mail').val(data[2]);
            $('.get_email').val(data[2]);
            $('.rec_email').val(data[2]);
            $('#alumni_num').val(data[4]);
            $('#alumni_job').val(data[5]);
            $('.mail_job').val(data[5]);
            $('#alumni_status').val(data[6]);
            $('.mail_status').val('Screening');
            $('#alumni_applied').val(data[7]);
            $('#alumni_address').val(data[8]);
            $('#alumni_desc').val(data[9]);
            $('#sendMessageLink').attr('href', `Employer-Inbox.php?alumni_email=${$('#alumni_mail').val()}`);
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
            $('.ScreeningModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            $('#view_id1').val(data[0]);
            $('#applicant_name1').val(data[1]);
            $('#alumni_mail1').val(data[2]);
            $('.get_email').val(data[2]);
            $('.rec_email').val(data[2]);
            $('#alumni_num1').val(data[4]);
            $('#alumni_job1').val(data[5]);
            $('.mail_job').val(data[5]);
            $('#alumni_status1').val(data[6]);
            $('.mail_status').val('Interview Scheduled');
            $('#alumni_applied1').val(data[7]);
            $('#alumni_address1').val(data[8]);
            $('#alumni_desc1').val(data[9]);
            $('#sendMessageLink1').attr('href', `Employer-Inbox.php?alumni_email=${$('#alumni_mail1').val()}`);
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
            $('.InterviewModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            const originalDateTime = data[14];

            $('#view_id2').val(data[0]);
            $('#applicant_name2').val(data[1]);
            $('#alumni_mail2').val(data[2]);
            $('.get_email').val(data[2]);
            $('.rec_email').val(data[2]);
            $('#alumni_num2').val(data[4]);
            $('#alumni_job2').val(data[5]);
            $('.mail_job').val(data[5]);
            $('#alumni_status2').val(data[6]);
            $('.mail_status').val('Hired');
            $('#alumni_applied2').val(data[7]);
            $('#alumni_address2').val(data[8]);
            $('#alumni_desc2').val(data[9]);
            $('#alumni_id2').val(data[10]);
            $('#alumni_jobid2').val(data[11]);
            $('#alumni_empmail2').val(data[12]);
            $('#alumni_img2').val(data[13]);
            $('#sched_link').text(data[15]);
            $('#sched_msg').text(data[16]);
            $('#sendMessageLink2').attr('href', `Employer-Inbox.php?alumni_email=${$('#alumni_mail2').val()}`);

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
              $('#sched_time').text(formattedDateTime);
        });
    });

  // Get the current date and time
    const now = new Date();

    // Format the current date and time to 'YYYY-MM-DDTHH:MM' for 'datetime-local'
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    // Combine to form 'YYYY-MM-DDTHH:MM'
    const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

    // Set the 'min' attribute of the datetime-local input to the current date and time
    document.getElementById('setdatetime').setAttribute('min', currentDateTime);
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


            $('.mail_status').val('Declined');

            });
        });
</script>

<script>
        $(document).ready(function () {

            $('.updatedata1').on('click', function () {

                $('.SetIntModal').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                console.log(data);

            });
        });
</script>

<script>
        $(document).ready(function () {

            $('.updatesched').on('click', function () {

                $('.SetIntModal').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                console.log(data);

            });
        });
</script>


<!-- PASS THE INPUT VALUE (JS FUNCTION) -->

<script>
    // Select input elements
    const viewIdInput = document.getElementById("view_id2");
    const deleteIdInput = document.getElementById("delete_id");
    const applicant_name3 = document.getElementById("applicant_name3");
    const alumni_mail3 = document.getElementById("alumni_mail3");
    const alumni_job3 = document.getElementById("alumni_job3");
    const alumni_num3 = document.getElementById("alumni_num3");
    const alumni_status3 = document.getElementById("alumni_status3");
    const alumni_applied3 = document.getElementById("alumni_applied3");
    const alumni_address3 = document.getElementById("alumni_address3");
    const alumni_desc3 = document.getElementById("alumni_desc3");
    const alumni_id3 = document.getElementById("alumni_id3");
    const alumni_jobid3 = document.getElementById("alumni_jobid3");
    const alumni_empmail3 = document.getElementById("alumni_empmail3");
    const alumni_img3 = document.getElementById("alumni_img3");

    const applicant_name2 = document.getElementById("applicant_name2");
    const alumni_mail2 = document.getElementById("alumni_mail2");
    const alumni_job2 = document.getElementById("alumni_job2");
    const alumni_num2 = document.getElementById("alumni_num2");
    const alumni_status2 = document.getElementById("alumni_status2");
    const alumni_applied2 = document.getElementById("alumni_applied2");
    const alumni_address2 = document.getElementById("alumni_address2");
    const alumni_desc2 = document.getElementById("alumni_desc2");
    const alumni_id2 = document.getElementById("alumni_id2");
    const alumni_jobid2 = document.getElementById("alumni_jobid2");
    const alumni_empmail2 = document.getElementById("alumni_empmail2");
    const alumni_img2 = document.getElementById("alumni_img2");
    const deleteBtn = document.getElementById("deletebtn");

    // Copy function
    deleteBtn.onclick = function () {
        applicant_name3.value = applicant_name2.value;
        alumni_mail3.value = alumni_mail2.value;
        alumni_job3.value = alumni_job2.value;
        alumni_status3.value = alumni_status2.value;
        alumni_applied3.value = alumni_applied2.value;
        alumni_address3.value = alumni_address2.value;
        alumni_desc3.value = alumni_desc2.value;
        alumni_num3.value = alumni_num2.value;
        alumni_id3.value = alumni_id2.value;
        alumni_jobid3.value = alumni_jobid2.value;
        alumni_empmail3.value = alumni_empmail2.value;
        alumni_img3.value = alumni_img2.value;
        deleteIdInput.value = viewIdInput.value;
    };
</script>

<script>
    // Select input elements
    const ScreeningIdInput = document.getElementById("view_id1");
    const ApplicantEmail = document.getElementById("alumni_mail1");


    const SetIdInput = document.getElementById("set_id");
    const SetEmail = document.getElementById("set_mail");
    const SetDate1 = document.getElementById("setdatetime");
    const SetLink1 = document.getElementById("setlink");
    const SetMsg1 = document.getElementById("setmsg");

    const setIntBtn = document.getElementById("updatedata1");

    // Copy function
    setIntBtn.onclick = function () {
        SetIdInput.value = ScreeningIdInput.value;
        SetEmail.value = ApplicantEmail.value;
        SetDate1.value = "";
        SetLink1.value = "";
        SetMsg1.value = "";
    };
</script>

<script>
    // Select input elements
    const ScreeningIdInput1 = document.getElementById("view_id2");
    const ApplicantEmail1 = document.getElementById("alumni_mail2");
    const Get_Time = document.querySelector("#sched_time");
    const Get_Link = document.querySelector("#sched_link");
    const Get_Msg = document.querySelector("#sched_msg");  

    const SetIdInput1 = document.getElementById("set_id");
    const SetEmail1 = document.getElementById("set_mail");
    const SetDate = document.getElementById("setdatetime");
    const SetLink = document.getElementById("setlink");
    const SetMsg = document.getElementById("setmsg");

    const GetIntBtn = document.getElementById("updatesched");

    // Helper function to parse date in the format "Month DD, YYYY at HH:MM AM/PM"
    function convertToOriginalFormat(dateTimeString) {
        // Extract the date and time components
        const datePattern = /(\w+ \d{1,2}, \d{4}) at (\d{1,2}:\d{2} \w{2})/;
        const match = dateTimeString.match(datePattern);

        if (!match) {
            return "";
        }

        const datePart = match[1];
        const timePart = match[2];

        // Parse date and time
        const date = new Date(`${datePart} ${timePart}`);

        if (isNaN(date)) {
            return "";
        }

        // Convert to "YYYY-MM-DD HH:MM:SS"
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    // Copy function with date format conversion
    GetIntBtn.onclick = function () {
        SetIdInput1.value = ScreeningIdInput1.value;
        SetEmail1.value = ApplicantEmail1.value;
        SetDate.value = convertToOriginalFormat(Get_Time.textContent);
        SetLink.value = Get_Link.textContent;
        SetMsg.value = Get_Msg.textContent;
    };
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