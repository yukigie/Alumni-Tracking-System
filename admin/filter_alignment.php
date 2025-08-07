<?php
require 'connection.php';

// Retrieve filter type and value
$filterType = $_POST['filterType'] ?? '';
$filterValue = $_POST['filterValue'] ?? '';

// Map filter types to corresponding table columns
$filterColumns = [
    'gender' => 'a.Gender',         
    'course' => 'a.Course',         
    'year' => 'a.Batch_Year',       
    'status' => 'a.Status',         
    'company' => 'j.Employment_Type',   
    'place' => 'j.Place',            
    'alignment' => 'alignment' 
];

// Validate the filter type
if (!array_key_exists($filterType, $filterColumns)) {
    die("Invalid filter type.");
}

$column = $filterColumns[$filterType];
$filterValueEscaped = mysqli_real_escape_string($con, $filterValue);

// Base SQL query
$sql = "
    SELECT 
        a.Alumni_ID, a.First_Name, a.Last_Name, a.Course, a.Batch_Year, 
        a.Status, j.Job_Name, j.Date_Hired, j.Company_Name, j.Place, 
        j.Employment_Type, j.Job_Type
    FROM 
        tbl_alumni a
    JOIN 
        tbl_alumni_jobstatus j ON a.Alumni_ID = j.ID
";

// Append the appropriate `WHERE` clause
if ($filterType === 'alignment') {
    // Handle alignment filtering based on the value selected (Aligned or Not Aligned)
    if ($filterValue === 'Aligned') {
        $sql .= " WHERE 1";  // Default condition, all rows will be included, alignment check happens later
    } elseif ($filterValue === 'Not Aligned') {
        $sql .= " WHERE 1";  // Default condition, alignment check happens later
    }
} else {
    $sql .= " WHERE $column = '$filterValueEscaped'";
}

// Execute the query
$result = $con->query($sql);

if (!$result) {
    // Catch and display the exact SQL error
    die("SQL Error: " . $con->error);
}

// Display the header
echo "
<div class='header-print' style='text-align: center;'>
    <img src='admin_img/cvsu-logo.png' width='60' height='60' style='margin-top: 20px; margin-left: -23rem;'>
    <h4 style='margin-top: -50px;'>Cavite State University - Imus Campus</h4>
    <p>Cavite Civic Center Palico IV, Imus, Cavite <br>
       (046) 436 6584 / (046) 436 6584 / (046) 436 6584</p>
    <a href='www.cvsu.edu.ph'>www.cvsu.edu.ph</a>
    <h3>Alumni Tracer System</h3>
    <div class='qoute' style='width: 500px; font-size: 14px; color: #555; margin: 0 auto;'>
        <p><i>Cavite State University - Imus Campus Alumni Tracer System, where we help our alumni stay connected, celebrate their successes, and contribute to our community's growth and development.</i></p>

        <h2>Job Alignment Report</h2>
        
    </div>
</div>";

if ($result->num_rows > 0) {
    echo "<table style='border-collapse: collapse; width: 100%; margin-top: 20px;'>
            <thead>
                <tr style='background-color: green; color: white;'>
                    <th>No.</th>
                    <th>Alumni ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Course</th>
                    <th>Graduated Year</th>
                    <th>Status</th>
                    <th>Position</th>
                    <th>Hired Date</th>
                    <th>Company Name</th>
                    <th>Place</th>
                    <th>Employment Type</th>
                    <th>Job Alignment</th>
                </tr>
            </thead>
            <tbody>";
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        // Extract relevant data for alignment checks
        $course = strtolower($row['Course']);
        $jobType = strtolower($row['Job_Type']);

        // Extract course key and job type key
        $courseHyphenPos = strpos($course, '-');
        $courseKey = ($courseHyphenPos !== false) ? trim(substr($course, 0, $courseHyphenPos)) : $course;

        $jobTypeHyphenPos = strpos($jobType, '-');
        $jobTypeKey = ($jobTypeHyphenPos !== false) ? trim(substr($jobType, $jobTypeHyphenPos + 1)) : $jobType;

        // Normalize keys: remove special characters
        $courseKey = preg_replace('/[^a-z0-9 ]/i', '', $courseKey);
        $jobTypeKey = preg_replace('/[^a-z0-9 ]/i', '', $jobTypeKey);

        // Check for matches between course and job type
        $alignment = (strpos($courseKey, $jobTypeKey) !== false || strpos($jobTypeKey, $courseKey) !== false) ? "Aligned" : "Not Aligned";

        // Apply the alignment filter if needed
        if ($filterType === 'alignment') {
            if (($filterValue === 'Aligned' && $alignment === 'Aligned') || ($filterValue === 'Not Aligned' && $alignment === 'Not Aligned')) {
                echo "
                <tr style='text-align: center;'>
                    <td>$no</td>
                    <td>{$row['Alumni_ID']}</td>
                    <td>{$row['First_Name']}</td>
                    <td>{$row['Last_Name']}</td>
                    <td class='course-column'>{$row['Course']}</td>
                    <td>{$row['Batch_Year']}</td>
                    <td>{$row['Status']}</td>
                    <td>{$row['Job_Name']}</td>
                    <td>{$row['Date_Hired']}</td>
                    <td>{$row['Company_Name']}</td>
                    <td>{$row['Place']}</td>
                    <td>{$row['Employment_Type']}</td>
                    <td>$alignment</td>
                </tr>";
                $no++;
            }
        } else {
            echo "
            <tr style='text-align: center;'>
                <td>$no</td>
                <td>{$row['Alumni_ID']}</td>
                <td>{$row['First_Name']}</td>
                <td>{$row['Last_Name']}</td>
                <td class='course-column'>{$row['Course']}</td>
                <td>{$row['Batch_Year']}</td>
                <td>{$row['Status']}</td>
                <td>{$row['Job_Name']}</td>
                <td>{$row['Date_Hired']}</td>
                <td>{$row['Company_Name']}</td>
                <td>{$row['Place']}</td>
                <td>{$row['Employment_Type']}</td>
                <td>$alignment</td>
            </tr>";
            $no++;
        }
    }
    echo "</tbody></table> <p style='text-align: left; font-weight: 600; margin-top: 20px;'>Prepared By. </p>";
} else {
    echo "<p>No results found for the selected filter.</p>";
}

$con->close();
?>
