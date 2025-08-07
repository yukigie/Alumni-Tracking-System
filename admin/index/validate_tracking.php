<?php
// Database connection
require 'connection.php';

if (isset($_POST['tracking_number'])) {
    $tracking_number = mysqli_real_escape_string($con, $_POST['tracking_number']);

    // Check if Tracking Number exists in tbl_atn
    $tracking_check = "SELECT * FROM tbl_atn WHERE Tracking_Number = '$tracking_number'";
    $tracking_res = mysqli_query($con, $tracking_check);

    if (mysqli_num_rows($tracking_res) == 0) {
        echo "<i class='fa fa-exclamation-circle' aria-hidden='true'></i>";
    } else {
        // Check if Tracking Number is already registered in tbl_alumni
        $alumni_check = "SELECT * FROM tbl_alumni WHERE Alumni_ID = '$tracking_number'";
        $alumni_res = mysqli_query($con, $alumni_check);

        if (mysqli_num_rows($alumni_res) > 0) {
            echo "<i class='fa fa-exclamation-circle' aria-hidden='true'></i>";
        } else {
            echo "<span style='color: green;'><i class='fa fa-check-circle' aria-hidden='true'></i></span>";
        }
    }
}
?>
