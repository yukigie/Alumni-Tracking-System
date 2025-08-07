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
    <title>Admin | Report</title>
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

    <section class="home" style="max-height: 200vh; height: 150vh;">

        <div class="dashboard-btmitems">
            <div class="row">

                <div class="col-lg-12" style="margin-top: 20px; width: 98%;">
                    <a href="Print-BatchYear.php" style="color: #333; display: inline-block;">
                        <h5><i class="fa fa-external-link" aria-hidden="true"></i> Graduates of CVSU Imus Campus 2008 - </h5>
                    </a>
                <select name="batch_year1" id="batch_year1" class="form-control" style="width: 150px; display: inline-block;">
                  <option hidden id="currentYear1"></option>
                  <!-- dynamically generate this list using server-side logic if needed -->
                  <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                    }
                  ?>
                </select>

                <h5 style="display: inline; float: right;">Total of: <span id="TotalGrad" style="font-weight: 600; color: green;"></span></h5>

                    <div class="alumnigraph" style="height: 350px; padding: 20px; background-color: #EAFFF7;">

                        <div id="noDataMessage1" style="display: none; text-align: center; color: #333; font-size: 16px; margin-top: 3%;">
                            No data available for the selected year.
                        </div>

                        <canvas id="BatchGraph"></canvas>
                    </div>
                </div>

                <div class="col-lg-12" style="margin-top: 20px; width: 98%;">
                    <a href="Print-Placement.php" style="color: #333; display: inline-block;">
                        <h5><i class="fa fa-external-link" aria-hidden="true"></i> Placed Alumni of CVSU Imus Campus 2008 - </h5>
                    </a>
                <select name="placed_year" id="placed_year" class="form-control" style="width: 150px; display: inline-block;">
                  <option hidden id="currentYear2"></option>
                  <!-- dynamically generate this list using server-side logic if needed -->
                  <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                    }
                  ?>
                </select>

                <h5 style="display: inline; float: right;">Total of: <span id="TotalGrad1" style="font-weight: 600; color: green;"></span></h5>

                    <div class="alumnigraph" style="height: 350px; padding: 20px; background-color: #EAFFF7;">

                        <div id="noDataMessage2" style="display: none; text-align: center; color: #333; font-size: 16px; margin-top: 3%;">
                            No data available for the selected year.
                        </div>

                        <canvas id="BatchGraph1"></canvas>
                    </div>
                </div>

                <div class="col-lg-6" style="margin-top: 20px;">
                    <a href="Print-EmpStatus.php" style="color: #333; display: inline-block;">
                        <h6><i class="fa fa-external-link" aria-hidden="true"></i> Employment Status Report for Year & Program Name: </h6>
                    </a>
                <select name="batch_year" id="batch_year" class="form-control" style="margin-bottom: 20px;">
                  <option value="All Batch Year">All Batch Year</option>
                  <!-- dynamically generate this list using server-side logic if needed -->
                  <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                    }
                  ?>
                </select>

                <select name="course" id="course" class="form-control">
                  <option value="">All Courses</option>
                </select>
                    <div class="alumnigraph">

                        <div id="noDataMessage" style="display: none; text-align: center; color: #333; font-size: 16px; margin-top: 25%;">
                            No data available for the selected year.
                        </div>

                        <canvas id="coursePieChart"></canvas>
                    </div>
                </div>


            <div class="col-lg-6" style="margin-top: 20px; padding-right: 40px;">
                    <a href="Print-JobMatch.php" style="color: #333; display: inline-block;">
                        <h6><i class="fa fa-external-link" aria-hidden="true"></i> Job Matching Report for Year & Program Name: </h6>
                    </a>
                <select name="batch_year2" id="batch_year2" class="form-control" style="margin-bottom: 20px;">
                  <option hidden="" id="currentYear3"></option>
                  <!-- dynamically generate this list using server-side logic if needed -->
                  <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year >= 1950; $year--) {
                      echo "<option value='$year'>$year</option>";
                    }
                  ?>
                </select>

                <select name="course1" id="course1" class="form-control">
                  <option value="" selected>All Courses</option>
                </select>
                    <div class="alumnigraph">

                        <div id="noDataMessage3" style="display: none; text-align: center; color: #333; font-size: 16px; margin-top: 25%;">
                            No data available for the selected year.
                        </div>

                        <canvas id="coursePieChart2"></canvas>
                    </div>
                </div>

        <div class="col-lg-12" id="EmploymentTbl" style="margin-top: 20px; width: 98%;">
                <a href="Print-Employment.php" style="color: #333; display: inline-block;">
                    <h6><i class="fa fa-external-link" aria-hidden="true"></i> Employment Status Table Report: </h6>
                </a>

        <div class="details" style="margin-top: -10px;">
            <div class="tblPlacement" style="height: 100%;">

                <?php
                require 'connection.php';

                // Fetch distinct courses and batch years from tbl_alumni
                $course_query = "SELECT DISTINCT Course FROM tbl_alumni ORDER BY Course";
                $batch_year_query = "SELECT DISTINCT Batch_Year FROM tbl_alumni ORDER BY Batch_Year ASC";

                // Get courses
                $course_result = $con->query($course_query);
                $courses = [];
                while ($row = $course_result->fetch_assoc()) {
                    $courses[] = $row['Course'];
                }

                // Get batch years
                $batch_year_result = $con->query($batch_year_query);
                $years = [];
                while ($row = $batch_year_result->fetch_assoc()) {
                    $years[] = $row['Batch_Year'];
                }

                // Generate table for Employed and Unemployed alumni
                foreach (["Employed", "Unemployed"] as $status) {
                    echo "<h6 class='empstats'>$status</h6>";
                    echo "<table style='width: 100%;'>";
                    echo "<thead>
                            <tr>
                                <th class='coursehead' style='color: #fff;'>Courses</th>";
                    // Display years as headers
                    foreach ($years as $year) {
                        echo "<th style='color: #fff; text-align: start; padding-left: 0px;'>$year</th>";
                    }
                    echo "<th style='color: #fff; text-align: start; padding-left: 0px;'>Total</th></tr></thead><tbody>";

                    // Initialize totals for each year
                    $year_totals = array_fill(0, count($years), 0);

                    foreach ($courses as $course) {
                        // Extract course name before the hyphen for display
                        $course_display = explode(' -', $course)[0];  // Split and take the first part before the hyphen
                        
                        echo "<tr><td>$course_display</td>";  // Display only the part before the hyphen
                        $total = 0;
                        foreach ($years as $index => $year) {
                            // Query to fetch the count based on course, batch year, and status
                            $sql = "SELECT COUNT(*) AS count FROM tbl_alumni 
                                    WHERE Course = '$course' AND Batch_Year = $year AND Status = '$status'";
                            $result = $con->query($sql);
                            $count = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['count'] : 0;
                            echo "<td>$count</td>";

                            // Add to the total per year
                            $year_totals[$index] += $count;
                            $total += $count;
                        }
                        echo "<td>$total</td></tr>";
                    }

                    // Display total per year row
                    echo "<tr style='background-color: #FFE6A0;'><td><strong>Total Per Year</strong></td>";
                    foreach ($year_totals as $year_total) {
                        echo "<td><strong>$year_total</strong></td>";
                    }
                    echo "<td><strong>" . array_sum($year_totals) . "</strong></td></tr>";

                    echo "</tbody></table><br>";
                }

                $con->close();
                ?>
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


<!-- Graph For Cvsu Placed Alumni (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const selectYear = document.getElementById('placed_year');
    const ctx = document.getElementById('BatchGraph1').getContext('2d');
    const noDataMessage = document.getElementById('noDataMessage2');
    const totalDisplay = document.getElementById('TotalGrad1');
    let chart = null; // Chart instance

    const updateChart = (selectedYear) => {
        fetch(`fetch_placed_alumni.php?year=${selectedYear}`)
            .then(response => response.json())
            .then(data => {
                if (data.counts.length === 0) {
                    if (chart) {
                        chart.destroy();
                        chart = null;
                    }
                    ctx.canvas.style.display = 'none';
                    noDataMessage.style.display = 'block';
                    totalDisplay.textContent = "0";
                } else {
                    ctx.canvas.style.display = 'block';
                    noDataMessage.style.display = 'none';

                    const counts = data.counts.map(Number);
                    const total = counts.reduce((sum, val) => sum + val, 0);
                    totalDisplay.textContent = total;

                    if (chart) {
                        chart.data.labels = data.years;
                        chart.data.datasets[0].data = counts;
                        chart.update();
                    } else {
                        chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.years,
                                datasets: [{
                                    label: 'Number of Placed Alumni',
                                    data: counts,
                                    backgroundColor: [
                                        '#126A48', '#C89C1D', '#C8D6D3', '#12536A', '#126A25',
                                        '#6A5512', '#6A2E12', '#6A1212', '#6A123B', '#6A124E',
                                        '#12536A', '#126A69', '#716D4A', '#6E6E68'
                                    ],
                                    borderColor: '#ffffff',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function (tooltipItem) {
                                                return `${tooltipItem.raw} alumni`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Years'
                                        }
                                    },
                                    y: {
                                        title: {
                                            display: true,
                                            text: 'Number of Alumni'
                                        },
                                        beginAtZero: true,
                                        ticks: {
                                            precision: 0 // Ensure whole numbers on the y-axis
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

    // Set current year in the dropdown
    const currentYear = new Date().getFullYear();
    document.getElementById('currentYear2').textContent = currentYear;

    updateChart(currentYear);

    selectYear.addEventListener('change', function () {
        const selectedYear = this.value;
        updateChart(selectedYear);
    });
});

</script>

<!-- Graph For Cvsu Graduates (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const batchGraphCtx = document.getElementById('BatchGraph').getContext('2d');
    const noDataMessage = document.getElementById('noDataMessage1');
    const batchYearSelect = document.getElementById('batch_year1');
    const totalGradSpan = document.getElementById('TotalGrad');
    let batchChart;

    // Function to fetch data and update the chart and total graduates
    function fetchBatchData(batchYear) {
        fetch('fetch_batch_data.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `batch_year=${batchYear}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    noDataMessage.style.display = 'block';
                    totalGradSpan.textContent = '0'; // Reset total graduates
                    if (batchChart) batchChart.destroy();
                    return;
                }

                noDataMessage.style.display = 'none';

                const labels = data.map(item => item.year); // Get years
                const counts = data.map(item => item.count); // Get counts

                // **Compute the total graduates**
                const totalGraduates = counts.reduce((sum, count) => sum + count, 0);
                totalGradSpan.textContent = totalGraduates; // Display grand total graduates

                // Generate colors for bars dynamically
                const colors = [
                    '#126A48', '#C89C1D', '#C8D6D3', '#12536A', '#126A25',
                    '#6A5512', '#6A2E12', '#6A1212', '#6A123B', '#6A124E',
                    '#12536A', '#126A69', '#716D4A', '#6E6E68'
                ];
                const backgroundColors = counts.map((_, index) => colors[index % colors.length]); // Cycle colors

                // Destroy the existing chart before creating a new one
                if (batchChart) batchChart.destroy();

                batchChart = new Chart(batchGraphCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Graduates',
                            data: counts,
                            backgroundColor: backgroundColors,
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                title: { display: true, text: 'Batch Year' }
                            },
                            y: {
                                title: { display: true, text: 'Number of Graduates' },
                                beginAtZero: true,
                                ticks: {
                                    callback: function (value) {
                                        return Math.floor(value); // Whole numbers on y-axis
                                    },
                                    stepSize: 1 // Force step size to be at least 1
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return `Graduates: ${Math.floor(context.raw)}`; // Whole numbers in tooltips
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching batch data:', error));
    }

    // Set current year in the dropdown
    const currentYear = new Date().getFullYear();
    document.getElementById('currentYear1').textContent = currentYear;

    // Fetch initial data for the current year
    fetchBatchData(currentYear);

    // Add event listener to dropdown for filtering
    batchYearSelect.addEventListener('change', function () {
        fetchBatchData(this.value);
    });
});

</script>

<!-- PieChart For Job Matching (JS FUNCTION) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const selectYear = document.getElementById('batch_year2');
    const selectCourse = document.getElementById('course1');
    const ctx = document.getElementById('coursePieChart2').getContext('2d');
    const noDataMessage = document.getElementById('noDataMessage3');
    let chart = null;

    // Add "All Batch Year" as the default option
    const defaultYearOption = document.createElement('option');
    defaultYearOption.value = "All Batch Year";
    defaultYearOption.textContent = "All Batch Year";
    selectYear.insertBefore(defaultYearOption, selectYear.firstChild);
    selectYear.value = "All Batch Year";

    // Function to fetch and update chart data
    const updateChart = (selectedYear, selectedCourse) => {
        const yearQuery = selectedYear === "All Batch Year" ? "" : selectedYear; // Empty means all years
        const courseQuery = selectedCourse || ""; // Empty means all courses

        fetch(`fetch_job_matching.php?year=${yearQuery}&course=${courseQuery}`)
            .then(response => response.json())
            .then(data => {
                if (data.total === 0) {
                    // Hide the chart and display the no-data message
                    if (chart) {
                        chart.destroy();
                        chart = null;
                    }
                    ctx.canvas.style.display = 'none';
                    noDataMessage.style.display = 'block';
                } else {
                    // Display the chart and hide the no-data message
                    ctx.canvas.style.display = 'block';
                    noDataMessage.style.display = 'none';

                    const counts = [data.matched, data.unmatched];
                    const total = data.total;
                    const percentages = counts.map(value => ((value / total) * 100).toFixed(2));

                    if (chart) {
                        // Update existing chart data
                        chart.data.labels = [
                            `Matched (${percentages[0]}%)`,
                            `Unmatched (${percentages[1]}%)`
                        ];
                        chart.data.datasets[0].data = counts;
                        chart.update();
                    } else {
                        // Create a new chart if none exists
                        chart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: [
                                    `Matched (${percentages[0]}%)`,
                                    `Unmatched (${percentages[1]}%)`
                                ],
                                datasets: [{
                                    label: 'Job Matching',
                                    data: counts,
                                    backgroundColor: ['#6A5512', '#126A69'],
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
                                                return data.labels.map((label, i) => {
                                                    return {
                                                        text: label,
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
                                                const percentage = total > 0
                                                    ? ((value / total) * 100).toFixed(2)
                                                    : 0;
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

    // Initialize with all years and all courses
    updateChart("All Batch Year", "");

    // Fetch and update course list dynamically
    const fetchCourses = () => {
        fetch("fetch_courses.php")
            .then(response => response.json())
            .then(data => {
                const defaultOption = document.createElement('option');
                defaultOption.value = "";
                defaultOption.textContent = "All Courses";
                selectCourse.appendChild(defaultOption);

                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course;
                    option.textContent = course;
                    selectCourse.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching courses:', error));
    };

    fetchCourses();

    // Update chart on year or course selection change
    selectYear.addEventListener('change', function () {
        const selectedYear = this.value;
        const selectedCourse = selectCourse.value;
        updateChart(selectedYear, selectedCourse);
    });

    selectCourse.addEventListener('change', function () {
        const selectedYear = selectYear.value;
        const selectedCourse = this.value;
        updateChart(selectedYear, selectedCourse);
    });
});

</script>


<!-- PieChart For Employment Status (JS FUNCTION) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectYear = document.getElementById('batch_year');
    const selectCourse = document.getElementById('course');
    const ctx = document.getElementById('coursePieChart').getContext('2d');
    const noDataMessage = document.getElementById('noDataMessage');
    let chart = null;

    const updateChart = (selectedYear, selectedCourse) => {
        const yearQuery = selectedYear === "All Batch Year" ? "" : selectedYear;
        const courseQuery = selectedCourse || "";

        fetch(`fetch_employment_data.php?year=${yearQuery}&course=${courseQuery}`)
            .then(response => response.json())
            .then(data => {
                const counts = data.counts.map(Number);
                const total = counts.reduce((sum, val) => sum + val, 0);

                if (total === 0) {
                    // Handle case when no data is available
                    if (chart) {
                        chart.destroy();
                        chart = null;
                    }
                    ctx.canvas.style.display = 'none';
                    noDataMessage.style.display = 'block';
                } else {
                    ctx.canvas.style.display = 'block';
                    noDataMessage.style.display = 'none';

                    const percentages = counts.map(value =>
                        total > 0 ? ((value / total) * 100).toFixed(2) : 0
                    );

                    if (chart) {
                        chart.data.labels = data.legends.map(
                            (label, i) => `${label} (${percentages[i]}%)`
                        );
                        chart.data.datasets[0].data = counts;
                        chart.update();
                    } else {
                        chart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: data.legends.map(
                                    (label, i) => `${label} (${percentages[i]}%)`
                                ),
                                datasets: [
                                    {
                                        label: 'Employment Status',
                                        data: counts,
                                        backgroundColor: ['#126A48', '#C89C1D'], // Green and Yellow
                                        borderColor: '#ffffff',
                                        borderWidth: 1
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'right'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function (tooltipItem) {
                                                const value = counts[tooltipItem.dataIndex];
                                                return `${value} (${percentages[tooltipItem.dataIndex]}%)`;
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

    // Populate course dropdown dynamically
    const fetchCourses = () => {
        fetch("fetch_courses.php")
            .then(response => response.json())
            .then(courses => {
                selectCourse.innerHTML = '<option value="">All Courses</option>';
                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course;
                    option.textContent = course;
                    selectCourse.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching courses:', error));
    };

    // Initialize with all years and all courses
    fetchCourses();
    updateChart("All Batch Year", "");

    // Update chart on selection change
    selectYear.addEventListener('change', () => {
        updateChart(selectYear.value, selectCourse.value);
    });

    selectCourse.addEventListener('change', () => {
        updateChart(selectYear.value, selectCourse.value);
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