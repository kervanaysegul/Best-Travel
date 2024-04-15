<?php 

require "admin/includes/dbh.php";

if (isset($_REQUEST['group'])) {

    $categoryPath = $_REQUEST['group']; 

    $sqlGetCategory = "SELECT * FROM blog_category WHERE v_category_path = '$categoryPath'";
    $queryGetCategory = mysqli_query($conn, $sqlGetCategory);

    if ($rowGetCategory = mysqli_fetch_assoc($queryGetCategory)) {
        $categoryId = $rowGetCategory['n_category_id'];
        $categoryTitle = $rowGetCategory['v_category_title'];
        $categoryMetaTitle = $rowGetCategory['v_category_meta_title'];
    }
    else {
        header("Location: index.php");
        exit();
    }

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>BestTravel | <?php echo $categoryMetaTitle; ?></title>
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


    <!-- preloader
    ================================================== -->
    <div id="preloader"> 
    	<div id="loader"></div>
    </div>


    <?php include "header-opaque.php"; ?>


    <!-- content
    ================================================== -->
    <section class="s-content">


        <!-- page header
        ================================================== -->
        <div class="s-pageheader">
            <div class="row">
                <div class="column large-12">
                    <h1 class="page-title">
                        <span class="page-title__small-type">Category</span>
                        <?php echo $categoryTitle; ?>
                    </h1>
                </div>
            </div>
        </div> <!-- end s-pageheader-->
        

        <!-- masonry
        ================================================== -->
        <div class="s-bricks s-bricks--half-top-padding">

            <div class="masonry">
                <div class="bricks-wrapper h-group">

                    <div class="grid-sizer"></div>

                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                    <?php 
                    
                    $sqlGetAllBlogs = "SELECT * FROM blog_post WHERE n_category_id = '$categoryId' AND f_post_status = '1' ORDER BY n_blog_post_id DESC";
                    $queryGetAllBlogs = mysqli_query($conn, $sqlGetAllBlogs);

                    while ($rowGetAllBlogs = mysqli_fetch_assoc($queryGetAllBlogs)) {

                        $blogTitle = $rowGetAllBlogs['v_post_title'];
                        $blogPath = $rowGetAllBlogs['v_post_path'];
                        $blogSummary = $rowGetAllBlogs['v_post_summary'];
                        $blogAltImageUrl = $rowGetAllBlogs['v_alt_image_url'];
                    
                    ?>
                    
                    <article class="brick entry" data-aos="fade-up">
    
                        <div class="entry__thumb">
                            <a href="single-blog.php?blog=<?php echo $blogPath; ?>" class="thumb-link">
                                <img src="<?php echo $blogAltImageUrl; ?>" 
                                     srcset="<?php echo $blogAltImageUrl; ?> 1x, <?php echo $blogAltImageUrl; ?> 2x" alt="">
                            </a>
                        </div> <!-- end entry__thumb -->
    
                        <div class="entry__text">
                            <div class="entry__header">
                                <h1 class="entry__title"><a href="single-blog.php?blog=<?php echo $blogPath; ?>"><?php echo $blogTitle; ?></a></h1>
                                
                                <div class="entry__meta">
                                    <span class="byline">By:
                                        <span class='author'>
                                            <a href="https://www.dreamhost.com/r.cgi?287326">Best Travel</a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="entry__excerpt">
                                <p><?php echo $blogSummary; ?></p>
                            </div>
                            <a class="entry__more-link" href="single-blog.php?blog=<?php echo $blogPath; ?>">Read Blog</a>
                        </div> <!-- end entry__text -->
                    
                    </article> <!-- end article -->

                    <?php } ?>

                </div> <!-- end brick-wrapper -->
            </div> <!-- end masonry -->
        </div> <!-- end s-bricks -->
    </section> <!-- end s-content -->


    <?php include "footer.php"; ?>

    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>

<?php 

}
else {
    header("Location: index.php");
    exit();
}

?>