<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
//saving tags in database
$aid = @$_SESSION['admin_id']; //getting admin id

$title = "";
$code = "";

if (isset($_POST['submit'])) {

    $alphanumeric = "/\w|\s+/"; //regular expression
    //searching for similar color name
    extract($_POST);
    $colorsrch = mysqli_query($con, "SELECT * FROM colors WHERE color_title='$title'");
    $rowsrch = mysqli_num_rows($colorsrch);
    if ($rowsrch > 0) {
        $err = "You already have this color in record.";
    } elseif ($title == "") {
        $err = "Color title is required.";
    } elseif ($_FILES['img']['size'] == 0 || empty($_FILES['img']['tmp_name'])) {
        $err = "Color image is required";
    } elseif (!preg_match($alphanumeric, $title)) {
        $err = "Color Title can only be alphanumeric";
    } else {
       
        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/color_img/')) {
            mkdir($config['IMAGE_UPLOAD_PATH'] . '/color_img/', 0777, TRUE);
        }
        $target_dir = $config['IMAGE_UPLOAD_PATH'] . '/color_img/';
        $full_path = $target_dir . basename($_FILES['img']['name']);
        $image_name = $_FILES['img']['name'];
        move_uploaded_file($_FILES['img']['tmp_name'], $full_path);

        $AddColor = '';
        $AddColor .= ' color_title = "' . mysqli_real_escape_string($con, $title) . '"';
        $AddColor .= ', color_code = "' . mysqli_real_escape_string($con, $code) . '"';
        $AddColor .= ', color_image_name = "' . mysqli_real_escape_string($con, $image_name) . '"';
        $AddColor .= ', color_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';

        $SqlAddColor = "INSERT INTO `colors` SET $AddColor";
        $ExecuteAddColor = mysqli_query($con, $SqlAddColor);

        if ($ExecuteAddColor) {
            $msg = "Color added successfully.";
        } else {
            if (DEBUG) {
                echo "ExecuteAddColor mysqlerror:" . mysqli_error($con);
            }
            $err = "Color could not added successfully.";
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Color</title>

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
            function del(pin_id1)
            {
                if (confirm('Are you sure to delete this size!!'))
                {
                    window.location = 'index.php?del=' + pin_id1;
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
<?php include basePath('admin/product_settings/product_settings_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Color Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Color</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="index.php" method="post" class="mainForm" enctype="multipart/form-data" >

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Add Color</h5></div>
                                        <div class="rowElem noborder">
                                            <label>Insert Color Title:</label>
                                            <div class="formRight">
                                                <input name="title" type="text" maxlength="20" value="<?php echo $title; ?>"/>
                                            </div>
                                        </div>
                                        <div class="fix"></div>

                                        <div class="rowElem">
                                            <label>Pick Color:</label>
                                            <div class="formRight">
                                                <input type="text" class="colorpick" id="colorpickerField" name="code" value="<?php echo $code; ?>" />
                                                <label for="colorpickerField" class="pick"></label>
                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <div class="rowElem noborder">
                                            <label>Upload Color Image:</label>
                                            <div class="formRight">
                                                <input name="img" type="file" />
                                            </div>
                                        </div>       



                                        <input type="submit" name="submit" value="Add Color" class="greyishBtn submitForm" />
                                        <div class="fix"></div>


                                    </div>
                                </fieldset>

                            </form>		


                        </div>




                        <div class="table">
                            <div class="head">
                                <h5 class="iFrames">Size List</h5></div>
                            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                <thead>
                                    <tr>
                                        <th>Color ID</th>
                                        <th>Color Title</th>
                                        <th>Color</th>
                                        <th>Color Image</th>
                                        <th>Size Last Updated</th>
                                        <th>Size Updated By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$colorsql = mysqli_query($con, "SELECT * FROM colors");
while ($colorrow = mysqli_fetch_array($colorsql)) {
    ?>                        

                                        <tr class="gradeA">
                                            <td><?php echo $colorrow['color_id']; ?></td>
                                            <td><?php echo $colorrow['color_title']; ?></td>
                                            <td><div style="margin:0 auto; background:#<?php echo $colorrow['color_code']; ?>; height:10px; width:10px;"></div></td>
                                            <td><img src="<?php echo baseUrl('/upload/color_img/' . $colorrow['color_image_name']); ?>" width="20px" style="margin:0 auto !important;" /></td>
                                            <td>
    <?php echo $colorrow['color_updated']; ?></td>
                                            <td><?php
                                                $aid = $colorrow['color_updated_by'];
                                                $adminsql = mysqli_query($con, "SELECT (admin_full_name) FROM admins WHERE admin_id='$aid'");
                                                $adminrow = mysqli_fetch_array($adminsql);
                                                echo $adminrow[0];
                                                ?></td>
                                            <td class="center"><a href="edit.php?id=<?php echo base64_encode($colorrow['color_id']); ?>"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png');?>" height="14" width="14" /></a></td>
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