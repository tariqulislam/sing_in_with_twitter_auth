<?php
include ('../../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];

//saving tags in database

$pid = base64_decode($_GET['pid']);



if (isset($_POST['update'])) {
    extract($_POST);
    if (isset($_POST['product'])) { //checking if checkbox submitted
        $proin = implode(',', $product);
        $delpro = mysqli_query($con, "DELETE FROM products_upsell WHERE PU_current_product_id='$pid' AND PU_related_product_id NOT IN($proin)");

        $getid = mysqli_query($con, "SELECT PU_related_product_id FROM products_upsell WHERE PU_current_product_id='$pid'");
        $countrow = mysqli_num_rows($getid);

        if ($countrow > 0) {
            while ($rowid = mysqli_fetch_object($getid)) {
                $proiddb[] = $rowid->PU_related_product_id;
            }
            $diff = array_diff($product, $proiddb); //getting the difference between submitted id and existing id
            foreach ($diff as $products) { //getting product id from array
                //echo $proin = implode(',',$product);
                $pid = base64_decode($_GET['pid']); //product id
                $p = "priority" . $products; //getting priority variable name for the product selected
                $priority = $$p; //getting priority value from created variable
                $saverelated = mysqli_query($con, "INSERT INTO products_upsell(PU_current_product_id,PU_related_product_id,PU_priority_id,PU_created_by_id) VALUES('$pid','$products','$priority','$aid')");

                if ($saverelated) {
                    $msg = "Successfully saved";
                } else {
                    $err = "Could not save.";
                }
            }
        } else {
            foreach ($product as $products) { //getting product id from array
                //echo $proin = implode(',',$product);
                $pid = base64_decode($_GET['pid']); //product id
                $p = "priority" . $products; //getting priority variable name for the product selected
                $priority = $$p; //getting priority value from created variable
                $saverelated = mysqli_query($con, "INSERT INTO products_upsell(PU_current_product_id,PU_related_product_id,PU_priority_id,PU_created_by_id) VALUES('$pid','$products','$priority','$aid')");

                if ($saverelated) {
                    $msg = "Successfully saved";
                } else {
                    $err = "Could not save.";
                }
            }
        }
    }
}


$sql = "SELECT * FROM products
LEFT JOIN products_upsell ON products_upsell.PU_related_product_id = products.product_id
WHERE product_id != $pid
";
$prosel = mysqli_query($con, $sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Product Upsell</title>

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
<?php include('left_navigation.php'); ?>
            </div>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Product Upsell Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>

                <!-- Charts -->


                <form id ="frm1" action="upsell.php?pid=<?php echo $_GET['pid']; ?>" method="post">            
                    <div class="table">
                        <div class="head">
                            <h5 class="iFrames">Upsell List</span></h5></div>
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                            <thead>
                                <tr>
                                    <td style="mystyle">Select</td>
                                    <th>Product Size</th>
                                    <th>Product Updated By</th>
                                    <th>Set Priority</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
while ($prorow = mysqli_fetch_array($prosel)) {
    ?>                        

                                    <tr class="gradeA">
                                        <td><input type="checkbox" name="product[]" value="<?php echo $prorow['product_id']; ?>" <?php if ($prorow['PU_current_product_id'] > 0) {
        echo "checked";
    } ?>/></td>
                                        <td><?php echo $prorow['product_title']; ?></td>
                                        <td><?php echo $prorow['product_short_description']; ?></td>
                                        <td style="width:80px !important"><input name="priority<?php echo $prorow['product_id']; ?>" type="text" style="width:50px !important" maxlength="3" value="<?php echo $prorow['PU_priority_id']; ?>" /></td>
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
