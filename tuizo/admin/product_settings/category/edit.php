<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}


//saving tags in database

$aid = $_SESSION['admin_id']; //getting admin id
$tid = base64_decode($_GET['id']);
$chk = "";

$title = "";
$desc = "";
$priority = 0;

$catsql = mysqli_query($con, "SELECT * FROM `categories` WHERE category_id='$tid'");
if ($catsql) {
    $catrow = mysqli_fetch_array($catsql);
    $title = $catrow['category_name'];
    $desc = $catrow['category_description'];
    $priority = $catrow['category_priority'];
}

if (isset($_POST['update'])) {
    $alphanumeric = "/\w|\s+/"; //regular expression

    extract($_POST);
    if ($title == "") {
        $err = "Category title is required";
    } elseif ($desc == "") {
        $err = "Category description is required";
    } elseif (!preg_match($alphanumeric, $title)) {
        $err = "Category Title can only be alphanumeric";
    } elseif (!ctype_digit($priority)) {
        $err = "Category Priority can only be numeric.";
    } else {
        //check  the category is builtin
        $checkUpdateQuery = "SELECT * FROM categories WHERE category_id='$tid' AND category_type='builtin'";
        $checkUpdateResult = mysqli_query($con, $checkUpdateQuery);
        $countUpdateResultRow = mysqli_num_rows($checkUpdateResult);
        if ($countUpdateResultRow) {
            if ($countUpdateResultRow >= 1) {
                $err = "This category is not Changable";
            } else {
                /* Start: categoires  level check: if category id is greater than 0 */
                $cate = @$categories[0];
                $cat_level = 0;
                if ($cate > 0) {
                    $level_check_query = "SELECT category_level FROM categories WHERE category_id=" . intval($cate);
                    $level_check_result = mysqli_query($con, $level_check_query);
                    if ($level_check_result) {
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
                /* end categories level check: if category id is greater than 0 */

                if ($err == '') {
                    $UpdateCategory = '';
                    $UpdateCategory .= ' category_name = "' . mysqli_real_escape_string($con, $title) . '"';
                    $UpdateCategory .= ', category_parent_id = "' . mysqli_real_escape_string($con, $cate) . '"';
                    $UpdateCategory .= ', category_description = "' . mysqli_real_escape_string($con, $desc) . '"';
                    $UpdateCategory .= ', category_priority = "' . mysqli_real_escape_string($con, $priority) . '"';
                    $UpdateCategory .= ', category_updated_by = "' . mysqli_real_escape_string($con, $aid) . '"';
                    $UpdateCategory .= ', category_level = "' . $cat_level . '"';


                    $SqlUpdateCategory = "UPDATE `categories` SET $UpdateCategory WHERE category_id='$tid'";
                    $ExecuteUpdateCategory = mysqli_query($con, $SqlUpdateCategory);

                    if ($ExecuteUpdateCategory) {
                        $msg = "Category updated successfully";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateCategory mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Category could not update successfully";
                    }
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
                <div class="title"><h5>Tax Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>




                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Tax</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Edit Tags</h5></div>
                                        <div class="rowElem noborder"><label>Category Title:</label><div class="formRight"><input name="title" type="text" maxlength="20" value="<?php echo $title; ?>"/></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Category Description:</label><div class="formRight"><textarea rows="5" cols="" class="auto" name="desc"><?php echo $desc; ?></textarea></div><div class="fix"></div></div>
                                        <div class="rowElem"><label>Category Priority:</label><div class="formRight"><input name="priority" type="text" maxlength="5" value="<?php echo $priority; ?>"/></div><div class="fix"></div></div>
<?php
$pcat = $catrow['category_parent_id'];
?>                                        


                                        <!--Start category div-->
                                        <div class="rowElem"><label>Parent Category:</label><div class="formRight tree">

<?php
include basePath('lib/category2.php');
$c = new Category2($con);
?> 
                                                <?php
                                                $chk = "";
                                                if (@$categories[0] == 0) {
                                                    $chk = 'checked="checked"';
                                                }
                                                ?>
                                                <ul class="treeParent">
                                                    <li>
                                                        <a> </a>
                                                        <input type="radio" value="0" name="categories[]" <?php echo $chk; ?> />Root Category
                                                        <ul>
<?php
$c->inputType = 'radio';
$c->checked = array($pcat);
echo $c->viewTree();
?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div><div class="fix"></div></div>

                                        <!--                                         End category div   -->

<?php ?>

                                        <input type="hidden" name="id" value="<?php echo $catrow['category_id']; ?>" />
                                        <input type="submit" name="update" value="Update Category" class="greyishBtn submitForm" />
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