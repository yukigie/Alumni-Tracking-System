<?php
require 'connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the year parameter
$year = isset($_GET['year']) ? $_GET['year'] : ""; // Empty means all years

// Base query to fetch alumni and their job types
$query = "SELECT 
            a.Alumni_ID, a.Course, a.Email, j.Job_Type
          FROM 
            tbl_alumni a
          INNER JOIN 
            tbl_alumni_jobstatus j 
          ON 
            a.Email = j.Email";  

// Apply filter for year if provided
if (!empty($year) && $year !== "All Batch Year") {
    $query .= " WHERE a.Batch_Year = $year";
}

$result = mysqli_query($con, $query);

$matched = 0;
$unmatched = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $course = strtolower($row['Course']);
    $jobType = strtolower($row['Job_Type']);

    // Extract specific matching values (course before hyphen, job type after hyphen)
    $courseHyphenPos = strpos($course, '-');
    $courseKey = ($courseHyphenPos !== false) ? trim(substr($course, 0, $courseHyphenPos)) : $course;

    $jobTypeHyphenPos = strpos($jobType, '-');
    $jobTypeKey = ($jobTypeHyphenPos !== false) ? trim(substr($jobType, $jobTypeHyphenPos + 1)) : $jobType;

    // Normalize spaces and remove extra symbols
    $courseKey = preg_replace('/[^a-z0-9 ]/i', '', $courseKey);
    $jobTypeKey = preg_replace('/[^a-z0-9 ]/i', '', $jobTypeKey);

    // Check for matches
    if (strpos($courseKey, $jobTypeKey) !== false || strpos($jobTypeKey, $courseKey) !== false) {
        $matched++;
    } else {
        $unmatched++;
    }
}

// Prepare the JSON response
$response = [
    "matched" => $matched,
    "unmatched" => $unmatched,
    "total" => $matched + $unmatched
];

// Output the response in JSON format
echo json_encode($response);

// Close the database connection
mysqli_close($con);
?>
