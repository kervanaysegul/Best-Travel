<?php

session_start();

unset($_SESSION['blogTitle']);
unset($_SESSION['blogMetaTitle']);
unset($_SESSION['blogCategoryId']);
unset($_SESSION['blogSummary']);
unset($_SESSION['blogContent']);
unset($_SESSION['blogTags']);
unset($_SESSION['blogPath']);
unset($_SESSION['blogHomePagePlacement']);

unset($_SESSION['editBlogId']);
unset($_SESSION['editTitle']);
unset($_SESSION['editMetaTitle']);
unset($_SESSION['editCategoryId']);
unset($_SESSION['editSummary']);
unset($_SESSION['editContent']);
unset($_SESSION['editPath']);
unset($_SESSION['editTags']);
unset($_SESSION['editHomePagePlacement']);