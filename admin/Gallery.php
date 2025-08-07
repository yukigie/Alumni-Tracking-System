<?php require 'connection.php';
session_start();

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
    <title>Admin | Gallery</title>
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

function validateImage($image, $allowedExtensions, $maxSize) {
    $fileName = $image["name"];
    $fileSize = $image["size"];
    $tmpName = $image["tmp_name"];
    $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($imageExtension, $allowedExtensions)) {
        alert("Invalid Image Extension", "Please upload a JPG, JPEG, or PNG image.", "warning");
        return false;
    } elseif ($fileSize > $maxSize) {
        alert("Image Size Too Large", "Please upload an image smaller than 10MB.", "warning");
        return false;
    }

    $newImageName = uniqid() . '.' . $imageExtension;
    move_uploaded_file($tmpName, 'img/' . $newImageName);

    return $newImageName;
}

// Insert Data
if (isset($_POST['insertdata'])) {
    $Title = $_POST['Title'];
    $BatchYear = $_POST['BatchYear'];
    $Description = $_POST['Description'];

    $check = mysqli_query($con, "SELECT * FROM tbl_gallery WHERE Title='$Title'");
    
    if (mysqli_num_rows($check) > 0) {
        setAlert("Already Exist!", "The Title You Entered Is Already On The List!", "warning");
    } else {
        if ($_FILES["Image"]["error"] === 4) {
            setAlert("Image Does Not Exist", "Please upload an image.", "warning");
        } else {
            $newImageName = validateImage($_FILES["Image"], ['jpg', 'jpeg', 'png'], 10000000);

            if ($newImageName) {
                $query = "INSERT INTO tbl_gallery (Title, Image, BatchYear, Description) VALUES ('$Title', '$newImageName', '$BatchYear', '$Description')";
                
                if (mysqli_query($con, $query)) {
                    setAlert("Successfully Added!", "A New Gallery Has Been Successfully Added!", "success");
                    header("Location: Gallery.php");
                    exit;
                } else {
                    setAlert("Data Not Saved", "Failed to save the data.", "error");
                    header("Location: Gallery.php");
                    exit;
                }
            }
        }
    }
}

// Update Data
if (isset($_POST['updatedata'])) {
    $ID = $_POST['update_id'];
    $UP_Title = $_POST['UP_Title'];
    $UP_BatchYear = $_POST['UP_BatchYear'];
    $UP_Description = $_POST['UP_Description'];
    
    // Check if the title already exists in another record
    $check = mysqli_query($con, "SELECT * FROM tbl_gallery WHERE Title='$UP_Title' AND Gallery_ID != '$ID'");
    
    if (mysqli_num_rows($check) > 0) {
        setAlert("Already Exist!", "The Title You Entered Is Already On The List!", "warning");
    } else {
        $newImageName = $_FILES["Image1"]["error"] === 4 ? mysqli_fetch_assoc(mysqli_query($con, "SELECT Image FROM tbl_gallery WHERE Gallery_ID = '{$ID}'"))['Image'] : validateImage($_FILES["Image1"], ['jpg', 'jpeg', 'png'], 10000000);

        if ($newImageName) {
            $query = "UPDATE tbl_gallery SET Title = '{$UP_Title}', Image = '{$newImageName}', BatchYear = '{$UP_BatchYear}', Description = '{$UP_Description}' WHERE Gallery_ID = '{$ID}'";
            
            if (mysqli_query($con, $query)) {
                setAlert("Successfully Updated!", "Gallery has been successfully updated!", "success");
                header("Location: Gallery.php");
                    exit;
            } else {
                setAlert("Data Not Updated!", "Failed to update the gallery.", "error");
                header("Location: Gallery.php");
                    exit;
            }
        }
    }
}

// Delete Data
if (isset($_POST['deletedata'])) {
    $Gallery_ID = $_POST['delete_id'];
    $sql = "DELETE FROM tbl_gallery WHERE Gallery_ID=$Gallery_ID";
    
    if ($con->query($sql)) {
        setAlert("Successfully Deleted!", "Gallery Is Successfully Deleted!", "success");
        header("Location: Gallery.php");
        exit;
    } else {
        setAlert("Data Not Deleted!", "Failed to delete the gallery.", "error");
        header("Location: Gallery.php");
        exit;
    }
}

mysqli_close($con);

?>



        
 <!-- Add Modal -->
<div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Add Gallery</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Gallery Details</h5>

      <form action="Gallery.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off">

        <div class="modal-body">
          <div class="form-group">
            <label>Title:</label>
            <input type="text" name="Title" id="Title" class="form-control" placeholder="Enter Title" required>
          </div>

          <div class="row justify-content-center">
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Batch Year:</label>
                <select name="BatchYear" id="BatchYear" class="form-control">
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
                <label>Image:</label>
                <input type="file" name="Image" id="Image" accept=".jpg, .jpeg, .png" required class="form-control">
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Description:</label>
                <textarea name="Description" id="Description" class="form-control" placeholder="Enter Description" required></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary addbtn" name="insertdata">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Update Gallery</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Gallery Details</h5>

      <form action="Gallery.php" method="POST" enctype="multipart/form-data" class="form_body" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="update_id" id="update_id">

          <div class="form-group">
            <label>Title:</label>
            <input type="text" name="UP_Title" id="UP_Title" class="form-control" placeholder="Enter Title" required>
          </div>

          <div class="row justify-content-center">
            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Batch Year:</label>
                <select name="UP_BatchYear" id="UP_BatchYear" class="form-control">
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
                <label>Image:</label>
                <input type="file" name="Image1" id="Image1" accept=".jpg, .jpeg, .png" class="form-control">
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group" style="margin-top: 20px;">
                <label>Description:</label>
                <textarea name="UP_Description" id="UP_Description" class="form-control" placeholder="Enter Description" required></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="updatedata" class="btn btn-primary upbtn">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="font-weight: 600;">Delete Gallery</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <h5>Confirm Message</h5>

      <form action="Gallery.php" method="POST" class="form_body" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id">
          <h4>Are you sure you want to Delete this?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="submit" name="deletedata" class="btn btn-primary delbtn">Yes, Delete it</button>
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
        <li class="dropdown" style="margin-top: 85%;">
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

    <!-- <section class="home">
        <div class="toggle-sidebar">
            <i class='bx bx-menu-alt-left' ></i>
        </div>

        <div class="head-title">
            <h2>Dashboard Page</h2>
        </div>
    </section> -->

    <section class="home">

        <div class="head-title">
            <h2>Gallery</h2>
            <p><a href="">Dashboard</a> / Gallery</p>
        </div>

        <div class="container_my-5">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" id="new" data-bs-toggle="modal" data-bs-target="#AddModal">
        <i class="fa fa-plus-circle" aria-hidden="true"></i><span>NEW</span>
    </button>

    <br><br>
    <div class="table-responsive">
        <table class="table table-bordered" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Ref. ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Picture</th>
                    <th scope="col">Batch Year</th>
                    <th scope="col">Description</th>     
                    <th scope="col">Tools</th>                  
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';

                $sql = "SELECT * FROM tbl_gallery";
                $result = $con->query($sql);

                if (!$result) {
                    die("Invalid query: " . $con->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[Gallery_ID]</td>
                        <td>$row[Title]</td>
                        <td><img src='img/$row[Image]' width='50' height='50'></td>
                        <td>$row[BatchYear]</td>
                        <td>$row[Description]</td>
                        <td>
                            <button type='button' class='btn btn-primary btn-sm editbtn' id='edit'>
                                <i class='fa fa-pencil' aria-hidden='true'></i><span>EDIT</span>
                            </button>
                            <button type='button' class='btn btn-danger btn-sm deletebtn' id='delete'>
                                <i class='fa fa-minus-circle' aria-hidden='true'></i><span>DELETE</span>
                            </button>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


    </section>

<!--  JS SECTION AND LINKS -->
<script src="js/main.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

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
        // Initialize DataTable
        const table = $('#myTable').DataTable({
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

        // Event delegation for .editbtn within #myTable to support pagination
        $('#myTable').on('click', '.editbtn', function () {
            // Show the DeclinedModal
            $('#EditModal').modal('show');

            // Get data from the closest row
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

            console.log(data);

            // Populate modal fields with data from the selected row
            $('#update_id').val(data[0]);
            $('#UP_Title').val(data[1]);
            $('#UP_BatchYear').val(data[3]);
            $('#UP_Description').val(data[4]);
        });
    });
</script>

<!-- DELETE POP UP FORM (JS FUNCTION) -->

<script>
    $('#myTable').on('click', '.deletebtn', function () {
            $('#DeleteModal').modal('show');
            const data = $(this).closest('tr').children("td").map(function () {
                return $(this).text();
            }).get();

          $('#delete_id').val(data[0]);
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