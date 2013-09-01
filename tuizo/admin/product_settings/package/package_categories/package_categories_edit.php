<?php
include ('../../../../config/config.php');
require basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$PC_updated_by = $_SESSION['admin_id'];
$package_name = '';
$PC_id='';
$PC_catagory_quantity = '';
//================ GET FORM Value FROM form GET method ==========================//
if (isset($_REQUEST['pcid'])) {
   
    /**===== Get the product type from Category table =====================/*/
    $PC_id= base64_decode($_GET['pcid']);
    $package_name=  base64_decode($_GET['pc_name']);
    $rows= mysqli_fetch_assoc(mysqli_query($con,"SELECT PC_catagory_quantity FROM package_categories WHERE PC_id='$PC_id'"));
    $PC_catagory_quantity =$rows['PC_catagory_quantity']; 
}
//**============== End FORM value FROM form GET method =============================//
//**===================== SAVE Package category ====================================//
if (isset($_POST['package_category_edit']) && $_POST['package_category_edit'] == 'Submit') {
    extract($_POST);
    if (empty($PC_catagory_quantity)) {
        $err = "Package category  quantity is Required";
    } else {

        $package_category_field = '';
        $package_category_field .=' PC_catagory_quantity ="' . mysqli_real_escape_string($con, $PC_catagory_quantity) . '"';
        $package_category_field .=', PC_updated_by ="' . mysqli_real_escape_string($con, $PC_updated_by) . '"';

        $category_product_save_query = "UPDATE package_categories SET $package_category_field WHERE PC_id=".  intval($PC_id);

        $category_product_save_result = mysqli_query($con, $category_product_save_query);

        if ($category_product_save_result) {
            $msg = "Package Categories Information is Updated successfully";
        } else {
            if (DEBUG) {
                echo 'PackageCategories Information Error: ' . mysqli_error($con);
            }
            $err = "Insert Query failed.";
        }
    }
}

//===================== End Save package Cagetory ================================//
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Package Category panel: Edit Package Category </title>

        <!-- Script and style for Light box 2 -->
        <script src="<?php echo baseUrl('js/lightbox/js/jquery-1.10.2.min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/lightbox/js/lightbox-2.6.min.js'); ?>"></script>
        <link href="<?php echo baseUrl('js/lightbox/css/lightbox.css'); ?>" rel="stylesheet" />
        <!-- End Script and Style for Light box 2 -->

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

    </head>

    <body>

<?php include basePath('admin/top_navigation.php'); ?>

<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
<?php include basePath('admin/product_settings/product_settings_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Package Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Edit package category </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="package_categories_edit.php?pcid=<?php echo $_REQUEST['pcid']; ?>&pc_name=<?php echo $_REQUEST['pc_name']; ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Package Information</h5></div>
                                        <!-- form Design -->
                                         <div class="rowElem noborder"><label>Package Category Name:</label><div class="formRight"><input type="text" name="package_name" value="<?php echo $package_name; ?>" readonly="readonly" /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Package Category Quantity:</label><div class="formRight"><input type="text" name="PC_catagory_quantity" value="<?php echo $PC_catagory_quantity; ?>"  /></div><div class="fix"></div></div>

                                     
                                        <input type="submit" name="package_category_edit" value="Submit" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                        <!-- End Form Design -->


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


