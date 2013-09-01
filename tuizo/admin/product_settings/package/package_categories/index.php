<?php
include ('../../../../config/config.php');
require basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$PC_updated_by = $_SESSION['admin_id'];
$package_name = '';
$package_type = '';
$Package_catagory_id = '';
$PC_package_id = '';
$PC_catagory_id = '';
$PC_catagory_quantity = '';
$package_user_type = '';
//================ GET FORM Value FROM form GET method ==========================//
if (isset($_REQUEST['package_id'])) {
    $PC_package_id = base64_decode($_REQUEST['package_id']);
    $package_name = base64_decode($_REQUEST['package_name']);
    $_SESSION["package_name"] = $package_name;  // for next page
    $Package_catagory_id = base64_decode($_REQUEST['package_catagory_id']);
    $package_user_type = base64_decode($_REQUEST['package_user_type']);
    /*     * ===== Get the product type from Category table =====================/ */
    $Package_category_name_query = "SELECT category_name FROM categories WHERE category_id=" . intval($Package_catagory_id);
    $package_category_name_result = mysqli_query($con, $Package_category_name_query);
    $package_type_row = mysqli_fetch_assoc($package_category_name_result);
    $package_type = $package_type_row['category_name'];
}
//============== End FORM value FROM form GET method =============================//
//===================== SAVE Package category ====================================//
if (isset($_POST['package_category_create']) && $_POST['package_category_create'] == 'Submit') {
    extract($_POST);
    if ($PC_catagory_id < 1) {
        $err = "Package Category is required";
    } elseif (empty($PC_catagory_quantity)) {
        $err = "Package category  qunaitty is Required";
    } else {

        $package_category_field = '';
        $package_category_field .=' PC_package_id = "' . $PC_package_id . '"';
        $package_category_field .=', PC_catagory_id   ="' . $PC_catagory_id . '"';
        $package_category_field .=', PC_catagory_quantity ="' . mysqli_real_escape_string($con, $PC_catagory_quantity) . '"';
        $package_category_field .=', PC_updated_by ="' . mysqli_real_escape_string($con, $PC_updated_by) . '"';

        $category_product_save_query = "INSERT INTO package_categories SET $package_category_field";

        $category_product_save_result = mysqli_query($con, $category_product_save_query);

        if ($category_product_save_result) {
            $msg = "Package Categories Information is created successfully";
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
        <title>Package Category panel: Package create </title>

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
                        <h5 class="iGraph">Create Package Category For <?php echo $package_name;?></h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="index.php?package_id=<?php echo $_REQUEST['package_id']; ?>&package_catagory_id=<?php echo $_REQUEST['package_catagory_id']; ?>&package_name=<?php echo $_REQUEST['package_name']; ?>&package_user_type=<?php echo $_REQUEST['package_user_type']; ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Package Information</h5></div>
                                        <!-- form Design -->
                                        <div class="rowElem noborder"><label>Package Name:</label><div class="formRight"><input type="hidden" name="PC_package_id" value="<?php echo $PC_package_id; ?>" /><input type="text" name="package_name" value="<?php echo $package_name; ?>" readonly="readonly" /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Package Type:</label><div class="formRight"><input type="text" name="package_type"  value="<?php echo $package_type; ?>" readonly="readonly" /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Package User Type:</label><div class="formRight"><input type="text" name="package_user_type"  value="<?php echo $package_user_type; ?>" readonly="readonly" /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Package Category:</label>
                                            <div class="formRight">      
                                                <select name="PC_catagory_id">
                                                    <option value="">Select</option>
                                                    <?php
                                                    $package_categories_query = "SELECT category_id,category_name FROM categories  WHERE category_parent_id =$Package_catagory_id AND category_id NOT IN(SELECT PC_catagory_id  FROM package_categories WHERE PC_package_id=$PC_package_id)";
                                                    $package_categories_query_result = mysqli_query($con, $package_categories_query);
                                                    while ($rows = mysqli_fetch_assoc($package_categories_query_result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['category_id']; ?>" ><?php echo $rows['category_name'] ?></option>
                                                        <?php
                                                    }
                                                    ?>

                                                </select>
                                            </div>
                                            <div class="fix"></div></div>                      
                                        <div class="rowElem"><label>Quantity :</label><div class="formRight"><input type="text" name="PC_catagory_quantity"  /></div><div class="fix"></div></div>

                                        <input type="submit" name="package_category_create" value="Submit" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                        <!-- End Form Design -->


                                    </div>
                                </fieldset>
                            </form>


                        </div>
                        <!-- Start Grid View for Package -->
                        <div class="table">
                            <div class="head">
                                <h5 class="iFrames">Product Category List For <?php echo $package_name.' ('.$package_type.')';?></h5></div>
                            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                <thead>
                                    <tr>
                                        <th>Package Category Name</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                        <th>Package category Products</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $package_grid_query = "SELECT
    pc.PC_id,c.category_name,c.category_id,pc.PC_catagory_quantity
FROM
    package_categories pc,
    categories c,
    packages p
   
WHERE
    pc.PC_package_id= p.package_id AND
    pc.PC_catagory_id=c.category_id AND
    pc.PC_package_id=" . intval($PC_package_id);

                                    $result_for_package_grid = mysqli_query($con, $package_grid_query);
                                    while ($package_row = mysqli_fetch_array($result_for_package_grid)) {
                                        ?>                        
                                        <tr class="gradeA">
                                            <td><?php echo $package_row['category_name']; ?></td>
                                            <td><?php echo $package_row['PC_catagory_quantity']; ?></td>
                                            <td align="center"><a href="package_categories_edit.php?pcid=<?php echo base64_encode($package_row['PC_id']) ?>&pc_name=<?php echo base64_encode($package_row['category_name']); ?>"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png'); ?>" height="14" width="14" alt="Edit Package Category" /></a></td>
                                            <td align="center"><a  
                                                <?php
                                                $sql = "SELECT 
                                                        pc.PC_id
                                                        FROM
                                                        product_categories pc,
                                                        products p
                                                        WHERE
                                                        pc.PC_product_id = p.product_id AND
                                                        pc.PC_category_id=" . intval($package_row['category_id']);
                                                $checkSQL = mysqli_query($con, $sql);
                                                $countRow = mysqli_num_rows($checkSQL);
                                                if ($countRow >= 1) {
                                                    ?>
                                                        href="package_categories_products/index.php?pid=<?php echo base64_encode($package_row['category_id']); ?>&ppid=<?php echo base64_encode($PC_package_id); ?>&package_user_type=<?php echo base64_encode($package_user_type); ?>" 
                                                        <?php
                                                    } else {
                                                        ?>
                                                        href="javascript:void(0);" onclick="alert('Package category product is empty.please Add the product.');
                                                                        return false;"
                                                        <?php
                                                    }
                                                    ?>
                                                    ><img src="<?php echo baseUrl('admin/images/add_cat.png'); ?>" height="14" width="14" alt="Edit Package Category Product" /></a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Grid view for package -->

                    </div>
                </div>

            </div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>


        <?php include basePath('admin/footer.php'); ?>


