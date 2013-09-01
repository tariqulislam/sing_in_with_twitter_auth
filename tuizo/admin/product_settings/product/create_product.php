<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];

$title = "";
$desc = "";
$sku = "";
$price = "";
$product_avg_rating = "";
$product_user_type='';
//saving tags in database

if (isset($_POST['submit'])) {
    extract($_POST);
    $err = "";
    $msg = "";

    if (!preg_match("/([A-Za-z0-9]+)/", $title)) {
        $err .= "Only numbers & alphabets are allowed for Product Title";
    } else if (!is_numeric($product_avg_rating)) {
        $err = "Product rating will be only number";
        
    } else if ($price < 0) {
        $err = "Product Price must be grater than 0.";
        
    }else if ($product_avg_rating < 0 && $product_avg_rating > 5) {
        $err = "Product rating will be between 0 to 5";
        
    } else {

        $ProductCreate = '';
        $ProductCreate .= ' product_title = "' . mysqli_real_escape_string($con, $title) . '"';
        $ProductCreate .= ', product_sku = "' . mysqli_real_escape_string($con, $sku) . '"';
        $ProductCreate .= ', product_long_description = "' . mysqli_real_escape_string($con, $desc) . '"';
        $ProductCreate .= ', product_price = "' . mysqli_real_escape_string($con, $price) . '"';
        $ProductCreate .= ', product_avg_rating = "' . mysqli_real_escape_string($con, $product_avg_rating) . '"';
        $ProductCreate .= ', product_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';
        $ProductCreate .= ', product_user_type = "' . mysqli_real_escape_string($con, $product_user_type) . '"';
        
        $CreateProductSql = "INSERT INTO products SET $ProductCreate";
        $ExecuteCreateProduct = mysqli_query($con, $CreateProductSql);
        $InsertedProductID = mysqli_insert_id($con);

        if ($ExecuteCreateProduct) {
            $msg = "Product created successfully";
            $link = baseUrl('admin/product_settings/product/edit/index.php?pid=' . base64_encode($InsertedProductID) . '&msg=' . base64_encode($msg));
            redirect($link);
        } else {
            if (DEBUG) {
                echo 'ExecuteCreateProduct Error: ' . mysqli_error($con);
            }
            $err = "Product Creation failed.";
        }
    }
}
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
             <?php include basePath('admin/product_settings/product_settings_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Product Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Product</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="create_product.php" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head">
                                            <h5 class="iList">Add Product</h5></div>
                                        <div class="rowElem noborder"><label>Product Title:</label><div class="formRight"><span id="sprytextfield1">                                         <input name="title" type="text" value="<?php echo $title; ?>"/>
                                                    <span class="textfieldRequiredMsg">Product title is required.</span></span></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Product SKU:</label><div class="formRight"><span id="sprytextfield2">
                                                    <input name="sku" type="text" maxlength="25" value="<?php echo $sku; ?>"/>
                                                    <span class="textfieldRequiredMsg">Product SKU is required.</span></span></div><div class="fix"></div><font color="#999999"><i>SKU Pattern: ABCD123456</i></font></div>

                                        <div class="rowElem noborder"><label>Long Description:</label><div class="formRight"><span id="sprytextarea1">
                                                    <textarea rows="5" cols="" class="auto" name="desc"><?php echo $desc; ?></textarea>
                                                    <span class="textareaRequiredMsg">Product description is required.</span></span></div><div class="fix"></div></div>


                                        <div class="rowElem noborder"><label>Product Price:</label><div class="formRight"><span id="sprytextfield3">
                                        <input name="price" type="text" maxlength="20" value="<?php echo $price; ?>"/>
                                        <span class="textfieldRequiredMsg">Product price is required.</span><span class="textfieldInvalidFormatMsg">currency value needed.</span><span class="textfieldMinValueMsg">The entered value is less than the minimum required.</span></span></div><div class="fix"></div>
                                        </div>
                                        <div class="rowElem noborder"><label>Product Rating:</label><div class="formRight"><span id="sprytextfield3"><span id="sprytextfield4">
                                        <input name="product_avg_rating" type="text" maxlength="1" value="<?php echo $product_avg_rating; ?>"/>
                                      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMinValueMsg">The entered value is less than the minimum required.</span><span class="textfieldMaxValueMsg">The entered value is greater than the maximum allowed.</span></span><span class="textareaRequiredMsg">Product average rating is required.</span></span></div><div class="fix"></div></div>
                                          <div class="rowElem noborder"><label>Package User Type:</label>
                                            <div class="formRight">      
                                                <select name="product_user_type">
                                                    <?php
                                                    $user_types = array('all', 'man', 'woman', 'kids');

                                                    foreach ($user_types as $u) {
                                                        ?>
                                                        <option value="<?php echo $u; ?>" 
                                                        <?php
                                                        if ($product_user_type == $u) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>

                                                                ><?php echo $u; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="fix"></div></div>
                                        <input type="submit" name="submit" value="Add Product" class="greyishBtn submitForm" />
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
