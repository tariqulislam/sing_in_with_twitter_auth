<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];
$cid = base64_decode($_GET['cid']);


//updating city in database

if (isset($_POST['submit'])) {
    extract($_POST);
    $err = "";
    $msg = "";
	
	$CheckCity = mysqli_query($con,"SELECT * FROM cities WHERE city_country_id='$country' AND city_name='$city' AND city_id NOT IN($cid)");
	$CountCity = mysqli_num_rows($CheckCity);

    if ($country == "") {
        $err = "Country Name is required.";
    } elseif ($city == "") {
        $err = "City Name is required.";
	} else {
		
		if($status == ""){
			$status = "notallow";
		}
		
		$cid = base64_decode($_GET['cid']);
        $UpdateCity = '';
        $UpdateCity .= ' city_country_id = "' . mysqli_real_escape_string($con, $country) . '"';
        $UpdateCity .= ', city_name = "' . mysqli_real_escape_string($con, $city) . '"';
        $UpdateCity .= ', city_status = "' . mysqli_real_escape_string($con, $status) . '"';
		$UpdateCity .= ', city_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';
        
        $UpdateCitySql = "UPDATE cities SET $UpdateCity WHERE city_id='$cid'";
        $ExecuteSql = mysqli_query($con, $UpdateCitySql);
        
		if ($ExecuteSql) {
            $msg = "City updated successfully";
            $link = baseUrl('admin/country/city_list.php?msg=' . base64_encode($msg));
            redirect($link);
        } else {
            if (DEBUG) {
                echo 'ExecuteSql Error: ' . mysqli_error($con);
            }
            $err = "City update failed.";
        }
    }
}


$GetCity = "SELECT * FROM cities WHERE city_id='$cid'";
$ExecuteCitySql = mysqli_query($con,$GetCity);
$SetCity = mysqli_fetch_object($ExecuteCitySql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Create Product </title>

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
            <?php include ('country_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>City Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">City Module</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="edit_city.php?cid=<?php echo $_GET['cid']; ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head">
                                            <h5 class="iList">Edit City</h5></div>
                                         
                                        <div class="rowElem noborder"><label>Country Name:</label><div class="formRight">                                        <select name="country">
                                        <option value="">-- Select Country --</option>
                                        <?php
										$SelectCountry = "SELECT * FROM countries";
										$ExecuteSelect = mysqli_query($con,$SelectCountry);
										while($GetCountry = mysqli_fetch_object($ExecuteSelect)){
											
											$chk = "";
											if($GetCountry -> country_id == $SetCity -> city_country_id){
												$chk = "selected";
											}
										?>
                                        <option value="<?php echo $GetCountry -> country_id; ?>" <?php echo $chk; ?>><?php echo $GetCountry -> country_name; ?></option>
                                        <?php
										}
										?>
                                        </select>
                                        </div><div class="fix"></div></div>      
                                            
                                        <div class="rowElem noborder"><label>City Name:</label><div class="formRight">                                         <input name="city" type="text" value="<?php echo $SetCity -> city_name; ?>"/>
                                                    </div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Status:</label><div class="formRight">
                                        
                                        <?php
										$chk = "";
										if($SetCity -> city_status == "allow"){
											$chk = "checked";
										}
										?>
                                     
                                                    <input name="status" type="checkbox" value="allow" <?php echo $chk; ?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="position:relative; top:5px;">Allow</span>
                                                   </div><div class="fix"></div></div>

                                        
                                        <input type="submit" name="submit" value="Update Country" class="greyishBtn submitForm" />
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
        <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
            var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none");
            var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
            var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency", {minValue:1});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {minValue:0, maxValue:5});
        </script>
