<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

//saving tags in database

$aid = @$_SESSION['admin_id']; //getting admin id

$title = "";
$desc = "";
$priority = "";


if (isset($_POST['submit'])) {
    $alphanumeric = "/\w|\s+/"; //regular expression
    extract($_POST);
    $cat = $categories[0];

    $CheckIfExist = "SELECT * FROM categories WHERE category_name='$title' AND category_parent_id='$cat'";
    $ExecuteCheck = mysqli_query($con, $CheckIfExist);
    $CheckedRow = mysqli_num_rows($ExecuteCheck);

    if ($ExecuteCheck) {
        if ($title == "") {
            $err = "Category title is required";
        } elseif ($desc == "") {
            $err = "Category description is required";
        } elseif (!preg_match($alphanumeric, $title)) {
            $err = "Category Title can only be alphanumeric";
        } elseif ($CheckedRow > 0) {
            $err = "Category Title already exist in record.";
        } elseif (!ctype_digit($priority)) {
            $err = "Category Priority can only be numeric.";
        } else {
            $cat = $categories[0];
            $cat_level = 0;
            /* Start: check catgory level: if category id greater than 0 */
            if ($cat > 0) {
                $level_check_query = "SELECT category_level FROM categories WHERE category_id=" . intval($cat);
                $level_check_result = mysqli_query($con, $level_check_query);
                if ($level_check_query) {
                    if (mysqli_num_rows($level_check_result) >= 1) {
                        $category_level_object = mysqli_fetch_object($level_check_result);
                        $category_level = $category_level_object->category_level;
                        $cat_level = $category_level + 1;
                        if ($category_level > $config['MAX_CATEGORY_LEVEL'] - 1) {
                            $err = "Maximum category level exict";
                        }
                    }
                }
          
            }
            /* End: check catgory level:if category id greater than 0 */

            /* Start: save category to database */
            if ($err == '') {
                //$cat_level = $category_level + 1;
                $tid = time(); /* use in image name */
                $cat_logo = basename($_FILES['logo']['name']);
                $info = pathinfo($cat_logo, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                $image_name = $title . '-' . $tid . '.' . $info; /* create custom image name color id will add  */
                $image_source = $_FILES["logo"]["tmp_name"];

                if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/category_logo/')) {
                    mkdir($config['IMAGE_UPLOAD_PATH'] . '/category_logo/', 0777, TRUE);
                }
                $image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/category_logo/' . $image_name;

                if ($_FILES["logo"]["tmp_name"] != "") {
                    move_uploaded_file($image_source, $image_target_path);
                }

                $AddCategory = '';
                $AddCategory .= ' category_name = "' . mysqli_real_escape_string($con, $title) . '"';
                $AddCategory .= ', category_description = "' . mysqli_real_escape_string($con, $desc) . '"';
                $AddCategory .= ', category_parent_id = "' . mysqli_real_escape_string($con, $cat) . '"';
                $AddCategory .= ', category_priority = "' . mysqli_real_escape_string($con, $priority) . '"';
                $AddCategory .= ', category_logo = "' . mysqli_real_escape_string($con, $image_name) . '"';
                $AddCategory .= ', category_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';
                $AddCategory .= ', category_level = "' . $cat_level . '"';
                $SqlAddCategory = "INSERT INTO `categories` SET $AddCategory";
                $ExecuteAddCategory = mysqli_query($con, $SqlAddCategory);

                if ($ExecuteAddCategory) {
                    $msg = "Category added successfully.";
                } else {
                    if (DEBUG) {
                        echo "ExecuteAddCategory mysql_error: " . mysqli_error($con);
                    }
                    $err = "Category could not add successfully";
                }
            }
            /* End: save category to database */
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
        <script src="<?php echo baseUrl('admin/js/jquery.min.js'); ?>"></script>
        <script src ="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type = "text / javascript" ></script>

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
                <div class="title"><h5>Category Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>


                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Category</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="index.php" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Add Category</h5></div>
                                        <div class="rowElem noborder"><label>Category Title:</label><div class="formRight"><input name="title" type="text" maxlength="20" value="<?php echo $title; ?>"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Category Description:</label><div class="formRight"><textarea rows="5" cols="" class="auto" name="desc"><?php echo $desc; ?></textarea></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Category Priority:</label><div class="formRight"><input name="priority" type="text" maxlength="20" value="<?php echo $priority; ?>"/></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Category Logo:</label><div class="formRight"><input name="logo" type="file"/></div><div class="fix"></div></div>
                                        <!--Start category div-->
                                        <div class="rowElem"><label>Category:</label><div class="formRight tree">

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
//$c->checked = ;
echo $c->viewTree();
?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div><div class="fix"></div></div>

                                        <!--                                         End category div   -->




                                        <input type="submit" name="submit" value="Add Category" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>   
                                </fieldset>

                            </form>		

                        </div>





                        <div class="table">
                            <div class="head">
                                <h5 class="iFrames">Tags List</h5></div>
                            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                <thead>
                                    <tr>
                                        <th>Category ID</th>
                                        <th>Category Title</th>
                                       <!-- <th>Category Description</th>-->
                                        <th>Parent Category</th>
                                        <th>Category Updated</th>
                                        <th>Category Updated By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$catsql = mysqli_query($con, "SELECT * FROM `categories`");
while ($catrow = mysqli_fetch_array($catsql)) {
    ?>                        

                                        <tr class="gradeA">
                                            <td><?php echo $catrow['category_id']; ?></td>
                                            <td><?php echo $catrow['category_name']; ?></td>
                                            <!--<td class="center"><?php echo $catrow['category_description']; ?></td>-->
                                            <td class="">
    <?php
    //selecting category
    $pcat = $catrow['category_parent_id'];
    $pcatsql = mysqli_query($con, "SELECT * FROM `categories` WHERE category_id='$pcat'");
    $pcatrow = mysqli_fetch_array($pcatsql);
    if ($pcat == 0) {
        echo "Parent Category";
    } else {
        echo $pcatrow['category_name'];
    }
    ?>
                                            </td>
                                            <td><?php echo $catrow['category_updated']; ?></td>
                                            <td>
    <?php
    //selecting admin
    $aid = $catrow['category_updated_by'];
    $adminsql = mysqli_query($con, "SELECT (admin_full_name) FROM admins WHERE admin_id='$aid'");
    $adminrow = mysqli_fetch_array($adminsql);
    echo $adminrow[0];
    ?></td>
                                            <td class="center"><a href="edit.php?id=<?php echo base64_encode($catrow['category_id']); ?>" title="Edit"><img src="../../images/pencil-grey-icon.png" height="14" width="14" alt="Edit" /></a></td>
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
