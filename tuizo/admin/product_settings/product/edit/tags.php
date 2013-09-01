<?php
include ('../../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id']; //getting admin id from session
//saving tags in database

$pid = base64_decode($_GET['pid']);


if (isset($_POST['add'])) {
    extract($_POST);
    $err = "";
    $msg = "";

    if (sizeof($tags) == 0) {
        $err = "Tag Selection is required.";
    } else {

        foreach ($tags as $tag) { //getting tag id from array

            $AddTag = '';
            $AddTag .= ' PT_product_id = "' . mysqli_real_escape_string($con, $pid) . '"';
            $AddTag .= ', PT_tag_id = "' . mysqli_real_escape_string($con, $tag) . '"';
            $AddTag .= ', PT_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';

            $AddTagSQL = "INSERT INTO product_tags SET $AddTag";
            $ExecuteAddTagSQL = mysqli_query($con, $AddTagSQL);

            if ($ExecuteAddTagSQL) {
                $msg = "Tag added successfully.";
            } else {
                if (DEBUG) {
                    echo "ExecuteAddTagSQL error" . mysqli_error($con);
                }
                $err = "Tag add failed";
            }
        }
    }
}

$pid = base64_decode($_GET['pid']);
$product = mysqli_query($con, "SELECT * FROM products WHERE product_id='$pid'");
$rowproduct = mysqli_fetch_array($product);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Tag Module</title>

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

            function delid(pin_id)
            {

                if (pin_id == "")
                {
                    document.getElementById("deltag").innerHTML = "";
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
                        document.getElementById("deltag").innerHTML = xmlhttp.responseText;
                    }
                }
                xmlhttp.open("GET", "tagdelete.php?id=" + pin_id, true);
                xmlhttp.send();
            }
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

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Product's Tag Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Tag Module</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="tags.php?pid=<?php echo base64_encode($pid); ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Tag Module For <?php echo getFieldValue($tableNmae = 'products', $fieldName = 'product_title', $where = 'product_id='.$pid)?></h5></div>
                                        <div class="rowElem">
                                            <label>Tags List :</label>
                                            <div class="formRight">

                                                <select name="tags[]" multiple="multiple" class="multiple" title="Click to Select a City" style="height:150px !important">
                                                    <option value="">-- Select Multiple Tags --</option> 
													<?php
													//getting inserted tages in product_tags table
													$SqlSelectTag = "SELECT PT_tag_id FROM product_tags WHERE PT_product_id='$pid'";
													$ExecuteQuery = mysqli_query($con,$SqlSelectTag);
													$TagArray = array();
													while($ResultArray = mysqli_fetch_object($ExecuteQuery)){
														$TagArray[] = $ResultArray->PT_tag_id;
													}
													
                                                    $seltag = mysqli_query($con, "SELECT * FROM tags");
                                                    while ($rowtag = mysqli_fetch_assoc($seltag)) 
													{
														if(!in_array($rowtag['tag_id'],$TagArray))
														{
                                                        ?>     
                                                        <option value="<?php echo $rowtag['tag_id']; ?>"><?php echo $rowtag['tag_title']; ?></option>
                                                        <?php
														}
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <input type="submit" name="add" value="Add Tags" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>
                                </fieldset>

                            </form>		


                        </div>

<?php
$gettag = mysqli_query($con, "SELECT * FROM product_tags WHERE PT_product_id=$pid");
if (mysqli_num_rows($gettag) > 0) {
    ?>                          
                            <div class="table">
                                <div class="head">
                                    <h5 class="iFrames">Related Product List</span></h5></div>
                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                    <thead>
                                        <tr>
                                            <td style="mystyle">Product Tag ID</td>
                                            <th>Tag Title</th>
                                            <th>Product Tag Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    while ($showtag = mysqli_fetch_array($gettag)) {
        ?>                        

                                            <tr class="gradeA">
                                                <td><?php echo $showtag['PT_id']; ?></td>
                                                <td><?php
        $tagid = $showtag['PT_tag_id'];
        $tagsql = mysqli_query($con, "SELECT (tag_title) FROM tags WHERE tag_id='$tagid'");
        $tagrow = mysqli_fetch_array($tagsql);
        echo $tagrow[0];
        ?></td>
                                                <td><?php
                                    $adminid = $showtag['PT_updated_by'];
                                    $adminsql = mysqli_query($con, "SELECT (admin_full_name) FROM admins WHERE admin_id='$adminid'");
                                    $adminrow = mysqli_fetch_array($adminsql);
                                    echo $adminrow[0];
        ?></td>
                                                <td><a href="javascript:delid(<?php echo $showimg['PT_id']; ?>);"title="Delete"><img src="<?php echo baseUrl('admin/images/deleteFile.png" alt="Delete') ?>" /></a></td>
                                            </tr>
                                                    <?php
                                                }
                                            } else {
                                                //nothing to do
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
