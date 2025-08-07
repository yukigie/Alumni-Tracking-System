<?php
require 'connection.php';

// Get the selected year from query parameters
$year = isset($_GET['year']) ? $_GET['year'] : 'all'; // Default to 'all' if not provided

// Base query
$query = "SELECT Status, COUNT(*) AS status_count FROM tbl_alumni";

// Add condition for year if it's not 'all'
if ($year !== 'all') {
    $query .= " WHERE Batch_Year = $year";
}

// Group by employment status
$query .= " GROUP BY Status";

$result = mysqli_query($con, $query);

$statuses = [];
$counts = [];

// Fetch data from the result
while ($row = mysqli_fetch_assoc($result)) {
    // Only include "Employed" and "Unemployed"
    if ($row['Status'] === 'Employed' || $row['Status'] === 'Unemployed') {
        $statuses[] = $row['Status'];
        $counts[] = $row['status_count'];
    }
}

// Return an empty dataset if no rows are found or if only other statuses are present
if (empty($statuses)) {
    echo json_encode([
        "statuses" => [],
        "counts" => [],
        "legends" => []
    ]);
} else {
    // Prepare chart data
    $chartData = [
        "statuses" => $statuses,
        "counts" => $counts,
        "legends" => $statuses // Legends are equivalent to statuses
    ];
    echo json_encode($chartData);
}

// Close the database connection
mysqli_close($con);
?>
