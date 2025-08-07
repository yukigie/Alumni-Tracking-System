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

// Handle ATN generation logic if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'connection.php';

    $batchYear = $_POST['batch_year'];
    $quantity = (int)$_POST['atnqnty'];

    // Validate inputs
    if ($quantity < 1 || empty($batchYear)) {
        echo "<p style='color: red;'>Invalid input. Please provide a valid batch year and positive quantity.</p>";
        exit;
    }

    // Fetch all existing tracking numbers for the selected batch year
    $existingATNs = [];
    $sqlFetch = "SELECT Tracking_Number FROM tbl_atn WHERE Batch_Year = ?";
    $stmt = $con->prepare($sqlFetch);
    $stmt->bind_param('i', $batchYear);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $existingATNs[] = $row['Tracking_Number'];
    }
    $stmt->close();

    // Generate new unique ATNs
    $generatedATNs = [];
    $counter = 1;

    while (count($generatedATNs) < $quantity) {
        $uniqueNumber = str_pad($counter, 5, '0', STR_PAD_LEFT); // Generate padded unique numbers
        $trackingNumber = "TN{$uniqueNumber}{$batchYear}";

        // Ensure uniqueness
        if (!in_array($trackingNumber, $existingATNs) && !in_array($trackingNumber, $generatedATNs)) {
            $generatedATNs[] = $trackingNumber;
        }

        $counter++;
    }

    // Insert the generated ATNs into the database
    if (!empty($generatedATNs)) {
        $sqlInsert = "INSERT INTO tbl_atn (Tracking_Number, Batch_Year) VALUES (?, ?)";
        $stmt = $con->prepare($sqlInsert);

        foreach ($generatedATNs as $atn) {
            $stmt->bind_param('si', $atn, $batchYear);
            $stmt->execute();
        }

        $stmt->close();

        // Prepare a printable response
        echo "<div id='printContent'>";
        echo "<h3>Generated Alumni Tracking Numbers</h3>";
        echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px;'>";
        foreach ($generatedATNs as $atn) {
            echo "<div>{$atn}</div>";
        }
        echo "</div>";
        echo "</div>";
        echo "<button onclick='printATNs()' class='btn btn-primary'>Print</button>";
    } else {
        echo "<p style='color: red;'>No ATNs were generated.</p>";
    }

    // Close the database connection
    $con->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="shortcut icon" type="text/css" href="admin_img/cvsu-logo2.png">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>


<!-- Generate ATN Modal -->
<div class="modal fade GenerateModal" id="AddModal" tabindex="-1" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #1C4B2C;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Generate Alumni Tracking Number</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>ATN Details</h5>

      <form id="atnForm" method="POST" class="form_body" autocomplete="off">
        
        <div class="modal-body">

          <div class="row justify-content-center">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="batch_year" class="form-label">Batch Year:</label>
                <select name="batch_year" id="batch_year" class="form-control" required>
                <option hidden selected value="">Select Batch Year</option>
                <?php
                  $currentYear = date("Y");
                  for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                  }
                ?>
              </select>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label for="atnqnty" class="form-label">Quantity:</label>
                <input type="number" name="atnqnty" id="atnqnty" class="form-control" required min="1" max="1000">
                <small id="quantityError" style="color:red; display:none;">Please enter a valid positive number.</small>
              </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="generateButton" class="btn btn-success" style="background-color: #2E6D43;" 
            onmouseover="this.style.backgroundColor='#1C4B2C';" 
            onmouseout="this.style.backgroundColor='#2E6D43';" data-bs-dismiss="modal">Generate</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Import ATN Modal -->
<div class="modal fade ImportModal" id="AddModal" tabindex="-1" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #1C4B2C;">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Generate Alumni Tracking Number (Import Emails)</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>ATN Details</h5>

      <form id="uploadForm" action="import_excel.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off">
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="batch_year1" class="form-label">Batch Year:</label>
                            <select name="batch_year1" id="batch_year1" class="form-control" required>
                                <option hidden selected value="">Select Batch Year</option>
                                <?php
                                    $currentYear = date("Y");
                                    for ($year = $currentYear; $year >= 1950; $year--) {
                                        echo "<option value='$year'>$year</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group" style="margin-top: 20px;">
                            <label for="excelFile" class="form-label">Select File in (.xlsx, .xls) Format:</label>
                            <input type="file" name="excelFile" accept=".xlsx, .xls" class="form-control" required>
                            <small id="quantityError" style="color:red; display:none;">Please enter a valid positive number.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="generateButton1" id="generateButton1" class="btn btn-success" style="background-color: #2E6D43;" 
                    onmouseover="this.style.backgroundColor='#1C4B2C';" 
                    onmouseout="this.style.backgroundColor='#2E6D43';" data-bs-dismiss="modal">Generate</button>
            </div>
        </form>
    </div>
  </div>
</div>


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


    <!-- Sidebar -->
<div class="sidebar">

<!-- List Menu -->
    <ul class="sidebar-list">
<!-- Non Dropdown List Item -->
        <li>
            <div class="title"  id="landing1">
                <a href="home.php" class="link">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="name">Dashboard</span>
                </a>
                <!-- <i class='bx bxs-chevron-down' ></i> -->
            </div>
        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing3">
                <a href="Report.php" class="link">
                    <i class="fa fa-pie-chart" aria-hidden="true"></i>
                    <span class="name">Report</span>
                </a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing2">
                <a href="Alumni.php" class="link">
                    <i class='bx bxs-graduation' ></i>
                    <span class="name">Alumni</span>
                </a>
            </div>
        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing4">
                <a href="Jobs.php" class="link">
                    <i class='bx bxs-briefcase' ></i>
                    <span class="name">Jobs</span>
                </a>
            </div>
        </li>


        <!-- Dropdown List Item -->
        <li class="dropdown">
            <div class="title" id="landing5">
                <a href="AllUsers.php" class="link">
                    <i class='bx bxs-user' ></i>
                    <span class="name">All Users</span>
                </a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <label>General Setting</label>
        <li class="dropdown">
            <div class="title">
                <a href="#" class="link">
                    <i class='bx bxs-cog' ></i>
                    <span class="name">All Settings</span>
                </a>
                <i class='bx bxs-chevron-down' ></i>
            </div>

            <div class="submenu">
                <a href="#" class="link submenu-title">All Settings</a>
                <a href="Gallery.php" class="link">Add Gallery</a>
                <a href="Course.php" class="link">Add Courses</a>
                <a href="Events.php" class="link">Add Event</a>
                <a href="Admin-Setting.php" class="link">System Settings</a>
            </div>

        </li>

        <!-- Dropdown List Item -->
        <li class="dropdown" style="margin-top: 80%;">
            <div class="title">
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

    <div class="head-title">
        <h2>System Settings</h2>
        <p><a href="home.php">Dashboard</a> / System Settings</p>

        <button class="hirebtn" data-bs-toggle="modal" data-bs-target=".ImportModal">Import <span><i class="fa fa-upload" aria-hidden="true"></i></span></button>
    </div>

    <div class="container_my-5">

    <button type="button" class="btn btn-primary" id="new" data-bs-toggle="modal" data-bs-target=".GenerateModal" style="margin-bottom: 20px; background-color: #2E6D43;"
    onmouseover="this.style.backgroundColor='#1C4B2C';" 
    onmouseout="this.style.backgroundColor='#2E6D43';">

        <i class="fa fa-plus-circle" aria-hidden="true" style="
    color: #EACD38;"></i><span>GENERATE ATN</span>
    </button>
    
    <a href="Admin-Backup.php"><button type="button" class="btn btn-primary" id="new" style="margin-bottom: 20px; background-color: #388CEC;"
    onmouseover="this.style.backgroundColor='#225EA4';" 
    onmouseout="this.style.backgroundColor='#388CEC';">

        <i class="fa fa-database" aria-hidden="true" style="
    color: #EACD38;"></i><span>BACKUP DATA</span>
    </button></a>

    <br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable" style="width: 100%;">
    <thead>
        <tr>
            <th scope="col">Ref. ID</th>
            <th scope="col">Tracking Number</th>
            <th scope="col">Batch Year</th>
            <th scope="col">Status</th> <!-- Added Status Column -->
        </tr>
    </thead>
    <tbody>
        <?php
        require 'connection.php';

        // Query to get data from tbl_atn and tbl_alumni
        $sql = "SELECT a.ID, a.Tracking_Number, a.Batch_Year, 
                       IF(alumni.Alumni_ID IS NOT NULL, 'Used', 'Not Used') AS Status
                FROM tbl_atn a
                LEFT JOIN tbl_alumni alumni ON a.Tracking_Number = alumni.Alumni_ID";

        $result = $con->query($sql);

        if (!$result) {
            die("Invalid query: " . $con->error);
        }

        while($row = $result->fetch_assoc()) {
            // Set the text color based on the status
            $statusColor = ($row['Status'] == 'Used') ? 'green' : 'red';
            
            echo "
            <tr>
                <td>{$row['ID']}</td>
                <td>{$row['Tracking_Number']}</td>
                <td>{$row['Batch_Year']}</td>
                <td style='color: $statusColor; font-weight: 600;'>{$row['Status']}</td>
            </tr>
            ";
        }

        mysqli_close($con);
        ?>
    </tbody>
</table>

<div id="responseMessage" style="display: none;"></div>

 <div id="loader" style="display: none; margin-top: 20px;">
        <p>Processing... Please wait. <span><img src="admin_img/loader.gif" alt="Loading..." style="width: 50px; height: 50px;"></span></p>
        
    </div>

    </div>
</div>
    </section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- JavaScript for generating ATN with Import Email -->
<script>
    document.getElementsByName('generateButton1')[0].addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        const batchYear = document.getElementById('batch_year1').value;
        const excelFile = document.querySelector('input[name="excelFile"]').files[0]; // Get the uploaded file

        // Check if both fields are filled
        if (!batchYear || !excelFile) {
            alert("Please fill out all fields correctly.");
            return;
        }

        // Show the loading spinner
        const loader = document.getElementById('loader');
        const responseMessage = document.getElementById('responseMessage');
        loader.style.display = 'block';
        responseMessage.style.display = 'none'; // Hide the response message initially

        // Create a FormData object to send the file and batch year to PHP
        const formData = new FormData();
        formData.append('batch_year1', batchYear);
        formData.append('excelFile', excelFile);

        // Send data to the PHP script (import_excel.php)
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "import_excel.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) { // When request is complete
                loader.style.display = 'none'; // Hide the loader
                if (xhr.status === 200) {
                    // Assuming the PHP script returns a message or result
                    responseMessage.innerHTML = xhr.responseText;
                    responseMessage.style.display = 'block'; // Show the response message
                } else {
                    responseMessage.innerHTML = '<p style="color: red;">An error occurred. Please try again.</p>';
                    responseMessage.style.display = 'block'; // Show the error message
                }
            }
        };

        // Send the data (batch_year and file)
        xhr.send(formData); // Sends the batch year and file via FormData
    });
</script>

<!-- JavaScript for generating ATN -->
<script>
    document.getElementById('generateButton').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default form submission

        const batchYear = document.getElementById('batch_year').value;
        const quantity = document.getElementById('atnqnty').value;

        if (!batchYear || quantity <= 0 || isNaN(quantity)) {
            alert("Please fill out all fields correctly.");
            return;
        }

        // Show the loading spinner
        const loader = document.getElementById('loader');
        const responseMessage = document.getElementById('responseMessage');
        loader.style.display = 'block';
        responseMessage.style.display = 'none'; // Hide response message initially

        // Send data to the PHP script
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true); // PHP is on the same page
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) { // When request is complete
                loader.style.display = 'none'; // Hide the loader
                if (xhr.status === 200) {
                    responseMessage.innerHTML = xhr.responseText;
                    responseMessage.style.display = 'block'; // Show the response message
                } else {
                    responseMessage.innerHTML = '<p style="color: red;">An error occurred. Please try again.</p>';
                    responseMessage.style.display = 'block'; // Show the error message
                }
            }
        };

        xhr.send(`batch_year=${batchYear}&atnqnty=${quantity}`);
    });

    // Function to print ATNs
    function printATNs() {
        const printContent = document.getElementById('printContent').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Generated ATNs</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; }');
        printWindow.document.write('#printContent { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

<!-- Sidebar Landing Page (JS FUNCTION) -->
<script>
    // Add a click event listener to redirect on click
    document.getElementById('landing1').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'home.php'; // Replace with your desired URL
    });

    document.getElementById('landing2').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Alumni.php'; // Replace with your desired URL
    });

    document.getElementById('landing3').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Report.php'; // Replace with your desired URL
    });

    document.getElementById('landing4').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'Jobs.php'; // Replace with your desired URL
    });

    document.getElementById('landing5').addEventListener('click', function() {
        // Redirect to a different page
        window.location.href = 'AllUsers.php'; // Replace with your desired URL
    });
</script>

<!-- Datatable Searchbox (JS FUNCTION) -->
<script>
    $(document).ready(function () {
        // Initialize #myTable for ViewModal
        $('#myTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [5, 25, 50, -1],
                [5, 25, 50, "All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search here.....",
            }
        });

        // Use event delegation for ViewModal
        $('#myTable').on('click', '.editbtn1', function () {
            $('.ViewModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();


            const imageFileName = data[10]; 
            // Full image path
            const imgSrc = `index/css/img/${imageFileName}`;

            $('#view_id1').val(data[0]);
            $('#job_title').val(data[1]);
            $('#com_name').val(data[2]);
            $('#job_type').val(data[6]);
            $('#posted_date').val(data[3]);
            $('#position').val(data[7]);
            $('#close_date').val(data[8]);
            $('#address').val(data[9]);
            $('#skills').val(data[11]);
            $('#description').val(data[12]);
            $('#applicants').text(data[4]);
            $('.status').text(data[5]);
            $('#alumni_img').attr('src', imgSrc);
        });
    });
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