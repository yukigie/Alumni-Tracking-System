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
    <title>Alumni | Print Employment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">

    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <!-- Header -->
    <div class="header">

    <!-- Logo -->
    <div class="logo_content">
        <a href="#" class="logo-box">
            <img src="admin_img/logo-white.png" width="65" height="60">
            <div class="logo" style="font-size: 23px; font-weight: 800;">C<span style="color: #FBD25B; font-weight: 800; font-size: 23px;">v</span>SU 
                <span style="font-size: 15px; font-weight: 600;">Imus Campus</span>
                <p><span>Alumni Tracker<img src="admin_img/focus.png" width="25" height="25"></span></p>
            </div>

            <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        </a>
    </div>

<div class="side-nav1">
    <div class="side-nav">
        <p style="font-size: 16px; color: #fff; font-weight: 600;">ADMIN <span style="font-size: 30px; color: lightgreen;">•</span></p>
    </div>

    <div class="user-img" style="margin-top: 20px;">
        <img src="admin_img/cvsu-logo1.png" width="50" height="50">
    </div>
</div>

    </div>


<!-- Home Section -->

<section class="home" style="left: 50px; width: 95%;">

<div class="row">
    <div class="col-md-12">
    <div id="personal_details" style="padding-top: 20px;">
        <a href="Report.php" style="margin-top: -10px; color: #C2AE44"><span><i class='bx bx-chevron-left'></i></span> Return to Report</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Print Table <span><i class="fa fa-print" aria-hidden="true"></i></span></h5>

        <form action="Alumni-Resume-AddWork.php" method="POST" enctype="multipart/form-data" class="form_body" id="form_body" autocomplete="off">

        <div class="row justify-content-center">

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 10px;">
                <label style="margin-bottom: 10px;">Filter By<i class="fa fa-sort-amount-desc" aria-hidden="true"></i></label>
               
                <select name="filter_type" id="filter_type" class="form-control">
                    <option hidden selected>Select Category</option>
                    <option value="gender">Gender</option>
                    <option value="course">Course</option>
                    <option value="year">Year</option>
                    <option value="status">Status</option>
                    <option value="starting">Start of Employment</option>
                    <option value="company">Employment Type</option>
                    <option value="place">Place</option>
                    <option value="income">Income</option>
                </select>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group" style="margin-top: 10px;">

                <select name="filter_gender" id="filter_gender" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                    <option hidden selected>Select Gender</option>
                    <option>Female</option>
                    <option>Male</option>
                </select>
               
                <?php
                    $sql = "SELECT * FROM tbl_courses";

                    if ($result = mysqli_query($con, $sql)) {
                        if (mysqli_num_rows($result) > 0) {

                            echo "<select name='filter_course' id='filter_course' class='form-control' style='margin-top: 35px; display: none; width: 85%;'>";
                            echo "<option selected hidden value=''>Select Course</option>";

                           while($rows = mysqli_fetch_assoc($result)){
                                      echo "
                                      <option selected hidden value=''>Select Course Name</option>

                                      <option>".$rows["Course_Name"]."</option>";

                              }
                          echo "</select>";
                          }
                      }
                    ?>


                <select name="filter_year" id="filter_year" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                    <option hidden selected>Select Year</option>
                    <!-- dynamically generate this list using server-side logic if needed -->
                  <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                    }
                  ?>
                </select>

                <select name="filter_status" id="filter_status" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                    <option hidden selected>Select Status</option>
                    <option>Employed</option>
                    <option>Unemployed</option>
                </select>

                <select name="filter_start" id="filter_start" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                  <option hidden selected>Select Starting</option>
                  <option>1 - 6 Months</option>
                  <option>7 - 12 Months</option>
                  <option>1 Year</option>
                  <option>2 years</option>
                  <option>N/A</option>
                </select>

                <select name="filter_comp" id="filter_comp" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                    <option hidden selected>Select Type</option>
                      <option>Regular</option>
                      <option>Temporary</option>
                      <option>Contractual</option>
                      <option>On-Call</option>
                      <option>Full-Time</option>
                      <option>Part-Time</option>
                      <option>Own Business</option>
                </select>

                <select name="filter_place" id="filter_place" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                    <option hidden selected>Select Place</option>
                    <option>Local</option>
                    <option>International</option>
                </select>

                <select name="filter_income" id="filter_income" class="form-control" style="margin-top: 35px; display: none; width: 85%;">
                    <option hidden selected>Select Income</option>
                    <option value="Below 10,000">Below 10,000</option>
                    <option value="10,000 - 20,000">10,000 - 20,000 PHP</option>
                    <option value="21,000 - 40,000">21,000 - 40,000 PHP</option>
                    <option value="41,000 - 60,000">41,000 - 60,000 PHP</option>
                    <option value="More than 60,000">More than 60,000 PHP</option>
                </select>

                <button type="button" class="btn btn-primary" name="submit" id="submit" style="display: none; margin-left: 5px;">Submit</button>
              </div>
            </div>


            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <textarea name="textarea" id="default" class="form-control" rows="3">
                    
                <div class="header-print" style="text-align: center;">
                    <img src="admin_img/cvsu-logo.png" width="60" height="60" style="margin-top: 20px; margin-left: -23rem;">

                    <h4 style="margin-top: -50px;">Cavite State University - Imus Campus</h4>

                    <p>Cavite Civic Center Palico IV, Imus, Cavite <br>
                        (046) 436 6584 / (046) 436 6584 / (046) 436 6584</p>

                    <a href="www.cvsu.edu.ph">www.cvsu.edu.ph</a>

                    <h3>Alumni Tracer System</h3>

                    <div class="qoute" style="width: 500px; font-size: 14px; color: #555; margin: 0 auto;">
                    <p><i>Cavite State University - Imus Campus Alumni Tracer System, where we help our alumni stay connected, celebrate their successes, and contribute to our community's growth and development.</i></p>
                    </div>

 <h2>Employment Status Report</h2>

<table id="alumni-tbl" 
style="border-collapse: collapse; 
width: 100%; margin-top: 20px;">

<tr>
<th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">No.</th>
                        
<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">First Name</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Last Name</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Gender</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Course</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Graduated Year</th>
                        
<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Status</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Start of Employment</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Position</th>
                        
<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Hired Date</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Company Name</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Place</th>

<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Emp.Type</th>
                        
<th style=" border: 1px solid #ddd;
padding: 8px; padding-top: 12px;
padding-bottom: 12px;
background-color: green;
color: white;">Income</th>
                      </tr>

<?php
require 'connection.php';

$sql = "SELECT 
    tbl_alumni.*,
    tbl_alumni_jobstatus.ID,
    tbl_alumni_jobstatus.Job_Name,
    tbl_alumni_jobstatus.Date_Hired,
    tbl_alumni_jobstatus.Company_Name,
    tbl_alumni_jobstatus.Place,
    tbl_alumni_jobstatus.Employment_Type,
    tbl_alumni_jobstatus.Income,
    tbl_alumni_jobstatus.Starting_Emp,
    tbl_alumni_jobstatus.Email
FROM 
    tbl_alumni
JOIN 
    tbl_alumni_jobstatus ON (tbl_alumni.Email = tbl_alumni_jobstatus.Email AND 
    tbl_alumni.Alumni_ID = tbl_alumni_jobstatus.ID);
";
$result = $con->query($sql);

if (!$result) {
    die("Invalid query: " . $con->error);
}
$no = 1;
while($row = $result->fetch_assoc()) {
    echo "
    <tr>
        <td style='border: 1px solid #ddd; padding: 8px;'>$no</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[First_Name]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Last_Name]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Gender]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;' class='course-column'>$row[Course]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Batch_Year]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Status]</td>
<td style='border: 1px solid #ddd;
padding: 8px;'>$row[Starting_Emp]</td>
<td style='border: 1px solid #ddd;
padding: 8px;'>$row[Job_Name]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Date_Hired]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Company_Name]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Place]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Employment_Type]</td>
        <td style='border: 1px solid #ddd;
padding: 8px;'>$row[Income]</td>
    </tr>
    ";
    $no++;
}
?>
</table>
<p style="text-align: left; font-weight: 600; margin-top: 20px;">Prepared By. </p>
           </div>

                </textarea>
                
              </div>
            </div>

        </div>
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
<script src="tinymce/tinymce.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    
<!-- // Rich textbox Editor For Content (JS FUNCTION) -->
<script>
tinymce.init({
    selector: '#default', // Replace with your TinyMCE selector
    height: 1000,
    plugins: 'lists link image advtemplate print',
    toolbar: 'undo redo | bold italic | bullist numlist | link image | advtemplate | print',
    advtemplate_templates: [
        {
            title: 'Custom Template 1',
            description: 'Description of Custom Template 1',
            content: '<p>Your content here</p>'
        },
        {
            title: 'Custom Template 2',
            description: 'Description of Custom Template 2',
            content: '<p>Another template content here</p>'
        }
    ],
    setup: function (editor) {
        editor.on('init', function () {
            formatCourseAndJobColumns(); // Call the course formatting function on init
            addPrintCSS(); // Inject print CSS when the editor initializes
        });
    }
});

// Function to format course columns by removing text before/after the hyphen
function formatCourseAndJobColumns() {
    const editorContent = tinymce.get('default').getContent();
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = editorContent;

    // Process .course-column cells to display text after the hyphen
    tempDiv.querySelectorAll('.course-column').forEach(cell => {
        const fullText = cell.textContent;
        const hyphenIndex = fullText.indexOf('-');

        // Check if there's a hyphen and set text after it
        if (hyphenIndex !== -1) {
            cell.textContent = fullText.substring(hyphenIndex + 1).trim();
        }
    });

    // Set the updated content back to the editor
    tinymce.get('default').setContent(tempDiv.innerHTML);
}

// Function to dynamically add print-specific CSS
function addPrintCSS() {
    const style = document.createElement('style');
    style.type = 'text/css';
    style.innerHTML = `
        @media print {
            table {
                page-break-inside: auto;
                border-collapse: collapse;
                width: 100%;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
            
            tfoot {
                display: table-footer-group;
            }
            
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            
            th {
                background-color: green;
                color: white;
                text-align: left;
            }
        }
    `;
    document.head.appendChild(style); // Append the CSS to the <head>
}
</script>


<!-- Filter Functionality Script -->
<script>
document.getElementById('filter_type').addEventListener('change', function () {
    const selectedCategory = this.value;
    document.querySelectorAll('#filter_gender, #filter_course, #filter_year, #filter_status, #filter_comp, #filter_place, #filter_income, #filter_start, #submit').forEach(el => {
        el.style.display = 'none';
    });

    if (selectedCategory === 'gender') document.getElementById('filter_gender').style.display = 'inline-block';
    else if (selectedCategory === 'course') document.getElementById('filter_course').style.display = 'inline-block';
    else if (selectedCategory === 'year') document.getElementById('filter_year').style.display = 'inline-block';
    else if (selectedCategory === 'status') document.getElementById('filter_status').style.display = 'inline-block';
    else if (selectedCategory === 'company') document.getElementById('filter_comp').style.display = 'inline-block';
    else if (selectedCategory === 'place') document.getElementById('filter_place').style.display = 'inline-block';
    else if (selectedCategory === 'income') document.getElementById('filter_income').style.display = 'inline-block';
     else if (selectedCategory === 'starting') document.getElementById('filter_start').style.display = 'inline-block';

    if (selectedCategory !== 'Select Category') document.getElementById('submit').style.display = 'inline-block';
});

document.getElementById('submit').addEventListener('click', function () {
    const filterType = document.getElementById('filter_type').value;
    let filterValue = '';

    if (filterType === 'gender') filterValue = document.getElementById('filter_gender').value;
    else if (filterType === 'course') filterValue = document.getElementById('filter_course').value;
    else if (filterType === 'year') filterValue = document.getElementById('filter_year').value;
    else if (filterType === 'status') filterValue = document.getElementById('filter_status').value;
    else if (filterType === 'company') filterValue = document.getElementById('filter_comp').value;
    else if (filterType === 'place') filterValue = document.getElementById('filter_place').value;
    else if (filterType === 'income') filterValue = document.getElementById('filter_income').value;
    else if (filterType === 'starting') filterValue = document.getElementById('filter_start').value;

    if (filterType && filterValue) {
        // Send an AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "filter_employment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Replace the TinyMCE content with the filtered result
                tinymce.get('default').setContent(xhr.responseText);

                // Apply formatting to the loaded content
                applyFormattingToTinyMCEContent();
            }
        };

        xhr.send(`filterType=${encodeURIComponent(filterType)}&filterValue=${encodeURIComponent(filterValue)}`);
    } else {
        alert("Please select both a filter type and a filter value.");
    }
});

// Function to apply formatting to TinyMCE content
function applyFormattingToTinyMCEContent() {
    const editorContent = tinymce.get('default').getContent();
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = editorContent;

    // Process .course-column cells to display text after the hyphen
    tempDiv.querySelectorAll('.course-column').forEach(cell => {
        const fullText = cell.textContent.trim();
        const hyphenIndex = fullText.indexOf('-');
        if (hyphenIndex !== -1) {
            cell.textContent = fullText.substring(hyphenIndex + 1).trim();
        }
    });

    // Update the TinyMCE editor content
    tinymce.get('default').setContent(tempDiv.innerHTML);

    console.log("Formatting applied to TinyMCE content.");
}
</script>


   <!-- // DROPDOWN FOR HEADER SUBMENU (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const toggleSidebar = document.querySelector('.user-img');
    const sideNav = document.querySelector('.side-nav');
    const navLinks = sideNav.querySelectorAll('a');
    
    // Mapping icons to text
    const iconTextMap = {
        'bx bxs-message': 'Messages',
        'bx bxs-bell': 'Notifications'
    };

    // Toggle dropdown and switch icons to text
    toggleSidebar.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            sideNav.classList.toggle('show');
            navLinks.forEach(link => {
                const iconClass = link.querySelector('i').className;
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
                const iconClass = link.querySelector('i').className;
                if (iconTextMap[iconClass]) {
                    link.innerHTML = `<i class="${iconClass}"></i>`;
                }
            });
            toggleSidebar.style.pointerEvents = 'none'; // Make the toggle unclickable
        } else {
            toggleSidebar.style.pointerEvents = 'auto'; // Make the toggle clickable
        }
    }

    window.addEventListener('resize', checkScreenSize);

    // Initial check
    checkScreenSize();
});


</script>


   <!-- // DROPDOWN FOR SUBMENU (JS FUNCTION) -->
<script>

    // DROPDOWN FOR SUBMENU
    const listItems = document.querySelectorAll(".sidebar-list li");

    listItems.forEach(item => {
        item.addEventListener("click", () => {
            let isActive = item.classList.contains("active");

            listItems.forEach((el) => {
                el.classList.remove("active")
            });

            if (isActive) item.classList.remove("active");
            else item.classList.add("active");
        });
    });
    
    // TOGGLE SIDEBAR
    const toggleSidebar = document.querySelector(".toggle-sidebar");
    const logo = document.querySelector(".logo-box");
    const sidebar = document.querySelector(".sidebar");

    toggleSidebar.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });

    logo.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });
</script>
    
</body>
</html>