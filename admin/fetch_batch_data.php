<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batch_year'])) {
    $batchYear = intval($_POST['batch_year']); // Get the selected batch year
    $query = "SELECT Batch_Year AS year, COUNT(*) AS count 
              FROM tbl_alumni 
              WHERE Batch_Year <= $batchYear 
              GROUP BY Batch_Year 
              ORDER BY Batch_Year ASC"; // Fetch from least to selected year

    $result = mysqli_query($con, $query);

    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'year' => $row['year'],
                'count' => (int) $row['count'] // Ensure count is an integer
            ];
        }
    }

    echo json_encode($data);
    exit;
}

mysqli_close($con);
?>
