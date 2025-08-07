<?php
require 'connection.php';

// Get the selected year from the query parameter
$year = isset($_GET['year']) ? $_GET['year'] : 'all';

// Build the query based on the year filter
if ($year === 'all') {
    $query = "SELECT Course, COUNT(*) AS alumni_count FROM tbl_alumni GROUP BY Course";
} else {
    $query = "SELECT Course, COUNT(*) AS alumni_count FROM tbl_alumni WHERE Batch_Year = $year GROUP BY Course";
}

$result = mysqli_query($con, $query);

$courses = [];
$counts = [];
$legends = [];

// Fetch all rows from the query result
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['Course'];
    $counts[] = $row['alumni_count'];
    $hyphenPos = strpos($row['Course'], '-');
    $legends[] = ($hyphenPos !== false) ? trim(substr($row['Course'], $hyphenPos + 1)) : $row['Course'];
}

// Return an empty dataset if no rows are found
if (empty($courses)) {
    echo json_encode(["courses" => [], "counts" => [], "legends" => []]);
} else {
    // Encode all data into JSON format
    $chartData = [
        "courses" => $courses,
        "counts" => $counts,
        "legends" => $legends
    ];
    echo json_encode($chartData);
}

mysqli_close($con);
?>
