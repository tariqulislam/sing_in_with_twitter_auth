<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$admin_id = 0;
if (!isset($_REQUEST['id']) OR $_REQUEST['id'] == '') {
    $link = 'admin_list.php?err=' . base64_encode('Id missing');
    redirect($link);
} else {
    $admin_id = intval(base64_decode($_REQUEST['id']));
}


$admin_name = '';
$admin_email = '';
$admin_type = '';
$adminSql = "SELECT * FROM admins WHERE admin_id=$admin_id LIMIT 1";
$adminSqlResult = mysqli_query($con, $adminSql);
if ($adminSqlResult) {
    $adminSqlResultRowObj = mysqli_fetch_object($adminSqlResult);
    if (isset($adminSqlResultRowObj->admin_id)) {
        $admin_name = $adminSqlResultRowObj->admin_full_name;
        $admin_email = $adminSqlResultRowObj->admin_email;
        $admin_type = $adminSqlResultRowObj->admin_type;
    } else {
        $link = 'admin_list.php?err=' . base64_encode('Id does not match!!');
        redirect($link);
    }
} else {
    if (DEBUG) {
        echo 'adminSqlResult Error: ' . mysqli_error($con);
    }
}


if (isset($_POST['admin_edit']) AND $_POST['admin_edit'] == 'Submit') {

    $admin_name = $_POST['admin_full_name'];
    $admin_email = $_POST['admin_email'];
    $admin_type = $_POST['admin_type'];

    if (isset($_POST['admin_full_name']) AND $_POST['admin_full_name'] == '') {
        $err = 'Name filed is required!!';
    } elseif (isset($_POST['admin_email']) AND $_POST['admin_email'] == '') {
        $err = 'Email filed is required!!';
    } elseif (isset($_POST['admin_email']) AND !isValidEmail($_POST['admin_email'])) {
        $err = 'Valid email is required!!';
    } elseif (isset($_POST['admin_type']) AND $_POST['admin_type'] == '') {
        $err = 'Type filed is required!!';
    } else {
        /* Start :Checking the user already exist or not */
        $adminCheckSql = "SELECT admin_email FROM admins WHERE admin_email='" . mysqli_real_escape_string($con, $admin_email) . "' AND admin_id != $admin_id";
        $adminCheckSqlResult = mysqli_query($con, $adminCheckSql);
        if ($adminCheckSqlResult) {
            $adminCheckSqlResultRowObj = mysqli_fetch_object($adminCheckSqlResult);
            if (isset($adminCheckSqlResultRowObj->admin_email) AND $adminCheckSqlResultRowObj->admin_email = $admin_email) {
                $err = '(<b>' . $admin_email . '</b>) already exist in our databse ';
            }
        } else {
            if (DEBUG) {
                echo 'adminCheckSqlResult Error: ' . mysqli_error($con);
            }
        }

        /* End :Checking the user already exist or not */
    }

    if ($err == '') {


        $adminFiled = '';
        $adminFiled .=' admin_full_name = "' . mysqli_real_escape_string($con, $admin_name) . '"';
        $adminFiled .=', admin_email ="' . mysqli_real_escape_string($con, $admin_email) . '"';
        $adminFiled .=', admin_type="' . $admin_type . '"';
        $adminFiled .=', admin_update ="' . date("Y-m-d H:i:s") . '"';
        $adminFiled .=', admin_updated_by=0'; /* it will be loged in user ussesion id */
        $adminUpdateSql = "UPDATE admins SET $adminFiled WHERE admin_id= $admin_id";
        $adminUpdateSqlResult = mysqli_query($con, $adminUpdateSql);
        if ($adminUpdateSqlResult) {
            $msg = "Admin Updated successfully";
			$link = 'index.php?msg=' . base64_encode($msg);
            redirect($link);
        } else {
            if (DEBUG) {
                echo 'adminUpdateSql Error: ' . mysqli_error($con);
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin panel: Admin create </title>


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


    </head>

    <body>

     <?php include basePath('admin/top_navigation.php'); ?>

        <?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include 'admin_left_navigation.php'; ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Admin Module</h5></div>

                <!-- Notification messages -->
                 <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Create Admin </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/admin/admin_edit.php?id=' . base64_encode($admin_id)); ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Admin Information</h5></div>
                                        <div class="rowElem noborder"><label>Admin Full Name:</label><div class="formRight"><input type="text" name="admin_full_name" value="<?php echo $admin_name; ?>"  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Admin Email:</label><div class="formRight"><input type="text" name="admin_email"  value="<?php echo $admin_email; ?>"/></div><div class="fix"></div></div>
                                        <div class="rowElem noborder">
                                            <label>Admin Type :</label>
                                            <div class="formRight">                        
                                                <select name="admin_type">
                                                    <option value="normal" <?php
                                                    if ($admin_type == 'normal') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>>Normal</option>
                                                    <option value="super" <?php
                                                    if ($admin_type == 'super') {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?> >Super Admin</option>

                                                </select>
                                            </div>
                                            <div class="fix"></div>
                                        </div>

                                        <input type="submit" name="admin_edit" value="Submit" class="greyishBtn submitForm" />
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
