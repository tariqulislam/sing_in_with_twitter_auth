<?php
include ('../../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id']; //get admin id
$pid = base64_decode($_GET['pid']);
/** Start: Get the color for product for inventory * */
$query_for_inventory_color = "SELECT
    c.color_id,c.color_title,c.color_code
FROM
    colors c
WHERE c.color_id IN(SELECT PI_color FROM product_images WHERE PI_product_id=" . intval($pid) . ")";
$sqlcolor = mysqli_query($con, $query_for_inventory_color);
/** End: Get the color for product for inventory * */
$quan = '';
$weight = '';
$cost = '';
$price = '';
$color = 0;
$size = 0;
if (isset($_POST['update'])) {
    extract($_POST);
    $err = "";
    $msg = "";


    if ($color == 0) {
        $err = "Product color is required.";
    } elseif ($size == 0) {
        $err = "Product Size is required.";
    } elseif ($quan == "") {
        $err = "Product Quantity is required.";
    } elseif ($weight == "") {
        $err = "Product Weight is required.";
    } elseif ($cost == "") {
        $err = "Product Cost is required.";
    } elseif (!is_numeric($cost)) {
        $err = "Product Cost can only be numeric.";
    } elseif (!empty($price)) {

        if (!ctype_digit($price)) {
            $err = "Product Price can only be numeric.";
        } else {
            goto UpdateInventorySection;
        }
    } else {
        UpdateInventorySection:
        if ($price == "") {
            $price = 0;
        }
        $query_of_checkDuplicateInventoryItem = "SELECT * FROM product_inventories WHERE PI_product_id='" . $pid . "' AND PI_color_id='" . $color . "' AND PI_size_id='" . $size . "'";
        $result_of_checkDuplicateInventoryItem = mysqli_query($con, $query_of_checkDuplicateInventoryItem);

        if (mysqli_num_rows($result_of_checkDuplicateInventoryItem) >= 1) {
            $err = "same color,size product is already exists";
        } else {
            $UpdateInventory = '';
            $UpdateInventory .= ' PI_product_id = "' . mysqli_real_escape_string($con, $pid) . '"';
            $UpdateInventory .= ', PI_color_id = "' . mysqli_real_escape_string($con, $color) . '"';
            $UpdateInventory .= ', PI_size_id = "' . mysqli_real_escape_string($con, $size) . '"';
            $UpdateInventory .= ', PI_quantity = "' . mysqli_real_escape_string($con, $quan) . '"';
            $UpdateInventory .= ', PI_weight = "' . mysqli_real_escape_string($con, $weight) . '"';
            $UpdateInventory .= ', PI_cost = "' . mysqli_real_escape_string($con, $cost) . '"';
            $UpdateInventory .= ', PI_price = "' . mysqli_real_escape_string($con, $price) . '"';
            $UpdateInventory .= ', PI_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';

            $UpdateInventorySQL = "INSERT INTO product_inventories SET $UpdateInventory";
            $ExecuteInventory = mysqli_query($con, $UpdateInventorySQL);

            if ($ExecuteInventory) {
                $msg = "Product Inventory Information added successfully.";
            } else {
                if (DEBUG) {
                    echo "ExecuteGeneralInfo error" . mysqli_error($con);
                }
                $err = "Product Inventory Information could not added";
            }
        }
    }
}

//$pid = $_GET['pid'];
//$product = mysqli_query($con, "SELECT * FROM products WHERE product_id='$pid'");
//$rowproduct = mysqli_fetch_array($product);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Product Inventory</title>

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
            function getColorCodeOrImage(colorId)
            {
                $.ajax({
                    type: "POST",
                    url: "ajaxcolor.php",
                    data: {color_id: colorId},
                    success: function(result) {
                        //alert(result);
                        $("#shwClr").html('');
                        $("#shwClr").html(result);
                    }
                });
            }
        </script>   
        <script type="text/javascript">
            function redirect()
            {
                if (confirm('Do you want to leave Product Editing Module?'))
                {
                    window.location = "../index.php";
                }
            }

        </script>        
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
            <div class="leftNav">
                <?php include('left_navigation.php'); ?>
            </div>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Inventory Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Inventory</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="inventory.php?pid=<?php echo $_GET['pid']; ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Product Inventory For <?php echo getFieldValue($tableNmae = 'products', $fieldName = 'product_title', $where = 'product_id='.$pid)?></h5></div>

                                        <div class="rowElem noborder"><label>Product Quantity:</label><div class="formRight">
                                                <input name="quan" type="text" value="<?php echo $quan; ?>" maxlength="20" />
                                            </div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Product Weight:</label><div class="formRight">
                                                <input name="weight" type="text" value="<?php echo $weight; ?>" maxlength="20" />
                                            </div><div class="fix"></div></div>

                                        <div class="rowElem">
                                            <label>Product Color :</label>
                                            <div class="formRight">
                                                <select name="color"  onchange="getColorCodeOrImage(this.value)">
                                                    <option value="0">Select Product Color</option>	
                                                    <?php
                                                    while ($rowcolor = mysqli_fetch_object($sqlcolor)) {
                                                        ?>
                                                        <option value="<?php echo $rowcolor->color_id; ?>" <?php if($color ==$rowcolor->color_id) echo 'selected="selected"';?>><?php echo $rowcolor->color_title; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;<div id="shwClr" style="position:relative; left:170px; bottom:10px">Select a color.</div>&nbsp;&nbsp;&nbsp;&nbsp;
                                            </div>

                                        </div>

                                        <div class="rowElem">
                                            <label>Product Size :</label>
                                            <div class="formRight">
                                                <select name="size" >
                                                    <option value="0">Select Product Size</option>
                                                    <?php
                                                    $sqlsiz = mysqli_query($con, "SELECT * FROM sizes");
                                                    while ($rowsiz = mysqli_fetch_object($sqlsiz)) {
                                                        ?>
                                                        <option value="<?php echo $rowsiz->size_id; ?>" <?php if($size ==$rowsiz->size_id) echo 'selected="selected"';?>><?php echo $rowsiz->size_title; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="rowElem noborder"><label>Product Cost:</label><div class="formRight">
                                                <input name="cost" type="text" value="<?php echo $cost; ?>" maxlength="20"/>
                                            </div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Product Price:</label><div class="formRight">
                                                <input name="price" type="text" value="<?php echo $price; ?>" maxlength="20"/>
                                            </div><div class="fix"></div></div>



                                        <input type="submit" name="update" value="Update Inventory Details" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>
                                </fieldset>

                            </form>		


                        </div>

                        <?php
                        /** Start : Query for product inventory Grid * */
                        $query_of_product_inventory_grid = "SELECT 
     c.color_title,s.size_title,piv.PI_quantity,piv.PI_weight,piv.PI_cost,piv.PI_price,a.admin_full_name
FROM 
    product_inventories piv,
    colors c,
    sizes s,
    admins a

WHERE 
     piv.PI_color_id= c.color_id AND
     piv.PI_size_id= s.size_id AND
     piv.PI_updated_by =a.admin_id  AND
     PI_product_id='" . $pid . "'";
                        $result_of_product_invertory_grid = mysqli_query($con, $query_of_product_inventory_grid);
                        /** End : Query for product inventory Grid * */
                        $count = mysqli_num_rows($result_of_product_invertory_grid);
                        ?>                        
                        <div class="table">
                            <div class="head">
                                <h5 class="iFrames">Inventory List For <?php echo getFieldValue($tableNmae = 'products', $fieldName = 'product_title', $where = 'product_id='.$pid)?></h5></div>
                            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                <thead>
                                    <tr>
                                        <th>Product Color</th>
                                        <th>Product Size</th>
                                        <th>Product Quantity</th>
                                        <th>Product Weight</th>
                                        <th>Product Cost</th>
                                        <th>Product Price</th>
                                        <th>Product Updated By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($rows = mysqli_fetch_object($result_of_product_invertory_grid)) {
                                        ?>                        

                                        <tr class="gradeA">
                                            <td><?php echo $rows->color_title; ?></td>
                                            <td><?php echo $rows->size_title; ?></td>
                                            <td><?php echo $rows->PI_quantity; ?></td>
                                            <td><?php echo $rows->PI_weight; ?></td>
                                            <td><?php echo $rows->PI_cost; ?></td>
                                            <td><?php echo $rows->PI_price; ?></td>
                                            <td><?php echo $rows->admin_full_name; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
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
