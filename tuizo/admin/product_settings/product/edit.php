<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}


//saving tags in database

$aid = $_SESSION['admin_id']; //getting admin id
$tid = $_GET['id'];
$title = '';
$percent = '';
if (isset($_POST['update'])) {
    extract($_POST);
    if ($title == "") {
        $err = "Tax title is required ";
    }
    else if ($percent == "") {
        $err = "Tax percent is required";
    }
     else {
        /* Start :Checking the title already exist or not */
        $taxCheckSql = "SELECT TC_title FROM tax_classes WHERE TC_title='" . mysqli_real_escape_string($con, $title) . "'";
        $taxCheckSqlResult = mysqli_query($con, $taxCheckSql);
        if ($taxCheckSqlResult) {
            $taxCheckSqlResultRowObj = mysqli_fetch_object($taxCheckSqlResult);
            if (isset($taxCheckSqlResultRowObj->TC_title) AND $taxCheckSqlResultRowObj->TC_title = $title) {
                $err = '(<b>' . $title . '</b>) already exist in our databse ';
            }
            mysqli_free_result($taxCheckSqlResult);
        } else {
            if (DEBUG) {
                echo 'taxCheckSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Query failed.";
        }

        /* End :Checking the user title exist or not */
    }

    if ($err == "") {
        $taxFiled = '';
        $taxFiled .=' TC_title = "' . mysqli_real_escape_string($con, $title) . '"';
        $taxFiled .=', TC_percent = "' . mysqli_real_escape_string($con, $percent) . '"';
        $taxFiled .=', TC_updated_by ="' . mysqli_real_escape_string($con, $aid) . '"';
        $taxInsSql = "INSERT INTO tax_classes SET $taxFiled";
        $taxInSqlResult = mysqli_query($con, $taxInsSql);
        if ($taxInSqlResult) {
            $msg = "Tax saved successfully";
        } else {
            if (DEBUG) {
                echo 'taxSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Insert Query failed.";
        }
    }

}


$taxsql = mysqli_query($con, "SELECT * FROM `tax_classes` WHERE TC_id='$tid'");
$taxrow = mysqli_fetch_array($taxsql);
/* if(isset($_POST['submit']))
  {
  echo $sql = mysqli_query($con,"INSERT INTO `tags`(tag_title,tag_updated_by) VALUES('$tag',$aid)");


  if($sql)
  {
  $msg = "Tages saved successfully";
  }
  else
  {
  $err = "Tages was not saved";
  }
  }
  else
  {
  $msg = "";
  } */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Admin Panel | Edit Tags</title>

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
                if (confirm('Are you sure to delete this tag!!'))
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
                <div class="title"><h5>Tax Module</h5></div>

                <!-- Notification messages -->
            <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Tax</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="edit.php?id=<?php echo $taxrow['TC_id']; ?>" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Edit Tags</h5></div>
                                        <div class="rowElem noborder"><label>Update Tags:</label><div class="formRight"><input type="text" name="title" value="<?php echo $taxrow['TC_title']; ?>"/></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Tax Amount (%):</label><div class="formRight"><input name="percent" type="text" maxlength="20" value="<?php echo $taxrow['TC_percent']; ?>"/></div><div class="fix"></div></div>
                                        <input type="hidden" name="id" value="<?php echo $tagrow['tag_id']; ?>" />
                                        <input type="submit" name="update" value="Update Tags" class="greyishBtn submitForm" />
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