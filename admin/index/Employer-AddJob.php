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

// Define the setAlert function to store SweetAlert message data
function setAlert($title, $text, $icon, $button = "Done") {
    $_SESSION['alert'] = [
        'title' => $title,
        'text' => $text,
        'icon' => $icon,
        'button' => $button
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employer | Jobs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="shortcut icon" type="text/css" href="css/admin_img/cvsu-logo.png">

    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>

    <!-- PHP INSERT DELETE AND UPDATE -->

<?php

require 'connection.php';

// Show the alert if it's set in the session
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo "<script>swal({
        title: '{$alert['title']}',
        text: '{$alert['text']}',
        icon: '{$alert['icon']}',
        button: '{$alert['button']}',
        closeOnClickOutside: true,
    });</script>";

    // Clear the alert from the session so it doesn't display again
    unset($_SESSION['alert']);
}

 // Query to get the last (maximum) ID from the tbl_employer_joblist table
    $query = "SELECT MAX(ID) AS last_id FROM tbl_employer_joblist";
    $query_run = mysqli_query($con, $query);

    // Fetch the result
    $row = mysqli_fetch_assoc($query_run);

    // If a value is found, set it to the job_id input, otherwise set a default value
    $new_id = isset($row['last_id']) ? $row['last_id'] + 1 : 1;


// Insert Data
if (isset($_POST['submit'])) {
    $com_id = $_POST['com_id'];
    $com_name = $_POST['com_name'];
    $job_title = $_POST['job_title'];
    $positions = $_POST['positions'];
    $close_date = $_POST['close_date'];
    $address = $_POST['address'];
    $job_type = $_POST['job_type'];
    $salary = $_POST['salary'];
    $skill = $_POST['skill'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $status = $_POST['status'];
    $skills = $_POST['skills']; // Array of skills from the input
    $days = $_POST['days'];
    $shift = $_POST['shift'];
    $sched = $_POST['sched'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $postal = $_POST['postal'];
    $job_id = $new_id;


    // Check if the job title already exists
    $check = mysqli_query($con, "SELECT * FROM tbl_employer_joblist WHERE Job_Title ='$job_title' AND Email ='$email'");
    
    if (mysqli_num_rows($check) > 0) {
        setAlert("Already Exist!", "The Job Title You Entered Is Already On The List!", "warning");
        header("Location: Employer-AddJob.php");
            exit;
    } else {
        // Insert into the job list table
        $query = "INSERT INTO tbl_employer_joblist 
        (ID, Company_ID, Job_Title, Image, Company_Name, Available_Positions, Job_Start_Date, Job_Close_Date, Address, Job_Type, Salary, Skills, Description, Status, State, City, Postal, Sched_Day, Email) 
        VALUES 
        ('$job_id','$com_id','$job_title', '$image', '$com_name', '$positions', NOW(), '$close_date', '$address', '$job_type', '$salary', '$skill', '$description', '$status', '$state', '$city', '$postal', '$days', '$email')";

        $query2 = "INSERT INTO tbl_employer_jobsched 
        (Job_Title, Job_ID, Day, Shift, Type, Email) 
        VALUES 
        ('$job_title', '$job_id', '$days', '$shift', '$sched', '$email')";

        // Execute the job list insertion
        if (mysqli_query($con, $query) && mysqli_query($con, $query2)) {
            // Insert skills into tbl_employer_skill
            foreach ($skills as $skillset) {
                $skillset = mysqli_real_escape_string($con, $skillset); // Escape skill value for security
                $query1 = "INSERT INTO tbl_employer_skill (Job_Title, Job_ID, Skill_Name, Email) 
                VALUES ('$job_title', '$job_id', '$skillset', '$email')";
                
                // Execute skill insertion
                if (!mysqli_query($con, $query1)) {
                    // If one skill fails, log the error (you can handle this better with error messages if needed)
                    setAlert("Skill Insertion Failed!", "Failed to insert the skill: $skillset", "error");
                    header("Location: Employer-AddJob.php");
                    exit;
                }
            }

            setAlert("Successfully Added!", "A New Job and Skills Have Been Successfully Added!", "success");
            header("Location: Employer-AddJob.php");
            exit;
        } else {
            setAlert("Data Not Updated!", "Failed to update Your Job Details.", "error");
            header("Location: Employer-AddJob.php");
            exit;
        }
    }
}

?>


      <!-- Header -->
    <div class="header" style="background-color: #85847B;">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="css/admin_img/logo-white.png" width="65" height="60">
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

        <label id="greettxt" style="color: #fff; font-weight: 600; font-size: 14px; margin-bottom: 5px;">Hi there, Valued Partner!<span style="color: #FBD25B; font-size: 20px;">👋</span></label>
        <a href="Employer-Inbox.php"><i class='bx bxs-message'></i></a>
    </div>

    <div class="user-img">
        <?php

                $query = "SELECT * FROM tbl_employer WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

        <img src="css/img/<?php echo $row['Image']; ?>" width="50" height="50" style="border: 2px solid white;">

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
                <a href="home-Employer.php" class="link">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="name">Dashboard</span>
                </a>
                <!-- <i class='bx bxs-chevron-down' ></i> -->
            </div>

            <div class="submenu">
                <a href="home-Employer.php" class="link submenu-title">Dashboard</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing2">
                <a href="Employer-JobReport.php" class="link">
                    <i class='bx bx-bar-chart'></i>
                    <span class="name">Job Report</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-JobReport.php" class="link submenu-title">Job Report</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing3">
                <a href="Employer-Applications.php" class="link">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span class="name">Application</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Applications.php" class="link submenu-title">Application</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing4">
                <a href="Employer-Applicant.php" class="link">
                    <i class='bx bx-calendar-event'></i>
                    <span class="name">Applicant List</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Applicant.php" class="link submenu-title">Applicant List</a>
            </div>

        </li>

         <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing5">
                <a href="Employer-Inbox.php" class="link">
                    <i class='bx bxs-message'></i>
                    <span class="name">Inbox</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Inbox.php" class="link submenu-title">Inbox</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing6">
                <a href="Employer-Profile.php" class="link">
                    <i class='bx bxs-user' ></i>
                    <span class="name">Account</span>
                </a>
            </div>

            <div class="submenu">
                <a href="Employer-Profile.php" class="link submenu-title">Account</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown" id="signoutbtnemp">
            <div class="title" id="landing7">
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
        <a href="Employer-JobReport.php" style="margin-top: -10px;"><span><i class='bx bx-chevron-left'></i></span> Return to Job Report</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Add Job <span><i class='bx bxs-briefcase' ></i></span></h5>
        <p style="font-size: 13px; color: red;">*Please Fill Out All Fields</p>
        <form action="Employer-AddJob.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <div class="row justify-content-center">

            <?php

                $query = "SELECT * FROM tbl_employer WHERE Email ='$email'";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

            <input type="hidden" name="com_id" id="com_id" value="<?php echo $row['Employer_ID']; ?>">
            <input type="hidden" name="com_name" id="com_name" value="<?php echo $row['Company_Name']; ?>">
             <input type="hidden" name="image" id="image" value="<?php echo $row['Image']; ?>">
             <input type="hidden" name="state" id="state" value="<?php echo $row['State']; ?>">
             <input type="hidden" name="city" id="city" value="<?php echo $row['City']; ?>">
             <input type="hidden" name="postal" id="postal" value="<?php echo $row['Postal_Code']; ?>">

             <?php

                  }
              }

             
            ?>

            <input type="hidden" name="status" id="status" value="Active">
            <input type="hidden" name="job_id" id="job_id" value="<?php echo $new_id; ?>">

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Title*</label>
                 <input type="text" name="job_title" id="job_title" class="form-control">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>No. of Available Positions*</label>
                <input type="text" name="positions" id="positions" class="form-control">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Close Date*</label>
                <input type="date" name="close_date" id="close_date" class="form-control" required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Resides In*</label>
                <input type="text" name="address" id="address" class="form-control" required>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Job Status*</label>
                <select name="job_type" id="job_type" class="form-control" required>
                 <option selected hidden value="">Select Job Status</option>
                  <option value="Full-Time">Full-Time</option>
                  <option value="Part-Time">Part-Time</option>
                  <option value="Contract-Based">Contract-Based</option>
                  <option value="Internship">Internship</option>
                  <option value="Freelance">Freelance</option>
                  <option value="Temporary">Temporary</option>
                </select>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 20px;">
                <label>Salary Range*</label>
                <select name="salary" id="salary" class="form-control" required>
                <option selected hidden value="">Select Salary Range</option>
                <option value="Below 10,000">Below 10,000 PHP</option>
                <option value="10,000 - 20,000">10,000 - 20,000 PHP</option>
                <option value="21,000 - 40,000">21,000 - 40,000 PHP</option>
                <option value="41,000 - 60,000">41,000 - 60,000 PHP</option>
                <option value="More than 60,000">More than 60,000 PHP</option>
                </select>
              </div>
            </div>

           
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <h6>Schedule:</h6>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 5px;">
                <label>Days*</label>
                <select name="days" id="days" class="form-control" required>
                  <option selected hidden value="">Select Days</option>
                  <option value="Monday to Friday">Monday to Friday</option>
                  <option value="Full Week">Full Week</option>
                  <option value="Weekends">Weekends</option>
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 5px;">
                <label>Shift*</label>
                <select name="shift" id="shift" class="form-control" required>
                  <option selected hidden value="">Select Shift</option>
                  <option value="Day Shift">Day Shift</option>
                  <option value="Afternoon Shift">Afternoon Shift</option>
                  <option value="Night Shift">Night Shift</option>
                  <option value="Evening Shift">Evening Shift</option>
                  <option value="Rotational Shift">Rotational Shift</option>
                  <option value="Fixed Shift">Fixed Shift</option>
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-group" style="margin-top: 5px;">
                <label>Type*</label>
                <select name="sched" id="sched" class="form-control" required>
                  <option selected hidden value="">Select Type</option>
                  <option value="Flexible">Flexible</option>
                  <option value="Overtime">Overtime</option>
                  <option value="On Call">On Call</option>
                </select>
              </div>
            </div>

             <div class="col-lg-12">
              <div class="form-group" style="margin-top: 30px;">
                <label>Specific Skills Required</label>
                <textarea rows="2" name="skill" id="skill" class="form-control"></textarea>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
            <div class="addskilldiv">
                <button type="button" class="addskillbtn">Add Skill +</button>
                </div>
            </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Job Description</label>
                <textarea rows="3" name="description" id="description" class="form-control"></textarea>
              </div>
            </div>
          
        </div>

              <button class="submit-btn1" type="submit" id="submit" name="submit" form="form_body">Save</button>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Sidebar Landing Page (JS FUNCTION) -->
<script>
    // Add a click event listener to redirect on click
    document.getElementById('landing1').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'home-Employer.php'; // Replace with your desired URL
    });

    document.getElementById('landing2').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-JobReport.php'; // Replace with your desired URL
    });

    document.getElementById('landing3').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Applications.php'; // Replace with your desired URL
    });

    document.getElementById('landing4').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Applicant.php'; // Replace with your desired URL
    });

    document.getElementById('landing5').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Inbox.php'; // Replace with your desired URL
    });

    document.getElementById('landing6').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Employer-Profile.php'; // Replace with your desired URL
    });

    document.getElementById('landing7').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'logout-user.php'; // Replace with your desired URL
    });
</script>

<script>
    // Initialize the starting value
    let jobId = 0;

    // Function to increment the value and display it in the input field
    document.getElementById('submit').addEventListener('click', function () {
        if (jobId < 15) {
            jobId += 1;
            document.getElementById('job_id').value = jobId; // Display the incremented value
        }
    });
</script>

<script>
document.querySelector('.addskillbtn').addEventListener('click', function () {
    // Create a new div wrapper for the input and remove button
    const skillWrapper = document.createElement('div');
    skillWrapper.classList.add('skill-wrapper');
    skillWrapper.style.marginTop = '10px';  // Add margin-top to the input wrapper

    // Create a new input element
    const newInput = document.createElement('input');
    
    // Set attributes for the input
    newInput.type = 'text';
    newInput.required = true;
    newInput.name = 'skills[]';  // Set the name to be an array (skills[])
    newInput.classList.add('form-control');
    newInput.placeholder = 'Enter skill'; // Optional placeholder
    newInput.id = 'skills-' + Date.now(); // Create a unique id for each new input
    
    // Add the input to the wrapper
    skillWrapper.appendChild(newInput);

    // Create the remove button (X icon)
    const removeBtn = document.createElement('button');
    removeBtn.innerHTML = '&times;'; // X icon
    removeBtn.classList.add('remove-skill');
    removeBtn.type = 'button';
    removeBtn.style.marginLeft = '10px';  // Add some spacing between input and button
    
    // Append the remove button to the wrapper
    skillWrapper.appendChild(removeBtn);

    // Add the skillWrapper to the form group before the add skill button
    const formGroup = document.querySelector('.addskilldiv');
    formGroup.insertBefore(skillWrapper, formGroup.querySelector('.addskillbtn'));

    // Add event listener to remove the skill input when the remove button is clicked
    removeBtn.addEventListener('click', function () {
        formGroup.removeChild(skillWrapper);
    });

    // Apply autocomplete to the new input field
    $(newInput).autocomplete({
        source: availableSkills
    });

    // Add event listener to set input as read-only after blur (focus out)
    newInput.addEventListener('blur', function () {
        if (newInput.value.trim() !== "") {  // Only make it read-only if there is some value
            newInput.readOnly = true;
        }
    });
});

// Available skills list (move this outside the click function to make it accessible)
const availableSkills = [
    // Bachelor Of Arts In Journalism
        "Writing",
        "Editing",
        "Proofreading",
        "Research",
        "Interviewing",
        "Media Ethics",
        "Content Creation",
        "Fact-Checking",
        "News Reporting",
        "Digital Journalism",

        // Bachelor Of Early Childhood Education
        "Classroom Management",
        "Child Development",
        "Lesson Planning",
        "Early Literacy",
        "Play-Based Learning",
        "Positive Reinforcement",
        "Parent Communication",
        "Curriculum Design",
        "Behavioral Management",
        "Special Needs Education",

        // Bachelor Of Elementary Education
        "Teaching",
        "Classroom Management",
        "Lesson Planning",
        "Child Development",
        "Curriculum Design",
        "Assessment Strategies",
        "Student Engagement",
        "Technology in Education",
        "Differentiated Instruction",
        "Classroom Communication",

        // Bachelor Of Science In Business Management
        "Leadership",
        "Project Management",
        "Financial Analysis",
        "Strategic Planning",
        "Marketing",
        "Decision Making",
        "Human Resources",
        "Operations Management",
        "Problem Solving",
        "Teamwork",

        // Bachelor Of Science In Computer Science
        "Programming",
        "Algorithms",
        "Data Structures",
        "Database Management",
        "Software Development",
        "Cybersecurity",
        "Operating Systems",
        "Artificial Intelligence",
        "Machine Learning",
        "Web Development",

        // Bachelor Of Science In Entrepreneurship
        "Business Planning",
        "Market Research",
        "Sales",
        "Marketing",
        "Financial Management",
        "Risk Management",
        "Innovation",
        "Networking",
        "Negotiation",
        "Business Strategy",

        // Bachelor Of Science In Hospitality Management
        "Customer Service",
        "Event Planning",
        "Hotel Management",
        "Food and Beverage Management",
        "Catering",
        "Tourism Management",
        "Team Leadership",
        "Marketing",
        "Crisis Management",
        "Problem Solving",

        // Bachelor Of Science In Information Technology
        "Network Management",
        "System Administration",
        "Technical Support",
        "Database Management",
        "Web Development",
        "Cybersecurity",
        "Cloud Computing",
        "Software Development",
        "IT Project Management",
        "Troubleshooting",

        // Bachelor Of Science In Office Administration
        "Office Management",
        "Record Keeping",
        "Administrative Support",
        "Microsoft Office",
        "Customer Service",
        "Communication",
        "Scheduling",
        "Time Management",
        "Document Management",
        "Event Coordination",

        // Bachelor Of Science In Psychology
        "Counseling",
        "Behavioral Analysis",
        "Research",
        "Interpersonal Skills",
        "Data Interpretation",
        "Report Writing",
        "Therapy Techniques",
        "Communication",
        "Conflict Resolution",
        "Mental Health Support",

        // Bachelor Of Secondary Education
        "Lesson Planning",
        "Subject Mastery",
        "Classroom Management",
        "Student Engagement",
        "Curriculum Design",
        "Assessment Strategies",
        "Technology in Education",
        "Differentiated Instruction",
        "Classroom Communication",
        "Special Needs Education"
];

// Initial binding for any existing input with id 'skills'
$("#skills").autocomplete({
    source: availableSkills
});

</script>

<!-- // INPUT VALIDATION FOR NO. OF POSITIONS AND CLOSE DATE (JS FUNCTION) -->

<script>
    // Prevent any non-numeric characters and 0 value in the input
document.getElementById('positions').addEventListener('input', function (e) {
    const value = e.target.value;
    
    // Replace non-numeric characters and remove leading zeros
    const cleanedValue = value.replace(/\D/g, ''); // Remove non-numeric characters
    e.target.value = cleanedValue.replace(/^0+/, ''); // Remove leading zeros
});

 // Get today's date
  const today = new Date();
  
  // Calculate tomorrow's date
  const tomorrow = new Date(today);
  tomorrow.setDate(today.getDate() + 1);
  
  // Convert tomorrow's date to 'YYYY-MM-DD' format
  const tomorrowDate = tomorrow.toISOString().split('T')[0];
  
  // Set the 'min' attribute of the date input to tomorrow's date
  document.getElementById('close_date').setAttribute('min', tomorrowDate);

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