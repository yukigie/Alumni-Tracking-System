<?php
// update-content.php
require 'connection.php';

require_once "controllerUserData.php";

$jobId = $_GET['job_id'];  // Pass the job ID in the URL parameters

// Query to check if the user has already applied for the job
$query = "SELECT Status FROM tbl_employer_application WHERE Job_ID = ? AND Email_Alumni = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('is', $jobId, $email);
$stmt->execute();
$result = $stmt->get_result();
$status = $result->fetch_assoc()['Status'] ?? null;

?>
<div class="col-lg-6" id="coltbtn" style="width: 100%; padding-top: 0px;">
    <?php if ($status === 'Applied'): ?>
        <button class="applybtn" disabled style="background-color: gray;">Application Submitted</button>
        <button class="cancelbtn" type="submit" name="cancelbtn" style="display: block; margin-top: 10px; background-color: gray;">Cancel Application</button>
    <?php else: ?>
        <button class="applybtn" type="submit" name="applybtn">APPLY NOW</button>
        <button class="cancelbtn" type="submit" name="cancelbtn" style="display: none; margin-top: 10px; background-color: gray;">Cancel Application</button>
    <?php endif; ?>
    <p class="appclose" style="display: none; margin-top: 5px;"></p>
</div>
