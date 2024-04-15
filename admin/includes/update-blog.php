<?php

require "dbh.php";
session_start();

if (isset($_POST['submit-edit-blog'])) {

    $blogId = $_POST['blog-id'];
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

    $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND v_post_title != '$title' AND f_post_status != '2'";
    $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);

    $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND v_post_path != '$blogPath' AND f_post_status != '2'";
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

    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main", "v_main_image_url");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt", "v_alt_image_url");

    if ($mainImgUrl == "noupdate") {
        if ($altImgUrl == "noupdate") {
            $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title ='$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
        }
        else {
            $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title ='$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_alt_image_url = '$altImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
        }

    }
    else if ($altImgUrl == "noupdate") {
        if ($mainImgUrl != "noupdate") {
            $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title ='$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_main_image_url = '$mainImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
        }

    }
    else {
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title ='$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_main_image_url = '$mainImgUrl', v_alt_image_url = '$altImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    }

    $sqlUpdateBlogTags = "UPDATE blog_tags SET v_tag = '$blogTags' WHERE n_blog_post_id = '$blogId'";

    if (mysqli_query($conn, $sqlUpdateBlog) && mysqli_query($conn, $sqlUpdateBlogTags)) {
        formSuccess();
    }
    else {
        formError("sqlerror");
    }

}
else {
    header("Location: ../index.php");
    exit();
}

function formSuccess() {

    require "dbh.php";
    mysqli_close($conn);

    unset($_SESSION['editBlogId']);
    unset($_SESSION['editTitle']);
    unset($_SESSION['editMetaTitle']);
    unset($_SESSION['editCategoryId']);
    unset($_SESSION['editSummary']);
    unset($_SESSION['editContent']);
    unset($_SESSION['editPath']);
    unset($_SESSION['editTags']);
    unset($_SESSION['editHomePagePlacement']);


    header("Location: ../blogs.php?updateblog=success");
    exit();
}

function formError($errorCode) {

    require "dbh.php";

    $_SESSION['editTitle'] = $_POST['blog-title'];
    $_SESSION['editMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['editCategoryId'] = $_POST['blog-category'];
    $_SESSION['editSummary'] = $_POST['blog-summary'];
    $_SESSION['editContent'] = $_POST['blog-content'];
    $_SESSION['editTags'] = $_POST['blog-tags'];
    $_SESSION['editPath'] = $_POST['blog-path'];
    $_SESSION['editHomePagePlacement'] = $_POST['blog-home-page-placement'];

    mysqli_close($conn);
    header("Location: ../edit-blog.php?updateblog=".$errorCode);
    exit();
}

function uploadImage($img, $imgName, $imgType, $imgDbColumn) {

    require "dbh.php";

    $imgUrl = "";

    $validExt = array("jpg", "jpeg", "png","bmg", "gif");

    if ($img == "") {
        return "noupdate";
    }
    else {

        if ($_FILES[$imgName]["size"] <= 0) {
            formError($imgType."imageerror");
        }
        else {
    
           
            $explode = explode(".", $img);
            $ext = strtolower(end($explode));
    
           
           if (!in_array($ext, $validExt)) {
                formError("invalidtype".$imgType."image");
            }

            $blogId = $_POST['blog-id'];

            $sqlGetOldImage = "SELECT ".$imgDbColumn." FROM blog_post WHERE n_blog_post_id = '$blogId'";
            $queryGetOldImage = mysqli_query($conn, $sqlGetOldImage);

            if ($rowGetOldImage = mysqli_fetch_assoc($queryGetOldImage)) {
                $oldImgURL = $rowGetOldImage[$imgDbColumn];
            }

            if (!empty($oldImgURL)) {
                $oldImgURLArray = explode("/", $oldImgURL);
                $oldImgName = end($oldImgURLArray);
                $oldImgPath = "../images/blog-images/".$oldImgName;
                unlink($oldImgPath);
            }

    
            $folder = "../images/blog-images/";
            $imgNewName = rand(10000, 990000).'_'.time().'.'.$ext;
            $imgPath = $folder.$imgNewName;
    
          
            if (move_uploaded_file($_FILES[$imgName]['tmp_name'], $folder)) {
                $imgUrl = "http://localhost/blog/admin/images/blog-images/".$imgNewName;
            }
            else {
                formError("erroruploading".$imgType."image");
            }
            
        }

        return $imgUrl;
    
    }
}
