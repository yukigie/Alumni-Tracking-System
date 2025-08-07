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
    <title>Admin | Home</title>
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
                <a href="#" class="link">
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

    <section class="home" style="max-height: 100vh; height: 90vh;">

        <div class="dashboard-items">
            <div class="row">
                <div class="col-lg-3">
                    <a href="AllUsers.php"><div class="item1">
                        <div class="db-icon">
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>All Verified Users</p>
                        <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM usertable WHERE status = 'verified'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>

                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="AllUsers.php"><div class="item2">
                        <div class="db-icon">
                            <i class="fa fa-briefcase" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Company Accounts</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer WHERE Account = 'verified'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Alumni.php"><div class="item3">
                        <div class="db-icon">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Employed Alumni</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_alumni WHERE Status = 'Employed'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Alumni.php"><div class="item4">
                        <div class="db-icon">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Unemployed Alumni</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_alumni WHERE status = 'Unemployed'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

            </div>
        </div>

        <div class="dashboard-items1">
            <div class="row">
                <div class="col-lg-3">
                    <a href="Events.php"><div class="item1">
                        <div class="db-icon">
                            <i class="fa fa-calendar-o" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>&emsp;Posted Events&emsp;</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_event";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Jobs.php"><div class="item2">
                        <div class="db-icon">
                            <i class="fa fa-briefcase" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>&emsp;&emsp;Posted Jobs&emsp;&nbsp;</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_joblist";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Placement.php"><div class="item3">
                        <div class="db-icon">
                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Job-Placed Alumni</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer_application";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

                <div class="col-lg-3">
                    <a href="Company-Request.php"><div class="item4">
                        <div class="db-icon">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                        </div>
                        <div class="db-text">
                            <p>Request Accounts</p>
                            <?php
                            $query = "SELECT COUNT(*) AS verified_count FROM tbl_employer WHERE Account = 'notverified'";
                            $query_run = mysqli_query($con, $query);

                            if ($query_run) {
                                $row = mysqli_fetch_assoc($query_run); // Fetch the result
                                $numberOfRows = $row['verified_count']; // Get the count from the result
                                echo "<h3 class='rowCountDisplay' data-count='{$numberOfRows}'>0</h3>";
                            } else {
                                echo "<h3>0</h3>";
                            }
                        ?>
                        </div>
                    </div></a>
                </div>

            </div>
        </div>

        <div class="dashboard-btmitems">
            <div class="row">
                <div class="col-lg-6">
                    <a href="Alumni.php" style="color: #333; display: inline-block;">
                        <h6>Alumni Report for Year: </h6>
                    </a>
                <select name="batch_year" id="batch_year" class="form-control" style="width: 150px; display: inline-block;">
                  <option hidden id="currentYear"></option>
                  <!-- dynamically generate this list using server-side logic if needed -->
                  <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                    }
                  ?>
                </select>
                    <div class="alumnigraph">

                        <div id="noDataMessage" style="display: none; text-align: center; color: #333; font-size: 16px; margin-top: 25%;">
                            No data available for the selected year.
                        </div>

                        <canvas id="coursePieChart"></canvas>
                    </div>
                </div>


        <div class="col-lg-6">
        <div class="details">
            <div class="tblPlacement">
                <div class="cardHeader">
                    <h5>Student Placement Progress</h5>
                    <a href="Placement.php"><button class="btn btn-secondary">See More</button></a>
                </div>

                <table style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th scope="col">Alumni ID</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Job Title</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>

                    <tbody>
            <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_employer_application";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[Alumni_ID]</td>
                        <td>$row[Applicant_Name]</td>
                        <td>$row[Job_Title]</td>
                        <td class='status-text'>$row[Status]</td>
                    </tr>
                    ";
                }

                    mysqli_close($con);
                ?>
                    </tbody>
                </table>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
document.getElementById('currentYear').textContent = new Date().getFullYear();

document.addEventListener("DOMContentLoaded", function () {
    const selectYear = document.getElementById('batch_year');
    const ctx = document.getElementById('coursePieChart').getContext('2d');
    const noDataMessage = document.getElementById('noDataMessage'); // Reference to the no-data message
    let chart = null; // Declare the chart instance

    // Function to fetch and update chart data
    const updateChart = (selectedYear) => {
        fetch(`fetch_course_data.php?year=${selectedYear}`) // Pass year as query param
            .then(response => response.json())
            .then(data => {
                if (data.counts.length === 0) {
                    // Hide the chart and display the no-data message
                    if (chart) {
                        chart.destroy(); // Destroy the chart if it exists
                        chart = null; // Reset the chart instance
                    }
                    ctx.canvas.style.display = 'none';
                    noDataMessage.style.display = 'block';
                } else {
                    // Display the chart and hide the no-data message
                    ctx.canvas.style.display = 'block';
                    noDataMessage.style.display = 'none';

                    const counts = data.counts.map(Number); // Ensure data is numeric
                    const total = counts.reduce((sum, val) => sum + val, 0); // Total count

                    if (chart) {
                        // Update existing chart data
                        chart.data.labels = data.legends;
                        chart.data.datasets[0].data = counts;
                        chart.update();
                    } else {
                        // Create a new chart if none exists
                        chart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: data.legends,
                                datasets: [{
                                    label: 'Alumni Percentage by Course',
                                    data: counts,
                                    backgroundColor: [
                                        '#126A48', '#C89C1D', '#C8D6D3', '#12536A', '#126A25',
                                        '#6A5512', '#6A2E12', '#6A1212', '#6A123B', '#6A124E', '#12536A', '#126A69', '#716D4A', '#6E6E68'
                                    ],
                                    borderColor: '#ffffff',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            font: { size: 12 },
                                            generateLabels: function (chart) {
                                                const { data } = chart;
                                                const dataset = data.datasets[0];
                                                const total = dataset.data.reduce((sum, val) => sum + val, 0); // Recalculate total
                                                return data.labels.map((label, i) => {
                                                    const value = dataset.data[i];
                                                    const percentage = total > 0
                                                        ? ((value / total) * 100).toFixed(2)
                                                        : 0; // Avoid division by zero
                                                    return {
                                                        text: `${label} (${percentage}%)`,
                                                        fillStyle: dataset.backgroundColor[i],
                                                        strokeStyle: dataset.borderColor,
                                                        lineWidth: dataset.borderWidth,
                                                        hidden: !chart.getDataVisibility(i),
                                                        index: i
                                                    };
                                                });
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (tooltipItem) {
                                                const value = counts[tooltipItem.dataIndex];
                                                const total = counts.reduce((sum, val) => sum + val, 0); // Ensure recalculation
                                                const percentage = total > 0
                                                    ? ((value / total) * 100).toFixed(2)
                                                    : 0; // Avoid division by zero
                                                return `${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    };

    // Initialize with the current year
    const currentYear = new Date().getFullYear();
    updateChart(currentYear);

    // Update chart on year selection change
    selectYear.addEventListener('change', function () {
        const selectedYear = this.value;
        updateChart(selectedYear);
    });
});
</script>


<!-- Define color map based on status value (JS FUNCTION) -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const statusTexts = document.querySelectorAll(".status-text"); // Select all elements with the class .status-text

    // Define color map based on status value
    const statusColors = {
        "Applied": "green",
        "Screening": "#8B7818",
        "Interview": "blue",
        "Hired": "#125428"
    };

    // Loop through each status-text element
    statusTexts.forEach(statusText => {
        const statusValue = statusText.textContent.trim(); // Get and trim the text content

        // Set the background color if a match is found
        if (statusColors[statusValue]) {
            statusText.style.color = statusColors[statusValue];
        }
    });
});
</script>

<!-- Rolling count effect  -->
<script>
    function animateCountByClass(className, duration) {
        const elements = document.querySelectorAll(`.${className}`);
        
        elements.forEach(element => {
            const targetCount = parseInt(element.getAttribute("data-count"), 10) || 0;
            const startCount = 0;
            const steps = duration / 50; // More steps for smoother animation
            const increment = targetCount / steps; // Smaller increments for smooth transition
            let currentCount = startCount;
            let currentStep = 0;

            const timer = setInterval(() => {
                currentStep++;
                currentCount += increment;

                if (currentStep >= steps || currentCount >= targetCount) {
                    currentCount = targetCount; // Final value to ensure precision
                    clearInterval(timer);
                }

                element.textContent = Math.floor(currentCount); // Update the element with the current count
            }, 50); // Update every 50ms
        });
    }

    // Example usage: animate numbers for elements with the class 'rowCountDisplay' over 3 seconds
    document.addEventListener("DOMContentLoaded", () => {
        animateCountByClass("rowCountDisplay", 2000); // 3000ms = 3 seconds
    });
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


   <!-- // DROPDOWN FOR HEADER SUBMENU (JS FUNCTION) -->
<!-- <script>
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


</script> -->


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