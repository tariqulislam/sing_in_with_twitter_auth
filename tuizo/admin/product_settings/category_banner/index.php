<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
//saving tags in database

$aid = @$_SESSION['admin_id']; //getting admin id
//get predefined image size
$getset = mysqli_query($con, "SELECT * FROM config_settings");
if (mysqli_num_rows($getset) > 0) {
    $responses = array();
    while ($row = mysqli_fetch_assoc($getset)) {
        $responses[] = array(
            'Option Name' => $row['CS_option'],
            'Option Value' => $row['CS_value']
        );
    }
}
$definedsize = ($responses[4]['Option Value']); //getting defined size
$definedwidth = ($responses[5]['Option Value']); //getting defined width
$definedheight = ($responses[6]['Option Value']); //getting defined height

$title = "";
$desc = "";
$url = "";
$priority = "";



if (isset($_POST['submit'])) {

    $alphanumeric = "/\w|\s+/"; //regular expression

    extract($_POST);
    if (sizeof(@$categories) == 0) {
        $err = "Parent category is required.";
    } elseif ($title == "") {
        $err = "Category banner title is required.";
    } elseif ($desc == "") {
        $err = "Category banner description is required";
    } elseif ($url == "" || $url == "http://") {
        $err = "Category URL is required.";
    } elseif ($_FILES['banner']['name'] == "") {
        $err = "Category banner image.";
    } elseif (!preg_match($alphanumeric, $title)) {
        $err = "Category Banner Title can only be alphanumeric.";
    } else {
        foreach ($categories as $cat) {

            list($width, $height, $type, $attr) = getimagesize($_FILES["banner"]["tmp_name"]); //getting image height, width, type and attribute

            if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/category_banner/')) {
                mkdir($config['IMAGE_UPLOAD_PATH'] . '/category_banner/', 0777, TRUE);
            }
            @$dir = $config['IMAGE_UPLOAD_PATH'] . '/category_banner/'; //destination folder
            $ext = pathinfo($_FILES["banner"]["name"], PATHINFO_EXTENSION);
            $image_name = "Category-" . $cat . "_Time-" . time() . "." . $ext;
            @$target = $dir . $image_name;


            if ($width > $definedwidth || $height > $definedheight || $_FILES["banner"]["size"] > $definedsize) {
                $err = "Your image is not in correct shape.";
            } else {
                //setting url type
                if (isset($_POST['type'])) {
                    $urltype = "external";
                } else {
                    $urltype = "internal";
                }
                move_uploaded_file($_FILES['banner']['tmp_name'], $target);

                $AddCatBanner = '';
                $AddCatBanner .= ' CB_category_id = "' . mysqli_real_escape_string($con, $cat) . '"';
                $AddCatBanner .= ', CB_image_name = "' . mysqli_real_escape_string($con, $image_name) . '"';
                $AddCatBanner .= ', CB_title = "' . mysqli_real_escape_string($con, $title) . '"';
                $AddCatBanner .= ', CB_description = "' . mysqli_real_escape_string($con, $desc) . '"';
                $AddCatBanner .= ', CB_url = "' . mysqli_real_escape_string($con, $url) . '"';
                $AddCatBanner .= ', CB_url_type = "' . mysqli_real_escape_string($con, $urltype) . '"';
                $AddCatBanner .= ', CB_priority = "' . mysqli_real_escape_string($con, $priority) . '"';
                $AddCatBanner .= ', CB_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';

                $SqlCatBanner = "INSERT INTO category_banners SET $AddCatBanner";
                $ExecuteCatBanner = mysqli_query($con, $SqlCatBanner);

                if ($ExecuteCatBanner) {
                    $msg = "Category Banner added successfully.";
                } else {
                    if (DEBUG) {
                        echo "ExecuteCatBanner mysqli_error: " . mysqli_error($con);
                    }
                    $err = "Category Banner could not add successfully.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Admin Panel | Category</title>

        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />
        <script src="<?php echo baseUrl('admin/js/jquery.min.js'); ?>" type="text/javascript"></script>
        <!--tree view -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
        <script src ="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type = "text / javascript" ></script>
        <!--tree view -->
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


        <!--Start: tree view--> 

        <link href="<?php echo baseUrl('admin/css/tree_style.css'); ?>" rel="stylesheet" type="text/css" />

        <script type="text/javascript">
            var leftSpace = 0;
            jQuery(document).ready(function($) {
                $('.tree li').each(function() {

                    if ($(this).children('ul').length > 0) {
                        $(this).addClass('parent');
                        leftSpace += 13;

                    }
                    if ($(this).find('input').is(':checked')) {
                        $(this).addClass(' active');
                        $(this).parent().css('display', 'block');
                        $('.treeParent').css('width', leftSpace + 700);

                    }


                });

                $('.treeParent').css('width', 700);
                $('.treeParent li').removeClass('active');
                $('.tree li.parent > a').click(function() {
                    $('.treeParent').css('width', leftSpace + 700);
                    $('.tree').css('overflow-x', 'scroll');
                    $(this).parent().toggleClass('active');
                    $(this).parent().children('ul').slideToggle('fast');
                });
            });
        </script>
        <!--End: tree view-->       


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
                <div class="title"><h5>Category Banner Module</h5></div>

                <!-- Notification messages -->
            <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Category Banner</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="index.php" method="post" class="mainForm" enctype="multipart/form-data" >

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Add Category Banner</h5></div>

                                        <!--Start category div-->
                                        <div class="rowElem"><label>Parent Category:</label><div class="formRight tree">

<?php
include basePath('lib/category2.php');
$c = new Category2($con);
?> 

                                                <ul class="treeParent">
                                                    <li>
                                                        <a> </a>
                                                        <input type="radio" value="0" name="categories[]" />Root Category
                                                        <ul>
                                                <?php
                                                $c->inputType = 'radio';
                                                $c->checked = array();
                                                echo $c->viewTree();
                                                ?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div><div class="fix"></div></div>

                                        <!--                                         End category div   -->


                                        <div class="rowElem noborder">
                                            <label>Category Banner Title:</label>
                                            <div class="formRight">
                                                <input name="title" type="text" value="<?php echo $title; ?>"/>
                                            </div>
                                        </div>
                                        <div class="fix"></div>

                                        <div class="rowElem noborder">
                                            <label>Category Banner Description:</label>
                                            <div class="formRight">
                                                <textarea rows="5" cols="" class="auto" name="desc"><?php echo $desc; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="fix"></div>

                                        <div class="rowElem noborder">
                                            <label>Category Banner Priority:</label>
                                            <div class="formRight">
                                                <input name="priority" type="text" value="<?php echo $priority; ?>" maxlength="3"/>
                                            </div>
                                        </div>
                                        <div class="fix"></div>

                                        <div class="rowElem noborder">
                                            <label>Category URL:</label>
                                            <div class="formRight">
                                                <input name="url" type="text" value="http://"/>
                                            </div>
                                        </div>
                                        <div class="fix"></div>

                                        <div class="rowElem noborder">
                                            <label>Category URL Type</label>
                                            <div class="formRight">
                                                <input type="checkbox" name="type" value="External" /><label style="position:relative; bottom:8px; left:95px;">External</label>
                                            </div>
                                        </div> 
                                        <div class="fix"></div>   

                                        <div class="rowElem">
                                            <label>Select Banner Image:</label>
                                            <div class="formRight">
                                                <input type="file" name="banner"/>
                                            </div>
                                            <div class="fix"></div>
                                            <font color="#666666"><i>Image should be <strong><?php echo $definedheight . "X" . $definedwidth ?> pixels</strong> and <strong><?php echo ($definedsize / 1000); ?>KB</strong> by size</i></font></div>   



                                        <input type="submit" name="submit" value="Add Category Banner" class="greyishBtn submitForm" />
                                        <div class="fix"></div>


                                    </div>
                                </fieldset>

                            </form>		


                        </div>




                        <div class="table">
                            <div class="head">
                                <h5 class="iFrames">Size List</h5></div>
                            <div id="showCat">
                                <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                    <thead>
                                        <tr>
                                            <th>Banner ID</th>
                                            <th>Banner Title</th>
                                            <th>Banner Category</th>
                                            <th>Banner Image</th>
                                            <th>Banner Last Updated</th>
                                            <th>Banner Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
$catbansql = mysqli_query($con, "SELECT * FROM category_banners");
while ($catbanrow = mysqli_fetch_array($catbansql)) {
    ?>                        

                                            <tr class="gradeA">
                                                <td><?php echo $catbanrow['CB_id']; ?></td>
                                                <td><?php echo $catbanrow['CB_title']; ?></td>
                                                <td><?php echo $catbanrow['CB_category_id']; ?></td>
                                                <td align="center"><img src="<?php echo baseUrl('upload/category_banner/') . $catbanrow['CB_image_name']; ?>" width="40px" style="margin:0 auto !important;" /></td>
                                                <td><?php echo $catbanrow['CB_updated']; ?></td>
                                                <td><?php
                                        $aid = $catbanrow['CB_updated_by'];
                                        $adminsql = mysqli_query($con, "SELECT (admin_full_name) FROM admins WHERE admin_id='$aid'");
                                        $adminrow = mysqli_fetch_array($adminsql);
                                        echo $adminrow[0];
                                        ?></td>
                                                <td><a href="edit.php?pid=<?php echo base64_encode($catbanrow['CB_id']); ?>" title="Edit"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png')?>" height="12px" width="12px" /></a>&nbsp;&nbsp;<a href="javascript:delid(<?php echo $catbanrow['CB_id']; ?>);" title="Delete"><img src="<?php echo baseUrl('admin/images/deleteFile.png')?>" height="12px" width="12px" /></a></td>
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

        </div>
        <!-- Content End -->

        <div class="fix"></div>
        </div>

<?php /* include basePath('admin/footer.php'); */ ?>