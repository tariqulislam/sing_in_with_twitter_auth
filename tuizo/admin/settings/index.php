<?php
include ('../../config/config.php');
include basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
//saving tags in database


$aid = @$_SESSION['admin_id']; //getting loggedin admin id
$url = '';
extract($_POST); 
if (isset($_POST['update'])) {
	
   	
   
    
        if ($_FILES['logo']['size'] > 0 || !empty($_FILES['logo']['tmp_name'])) {   //uploading logo if given
            /* if image select for logo */
            $image = basename($_FILES['logo']['name']);
            $info = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = "logo." . $info;
            $image_source = $_FILES["logo"]["tmp_name"];


            if (!is_dir($config['IMAGE_PATH'] . '/')) {
                mkdir($config['IMAGE_PATH'] . '/', 0777, TRUE);
            }
            $image_target_path = $config['IMAGE_PATH'] . '/' . $image_name;
            if (move_uploaded_file($image_source, $image_target_path)) {
                $logoupdate = mysqli_query($con, "UPDATE `config_settings` SET `CS_value` = CASE `CS_option`
										WHEN 'SITE_LOGO' THEN '$image_name'
										ELSE `CS_value`
										END");
            }
		}
     

        if ($_FILES['favicon']['size'] > 0 || !empty($_FILES['favicon']['tmp_name'])) {  //uploading favicon if given
            /* if image select for favicon */
            $image = basename($_FILES['favicon']['name']);
            $info = pathinfo($image, PATHINFO_EXTENSION);
            $image_name = "favicon.ico";
            $image_source = $_FILES["favicon"]["tmp_name"];

            if (!is_dir($config['IMAGE_PATH'] . '/')) {
                mkdir($config['IMAGE_PATH'] . '/', 0777, TRUE);
            }
            $image_target_path = $config['IMAGE_PATH'] . '/' . $image_name;
            if (move_uploaded_file($image_source, $image_target_path)) {
                $logoupdate = mysqli_query($con, "UPDATE `config_settings` SET `CS_value` = CASE `CS_option`
										WHEN 'SITE_FAVICON' THEN '$image_name'
										ELSE `CS_value`
										END");
            }
		}
       


       
	   
            $setupdate = mysqli_query($con, "UPDATE `config_settings` SET `CS_value` = CASE `CS_option`
										WHEN 'SITE_NAME' THEN '$name'
										WHEN 'SITE_URL' THEN '$url'
										WHEN 'SITE_DEFAULT_META_TITLE' THEN '$title'
										WHEN 'SITE_DEFAULT_META_DESCRIPTION' THEN '$desc'
										WHEN 'SITE_DEFAULT_META_KEYWORDS' THEN '$keyword'
										WHEN 'GOOGLE_ANALYTICS' THEN '$analytics'
										ELSE `CS_value`
										END");

            if ($setupdate) {
                $msg = "Size updated successfully";
//echo "<meta http-equiv='refresh' content='5; url=index.php'>";
            } else {
                $err = "Size update failed";
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
                            <form action="index.php" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Website Settings</h5></div>

                                        <div class="rowElem noborder"><label>Website Name:</label><div class="formRight"><input name="name" type="text" value="<?php echo ($responses[0]['Option Value']); ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Website URL:</label><div class="formRight"><input name="url" type="text" value="<?php echo ($responses[1]['Option Value']); ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Website Logo:</label><div class="formRight"><input type="file" name="logo" /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Website Favicon:</label><div class="formRight"><input type="file" name="favicon" /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Meta Title:</label><div class="formRight"><input name="title" type="text" value="<?php echo ($responses[6]['Option Value']); ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Meta Description:</label><div class="formRight"><input name="desc" type="text" value="<?php echo ($responses[7]['Option Value']); ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Meta Keyword:</label><div class="formRight"><input name="keyword" type="text" value="<?php echo ($responses[8]['Option Value']); ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Google Analytics:</label><div class="formRight"><textarea name="analytics" rows="8"><?php echo ($responses[12]['Option Value']); ?></textarea></div><div class="fix"></div></div>

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
