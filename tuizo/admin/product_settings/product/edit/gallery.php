<?php
include ('../../../../config/config.php');
include basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];
//http://localhost/tuizo/admin/product_settings/product/edit/gallery.php?edit=1&pid=Mw==&updateImageId=9
$pid = base64_decode($_GET['pid']);
//$edit = base64_decode($_REQUEST["edit"]);
$color = '';
$productColor = '';
if (isset($_REQUEST["edit"]) && base64_decode($_REQUEST["edit"]) == 1) {
    $edit = 1;
    $updateImageId = $_REQUEST["updateImageId"];
    $updateImageId = base64_decode($updateImageId);
    $productColorIdSql = "SELECT * FROM product_images WHERE PI_id=".  intval($updateImageId);
    $productColorIdSqlResult = mysqli_query($con, $productColorIdSql);
    $productColorIdSqlResultRowObj = mysqli_fetch_object($productColorIdSqlResult);
    $productColor = $productColorIdSqlResultRowObj->PI_color;
} else {
    $edit = 0;
    $updateImageId = '';
}
if (isset($_POST['update'])) {
    extract($_POST);
    if ($edit == 0) {
        if ($_FILES["img"]["error"] > 0) {
            $err = 'Image could not upload please try again.';
        }
    }
    if ($color == '') {
        $err = 'color name is required';
    }
    if ($color == 0) {
        $err = 'color name is reqiured';
    }
    if ($err == '') {
        $tid = time(); /* use in image name */
        $image = basename($_FILES['img']['name']);
        $info = pathinfo($image, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
        $image_name = $pid . '-' . $tid . '.' . $info; /* create custom image name color id will add  */
        $image_source = $_FILES["img"]["tmp_name"];


        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/product/original/')) {
            mkdir($config['IMAGE_UPLOAD_PATH'] . '/product/original/', 0777, TRUE);
        }
        $image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/product/original/' . $image_name;

        //saving image into db

            $AddImage = '';
            $AddImage .= ' PI_product_id = "' . mysqli_real_escape_string($con, $pid) . '"';
            if (move_uploaded_file($image_source, $image_target_path)) {
            if ($edit == 0) {
                $AddImage .= ', PI_file_name = "' . mysqli_real_escape_string($con, $image_name) . '"';
            } else {
                if ($image_name != '') {
                    $AddImage .= ', PI_file_name = "' . mysqli_real_escape_string($con, $image_name) . '"';
                } else {
                    $AddImage .= '';
                }
            }
            }
            $AddImage .= ', PI_color = "' . mysqli_real_escape_string($con, $color) . '"';
            $AddImage .= ', PI_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';
            if ($edit == 0) {
                $SqlAddImage = "INSERT INTO product_images SET $AddImage";
            } else {
                $SqlAddImage = "UPDATE product_images SET $AddImage WHERE PI_id=" . intval($updateImageId);
                $productColor = $color;
            }
            $ExecuteAddImage = mysqli_query($con, $SqlAddImage);

            if ($ExecuteAddImage) {
                if($edit == 0) {
                $msg = "Image added successfully.";
                } else {
                    $msg = "Image update successfully.";
                }
            } else {
                if (DEBUG) {
                    echo "ExecuteAddImage mysqli_error: " . mysqli_error($con);
                }
                if($edit == 0) {
                $err = "Image could not add successfully.";
                } else {
                    $err = "Image could not update successfully.";
                }
            }


            $image_source = $image_target_path; /* iimage uploaded now change the source set to ufrom ariginal path */
            if (file_exists($image_source)) {
                /* now i will resize the image into three folder */
                $zebra = new Zebra_Image();
                $zebra->source_path = $image_source; /* original image path */
                /* Start : large */

                if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/product/large/')) {
                    mkdir($config['IMAGE_UPLOAD_PATH'] . '/product/large/', 0777, TRUE);
                }

                $zebra->target_path = $config['IMAGE_UPLOAD_PATH'] . '/product/large/' . $image_name;
                $imgpathlar = '/product/large/' . $image_name;
                $width = 500; /* it will come latter from config_settings table */
                if (!$zebra->resize($width)) {
                    switch ($zebra->error) {

                        case 1:
                            $err = 'Source file could not be found!';
                            break;
                        case 2:
                            $err = 'Source file is not readable!';
                            break;
                        case 3:
                            $err = 'Could not write target file!';
                            break;
                        case 4:
                            $err = 'Unsupported source file format!';
                            break;
                        case 5:
                            $err = 'Unsupported target file format!';
                            break;
                        case 6:
                            $err = 'GD library version does not support target file format!';
                            break;
                        case 7:
                            $err = 'GD library is not installed!';
                            break;
                    }
                } else {

                    // image resized 
                }
                /* End : large */

                /* Start : mid */
                if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/product/mid/')) {
                    mkdir($config['IMAGE_UPLOAD_PATH'] . '/product/mid/', 0777, TRUE);
                }
                $zebra->target_path = $config['IMAGE_UPLOAD_PATH'] . '/product/mid/' . $image_name;
                $imgpathmed = '/product/mid/' . $image_name;
                $width = 250; /* it will come latter from config_settings table */
                if (!$zebra->resize($width)) {
                    switch ($zebra->error) {

                        case 1:
                            $err = 'Source file could not be found!';
                            break;
                        case 2:
                            $err = 'Source file is not readable!';
                            break;
                        case 3:
                            $err = 'Could not write target file!';
                            break;
                        case 4:
                            $err = 'Unsupported source file format!';
                            break;
                        case 5:
                            $err = 'Unsupported target file format!';
                            break;
                        case 6:
                            $err = 'GD library version does not support target file format!';
                            break;
                        case 7:
                            $err = 'GD library is not installed!';
                            break;
                    }
                } else {

                    // image resized 
                }
                /* End : mid */
                /* Start : small */
                if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/product/small/')) {
                    mkdir($config['IMAGE_UPLOAD_PATH'] . '/product/small/', 0777, TRUE);
                }
                $zebra->target_path = $config['IMAGE_UPLOAD_PATH'] . '/product/small/' . $image_name;
                $imgpathsma = '/product/small/' . $image_name;
                $width = 125; /* it will come latter from config_settings table */
                if (!$zebra->resize($width)) {
                    switch ($zebra->error) {

                        case 1:
                            $err = 'Source file could not be found!';
                            break;
                        case 2:
                            $err = 'Source file is not readable!';
                            break;
                        case 3:
                            $err = 'Could not write target file!';
                            break;
                        case 4:
                            $err = 'Unsupported source file format!';
                            break;
                        case 5:
                            $err = 'Unsupported target file format!';
                            break;
                        case 6:
                            $err = 'GD library version does not support target file format!';
                            break;
                        case 7:
                            $err = 'GD library is not installed!';
                            break;
                    }
                } else {

                    // image resized 
                }
                /* End : small */
            }
        }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Image Gallery</title>

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


        <script>
            function getColorCodeOrImage(colorId)
            {
                $.ajax({
                    type: "POST",
                    url: "ajaxcolor.php",
                    data: {color_id: colorId},
                    success: function(result) {
                        $("#shwClr").html('');
                        $("#shwClr").html(result);
                    }
                });
            }
            function delete_product_image(PI_id, PI_product_id)
            {
                $.ajax({
                    type: "POST",
                    url: "gallerydelete.php",
                    data: {PI_id: PI_id, PI_product_id: PI_product_id},
                    success: function(result) {
                        var obj = jQuery.parseJSON(result);
                        $("#showimg").html('');
                        $("#showimg").html(obj.product_gallery);
                        var confirmation = '';
                        if (obj.msg === '')
                        {
                            confirmation = obj.error;
                        }
                        else
                        {
                            confirmation = obj.msg;
                        }
                        var url = '<?php echo baseUrl('admin/product_settings/product/edit/gallery.php'); ?>?pid=<?php echo $_GET['pid']; ?>&msg=' + confirmation;
                        window.location.replace(url);
                    }
                });
                
            }
        </script>
        <?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
        }
        ?>
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

        <script type="text/javascript">
            function redirect()
            {
                if (confirm('Do you want to leave Product Editing Module?'))
                {
                    window.location = "../index.php";
                }
            }
            
        </script>
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

            <!--<div class="leftCol">
                <div class="title">
                    <h5>Note</h5>

                </div>
                <div class="leftColInner">
                    This is admin module you can create , update and see the list of admin here
                </div>
            </div>

        </div>-->

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Product Gallery</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Gallery</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="gallery.php?pid=<?php echo base64_encode($pid); ?>&edit=<?php echo base64_encode($edit)?>&updateImageId=<?php echo base64_encode($updateImageId);?>" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Gallery For <?php echo getFieldValue($tableNmae = 'products', $fieldName = 'product_title', $where = 'product_id='.$pid)?></h5></div>

                                        <div class="rowElem">
                                            <label>Product Color :</label>
                                            <div class="formRight">
                                                <div id="colors">
                                                    <select name="color"  onchange="getColorCodeOrImage(this.value)">
                                                        <option value="0">Select Product Color</option>	
                                                        <?php
                                                        /** Start: get Color by product id * */
                                                        if($edit = 0) {
                                                        $query_for_product_color = "SELECT
                                c.color_id,c.color_title,c.color_code
                            FROM
                                colors c
                            WHERE c.color_id NOT IN(SELECT PI_color FROM product_images WHERE PI_product_id='" . intval($pid) . "')";
                                                        } else {
                                                        
                                                        $query_for_product_color = "SELECT
                                c.color_id,c.color_title,c.color_code
                            FROM
                                colors c
                            WHERE c.color_id NOT IN(SELECT PI_color FROM product_images WHERE PI_product_id='" . intval($pid) . "' && PI_color!='".$productColor."')";
                                                        }     
                                                        $result_of_product_color = mysqli_query($con, $query_for_product_color);
                                                        /** End: get Color by product id * */
                                                        if ($result_of_product_color) {
                                                            while ($rows = mysqli_fetch_object($result_of_product_color)) {
                                                                ?>
                                                                <option value="<?php echo $rows->color_id; ?>" <?php if($productColor==$rows->color_id) echo 'selected="selected"'?>><?php echo $rows->color_title; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>&nbsp;&nbsp;&nbsp;&nbsp;<div id="shwClr"  style="position:relative; left:170px; bottom:10px">Select a color.</div>&nbsp;&nbsp;&nbsp;&nbsp;
                                            </div>

                                        </div>

                                        <div class="rowElem noborder"><label>Product Image:</label><div class="formRight">
                                                <input name="img" type="file"/>
                                            </div><div class="fix"></div></div>


                                        <input type="submit" name="update" value="Upload Image" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>
                                </fieldset>

                            </form>		
                        </div>
                        <div class="widget first">
                            <div class="head"><h5 class="iPreview">Product Gallery For <?php echo getFieldValue($tableNmae = 'products', $fieldName = 'product_title', $where = 'product_id='.$pid)?></h5></div>
                            <div class="pics">
                                <div id="showimg">
                                    <ul>
                                        <?php
                                        /** Start: show Product Image to Gallery * */
                                        $selimage = mysqli_query($con, "SELECT * FROM product_images WHERE PI_product_id='$pid'");
                                        /** Start: show Product Image to Gallery * */
                                        while ($showimg = mysqli_fetch_object($selimage)) {
                                            ?>
                                            <li><a href="<?php echo baseUrl('upload/product/large/'.$showimg->PI_file_name); ?>" data-lightbox="roadtrip" title=""><img src="<?php echo baseUrl('upload/product/small/' . $showimg->PI_file_name); ?>" alt="" height="84px" width="100px" /></a>
                                                <div class="actions">
                                                    <a href="<?php echo baseUrl("admin/product_settings/product/edit/gallery.php?pid=".base64_encode($pid)."&edit=".  base64_encode(1)."&updateImageId=".base64_encode($showimg->PI_id)); ?>"><img src="<?php echo baseUrl('admin/images/edit.png'); ?>" alt="" /></a>&nbsp;
                                                    <a href="javascript:delete_product_image(<?php echo $showimg->PI_id ?>,<?php echo $showimg->PI_product_id; ?>);"><img src="<?php echo baseUrl('admin/images/delete.png'); ?>" alt="" /></a>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul> 
                                </div>

                                <div class="fix"></div>
                            </div>

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
