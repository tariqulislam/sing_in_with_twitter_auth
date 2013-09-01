<?php
include ('../../config/config.php');
if(!checkAdminLogin()){
    $link=  baseUrl('admin/index.php?err='.  base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];
if($_GET['user_id'] == ''){
	$link = "index.php?err=".base64_encode("Wrong user id.");
} else {
	$User_id = base64_decode($_GET['user_id']);
}

extract($_POST);


if(isset($_POST['update'])){
	
	$UpdateUser = '';
	$UpdateUser .=' user_status="' . mysqli_real_escape_string($con, $status) . '"';
	
	$ExecuteStatus = mysqli_query($con,"UPDATE users SET $UpdateUser WHERE user_id='$User_id'");
	
	if($ExecuteStatus){
		$msg = "User Status updated successfully.";
		$link = "index.php?msg=".base64_encode($msg);
		redirect($link);
	} else {
		if(DEBUG){
			echo mysqli_error($con);
		}
		$err = "User status update failed.";
		
	}
}



$GetUser = mysqli_query($con,"SELECT * FROM users WHERE user_id='$User_id'");
$SetUser = mysqli_fetch_object($GetUser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin Panel | Customer Activity</title>

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
    <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    <script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
        
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


    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>

    <body>

       
        <?php include basePath('admin/top_navigation.php'); ?>

    	<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
          <?php include ('customer_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Customer Activity Module</h5></div>

                <!-- Notification messages -->
               <?php include basePath('admin/message.php'); ?>
               
                <!-- Charts -->
         
                        
              
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Sizes</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="user_show.php?user_id=<?php echo ($_GET['user_id']); ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Edit Size</h5></div>
                                        
                                        <div class="rowElem noborder"><label>Customer Name:</label><div class="formRight"><input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetUser -> user_first_name.' '.$SetUser -> user_middle_name.' '.$SetUser -> user_last_name; ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Customer Email:</label><div class="formRight"><input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetUser -> user_email; ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Customer DOB:</label><div class="formRight"><input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetUser -> user_DOB; ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Customer Gender:</label><div class="formRight"><input name="title" type="text" maxlength="20" style="text-transform:capitalize;" disabled="disabled" value="<?php echo $SetUser -> user_gender; ?>"/></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Customer Aboutme:</label><div class="formRight"><textarea name="title" disabled="disabled"><?php echo $SetUser -> user_aboutme; ?></textarea></div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Customer Status:</label><div class="formRight">
                                        <select name="status">
                                        <option value="active" <?php if($SetUser -> user_status == 'active'){ echo "selected"; } ?> >Active</option>
                                        <option value="inactive" <?php if($SetUser -> user_status == 'inactive'){ echo "selected"; } ?>>Inactive</option>
                                        </select>
                                        </div><div class="fix"></div></div>
                                        
                                        <input type="submit" name="update" value="Update Status" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>
                                </fieldset>

                            </form>		


                        </div>


                    </div>
                </div>

            </div>

                        
                        
                        
                        
              </div>
          </div>

</div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>

        <?php include  basePath('admin/footer.php'); ?>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "custom", {pattern:"XXXX000000"});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency");
    </script>
