<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

$faq_id = 0;
if (isset($_REQUEST['id'])) {
    $edit_faq_id = base64_decode($_REQUEST['id']);
} else {
    $link = baseUrl('admin/faq/index.php?err=' . base64_encode('Id missing.'));
    redirect($link);
}

$faq_question = '';
$faq_answer = '';
$faq_priority = '';
$faqSql = "SELECT * FROM faq WHERE faq_id=" . intval($edit_faq_id);
$faqSqlResult = mysqli_query($con, $faqSql);
if ($faqSqlResult) {
    $faqSqlResultRowObj = mysqli_fetch_object($faqSqlResult);
    if (isset($faqSqlResultRowObj->faq_id)) {

        $faq_question = $faqSqlResultRowObj->faq_question;
        $faq_answer = $faqSqlResultRowObj->faq_answer;
        $faq_priority = $faqSqlResultRowObj->faq_priority;
    }
} else {
    if (DEBUG) {
        echo "faqSqlResult error : " . mysqli_error($con);
    } else {
        $link = baseUrl('admin/faq/index.php?err=' . base64_encode('Edit sql fail.'));
        redirect($link);
    }
}

if (isset($_POST['faq_update']) AND $_POST['faq_update'] == 'Update') {

    extract($_POST);
    if ($faq_question == '') {
        $err = 'FAQ  Question field is required!!';
    }elseif ($faq_question != '') {
        $faqCheckSql = "select faq_question from `faq` where faq_question='" . mysqli_real_escape_string($con, $faq_question) . "'AND faq_id != ".intval($edit_faq_id);
        $faqCheckSqlResult = mysqli_query($con, $faqCheckSql);
        if ($faqCheckSqlResult) {
            $faqCheckSqlResultRowObj = mysqli_fetch_object($faqCheckSqlResult);
            if (isset($faqCheckSqlResultRowObj->faq_question) && $faqCheckSqlResultRowObj->faq_question == $faq_question) {
                $err = 'Faq question  (<b>' . $faq_question . '</b>) already exist in our databse ';
            }
            mysqli_free_result($faqCheckSqlResult);
        } else {
            if (DEBUG) {
                echo 'faqCheckSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Query failed.";
        }
    } elseif ($faq_priority == '') {
        $err = 'FAQ Priority field is required!!';
    } elseif (!is_numeric($faq_priority)) {
        $err = 'FAQ Priority should be numeric!!';
    } elseif ($faq_answer == '') {
        $err = 'FAQ Answer field is required!!';
    }
//    else{
//         /* Start :Checking the question already exist or not */
//        $faqSql = "SELECT faq_question FROM faq";
//        $faqSqlResult = mysqli_query($faqSql);
//        while($faqSqlResultRow = mysqli_fetch_array($faqSqlResult)){
//            echo $faqSqlResultRow['faq_question'];
//        }
//        
//        /* End :Checking the user already exist or not */
//    }
    
    if ($err == '') {

        $faqFiled = '';
        $faqFiled .=' faq_question = "' . mysqli_real_escape_string($con, $faq_question) . '"';
        $faqFiled .=', faq_answer ="' . htmlentities(mysqli_real_escape_string($con, $faq_answer)) . '"';
        $faqFiled .=', faq_priority ="' . intval($faq_priority) . '"';

        $faqUpdateSql = "UPDATE `faq` SET $faqFiled WHERE faq_id=" . intval($edit_faq_id);
        
        $faqUpdateSqlResult = mysqli_query($con, $faqUpdateSql);
        if ($faqUpdateSqlResult) {
            $msg = "FAQ information update successfully";
        } else {
            if (DEBUG) {
                echo 'faqInsSqlResult Error: ' . mysqli_error($con);
            }else{
                 $err = "Update Query failed.";
            }
           
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | faq update</title>   
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
    <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
    <script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />               
       
    </head>

    <body>

        <?php include basePath('admin/top_navigation.php'); ?>

        <?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include ('faq_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>FAQ Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Update FAQ </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/faq/faq_edit.php').'?id='.  base64_encode($edit_faq_id); ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">FAQ Information</h5></div>
                                        <div class="rowElem noborder"><label> Question:</label><div class="formRight"><input type="text" name="faq_question" value="<?php echo $faq_question; ?>"  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> Priority:</label><div class="formRight"><input type="text" name="faq_priority" value="<?php echo $faq_priority; ?>"  /></div><div class="fix"></div></div>
                                        <div class="head"><h5 class="iPencil">Answer:</h5></div>      
                                        <div><textarea class="wysiwyg" rows="5" cols="" name="faq_answer"><?php echo html_entity_decode($faq_answer); ?></textarea></div>
                                        <input type="submit" name="faq_update" value="Update" class="greyishBtn submitForm" />
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
