<?php
require 'connection.php';

$filterType = $_POST['filterType'] ?? '';
$filterValue = $_POST['filterValue'] ?? '';

// Map filter types to corresponding table columns
$filterColumns = [
    'course' => 'tbl_alumni.Course',
    'year' => 'tbl_alumni.Batch_Year',
    'status' => 'tbl_alumni.Status',
    'company' => 'tbl_alumni_jobstatus.Employment_Type',
    'place' => 'tbl_alumni_jobstatus.Place',
    'income' => 'tbl_alumni_jobstatus.Income'
];

// Ensure that the filter type is valid and exists in the filter column mapping
if (!array_key_exists($filterType, $filterColumns)) {
    die("Invalid filter type.");
}

$column = $filterColumns[$filterType];
$filterValue = mysqli_real_escape_string($con, $filterValue);

$sql = "SELECT 
    tbl_alumni.Alumni_ID,
    tbl_alumni.First_Name,
    tbl_alumni.Last_Name,
    tbl_alumni.Course,
    tbl_alumni.Batch_Year,
    tbl_alumni.Status,
    tbl_alumni_jobstatus.Job_Name,
    tbl_alumni_jobstatus.Date_Hired,
    tbl_alumni_jobstatus.Company_Name,
    tbl_alumni_jobstatus.Place,
    tbl_alumni_jobstatus.Employment_Type,
    tbl_alumni_jobstatus.Income
FROM 
    tbl_alumni
JOIN 
    tbl_alumni_jobstatus ON tbl_alumni.Email = tbl_alumni_jobstatus.Email AND 
    tbl_alumni.Alumni_ID = tbl_alumni_jobstatus.ID
WHERE $column = '$filterValue'";

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

        <h2>Alumni Profile</h2>
        
    </div>
</div>";


$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='alumni-tbl' style='border-collapse: collapse; 
width: 100%; margin-top: 20px;'>";
    echo "<tr>
<th style='border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;'>No.</th>
 <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Alumni ID</th>
 <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>First Name</th>
 <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Last Name</th>
 <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Course</th>
  <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Graduated Year</th>
  <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Status</th>
   <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Position</th>
   <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Hired Date</th>
   <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Company Name</th>
   <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Place</th>
   <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Emp.Type</th>
   <th style=' border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;'>Income</th>
          </tr>";
    $no = 1;
    while($row = $result->fetch_assoc()) {
        echo "<tr style='text-align: center;'>
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
                <td>{$row['Income']}</td>
              </tr>";
              $no++;
    }
    echo "</table> <p style='text-align: left; font-weight: 600; margin-top: 20px;'>Prepared By. </p>";
} else {
    echo "<p>No results found for the selected filter.</p>";
}

$con->close();
?>
