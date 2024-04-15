<?php

require "dbh.php";
session_start();

if (isset($_POST['submit-blog'])) {

    $title = $_POST['blog-title'];
    $metaTitle = $_POST['blog-meta-title'];
    $blogCategoryId = $_POST['blog-category'];
    $blogSummary = $_POST['blog-summary'];
    $blogContent = $_POST['blog-content'];
    $blogTags = $_POST['blog-tags'];
    $blogPath = $_POST['blog-path'];
    $homePagePlacement = $_POST['blog-home-page-placement'];

    $date = date("Y-m-d");
    $time = date("H:i:s");

    if (empty($title)) {
        formError("emptytitle");
    }
    else if (empty($blogCategoryId)) {
        formError("emptycategory");
    }
    else if (empty($blogSummary)) {
        formError("emptysummary");
    }
    else if (empty($blogContent)) {
        formError("emptycontent");
    }
    else if (empty($blogTags)) {
        formError("emptytags");
    }
    else if (empty($blogPath)) {
        formError("emptypath");
    }

    if (strpos($blogPath, " ") !== false) {
        formError("pathcontainsspaces");
    }
    if (empty($homePagePlacement)) {
        $homePagePlacement = 0;
    }

    $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND f_post_status != '2'";
    $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);

    $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND f_post_status != '2'";
    $queryCheckBlogPath = mysqli_query($conn, $sqlCheckBlogPath);

    if (mysqli_num_rows($queryCheckBlogTitle) > 0) {
        formError("titlebeingused");
    }
    else if (mysqli_num_rows($queryCheckBlogPath) > 0) {
        formError("pathbeingused");
    }

    if ($homePagePlacement != 0) {

        $sqliCheckBlogHomePagePlacement = "SELECT * FROM blog_post WHERE n_home_page_placement ='$homePagePlacement' AND f_post_status != '2'";
        $queryCheckBlogHomePagePlacement = mysqli_query($conn, $sqliCheckBlogHomePagePlacement);

        if (mysqli_num_rows($queryCheckBlogHomePagePlacement) > 0) {

            $sqlUpdateBlogPagePlacement = "UPDATE blog_post SET n_home_page_placement ='0' WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";

            if (!mysqli_query($conn, $sqlUpdateBlogPagePlacement)) {
                formError("homepageplacement");
            }
        }
    }

    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt");

    $sqlAddBlog = "INSERT INTO blog_post (n_category_id, v_post_title, v_post_meta_title, v_post_path,
     v_post_summary, v_post_content, v_main_image_url, v_alt_image_url, 
     n_home_page_placement, f_post_status, d_date_created, d_time_created) 
     VALUES ('$blogCategoryId', '$title', '$metaTitle', '$blogPath', 
     '$blogSummary', '$blogContent', '$mainImgUrl', '$altImgUrl','$homePagePlacement', '1', '$date', '$time')";

    if (mysqli_query($conn, $sqlAddBlog)) {

        $blogPostId = mysqli_insert_id($conn);
        $sqlAddTags = "INSERT INTO blog_tags (n_blog_post_id, v_tag) VALUES ('$blogPostId', '$blogTags')";

        if (mysqli_query($conn, $sqlAddTags)) {
            mysqli_close($conn);

            unset($_SESSION['blogTitle']);
            unset($_SESSION['blogMetaTitle']);
            unset($_SESSION['blogCategoryId']);
            unset($_SESSION['blogSummary']);
            unset($_SESSION['blogContent']);
            unset($_SESSION['blogTags']);
            unset($_SESSION['blogPath']);
            unset($_SESSION['blogHomePagePlacement']);


            header("Location: ../blogs.php?addblog=success");
            exit();
        }
        else {
            formError("sqlerror");
        }

        
    }
    else {
        formError("sqlerror");
    }

}
else {
    header("Location: ../index.php");
    exit();
}

function formError($errorCode) {

    require "dbh.php";

    $_SESSION['blogTitle'] = $_POST['blog-title'];
    $_SESSION['blogMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['blogCategoryId'] = $_POST['blog-category'];
    $_SESSION['blogSummary'] = $_POST['blog-summary'];
    $_SESSION['blogContent'] = $_POST['blog-content'];
    $_SESSION['blogTags'] = $_POST['blog-tags'];
    $_SESSION['blogPath'] = $_POST['blog-path'];
    $_SESSION['blogHomePagePlacement'] = $_POST['blog-home-page-placement'];

    mysqli_close($conn);
    header("Location: ../write-a-blog.php?addblog=".$errorCode);
    exit();
}

function uploadImage($img, $imgName, $imgType) {

    $imgUrl = "";

    $validExt = array("jpg", "jpeg", "png", "bmg", "gif");

    if ($img == "") {
        formError("empty".$imgType."image");
    }
    else if ($_FILES[$imgName]["size"] <= 0) {
        formError($imgType."imageerror");
    }
    else {

       
        $explode = explode(".", $img);
        $ext = strtolower(end($explode));

       
       if (!in_array($ext, $validExt)) {
            formError("invalidtype".$imgType."image");
        }

        $folder = "../images/blog-images/";
        $imgNewName = rand(10000, 990000).'_'.time().'.'.$ext;
        $imgPath = $folder.$imgNewName;

      
        if (move_uploaded_file($_FILES[$imgName]['tmp_name'], $imgPath)) {
            $imgUrl = "http://localhost/blog/admin/images/blog-images/".$imgNewName;
        }
        else {
            formError("erroruploading".$imgType."image");
        }
    }
    
    return $imgUrl;
}