<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

$customer_care_email = '';
$customer_care_address = '';
$customer_care_facebook_link = '';
$customer_care_twiter_link = '';
$customerCareSql = "SELECT * FROM customer_care";
$customerCareSqlResult = mysqli_query($con, $customerCareSql);
if ($customerCareSqlResult) {
    $customerCareSqlResultRowObj = mysqli_fetch_object($customerCareSqlResult);
    if (isset($customerCareSqlResultRowObj->customer_care_id)) {
        $customer_care_email = $customerCareSqlResultRowObj->customer_care_email;
        $customer_care_address = $customerCareSqlResultRowObj->customer_care_address;
        $customer_care_facebook_link = $customerCareSqlResultRowObj->customer_care_facebook_link;
        $customer_care_twiter_link = $customerCareSqlResultRowObj->customer_care_twiter_link;

    }
} else {
    if (DEBUG) {
        echo "customerCareSqlResult error : " . mysqli_error($con);
    } else {
        $link = baseUrl('admin/settings/index.php?err=' . base64_encode('Edit sql fail.'));
        redirect($link);
    }
}
if (isset($_POST['customerCare_create']) AND $_POST['customerCare_create'] == 'Submit') {

    extract($_POST);

    if ($customer_care_email == '') {
        $err = 'Customer Care Email field is required!!';
    } elseif (!isValidEmail($customer_care_email)) {
        $err = 'Valid Customer Care Email is required!!';
    } elseif ($customer_care_address == '') {
        $err = 'Customer Care Address field is required!!';
    }
    if ($err == '') {

        $customerCareFiled = '';
        $customerCareFiled .=' customer_care_email = "' . mysqli_real_escape_string($con, $customer_care_email) . '"';
        $customerCareFiled .=', customer_care_address ="' . htmlentities(mysqli_real_escape_string($con, $customer_care_address)) . '"';
        $customerCareFiled .=', customer_care_facebook_link ="' . mysqli_real_escape_string($con, $customer_care_facebook_link) . '"';
        $customerCareFiled .=', customer_care_twiter_link ="' . mysqli_real_escape_string($con, $customer_care_twiter_link) . '"';
        if(isset($customerCareSqlResultRowObj->customer_care_email)) {
        $customerCareInsSql = "UPDATE customer_care SET $customerCareFiled";
        }
        else {
            $customerCareInsSql = "INSERT INTO customer_care SET $customerCareFiled";
        }
        $customerCareInsSqlResult = mysqli_query($con, $customerCareInsSql);
        if ($customerCareInsSqlResult) {
            $msg = "Customer Care Page created successfully";
        } else {
            if (DEBUG) {
                echo '$customerCareInsSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Insert Query failed.";
        }
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin panel: Customer Care </title>


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
            <?php include ('settings_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Admin Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Customer Care </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/settings/customer_care.php'); ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Customer Care Information</h5></div>
                                        <div class="rowElem noborder"><label>Customer Care Email:</label><div class="formRight"><input type="text" name="customer_care_email"  value="<?php echo $customer_care_email; ?>"/></div><div class="fix"></div></div>
                                        <div class="head"><h5 class="iPencil">Customer Care Address:</h5></div>
                                        <div><textarea id="contact_address" class="wysiwyg" rows="5" cols="" name="customer_care_address"><?php echo $customer_care_address; ?></textarea></div>    
                                        <div class="rowElem noborder"><label>Facebook Link:</label><div class="formRight"><input type="text" name="customer_care_facebook_link"  value="<?php echo $customer_care_facebook_link; ?>"/></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Twiter Link:</label><div class="formRight"><input type="text" name="customer_care_twiter_link"  value="<?php echo $customer_care_twiter_link; ?>"/></div><div class="fix"></div></div>
                                        <input type="submit" name="customerCare_create" value="Submit" class="greyishBtn submitForm" />
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
<script type="text/javascript">
            $('#map').wysiwyg({
                initialContent: this.value,
                controls: {
                    html: {visible: true}
                }
            });
        </script>
        <?php include basePath('admin/footer.php'); ?>
