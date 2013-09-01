<?php
include ('../../config/config.php');
include basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
//saving tags in database

$titlelimit = "";
if (isset($_POST['update']) && $_POST['update']=='Update') {
    extract($_POST);
    if ($titlelimit == "") {
        $err = "Title Limit filed is required.";
    } 

    if ($err == "") {
        $setupdate = mysqli_query($con, "UPDATE `config_settings` SET `CS_value` = CASE `CS_option`
										WHEN 'MENU_TITLE_CHARACTER_LIMIT' THEN '$titlelimit'
										ELSE `CS_value`
										END");

        if ($setupdate) {
            $msg = "Menu Setting updated successfully";
            //echo "<meta http-equiv='refresh' content='5; url=index.php'>";
        } else {
            if (DEBUG) {
                echo 'setupdate Error' . mysqli_error($con);
            }
        }
    }
}


$responses = array();
$getset = mysqli_query($con, "SELECT * FROM config_settings");
if (mysqli_num_rows($getset) > 0) {

    while ($row = mysqli_fetch_object($getset)) {
        $responses[$row->CS_option] = $row->CS_value;
    }
}

$titlelimit = $responses['MENU_TITLE_CHARACTER_LIMIT'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Product</title>   
        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" /> 
        <script src="<?php echo baseUrl('admin/js/jquery.min.js'); ?>" type="text/javascript"></script>  
        <!--tree view -->  
        <script src="<?php echo baseUrl('admin/js/treeViewJquery.min.js'); ?>"></script> 
        <script src ="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type = "text / javascript" ></script>   
        <!--tree view --> 
        <!--Start admin panel js/css --> 
       <?php include basePath('admin/header.php'); ?>   
        <!--End admin panel js/css -->               
       
    </head>

    <body>

        <?php include basePath('admin/top_navigation.php'); ?>

        <?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include basePath('admin/settings/settings_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Settings Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Website Settings</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/settings/settings_menu.php'); ?>" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Menu Settings</h5></div>
                                        <div class="rowElem noborder"><label>Menu Title Limit:</label><div class="formRight"><input name="titlelimit" type="text" value="<?php echo $titlelimit; ?>"/></div><div class="fix"></div></div>
                                        <input type="submit" name="update" value="Update" class="greyishBtn submitForm" />
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
