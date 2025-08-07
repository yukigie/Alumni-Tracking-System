<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CvSU Alumni Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="main_style.css">
    
    
    <link rel="shortcut icon" type="text/css" href="admin/admin_img/cvsu-logo.png">
</head>
<body>

    <header>
        <a href="admin/login-user.php" class="logo" style="margin-top: -8px;"><img src="admin/admin_img/logo-orange.png" width="50" height="50">
            <span>CvSU Imus Campus Alumni Tracker</span></a>

        <nav class="navbar" style="padding: 1rem;">
            <ul style="margin-top: 10px;">
                <li><a href="index.php" style="text-decoration: none;">Home</a></li>
                <li><a href="about.php" style="text-decoration: none;">About</a></li>
                <li><a href="#gallery" class="active" style="text-decoration: none;">Gallery</a></li>
                <li><a href="index.php" style="text-decoration: none;">Partnership</a></li>
                <li><a href="#contact" style="text-decoration: none;">Contact</a></li>
                <li><a href="admin/index/login-user.php" class="signinbtn" style="text-decoration: none;">Sign In</a></li>
            </ul>
            
        </nav>

        <div class="fa fa-bars"></div>
    </header>

<!-- HOME CONTENT SECTION -->

<section class="home">
    
    <div class="content">
        <h1><b>Celebrating Moments of Alumni Excellence</b></h1>
        <p>Dive into a gallery that highlights the growth, achievements, and enduring connections of our alumni, and Experience the vibrant spirit of our alumni through photos that narrate their journey and successes.</p>
    </div>

    <div class="box-container">
        
        <div class="box">
            <i class="fa fa-image" aria-hidden="true"></i>
            <h3><b>Celebrate</b></h3>
            <p>Experience the vibrant spirit of our alumni through photos that narrate their journey and successes.</p>
        </div>

    </div>

</section>

<!-- ABOUT SECTION PART -->

<section class="about" id="gallery">
    
    <h1 class="heading">Gallery</h1>
    <h3 class="title">Honoring Achievements Through Imagery</h3>

    <div class="row">
        <?php

        require "admin/connection.php";

                $query = "SELECT * FROM tbl_gallery";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

    <div class="col-lg-6">
        <div class="content" id="gallery-details">
            <h3><?php echo $row['Title']; ?></h3>
            <h5 style="font-weight: 600;">Batch <?php echo $row['BatchYear']; ?></h5>
            <p><?php echo $row['Description']; ?></p>
        </div>
    </div>


    <div class="col-lg-6">
        <div class="image">
            <img src="admin/img/<?php echo $row['Image']; ?>">
        </div>
    </div>

         <?php

                  }

              } else {
                echo '<h2 class="title">No Gallery Available.<h2>';
              }
              mysqli_close($con);

            ?>

    </div>
</section>


<!-- CONTACT SECTION PART -->

<section class="contact" id="contact" style="margin-top: 2rem;">

    <div class="row">
        <div class="col-lg-6">
        <div class="content">
            <h3>Contact Us</h3>
            <p><i class="fa fa-map-marker" aria-hidden="true"></i> Cavite Civic Center, Palico IV, Imus City, Cavite 4103</p>
            <p><i class="fa fa-phone" aria-hidden="true"></i> (046) 471-6607</p>

            <div class="icons">
                <a href=""><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                <a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a>
                <a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
            </div>

            <p class="hide-txt">© 2024 Cavite State University - Imus Campus All Rights Reserved.</p>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="image">
            <img src="admin/admin_img/logo-white.png">
        </div>
    </div>

    <div class="col-lg-12" style="margin-top: -15%; padding: 0rem; margin-left: -50px;">
        <div class="bottom-txt">
            <p>© 2024 Cavite State University - Imus Campus All Rights Reserved.</p>
        </div>
    </div>

    </div>
</section>



<!--  JS SECTION AND LINKS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"crossorigin="anonymous"></script>

<!-- NAVIGATION BAR (JS FUNCTION) -->
<script>
    $(document).ready(function(){

    $('.fa-bars').click(function(){
        $(this).toggleClass('fa-times');
        $('.navbar').toggleClass('nav-toggle');
    });

    $(window).on('scroll load', function(){

        $('.fa-bars').removeClass('fa-times');
        $('.navbar').removeClass('nav-toggle');

        if($(window).scrollTop() > 30){
            $('header').addClass('header-active');
        } else {
            $('header').removeClass('header-active');
        }

        $('section').each(function(){
            var id = $(this).attr('id');
            var height = $(this).height();
            var offset = $(this).offset().top - 200;
            var top = $(window).scrollTop();
            if(top >= offset && top < offset + height){
                $('.navbar ul li a').removeClass('active');
                $('.navbar').find('[href="#' + id + '"]').addClass('active');
            }

        });
    });
});
</script>
    
</body>
</html>