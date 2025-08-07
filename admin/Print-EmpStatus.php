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
    <title>Alumni | Print Employment Status</title>
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
        <a href="Report.php" style="margin-top: -10px; color: #C2AE44;"><span><i class='bx bx-chevron-left'></i></span> Return to Report</a>
        <div class="profile_content" style="margin-top: 20px;">
        <h5>Print Table <span><i class="fa fa-print" aria-hidden="true"></i></span></h5>

        <div class="row justify-content-center">

            <div class="col-lg-12">
    <div class="form-group" style="margin-top: 10px;">
        <label style="margin-bottom: 10px; display: block;">Filter By <i class="fa fa-sort-amount-desc" aria-hidden="true"></i></label>
        <form method="GET" id="filterForm">
            <select name="filter_year" id="filter_year" class="form-control" style="display: inline; width: 50%;">
                <option value="all" <?php echo (!isset($_GET['filter_year']) || $_GET['filter_year'] === 'all') ? 'selected' : ''; ?>>All Batch Years</option>
                <?php
                $currentYear = date("Y");
                $selectedYear = isset($_GET['filter_year']) ? $_GET['filter_year'] : 'all';
                for ($year = $currentYear; $year >= 1950; $year--) {
                    echo "<option value='$year'" . ($selectedYear == $year ? ' selected' : '') . ">$year</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary" name="submit" id="submit" style="display: inline; margin-left: 5px;">Submit</button>
        </form>
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
    <div class="quote" style="width: 500px; font-size: 14px; color: #555; margin: 0 auto;">
        <p><i>Cavite State University - Imus Campus Alumni Tracer System, where we help our alumni stay connected, celebrate their successes, and contribute to our community's growth and development.</i></p>
    </div>

<h2>Employment Status Report</h2>
<div class="graph" style="height: 400px; margin-top: -20px; display: flex; justify-content: center; align-items: center;">
<canvas id="myPieChart" width="500" height="500"></canvas>
</div>
 <p style="margin-top: -30px;">Employment Status Percentage Chart</p>


    <table id="alumni-tbl" style="border-collapse: collapse; width: 100%; margin-top: 70px;">
    <tr>
        <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">No.</th>
        <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Program Name</th>
        <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Employed Alumni</th>
        <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Percentage</th>
        <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Unemployed Alumni</th>
        <th style="border: 1px solid #ddd; padding: 8px; padding-top: 12px; padding-bottom: 12px; background-color: green; color: white;">Percentage</th>
    </tr>
    <?php
    require 'connection.php';

    // Retrieve selected batch year from GET request
    $filterYear = isset($_GET['filter_year']) ? $_GET['filter_year'] : 'all';

    // Modify query based on the filter
    $whereClause = ($filterYear !== 'all') ? "WHERE Batch_Year = '$filterYear'" : '';

    // SQL query to count employed and unemployed alumni by course
    $sql = "
        SELECT 
            Course, 
            SUM(CASE WHEN Status = 'Employed' THEN 1 ELSE 0 END) AS employed,
            SUM(CASE WHEN Status = 'Unemployed' THEN 1 ELSE 0 END) AS unemployed
        FROM tbl_alumni
        $whereClause
        GROUP BY Course 
        ORDER BY Course ASC";
    
    $result = $con->query($sql);

    // Calculate total alumni for percentage computation
    $total_query = "SELECT COUNT(*) AS total_alumni FROM tbl_alumni $whereClause";
    $total_result = $con->query($total_query);
    $grand_total = ($total_result && $total_result->num_rows > 0) ? $total_result->fetch_assoc()['total_alumni'] : 1; // Avoid division by 0

    // Initialize overall counts for final total row
    $overall_employed = 0;
    $overall_unemployed = 0;

    if ($result && $result->num_rows > 0) {
        $no = 1; // Row counter
        while ($row = $result->fetch_assoc()) {
            $course_name = $row['Course'];
            $display_name = explode('-', $course_name)[0]; // Text before the hyphen
            $employed_count = $row['employed'];
            $unemployed_count = $row['unemployed'];

            // Calculate percentages
            $employed_percentage = ($employed_count / $grand_total) * 100;
            $unemployed_percentage = ($unemployed_count / $grand_total) * 100;

            // Accumulate totals for the last row
            $overall_employed += $employed_count;
            $overall_unemployed += $unemployed_count;

            // Display the row
            echo "
            <tr>
                <td style='border: 1px solid #ddd; padding: 8px;'>$no</td>
                <td style='border: 1px solid #ddd; padding: 8px;'>$display_name</td>
                <td style='border: 1px solid #ddd; padding: 8px;'>$employed_count</td>
                <td style='border: 1px solid #ddd; padding: 8px;'>" . number_format($employed_percentage, 2) . "%</td>
                <td style='border: 1px solid #ddd; padding: 8px;'>$unemployed_count</td>
                <td style='border: 1px solid #ddd; padding: 8px;'>" . number_format($unemployed_percentage, 2) . "%</td>
            </tr>";
            $no++;
        }

        // Add total row at the bottom
        $total_percentage = (($overall_employed + $overall_unemployed) > 0) ? ($overall_employed / ($overall_employed + $overall_unemployed)) * 100 : 0;

        echo "
        <tr style='font-weight: bold; background-color: #f0f0f0;'>
            <td colspan='2' style='border: 1px solid #ddd; padding: 8px; text-align: center;'>Grand Total</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>$overall_employed</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>" . number_format(($overall_employed / $grand_total) * 100, 2) . "%</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>$overall_unemployed</td>
            <td style='border: 1px solid #ddd; padding: 8px;'>" . number_format(($overall_unemployed / $grand_total) * 100, 2) . "%</td>
        </tr>";
    } else {
        echo "<tr><td colspan='6' style='border: 1px solid #ddd; padding: 8px; text-align: center;'>No records found</td></tr>";
    }

    $con->close();
    ?>
</table>
<p style="text-align: left; font-weight: 600; margin-top: 20px;">Prepared By. </p>
</div>

                </textarea>
                
              </div>
            </div>

        </div>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- // Rich textbox Editor For Content (JS FUNCTION) -->
<script>
tinymce.init({
    selector: '#default', // Replace with your TinyMCE selector
    height: 1000,
    plugins: 'lists link image advtemplate print',
    toolbar: 'undo redo | bold italic | bullist numlist | link image | advtemplate | print',
    valid_elements: '*[*]', // Allow all elements and attributes
    extended_valid_elements: 'canvas[id|width|height|style|class]', // Ensure <canvas> is allowed
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
            initializePieChart(); // Render the chart after insertion
        });
    }
});

// Function to initialize the pie chart
function initializePieChart() {
    const editor = tinymce.get('default'); // Access TinyMCE editor instance
    const contentDocument = editor.getDoc(); // Access editor content's document
    const canvasId = 'myPieChart'; // Define the canvas ID

    // Dynamically add canvas and "no data" message if they don't exist
    let canvas = contentDocument.getElementById(canvasId);

    if (!canvas) {
        const container = contentDocument.body; // TinyMCE content container
        container.innerHTML = `
            <div>
                <canvas id="${canvasId}" style="width: 100%; height: 400px;"></canvas>
                <div id="noDataMessage" style="display: none; color: red; text-align: center;">No data available for the selected year.</div>
            </div>
        ` + container.innerHTML;
    }

    // Re-fetch elements now that they've been added
    canvas = contentDocument.getElementById(canvasId);

    const ctx = canvas.getContext('2d');
    let chart = null; // Initialize chart instance

    // Get the selected year from the URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const selectedYear = urlParams.get('filter_year') || 'all'; // Default to 'all' if no year selected

    // Fetch data from the backend
    fetch(`fetch_employment_chart.php?year=${selectedYear}`)
        .then(response => response.json())
        .then(data => {
            if (!data.counts || data.counts.length === 0) {
                // No data available
                if (chart) {
                    chart.destroy(); // Destroy existing chart
                    chart = null;
                }

                // Hide chart and show no data message
                canvas.style.display = 'none';
            } else {
                // Show chart and hide no data message
                canvas.style.display = 'block';

                // Assuming the counts array contains the values [employed, unemployed]
                const counts = data.counts.map(Number); // Ensure numeric data
                const legends = data.legends;

                // Calculate percentages for employed and unemployed
                const total = counts.reduce((sum, val) => sum + val, 0);
                const percentages = counts.map(value => total > 0 ? ((value / total) * 100).toFixed(2) : 0);

                // Update chart or create a new one
                if (chart) {
                    chart.data.labels = legends;
                    chart.data.datasets[0].data = counts;
                    chart.update();
                } else {
                    chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: legends,
                            datasets: [{
                                label: 'Employment Status',
                                data: counts,
                                backgroundColor: [
                                    '#126A48', // Employed
                                    '#C89C1D'  // Unemployed
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
                                            const total = dataset.data.reduce((sum, val) => sum + val, 0);
                                            return data.labels.map((label, i) => {
                                                const value = dataset.data[i];
                                                const percentage = total > 0
                                                    ? ((value / total) * 100).toFixed(2)
                                                    : 0;
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
                                            const value = data.counts[tooltipItem.dataIndex];
                                            const total = data.counts.reduce((sum, val) => sum + val, 0);
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