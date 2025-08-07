<?php
require 'connection.php';

// Get the selected year from the query parameter
$year = isset($_GET['year']) ? intval($_GET['year']) : null;

// Base query for placed alumni with 'Hired' status
$query = "SELECT YEAR(Hired_Date) AS hired_year, COUNT(*) AS alumni_count
          FROM tbl_employer_hired
          WHERE Status = 'Hired'";

// Add filtering by year if provided
if ($year) {
    $query .= " AND YEAR(Hired_Date) <= $year";
}

$query .= " GROUP BY hired_year ORDER BY hired_year ASC";

$result = mysqli_query($con, $query);

$years = [];
$counts = [];

// Fetch all rows from the query result
while ($row = mysqli_fetch_assoc($result)) {
    $years[] = $row['hired_year'];
    $counts[] = $row['alumni_count'];
}

// Return JSON response
if (empty($years)) {
    echo json_encode(["years" => [], "counts" => []]);
} else {
    echo json_encode(["years" => $years, "counts" => $counts]);
}

mysqli_close($con);
?>
