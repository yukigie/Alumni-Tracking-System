<?php

require 'connection.php';
require_once "controllerUserData.php";

if (isset($_GET['job_id'])) {
    $job_id = mysqli_real_escape_string($con, $_GET['job_id']);

    $query = "SELECT * FROM tbl_employer_application WHERE Email_Alumni ='$email' AND Job_ID = '$job_id'";
    $result = mysqli_query($con, $query);

    $response = [];
    if ($row = mysqli_fetch_assoc($result)) {
        $response = [
            'Job_ID_Stats' => $row['Job_ID'],
            'Job_Email_Stats' => $row['Email_Alumni'],
            'Job_Stats' => $row['Status'],
        ];
    }
    echo json_encode($response);
}
?>
