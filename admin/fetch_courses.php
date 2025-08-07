<?php
require 'connection.php';

$query = "SELECT DISTINCT Course FROM tbl_alumni ORDER BY Course";
$result = mysqli_query($con, $query);

$courses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['Course'];
}

echo json_encode($courses);
mysqli_close($con);
?>
