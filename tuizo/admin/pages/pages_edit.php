<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$edit_page_id = 0;

if (isset($_REQUEST['id'])) {
    $edit_page_id = base64_decode($_REQUEST['id']);
} else {
    $link = 'index.php?msg=' . base64_encode('ID missing.');
    redirect($link);
}

$page_url = '';
$page_title = '';
$page_priority = '';
$page_short_description = '';
$page_body = '';
$page_meta_title = '';
$page_meta_description = '';
$page_meta_keywords = '';
$pageSql = "SELECT * FROM pages WHERE page_id=" . intval($edit_page_id);
$pageSqlResult = mysqli_query($con, $pageSql);
if ($pageSqlResult) {
    $pageSqlResultRowObj = mysqli_fetch_object($pageSqlResult);
    if (isset($pageSqlResultRowObj->page_id)) {
        $page_id = $pageSqlResultRowObj->page_id;
        $page_url = $pageSqlResultRowObj->page_url;
        $page_title = $pageSqlResultRowObj->page_title;
        $page_priority = $pageSqlResultRowObj->page_priority;
        $page_short_description = $pageSqlResultRowObj->page_short_description;
        $page_body = $pageSqlResultRowObj->page_body;
        $page_meta_title = $pageSqlResultRowObj->page_meta_title;
        $page_meta_description = $pageSqlResultRowObj->page_meta_description;
        $page_meta_keywords = $pageSqlResultRowObj->page_meta_keywords;
        $page_type = $pageSqlResultRowObj->page_type;
    }
} else {
    if (DEBUG) {
        echo "pageSqlResult error : " . mysqli_error($con);
    } else {
        $link = baseUrl('admin/pages/index.php?err=' . base64_encode('Edit sql fail.'));
        redirect($link);
    }
}
if (isset($_POST['page_edit']) AND $_POST['page_edit'] == 'Submit') {

    extract($_POST);

    if ($page_url == '') {
        $err = 'Page URL field is required!!';
    } else if (!filter_var($page_url, FILTER_VALIDATE_URL)) {
        $err = 'Valid URL is required!!';
    } else if ($page_title == '') {
        $err = 'Page Title field is required!!';
    } else if ($page_priority == '') {
        $err = 'Page priority field is required!!';
    } else if (!is_numeric($page_priority)) {
        $err = 'Page priority should be numeric!!';
    } else if ($page_short_description == '') {
        $err = 'Page Short Description field is required!!';
    } else if ($page_body == '') {
        $err = 'Page Body field is required!!';
    } else if ($page_meta_title == '') {
        $err = 'Page Meta Title field is required!!';
    } else if ($page_meta_description == '') {
        $err = 'Page Meta Description field is required!!';
    } else if ($page_meta_keywords == '') {
        $err = 'page Meta Keyword field is required!!';
    } else {
        $pageCheckSql = "select page_title from pages where page_title='" . mysqli_real_escape_string($con, $page_title) . "' && page_id !='" . $page_id . "'";
        $pageCheckSqlResult = mysqli_query($con, $pageCheckSql);
        if ($pageCheckSqlResult) {
            $pageCheckSqlResultRowObj = mysqli_fetch_object($pageCheckSqlResult);
            if (isset($pageCheckSqlResultRowObj->page_title) && $pageCheckSqlResultRowObj->page_title == $page_title) {
                $err = 'Page Title (<b>' . $page_title . '</b>) already exist in our databse ';
            }
            mysqli_free_result($pageCheckSqlResult);
        } else {
            if (DEBUG) {
                echo 'pageCheckSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Query failed.";
        }
    }

    if ($err == '') {
        $pageInfoFiled = '';
        $pageInfoFiled .=' page_url = "' . mysqli_real_escape_string($con, $page_url) . '"';
        $pageInfoFiled .=', page_title = "' . mysqli_real_escape_string($con, $page_title) . '"';
        $pageInfoFiled .=', page_priority = "' . intval($page_priority) . '"';
        $pageInfoFiled .=', page_short_description = "' . mysqli_real_escape_string($con, $page_short_description) . '"';
        $pageInfoFiled .=', page_body = "' . mysqli_real_escape_string($con, $page_body) . '"';
        $pageInfoFiled .=', page_meta_title = "' . mysqli_real_escape_string($con, $page_meta_title) . '"';
        $pageInfoFiled .=', page_meta_description = "' . mysqli_real_escape_string($con, $page_meta_description) . '"';
        $pageInfoFiled .=', page_meta_keywords = "' . mysqli_real_escape_string($con, $page_meta_keywords) . '"';
        $pageInfoInsSql = "UPDATE pages SET $pageInfoFiled where page_id=" . intval($edit_page_id);
        $pageInfoFiledResult = mysqli_query($con, $pageInfoInsSql);
        if ($pageInfoFiledResult) {
            $msg = "Page Information Update successfully";
        } else {
            if (DEBUG) {
                echo 'pageInfoUpdateSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Insert Query failed.";
        }
    }
}

$menuArray = array();
$menuSql = "SELECT * FROM menu";
$menuSqlResult = mysqli_query($con, $menuSql);
if ($menuSqlResult) {
    while ($menuSqlResultRowObj = mysqli_fetch_object($menuSqlResult)) {

        $menuUrlSql = "SELECT * FROM menu_url WHERE MU_menu_id= " . $menuSqlResultRowObj->menu_id;
        $menuUrlSqlResult = mysqli_query($con, $menuUrlSql);
        if ($menuUrlSqlResult) {
            while ($menuUrlSqlResultRowObj = mysqli_fetch_object($menuUrlSqlResult)) {
                $menuSqlResultRowObj->menu_url[] = $menuUrlSqlResultRowObj;
            }
            mysqli_free_result($menuUrlSqlResult);
        } else {
            echo 'menuUrlSqlResult Error: ' . mysqli_error($con);
        }

        $menuArray[] = $menuSqlResultRowObj;
    }
    mysqli_free_result($menuSqlResult);
} else {
    echo 'menuSqlResult Error : ' . mysqli_error($con);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Page Update</title>   
        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" /> 
        <script src="<?php echo baseUrl('admin/js/jquery.min.js'); ?>" type="text/javascript"></script>  
        <!--tree view -->  
        <script src="<?php echo baseUrl('admin/js/treeViewJquery.min.js'); ?>"></script> 
        <script src ="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type = "text / javascript" ></script>   
        <!--tree view --> 
        <!--Start admin panel js/css --> 
        <?php include basePath('admin/header.php'); ?>   
        <!--End admin panel js/css -->   
        <script>
            $("#menu_url").live("change", function() {
                var url = $(this).val();
                $("#page_url").val(url);
                if (url == '') {
                    $("#page_url").attr('readonly', false);
                }
                if (url != '') {
                    $("#page_url").attr('readonly', true);
                }

            });
            $("#menu_url").removeAttr("style")

        </script>

    </head>

    <body>

        <?php include basePath('admin/top_navigation.php'); ?>

        <?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include ('pages_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Pages Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Create Pages </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <div id="menu" style="border-color: red;">
                                <label>URL Import : </label>
                                <select name="menu_url" id="menu_url">
                                    <option value="">Select Menu Url</option>
                                    <option value="">New</option>
                                    <?php $menuCounter = count($menuArray); ?>
                                    <?php for ($i = 0; $i < $menuCounter; $i++): ?>

                                        <optgroup label="<?php echo $menuArray[$i]->menu_title; ?>">
                                            <?php $menu_urlCounter = count($menuArray[$i]->menu_url); ?>
                                            <?php if ($menu_urlCounter > 0): ?>
                                                <?php for ($j = 0; $j < $menu_urlCounter; $j++): ?>
                                                    <option value="<?php echo $menuArray[$i]->menu_url[$j]->MU_url; ?>"><?php echo $menuArray[$i]->menu_url[$j]->MU_url_title; ?></option>
                                                <?php endfor; /* ($j=0; $j < $menu_urlCounter; $j++ ) */ ?>
                                            <?php endif; /* ($menu_urlCounter > 0) */ ?>
                                        </optgroup>
                                    <?php endfor; /* ($i=0; $i < $menuCounter; $i++) */ ?>
                                    <?php if ($menuCounter > 0): ?>
                                    <?php endif; /* if($menuCounter > 0) */ ?>

                                </select>
                            </div>

                            <form action="<?php echo baseUrl('admin/pages/pages_edit.php?id=') . base64_encode($edit_page_id); ?>" method="post" enctype="multipart/form-data" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Pages</h5></div>
                                        <div class="rowElem noborder"><label> Pages URL:</label><div class="formRight"><input type="text" name="page_url" id="page_url" value="<?php  echo $page_url;?>" <?php if($page_type=='built-in'){echo 'readonly';}?>  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> Pages Title:</label><div class="formRight"><input type="text" name="page_title" value="<?php echo $page_title; ?>"  <?php if($page_type=='built-in'){echo 'readonly';}?>   /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> Pages Priority:</label><div class="formRight"><input type="text" name="page_priority" value="<?php echo $page_priority; ?>"  /></div><div class="fix"></div></div>
                                        <div class="head"><h5 class="iPencil">Page Short Description:</h5></div>
                                        <div><textarea rows="5" cols="" name="page_short_description"><?php echo $page_short_description; ?></textarea></div>
                                        <div class="head"><h5 class="iPencil">Page Body:</h5></div>
                                        <div><textarea class="wysiwyg" rows="5" cols="" name="page_body"><?php echo $page_body; ?></textarea></div>
                                        <div class="rowElem noborder"><label> Pages Meta Title:</label><div class="formRight"><input type="text" name="page_meta_title" value="<?php echo $page_meta_title; ?>"  /></div><div class="fix"></div></div>
                                        <div class="head"><h5 class="iPencil">Page Meta Description:</h5></div>
                                        <div><textarea rows="5" cols="" name="page_meta_description"><?php echo $page_meta_description; ?></textarea></div>
                                        <div class="rowElem noborder"><label> Pages Meta Keywords:</label><div class="formRight"><input type="text" name="page_meta_keywords" value="<?php echo $page_meta_keywords; ?>"  /></div><div class="fix"></div></div>     
                                        <input type="submit" name="page_edit" value="Submit" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                    </div>
                                </fieldset>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>

        <?php include basePath('admin/footer.php'); ?>
