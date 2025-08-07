<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CvSU Alumni Tracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="main_style.css">


    <link rel="shortcut icon" type="text/css" href="admin/admin_img/cvsu-logo.png">
</head>
<body>

    <header>
        <a href="admin/login-user.php" class="logo"><img src="admin/admin_img/logo-orange.png" width="50" height="50">
            <span>CvSU Imus Campus Alumni Tracker</span></a>

        <nav class="navbar">
            <ul>
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#gallery">Gallery</a></li>
                <li><a href="#partnership">Partnership</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="admin/index/login-user.php" class="signinbtn">Sign In</a></li>
            </ul>
            
        </nav>

        <div class="fa fa-bars"></div>
    </header>

<!-- HOME CONTENT SECTION -->

<section class="home" id="home">
    
    <div class="content">
        <h1>Welcome to the CVSU Alumni Tracker!</h1>
        <p>Reconnect with your alma mater and explore exciting career opportunities! <br>This platform is designed to support CVSU alumni in their job search, offer career advice, and foster a strong alumni network.</p>

        <h6 style="font-size: 12px; margin-bottom: 5px; color: #333;">Start Reconnecting here:</h6>
        <a href="admin/index/UserType.php"><button>Sign up</button></a>
    </div>

    <div class="box-container">
        
        <div class="box">
            <i class="fa fa-link"></i>
            <h3>Connect</h3>
            <p>Building a bridge between alumni and career opportunities for lasting success.</p>
        </div>

        <div class="box">
            <i class="fa fa-award"></i>
            <h3>Empower</h3>
            <p>Bringing together alumni, employers, and the university to foster collaboration and success.</p>
        </div>

        <div class="box">
            <i class="fa fa-handshake"></i>
            <h3>Engage</h3>
            <p>Strengthening connections between alumni and the university community to create lasting connections.</p>
        </div>

    </div>

</section>

<!-- ABOUT SECTION PART -->

<section class="about" id="about">
    
    <h1 class="heading">About us</h1>
    <h3 class="title">Getting to know about CvSU</h3>

    <div class="row">
        
        <div class="content">
            <h3>Preserving the Legacy of Cavite State University Alumni</h3>
            <p>Cavite State University’s vision of being the premier university in historic Cavite, prompted the launching of the College of Business and Entrepreneurship in Imus on August 15, 2003.</p>
            <a href="about.php"><button>See more</button></a>
        </div>

        <div class="image">
            <img src="admin/admin_img/about-img.jpg">
        </div>

    </div>
</section>

<!-- GALLERY SECTION PART -->

<section class="gallery" id="gallery">

    <h1 class="heading">Alumni Gallery</h1>
    <h3 class="title">Celebrating Moments of Alumni Excellence</h3>

    <div class="card-container">
        <?php

        require "admin/connection.php";

                $query = "SELECT * FROM tbl_gallery LIMIT 4";
                $query_run = mysqli_query($con, $query);
                 $check_data = mysqli_num_rows($query_run) > 0;

                if($check_data)
                    {

                while($row = mysqli_fetch_assoc($query_run))
                    {
            ?>

        <div class="card">
            <img src="admin/img/<?php echo $row['Image']; ?>">
            <h3><?php echo $row['Title']; ?></h3>
            <p class="event-descript"><?php echo $row['Description']; ?></p>
            <div class="icons">
                <a href="gallery.php"><button>See more</button></a>
            </div>
        </div>

        <?php

                  }

              } else {
                echo '<h2 class="title">No Gallery Available.<h2>';
              }

            ?>

    </div>

</section>

<!-- PARTNERSHIP SECTION PART -->

<section class="partnership" id="partnership">
    <h1 class="heading">Partnership</h1>
    <h3 class="title">Collaborating for Opportunities</h3>

    <div class="box-container">

    <!-- Swiper Container -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php
            require "admin/connection.php";

            $query = "SELECT * FROM tbl_employer";
            $query_run = mysqli_query($con, $query);
            $check_data = mysqli_num_rows($query_run) > 0;

            if ($check_data) {
                while ($row = mysqli_fetch_assoc($query_run)) {
                    $industry = $row['Industry'];
                    $industry_parts = explode('-', $industry);
                    $industry_display = trim($industry_parts[0]);
            ?>
            <!-- Swiper Slide -->
            <div class="swiper-slide">
                <div class="box">
                    <img src="admin/index/css/img/<?php echo $row['Image']; ?>">
                    <h3><?php echo $row['Company_Name']; ?></h3>
                    <p><?php echo $industry_display; ?></p>
                    <div class="case">
                        <i class="fa fa-briefcase"></i>
                        <i class="fa fa-briefcase"></i>
                        <i class="fa fa-briefcase"></i>
                    </div>
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
    </div>
</div>
</section>


<!-- CONTACT SECTION PART -->

<section class="contact" id="contact">

    <div class="row">
        
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

        <div class="bottom-txt">
            <p>© 2024 Cavite State University - Imus Campus All Rights Reserved.</p>
        </div>

        <div class="image">
            <img src="admin/admin_img/logo-white.png">
        </div>

    </div>
</section>




<!--  JS SECTION AND LINKS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper('.swiper-container', {
        loop: true, // Enable infinite loop
        autoplay: {
            delay: 1000, // Change slide every 3 seconds
            disableOnInteraction: false, // Keep autoplay running after user interacts
        },
        slidesPerView: 1, // Default: one slide at a time
        spaceBetween: 20, // Space between slides
        centeredSlides: true, // Center the active slide in the container
        breakpoints: {
            768: {
                slidesPerView: 2, // Medium screens: show 2 slides
                spaceBetween: 30,
            },
            1080: {
                slidesPerView: 4, // Large screens: show 3 slides
                spaceBetween: 30,
            },
        },
    });
</script>


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