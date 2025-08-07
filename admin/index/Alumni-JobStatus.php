<?php 
require_once "controllerUserData.php"; 

if (!isset($_SESSION['email'], $_SESSION['password'])) {
    header('Location: login-user.php');
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM usertable WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$fetch_info = $result->fetch_assoc();

if ($fetch_info) {
    if ($fetch_info['status'] !== "verified") {
        header('Location: user-otp.php');
        exit;
    }

    if ($fetch_info['code'] != 0) {
        header('Location: reset-code.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alumni | Employment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   
    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';

function alert($title, $text, $icon, $button = "Done") {
    echo "<script>swal({
        title: '{$title}',
        text: '{$text}',
        icon: '{$icon}',
        button: '{$button}',
        closeOnClickOutside: true,
    });</script>";
}

// Insert or Update Data
if (isset($_POST['submit'])) {
    $atn = $_POST['atn'];
    $employment_status = $_POST['employment_status'];
    $job_name = $_POST['job_name'];
    $company_name = $_POST['company_name'];
    $hired = $_POST['hired'];
    $job_type = $_POST['job_type'];
    $employ_type = $_POST['employ_type'];
    $place = $_POST['place'];
    $sector = $_POST['sector'];
    $income = $_POST['income'];
    $employ_start = $_POST['employ_start'];
    $description = $_POST['description'];

    // Convert the date string into a DateTime object in the format 'YYYY-MM-DD'
    $date = DateTime::createFromFormat('Y-m-d', $hired);

    if ($date !== false) {
        $month_name = $date->format('F'); // Dynamic month name, e.g., 'February'
        $year = $date->format('Y');       // Dynamic year, e.g., '2024'
    } else {

        // Check if the Email is already in the table
    $query = "INSERT INTO tbl_alumni_jobstatus (ID, Status, Job_Name, Company_Name, Date_Hired, Job_Type, Employment_Type, Place, Sector, Income, Starting_Emp, Description, Email) 
              VALUES ('{$atn}', '{$employment_status}', '{$job_name}', '{$company_name}', '{$hired}', '{$job_type}', '{$employ_type}', '{$place}', '{$sector}', '{$income}', '{$employ_start}', '{$description}', '{$email}')
              ON DUPLICATE KEY UPDATE 
              Status = VALUES(Status), 
              Job_Name = VALUES(Job_Name), 
              Company_Name = VALUES(Company_Name), 
              Date_Hired = VALUES(Date_Hired), 
              Job_Type = VALUES(Job_Type), 
              Employment_Type = VALUES(Employment_Type), 
              Place = VALUES(Place), 
              Sector = VALUES(Sector), 
              Income = VALUES(Income),
              Starting_Emp = VALUES(Starting_Emp), 
              Description = VALUES(Description), 
              ID = VALUES(ID)";

    $query1 = "UPDATE tbl_alumni 
              SET Status = '{$employment_status}'
              WHERE Alumni_ID = '{$atn}' AND Email = '{$email}'";

    // Execute the query
    if (mysqli_query($con, $query) && mysqli_query($con, $query1)) {
        header('location: Alumni-JobStatus.php');
    } else {
        alert("Data Not Updated!", "Failed to update Your Job status Details.", "error");
    }
        exit;
    }

    // Check if the Email is already in the table
    $query = "INSERT INTO tbl_alumni_jobstatus (ID, Status, Job_Name, Company_Name, Date_Hired, Job_Type, Employment_Type, Place, Sector, Income, Starting_Emp, Description, Email) 
              VALUES ('{$atn}', '{$employment_status}', '{$job_name}', '{$company_name}', '{$hired}', '{$job_type}', '{$employ_type}', '{$place}', '{$sector}', '{$income}', '{$employ_start}', '{$description}', '{$email}')
              ON DUPLICATE KEY UPDATE 
              Status = VALUES(Status), 
              Job_Name = VALUES(Job_Name), 
              Company_Name = VALUES(Company_Name), 
              Date_Hired = VALUES(Date_Hired), 
              Job_Type = VALUES(Job_Type),  
              Employment_Type = VALUES(Employment_Type), 
              Place = VALUES(Place), 
              Sector = VALUES(Sector), 
              Income = VALUES(Income), 
              Starting_Emp = VALUES(Starting_Emp), 
              Description = VALUES(Description), 
              ID = VALUES(ID)";

    $query1 = "UPDATE tbl_alumni 
              SET Status = '{$employment_status}'
              WHERE Alumni_ID = '{$atn}' AND Email = '{$email}'";


    $query2 = "INSERT INTO tbl_alumni_work (Job_Title, Company_Name, From_Month, From_Year, To_Month, To_Year, Description, Email) 
              VALUES ('{$job_name}', '{$company_name}', '{$month_name}', '{$year}', 'Present', '', '{$description}', '{$email}')";

    // Execute the query
    if (mysqli_query($con, $query) && mysqli_query($con, $query1)) {

      if ($employment_status === 'Employed')
      {
        mysqli_query($con, $query2);
        header('location: Alumni-JobStatus.php');

      } else {
        header('location: Alumni-JobStatus.php');
      }

    } else {
        alert("Data Not Updated!", "Failed to update Your Job status Details.", "error");
    }
}

?>


    <!-- Header -->
    <div class="header">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="css/admin_img/logo-orange.png" width="65" height="60">
            <div class="logo" style="font-size: 23px; font-weight: 800;">C<span style="color: #FBD25B; font-weight: 800; font-size: 23px;">v</span>SU 
                <span style="font-size: 15px; font-weight: 600;">Imus Campus</span>
                <p><span>Alumni Tracker<img src="css/admin_img/focus1.png" width="25" height="25"></span></p>
            </div>

            <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        </a>
    </div>

<div class="side-nav1">
    <div class="side-nav">

        <label id="greettxt" style="color: #FFEFBD; font-weight: 600; font-size: 14px; margin-bottom: 5px;">Hi there, CvSU Alumnus<span style="color: #FBD25B; font-size: 20px;">👋</span></label>
        <a href="Alumni-Inbox.php"><i class='bx bxs-message'></i></a>
    </div>

    <div class="user-img">
        <?php

                $query = "SELECT * FROM tbl_alumni WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

        <img src="css/img/<?php echo $row['Image']; ?>" width="50" height="50">

        <?php

                  }
              }


            ?>
    </div>
</div>

    </div>


    <!-- Sidebar -->
<div class="sidebar">

<!-- List Menu -->
    <ul class="sidebar-list">
<!-- Non Dropdown List Item -->
       <li>
            <div class="title" id="landing1">
                <a href="Home.php" class="link">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="name">Dashboard</span>
                </a>
                <!-- <i class='bx bxs-chevron-down' ></i> -->
            </div>

            <div class="submenu">
                <a href="Home.php" class="link submenu-title">Dashboard</a>
            </div>

        </li>

       <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title">
                <a href="#" class="link">
                    <i class='bx bxs-briefcase' ></i>
                    <span class="name">Jobs</span>
                </a>
                <i class='bx bxs-chevron-down' ></i>
            </div>

            <div class="submenu">
                <a href="#" class="link submenu-title">All Settings</a>
                <a href="Alumni-Jobs.php" class="link">Job Opening</a>
                <a href="Alumni-Applications.php" class="link">Applications</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing4">
                <a href="Events.php" class="link">
                    <i class='bx bx-calendar-event'></i>
                    <span class="name">Events</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Events.php" class="link submenu-title">Events</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing5">
                <a href="Alumni-Account.php" class="link">
                    <i class='bx bxs-user' ></i>
                    <span class="name">Account</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Alumni-Account.php" class="link submenu-title">Account</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown" id="signoutbtn">
            <div class="title" id="landing6">
                <a href="logout-user.php" class="link">
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class="name">Sign Out</span>
                </a>
            </div>

        </li>
    </ul>

</div>

<!-- Home Section -->

<section class="home">

<div class="row">
    <div class="col-md-12">
    <div id="personal_details" style="padding-top: 20px;">
        <a href="Alumni-Profile.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Profile</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Job Status<span><i class='bx bxs-edit' ></i></span></h5>
        <p style="font-size: 13px; color: red;">*Please Fill Out All Fields If Applicable</p>
        <form action="Alumni-JobStatus.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

             <?php

                $query = "SELECT * FROM tbl_alumni WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

            <input type="hidden" name="atn" id="atn" value="<?php echo $row['Alumni_ID']; ?>">

             <?php

                  }
              }

            ?>

      <div class="row justify-content-center">

         <?php

           // Fetch specific row data based on the ID
            $query = "SELECT * FROM tbl_alumni_jobstatus WHERE Email ='$email'";
            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                $row = mysqli_fetch_assoc($query_run);
                $industry = $row['Job_Type'];
                 // Split the string by the hyphen, and take the first part (before the hyphen)
                $industry_parts = explode('-', $industry);
                $industry_first_part = isset($industry_parts[0]) && trim($industry_parts[0]) !== '' ? trim($industry_parts[0]) : "Select Field";
            } else {
                // Set default empty values if no data is available for this ID
              $industry_first_part = 'Select Field';
                $row = [
                    'Status' => '',
                    'Job_Name' => '',
                    'Company_Name' => '',
                    'Date_Hired' => '',
                    'Job_Type' => $industry_first_part,
                    'Employment_Type' => '',
                    'Place' => '',
                    'Sector' => '',
                    'Income' => '',
                    'Description' => '',
                    'Starting_Emp' => '',
                ];
            }
       
        ?>

        <div class="col-lg-12">
            <div class="form-group" style="margin-top: 20px;">
                <label style="margin-bottom: 10px;">Employment Status*</label>
                <select name="employment_status" id="employment_status" class="form-control">
                    <option selected hidden><?php echo $row['Status']; ?></option>
                    <option value="Employed">Employed</option>
                    <option value="Unemployed">Unemployed</option>
                </select>
            </div>
        </div>

        <!-- Div to be toggled based on employment status -->
        <div class="col-lg-6" id="others" style="display: none;">
            <div class="form-group" style="margin-top: 20px;">
                <label>Job Name (N/A if not Applicable)*</label>
                <input type="text" name="job_name" id="job_name" class="form-control" value="<?php echo $row['Job_Name']; ?>">
            </div>
        </div>

        <div class="col-lg-6" id="others2" style="display: none;">
            <div class="form-group" style="margin-top: 20px;">
                <label>Company Name (N/A if not Applicable)*</label>
                <input type="text" name="company_name" id="company_name" class="form-control" value="<?php echo $row['Company_Name']; ?>">
            </div>
        </div>

        <div class="col-lg-12" id="others11" style="display: none;">
              <div class="form-group" style="margin-top: 20px;" >
                <label>Start of Employment After Graduation*</label>
                <select name="employ_start" id="employ_start" class="form-control">
                  <option hidden selected value="<?php echo !empty($row['Starting_Emp']) ? $row['Starting_Emp'] : ''; ?>"><?php echo !empty($row['Starting_Emp']) ? $row['Starting_Emp'] : 'Select Starting'; ?></option>
                  <option value="1 - 6 Months">1 - 6 Months</option>
                  <option value="7 - 12 Months">7 - 12 Months</option>
                  <option value="1 Year">1 Year</option>
                  <option value="2 years">2 years</option>
                  <option value="N/A">N/A</option>
                </select>
              </div>
            </div>

        <div class="col-lg-6" id="others3" style="display: none;">
            <div class="form-group" style="margin-top: 20px;" >
                <label>Date Hired</label>
                <input type="date" name="hired" id="hired" class="form-control" value="<?php echo $row['Date_Hired']; ?>">
            </div>
        </div>

        <div class="col-lg-6" id="others4" style="display: none;">
              <div class="form-group" style="margin-top: 20px;" >
                <label>Job Field (Select Related Field)*</label>
                <select name="job_type" id="job_type" class="form-control">
                  <option hidden selected value="<?php echo !empty($row['Job_Type']) ? $row['Job_Type'] : ''; ?>"><?php echo $industry_first_part; ?></option>
                  <option value="Information Technology - Computer Science">Information Technology</option>
                  <option value="Finance & Banking - Business Administration">Finance & Banking</option>
                  <option value="Healthcare & Pharmaceuticals - Psychology">Healthcare & Pharmaceuticals</option>
                  <option value="Hotel & Restaurant Management - Hospitality Management">Hotel & Restaurant Management</option>
                  <option value="Manufacturing - Business Administration">Manufacturing</option>
                  <option value="Retail & Wholesale - Entrepreneurship">Retail & Wholesale</option>
                  <option value="Telecommunications - Information Technology">Telecommunications</option>
                  <option value="Education & Training - Education">Education & Training</option>
                  <option value="Construction & Real Estate - Business Administration">Construction & Real Estate</option>
                  <option value="Transportation & Logistics - Business Administration">Transportation & Logistics</option>
                  <option value="Hospitality & Tourism - Hospitality Management">Hospitality & Tourism</option>
                  <option value="Food & Beverage - Hospitality Management">Food & Beverage</option>
                  <option value="Media & Entertainment - Journalism">Media & Entertainment</option>
                  <option value="Automotive - Business Administration">Automotive</option>
                  <option value="Energy & Utilities - Business Administration">Energy & Utilities</option>
                  <option value="Legal & Consulting - Business Administration">Legal & Consulting</option>
                  <option value="Marketing & Advertising - Business Administration">Marketing & Advertising</option>
                  <option value="Aerospace & Defense - Business Administration">Aerospace & Defense</option>
                  <option value="Agriculture & Farming - Business Administration">Agriculture & Farming</option>
                  <option value="Non-Profit & Social Services - Psychology">Non-Profit & Social Services</option>
                  <option value="Fashion & Apparel - Business Administration">Fashion & Apparel</option>
                  <option value="Beauty & Cosmetics - Business Administration">Beauty & Cosmetics</option>
                  <option value="Environmental Services - Business Administration">Environmental Services</option>
                  <option value="Insurance - Business Administration">Insurance</option>
                  <option value="Human Resources & Recruitment - Business Administration">Human Resources & Recruitment</option>
                  <option value="Chemical Industry - Business Administration">Chemical Industry</option>
                  <option value="Consumer Goods - Entrepreneurship">Consumer Goods</option>
                  <option value="Mining & Metals - Business Administration">Mining & Metals</option>
                  <option value="Public Administration & Government - Office Administration">Public Administration & Government</option>
                  <option value="Sports & Recreation - Business Administration">Sports & Recreation</option>
                  <option value="Arts & Crafts - Journalism">Arts & Crafts</option>
                  <option value="Others.">Others.</option>
                </select>
              </div>
            </div>


        <div class="col-lg-12" id="others10" style="display: none;">
            <div class="form-group">
                <label style="display: none; margin-top: 20px;" id="otherIndustryLabel">(Type Field Name if Others. was Selected, N/A if Not Applicable)*</label>
                <input type="text" name="fieldname" id="fieldname" class="form-control" style="display: none;">
            </div>
        </div>

            <div class="col-lg-6" id="others5" style="display: none;">
              <div class="form-group" style="margin-top: 20px;" >
                <label>Employment Type*</label>
                <select name="employ_type" id="employ_type" class="form-control">
                  <option hidden selected value="<?php echo !empty($row['Employment_Type']) ? $row['Employment_Type'] : ''; ?>"><?php echo !empty($row['Employment_Type']) ? $row['Employment_Type'] : 'Select Type'; ?></option>
                  <option value="Regular">Regular</option>
                  <option value="Temporary">Temporary</option>
                  <option value="Contractual">Contractual</option>
                  <option value="On-Call">On-Call</option>
                  <option value="Full-Time">Full-Time</option>
                  <option value="Part-Time">Part-Time</option>
                  <option value="Own Business">Own Business</option>
                  <option value="Others.">Others.</option>
                </select>
              </div>
            </div>

            <div class="col-lg-6" id="others6" style="display: none;">
              <div class="form-group" style="margin-top: 20px;" >
                <label>Place of Work*</label>
                <select name="place" id="place" class="form-control">
                  <option hidden selected value="<?php echo !empty($row['Place']) ? $row['Place'] : ''; ?>"><?php echo !empty($row['Place']) ? $row['Place'] : 'Select Place'; ?></option>
                  <option value="Local">Local</option>
                  <option value="International">International</option>
              </select>
              </div>
            </div>

            <div class="col-lg-6" id="others7" style="display: none;">
              <div class="form-group" style="margin-top: 20px;" >
                <label>Employment Sector*</label>
                <select name="sector" id="sector" class="form-control">
                  <option hidden selected value="<?php echo !empty($row['Sector']) ? $row['Sector'] : ''; ?>"> <?php echo !empty($row['Sector']) ? $row['Sector'] : 'Select Sector'; ?></option>
                  <option value="Public Sector">Public Sector</option>
                  <option value="Private Sector">Private Sector</option>
                  <option value="Government Sector">Government Sector</option>
              </select>
              </div>
            </div>

            <div class="col-lg-6" id="others8" style="display: none;">
              <div class="form-group" style="margin-top: 20px;" >
                <label>Monthly Income (Range)*</label>
                <select name="income" id="income" class="form-control">
                  <option hidden selected value="<?php echo !empty($row['Income']) ? $row['Income'] : ''; ?>"> <?php echo !empty($row['Income']) ? $row['Income'] : 'Select Income'; ?></option>
                  <option value="Below 10,000">Below 10,000</option>
                  <option value="10,000 - 20,000">10,000 - 20,000</option>
                  <option value="21,000 - 40,000">21,000 - 40,000</option>
                  <option value="41,000 - 60,000">41,000 - 60,000</option>
                  <option value="More than 60,000">More than 60,000</option>
              </select>
              </div>
            </div>

             <div class="col-lg-12" id="others9" style="display: none;">
            <div class="form-group" style="margin-top: 20px;" >
                <label>Job Description</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?php echo $row['Description']; ?></textarea> 
            </div>
        </div>

         <?php
        mysqli_close($con);
        ?>

    </div>

              <button class="submit-btn1" type="submit" id="submit" name="submit" form="form_body">Save</button>

              <button class="submit-btn1" type="button" id="clearbtn" name="clearbtn" style="padding: 10px; background-color: gray; display: none;" onmouseover="this.style.backgroundColor='#5B6268';" onmouseout="this.style.backgroundColor='gray';">Clear</button>

        </form>

            </div>

        </div>
    </div>


            </div>
    </div>
</div>
</section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- Sidebar Landing Page (JS FUNCTION) -->
<script>
    // Add a click event listener to redirect on click
    document.getElementById('landing1').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Home.php'; // Replace with your desired URL
    });

    document.getElementById('landing4').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Events.php'; // Replace with your desired URL
    });

    document.getElementById('landing5').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Alumni-Account.php'; // Replace with your desired URL
    });

    document.getElementById('landing6').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'logout-user.php'; // Replace with your desired URL
    });
</script>

<script>
    document.getElementById('job_type').addEventListener('change', function() {
        var selectedValue = this.value;
        var otherIndustryInput = document.getElementById('fieldname');
        var otherIndustryLabel = document.getElementById('otherIndustryLabel');
        var div = document.getElementById('others10');

        if (selectedValue === "Others.") {
            div.style.display = 'block';
            otherIndustryInput.style.display = 'block';
            otherIndustryLabel.style.display = 'block';

            otherIndustryInput.required = true;
            // Exchange the id and name between the select and input element
            otherIndustryInput.name = "job_type";
            otherIndustryInput.id = "job_type";
            this.name = "fieldname";
            this.id = "fieldname";
        } else {
            div.style.display = 'none';
            otherIndustryLabel.style.display = 'none';
            otherIndustryInput.style.display = 'none';

            otherIndustryInput.required = false;
            // Revert the id and name back to the original
            otherIndustryInput.name = "fieldname";
            otherIndustryInput.id = "fieldname";
            this.name = "job_type";
            this.id = "job_type";
            this.style.display = 'block';
        }
    });
</script>

<!-- Make other Inputs Visible (JS FUNCTION) -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const employmentStatus = document.getElementById("employment_status");
        const others = document.getElementById("others");
        const others2 = document.getElementById("others2");
        const others3 = document.getElementById("others3");
        const others4 = document.getElementById("others4");
        const others5 = document.getElementById("others5");
        const others6 = document.getElementById("others6");
        const others7 = document.getElementById("others7");
        const others8 = document.getElementById("others8");
        const others9 = document.getElementById("others9");
        const others11 = document.getElementById("others11");
        const jobname = document.getElementById("job_name");
        const companyName = document.getElementById("company_name");
        const jobtype = document.getElementById("job_type");
        const employtype = document.getElementById("employ_type");
        const employstart = document.getElementById("employ_start");
        const workplace = document.getElementById("place");
        const worksector = document.getElementById("sector");
        const monthlyincome = document.getElementById("income");
        const datehired = document.getElementById("hired");
        const jobdescription = document.getElementById("description");
        const clear = document.getElementById("clearbtn");

        employmentStatus.addEventListener("change", function() {
            if (employmentStatus.value === "Employed") {
                // Show the "others" div and make inputs required
                others.style.display = "block";
                others2.style.display = "block";
                others3.style.display = "block";
                others4.style.display = "block";
                others5.style.display = "block";
                others6.style.display = "block";
                others7.style.display = "block";
                others8.style.display = "block";
                others9.style.display = "block";
                others10.style.display = "block";
                others11.style.display = "block";
                clear.style.display = "inline";
                jobname.required = true;
                companyName.required = true;
                jobtype.required = true;
                employtype.required = true;
                workplace.required = true;
                worksector.required = true;
                monthlyincome.required = true;
                datehired.required = true;
                employstart.required = true;

            } else {
                // Hide the "others" div and remove required attribute
                others.style.display = "none";
                others2.style.display = "none";
                others3.style.display = "none";
                others4.style.display = "none";
                others5.style.display = "none";
                others6.style.display = "none";
                others7.style.display = "none";
                others8.style.display = "none";
                others9.style.display = "none";
                others10.style.display = "none";
                others11.style.display = "none";
                clear.style.display = "none";
                jobname.required = false;
                companyName.required = false;
                jobtype.required = false;
                employtype.required = false;
                workplace.required = false;
                worksector.required = false;
                monthlyincome.required = false;
                field.required = false;
                datehired.required = false;
                employstart.required = false;
                jobname.value = "";
                companyName.value = "";
                jobtype.value = "";
                employtype.value = "";
                workplace.value = "";
                worksector.value = "";
                monthlyincome.value = "";
                datehired.value = "";
                jobdescription.value = "";
                employstart.value = "";
                
            }
        });
    });
</script>

<!-- Clear Inputs Visible (JS FUNCTION) -->

<script>
    // Select input elements
    const jobname = document.getElementById("job_name");
    const companyName = document.getElementById("company_name");
    const jobtype = document.getElementById("job_type");
    const employtype = document.getElementById("employ_type");
    const workplace = document.getElementById("place");
    const worksector = document.getElementById("sector");
    const monthlyincome = document.getElementById("income");
    const datehired = document.getElementById("hired");
    const jobdescription = document.getElementById("description");
    const clearBtn = document.getElementById("clearbtn");

    // Clear function
    clearBtn.onclick = function () {
        jobname.value = "";
        companyName.value = "";
        jobtype.value = "";
        employtype.value = "";
        workplace.value = "";
        worksector.value = "";
        monthlyincome.value = "";
        datehired.value = "";
        jobdescription.value = "";
    };
</script>



    <!-- // DROPDOWN FOR HEADER SUBMENU (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleSidebar = document.querySelector('.user-img');
        const sideNav = document.querySelector('.side-nav');
        const navLinks = sideNav.querySelectorAll('a');
        const sidebar = document.querySelector('.sidebar'); // Reference to sidebar

        // Mapping icons to text
        const iconTextMap = {
            'bx bxs-message': 'Messages'
        };

        // Function to check default sidebar state
        function checkDefaultSidebarState() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add("close"); // Ensure sidebar is closed on smaller screens
            } else {
                sidebar.classList.remove("close"); // Open sidebar for larger screens
            }
        }

        // Toggle dropdown and switch icons to text
        toggleSidebar.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sideNav.classList.toggle('show');
                navLinks.forEach(link => {
                    const iconClass = link.querySelector('i')?.className;
                    if (sideNav.classList.contains('show')) {
                        if (iconTextMap[iconClass]) {
                            link.innerHTML = iconTextMap[iconClass];
                        }
                    } else {
                        if (iconTextMap[iconClass]) {
                            link.innerHTML = `<i class="${iconClass}"></i>`;
                        }
                    }
                });
            }
        });

        // Reset when resizing back to larger screens
        function checkScreenSize() {
            if (window.innerWidth > 768) {
                sideNav.classList.remove('show');
                navLinks.forEach(link => {
                    const iconClass = link.querySelector('i')?.className;
                    if (iconTextMap[iconClass]) {
                        link.innerHTML = `<i class="${iconClass}"></i>`;
                    }
                });
                toggleSidebar.style.pointerEvents = 'none'; // Make the toggle unclickable
            } else {
                toggleSidebar.style.pointerEvents = 'auto'; // Make the toggle clickable
                document.getElementById('greettxt').style.color = '#333';
            }
        }

        // Initial state check on page load
        checkDefaultSidebarState();
        checkScreenSize();

        // Adjust sidebar state dynamically on window resize
        window.addEventListener("resize", () => {
            checkDefaultSidebarState();
            checkScreenSize();
        });

        // TOGGLE SIDEBAR
        const sidebarToggle = document.querySelector(".toggle-sidebar");
        const logo = document.querySelector(".logo-box");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        logo.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    });
</script>
    
</body>
</html>