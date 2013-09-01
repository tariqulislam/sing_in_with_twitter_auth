<?php
include ('../../../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$PCP_updated_by = $_SESSION['admin_id'];

/** Get the product categories table data **/
$pid = base64_decode($_GET['pid']);
$ppid = base64_decode($_GET['ppid']);
$package_user_type = base64_decode($_GET['package_user_type']);
$product = array();
if (isset($_GET['pid'])) {
  /** Start: Query for package category product data **/
  $sql = "SELECT 
    pc.PC_id,p.product_id,p.product_title,pc.PC_product_id,pc.PC_category_id
    FROM
    product_categories pc,
    products p
    WHERE
    pc.PC_product_id = p.product_id AND
    pc.PC_category_id=" . intval($pid). " AND p.product_user_type='".$package_user_type."'";
    $prosel = mysqli_query($con, $sql);
/** End: Query for package category product data **/

  /** Start: Check the Package category product **/
   $selectQuery ="SELECT PCP_product_id FROM package_category_products WHERE PCP_package_id=".intval($ppid)." AND PCP_package_category_id=".intval($pid);
    $checkSql = mysqli_query($con, $selectQuery);
    if($checkSql)
    {
        while ($catrow = mysqli_fetch_object($checkSql)) {
            $product[] = $catrow->PCP_product_id;
        }
    }
     /** End: Check the Package category product **/
}
/** Start: Update the selected product **/
if (isset($_POST['update'])) {
    extract($_POST);
    if (empty($_POST['product'])) {
        /** Start: if All the product is uncheck **/
        
        $delete_package_category_product ="DELETE FROM package_category_products WHERE PCP_package_id='$ppid' AND PCP_package_category_id='$pid'";
      
        $delpro = mysqli_query($con, $delete_package_category_product);
        if ($delpro) {
            
            $product = array();
            $msg = "Package Category Porducts is Successfully saved";
        } else {
            $err = "Could not save.";
        }
        /** End: if All the product is uncheck **/
    } else if (isset($_POST['product'])) {
        //checking if checkbox submitted
        /** Start: Delete the unselected product **/
        
        $proin = implode(',', $product);
        $delpro = mysqli_query($con, "DELETE FROM package_category_products WHERE PCP_package_id='$ppid' AND PCP_package_category_id='$pid'  AND PCP_product_id NOT IN($proin)");
        /** End: Delete the unselected product **/
        $getid = mysqli_query($con, "SELECT PCP_product_id FROM package_category_products WHERE PCP_package_id='$ppid' AND PCP_package_category_id='$pid'");
        $countrow = mysqli_num_rows($getid);

        if ($countrow > 0) {
            while ($rowid = mysqli_fetch_object($getid)) {
                $proiddb[] = $rowid->PCP_product_id;
            }
            $diff = array_diff($product, $proiddb); //getting the difference between submitted id and existing id

            foreach ($diff as $products) { //getting product id from array
                $pid = base64_decode($_GET['pid']); //product id

                $saverelated = mysqli_query($con, "INSERT INTO package_category_products(PCP_package_id,PCP_package_category_id,PCP_product_id,PCP_created_by) VALUES('$ppid','$pid','$products','$PCP_updated_by')");

                if ($saverelated) {
                    $msg = "Package Category Porducts is Successfully saved";
                } else {
                    $err = "Could not save1.";
                }
            }
        } else {
            /** Start: save selected product **/
            
            foreach ($product as $products) { 
                //getting product id from array
                $pid = base64_decode($_GET['pid']); //product id
                $saverelated = mysqli_query($con, "INSERT INTO package_category_products(PCP_package_id,PCP_package_category_id,PCP_product_id,PCP_created_by) VALUES('$ppid','$pid','$products','$PCP_updated_by')");
                if ($saverelated) {

                    $msg = "Package Category Porducts is Successfully saved";
                } else {
                    $err = "Could not save package category products.";
                }
            }
            /** End: save selected product **/
        }
    }
}
/** End: Update the selected product **/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Package Category Product Panel | Package Category Product</title>

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
        <script>
            function related(str)
            {
                var id = document.frm1.pid.value;

                if (str == "")
                {
                    document.getElementById("txtHint").innerHTML = "";
                    return;
                }
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function()
                {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                    {
                        document.getElementById("shwClr").innerHTML = xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "ajaxcat.php?c=" + str + "&id=" + id, true);
                xmlhttp.send();
            }
        </script>

        <!--Effect on left error menu, top message menu, body-->
        <!--delete tags-->

        <!--select box script-->
        <script type="text/javascript">
            checked = false;
            function checkedAll(frm1) {
                var aa = document.getElementById('frm1');
                if (checked == false)
                {
                    checked = true
                }
                else
                {
                    checked = false
                }
                for (var i = 0; i < aa.elements.length; i++)
                {
                    aa.elements[i].checked = checked;
                }
            }
        </script>
        <!--end select box script-->

    </head>

    <body>


<?php include basePath('admin/top_navigation.php'); ?>

<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <div class="leftNav">
<?php include basePath('admin/product_settings/product_settings_left_navigation.php'); ?>
            </div>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Package Category Product</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>

                <!-- Charts -->


                <form id ="frm1" action="index.php?pid=<?php echo $_GET['pid']; ?>&ppid=<?php echo $_GET['ppid']; ?>&package_user_type=<?php echo $_GET['package_user_type'];?>" method="post">            
                    <div class="table">
                        <div class="head">
                            <h5 class="iFrames">Select Product For <?php echo getFieldValue($tableNmae = 'categories', $fieldName = 'category_name', $where = 'category_id='.$pid);?> Of <?php echo $_SESSION["package_name"];?></span></h5></div>
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                            <thead>
                                <tr>
                                    <th style="mystyle">Select</th>
                                    <th>Product Name</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
while ($prorow = mysqli_fetch_object($prosel)) {
    ?>
                                    <tr class="gradeA">
                                        <td><input type="checkbox" name="product[]" value=<?php echo $prorow->product_id; ?>
    <?php
    foreach ($product as $pro) {
        if ($pro == $prorow->product_id) {
            echo 'checked=checked';
        }
    }
    ?>   /></td>
                                        <td><?php echo $prorow->product_title; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <input type="submit" name="update" value="Update" class="greyishBtn submitForm" />
                </form>
            </div>

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
