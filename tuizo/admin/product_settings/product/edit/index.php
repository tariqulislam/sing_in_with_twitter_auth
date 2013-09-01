<?php
include ('../../../../config/config.php');

if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

$product_avg_rating = '';
$product_user_type='';
//saving tags in database
extract($_POST);
$pid = base64_decode($_GET['pid']);


if (isset($_POST['update'])) {
    $err = "";
    $msg = "";

    if ($sku == "") {
        $err = "Product SKU is required.";
    } elseif ($title == "") {
        $err = "Product Title is required.";
    } elseif ($product_avg_rating == "") {
        $err = "Product rating is required.";
    } elseif ($desc == "") {
        $err = "Long Description is required.";
    } elseif ($sdesc == "") {
        $err = "Short Description is required.";
    } elseif (!preg_match("/([A-Za-z0-9]+)/", $sku)) {
        $err = "Product SKU can only be Alphanumeric value.";
    } elseif (!preg_match("/([A-Za-z0-9]+)/", $title)) {
        $err = "Product Title can only be Alphanumeric value.";
    } else {

        $GeneralInformation = '';
        $GeneralInformation .= ' product_title = "' . mysqli_real_escape_string($con, $title) . '"';
        $GeneralInformation .= ', product_sku = "' . mysqli_real_escape_string($con, $sku) . '"';
        $GeneralInformation .= ', product_avg_rating = "' . mysqli_real_escape_string($con, $product_avg_rating) . '"';
        $GeneralInformation .= ', product_short_description = "' . mysqli_real_escape_string($con, $sdesc) . '"';
        $GeneralInformation .= ', product_long_description = "' . mysqli_real_escape_string($con, $desc) . '"';
        $GeneralInformation .= ', product_user_type = "' . mysqli_real_escape_string($con, $product_user_type) . '"';
        
        $UpdateGeneralInfoSQL = "UPDATE products SET $GeneralInformation WHERE product_id='$pid'";
        $ExecuteGeneralInfo = mysqli_query($con, $UpdateGeneralInfoSQL);

        if ($ExecuteGeneralInfo) {
            $msg = "Product General Information updated successfully.";
        } else {
            if (DEBUG) {
                echo "ExecuteGeneralInfo error" . mysqli_error($con);
            }
            $err = "Product General Information update failed";
        }
    }
}

//fetching product general information from db

$product = mysqli_query($con, "SELECT * FROM products WHERE product_id='$pid'");
$rowproduct = mysqli_fetch_object($product);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Product</title>

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
            function redirect()
            {
                if (confirm('Do you want to leave Product Editing Module?'))
                {
                    window.location = "../index.php";
                }
            }

        </script>
        <!--end delete tags-->



    </head>

    <body>


<?php include basePath('admin/top_navigation.php'); ?>

<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <div class="leftNav">
<?php include('left_navigation.php'); ?>
            </div>
            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Product's General Information</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">General Information</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="index.php?pid=<?php echo base64_encode($pid); ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">General Information For <?php echo $rowproduct->product_title; ?></h5></div>
                                        <div class="rowElem noborder"><label>Product Title:</label><div class="formRight">
                                                <input name="title" type="text" maxlength="20" value="<?php echo $rowproduct->product_title; ?>"/>
                                            </div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Product SKU:</label><div class="formRight">
                                                <input name="sku" type="text" maxlength="20" value="<?php echo $rowproduct->product_sku; ?>"/>
                                            </div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Product Avg Rating:</label><div class="formRight">
                                                <input name="product_avg_rating" type="text" maxlength="20" value="<?php echo $rowproduct->product_avg_rating; ?>"/>
                                            </div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Long Description:</label><div class="formRight">
                                                <textarea rows="5" cols="" class="auto" name="desc"><?php echo $rowproduct->product_long_description; ?></textarea></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Short Description:</label><div class="formRight">
                                                <textarea rows="5" cols="" class="auto" name="sdesc"><?php echo $rowproduct->product_short_description; ?></textarea></div><div class="fix"></div></div>
                                         <div class="rowElem noborder"><label>Product User Type:</label>
                                            <div class="formRight">      
                                                <select name="product_user_type">
                                                    <?php
                                                    $user_types = array('man', 'woman', 'kids');

                                                    foreach ($user_types as $u) {
                                                        ?>
                                                        <option value="<?php echo $u; ?>" 
                                                        <?php
                                                        if ($rowproduct->product_user_type == $u) {
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

                                        <input type="submit" name="update" value="Update Product Details" class="greyishBtn submitForm" />
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
            var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
            var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>
    </body>
</html>