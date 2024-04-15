<?php 

require "admin/includes/dbh.php";

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>About - BestTravel</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
    <script defer src="js/fontawesome/all.min.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

</head>

<body id="top">

    <div id="preloader"> 
    	<div id="loader"></div>
    </div>

    <?php include "header-opaque.php"; ?>

    <section class="s-content">
        <div class="row">
            <div class="column large-12">
                <article class="s-content__entry">
                    <div class="s-content__media">
                        <img src="images/thumbs/about/about-1050.jpg" 
                                srcset="images/thumbs/about/about-2100.jpg 2100w, 
                                        images/thumbs/about/about-1050.jpg 1050w, 
                                        images/thumbs/about/about-525.jpg 525w" 
                                        sizes="(max-width: 2100px) 100vw, 2100px" alt="">
                    </div> 
                    <div class="s-content__entry-header">
                        <h1 class="s-content__title">Learn More About Story.</h1>
                    </div> 

                    <div class="s-content__primary">
                        <div class="s-content__page-content">
                            <p class="lead">
                            We carefully select the best hotels, restaurants and places to visit and 
                            make suggestions for you to have a nice holiday experience. 
                            I hope you like the suggestions and get back to us.
                            </p>
                            <p>
                            All selected in detail for you.
                            </p> 
                            <p>
                            Enjoy!
                            </p>
                            <br>
                        </div>
                        </div> <!-- end s-entry__page-content -->
                    </div> <!-- end s-content__primary -->
                </article> <!-- end entry -->
            </div> <!-- end column -->
        </div> <!-- end row -->
    </section> <!-- end s-content -->

    <?php include "footer.php"; ?>

    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>