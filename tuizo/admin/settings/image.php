<?php
include ('../../config/config.php');
include basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
//saving tags in database

extract($_POST);
$aid = @$_SESSION['admin_id']; //getting loggedin admin id

$maxsize = "";
$maxwidth = "";
$large = "";
$medium = "";
$small = "";
$package_logo_width = "";
$package_banner_width = '';
if (isset($_POST['update'])) {
    extract($_POST);
    if ($maxsize == "") {
        $err = "Banner Max Size filed is required.";
    }elseif ($maxwidth == "" || !is_numeric($maxwidth)) {
        $err = "Banner Max Width filed is required and must be integer.";
    } elseif ($large == "" || !is_numeric($large)) {
        $err = "Large Image Width filed is required and must be integer.";
    } elseif ($medium == "" || !is_numeric($medium)) {
        $err = "Medium Image Width is required and must be integer.";
    
    } elseif ($small == "" || !is_numeric($small)) {
        $err = "Small Image Width is required and must be integer.";
    }elseif ($package_logo_width == "" || !is_numeric($package_logo_width)) {
        $err = "Package Logo Width is required and must be integer.";
    }elseif ($package_banner_width == "" || !is_numeric($package_banner_width)) {
        $err = "Package Banner Width is required and must be integer.";
    }
    if($err == '') {

if (isset($_POST['update'])) {
    

        $setupdate = mysqli_query($con, "UPDATE `config_settings` SET `CS_value` = CASE `CS_option`
										WHEN 'CATEGORY_BANNER_MAX_SIZE' THEN '$maxsize'
										WHEN 'CATEGORY_BANNER_MAX_WIDTH' THEN '$maxwidth'
										WHEN 'PRODUCT_LARGE_IMAGE_WIDTH' THEN '$large'
										WHEN 'PRODUCT_MEDIUM_IMAGE_WIDTH' THEN '$medium'
										WHEN 'PRODUCT_SMALL_IMAGE_WIDTH' THEN '$small'
										WHEN 'PACKAGE_LOGO_WIDTH' THEN '$package_logo_width'
										WHEN 'PACKAGE_BANNER_WIDTH' THEN '$package_banner_width'
										ELSE `CS_value`
										END");

        if ($setupdate) {
            $msg = "Size updated successfully";
            //echo "<meta http-equiv='refresh' content='5; url=index.php'>";
        } else {
            $err = "Size update failed";
        }
    
}

    }

}
$getset = mysqli_query($con, "SELECT * FROM config_settings");
if (mysqli_num_rows($getset) > 0) {
    $responses = array();
    while ($row = mysqli_fetch_assoc($getset)) {
        $responses[] = array(
            'Option Name' => $row['CS_option'],
            'Option Value' => $row['CS_value']
        );
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Settings</title>

        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />
        <script src="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type="text/javascript"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload, editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/spinner/ui.spinner.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery-ui.min.js'); ?>"></script>  
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/fileManager/elfinder.min.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/jquery.wysiwyg.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/wysiwyg.image.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/wysiwyg.link.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/wysiwyg.table.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/dataTables/jquery.dataTables.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/dataTables/colResizable.min.js'); ?>"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/forms.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/autogrowtextarea.js'); ?>"></script>
        <!--Effect on left error menu, top message menu, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/autotab.js'); ?>"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/jquery.validationEngine.js'); ?>"></script>
        <!--Effect on left error menu, top message menu-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/colorPicker/colorpicker.js'); ?>"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/plupload.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/plupload.html5.js'); ?>"></script>
        <!--Effect on file upload-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/plupload.html4.js'); ?>"></script>
        <!--No effect-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/jquery.plupload.queue.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/ui/jquery.tipsy.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,  -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jBreadCrumb.1.1.js'); ?>"></script>
        <!--Effect on left error menu, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/cal.min.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.collapsible.min.js'); ?>"></script>
        <!--Effect on left error menu, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.ToTop.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.listnav.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.sourcerer.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/custom.js'); ?>"></script>
        <!--Effect on left error menu, top message menu, body-->
        <!--delete tags-->
        <script type="text/javascript">
            /*function del(pin_id1)
             {
             if(confirm('Are you sure to delete this tag!!'))
             {
             window.location='index.php?del='+pin_id1;
             }
             }*/
        </script>
        <!--end delete tags-->


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
                            <form action="image.php" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Website Settings</h5></div>

                                        <div class="rowElem noborder"><label>Banner Max Size:</label><div class="formRight"><input name="maxsize" type="text" value="<?php echo ($responses[4]['Option Value']); ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Banner Max Width:</label><div class="formRight"><input name="maxwidth" type="text" value="<?php echo ($responses[5]['Option Value']); ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Large Image Width:</label><div class="formRight"><input name="large" type="text" value="<?php echo ($responses[9]['Option Value']); ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Medium Image Width:</label><div class="formRight"><input name="medium" type="text" value="<?php echo ($responses[10]['Option Value']); ?>"/></div><div class="fix"></div></div>
                                        
                                         <div class="rowElem noborder"><label>Small Image Width:</label><div class="formRight"><input name="small" type="text" value="<?php echo ($responses[11]['Option Value']); ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Package Logo Width:</label><div class="formRight"><input name="package_logo_width" type="text" value="<?php echo ($responses[13]['Option Value']); ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Package Banner Width:</label><div class="formRight"><input name="package_banner_width" type="text" value="<?php echo ($responses[14]['Option Value']); ?>"/></div><div class="fix"></div></div>
                                        

                                        <input type="submit" name="update" value="Update Settings" class="greyishBtn submitForm" />
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
