<?php
require 'connection.php';

// Default statuses for pie chart
$defaultStatuses = ['Employed', 'Unemployed'];

// Get the selected year and course from query parameters
$year = isset($_GET['year']) ? $_GET['year'] : "";
$course = isset($_GET['course']) ? $_GET['course'] : "";

// Base query
$query = "SELECT Status, COUNT(*) AS status_count FROM tbl_alumni";

$conditions = [];
if (!empty($year)) {
    $conditions[] = "Batch_Year = $year";
}
if (!empty($course)) {
    $conditions[] = "Course = '$course'";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " GROUP BY Status";

$result = mysqli_query($con, $query);

$counts = array_fill_keys($defaultStatuses, 0); // Initialize counts with default statuses

while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['Status'];
    $count = $row['status_count'];
    if (array_key_exists($status, $counts)) {
        $counts[$status] = $count; // Update count for existing status
    }
}

// Convert data into chart-compatible format
$chartData = [
    "statuses" => array_keys($counts),
    "counts" => array_values($counts),
    "legends" => array_keys($counts)
];

echo json_encode($chartData);
mysqli_close($con);
?>
