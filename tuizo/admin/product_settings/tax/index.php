<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
//deleting tags from db
if(!empty($_GET['del']))
{
	$did = $_GET['del'];
	mysqli_query($con,"DELETE FROM `tax_classes` WHERE tag_id='$did'");
}


//saving tags in database

$aid = $_SESSION['admin_id']; //getting admin id
$title = '';
$percent = '';
if(isset($_POST['submit'])){
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin Panel | Tax</title>

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
	/*function del(pin_id1)
	{
		if(confirm('Are you sure to delete this tag!!'))
		{
			window.location='index.php?del='+pin_id1;
		}
	}*/
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
              			   <form action="index.php" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Add Tax</h5></div>
                                        <div class="rowElem noborder"><label>Tax Title:</label><div class="formRight"><input name="title" type="text" value="<?php echo $title ?>" maxlength="20"/></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Tax Amount (%):</label><div class="formRight"><input name="percent" type="text" value="<?php echo $percent ?>" maxlength="20"/></div><div class="fix"></div></div>
                                        
                                        <input type="submit" name="submit" value="Add Tax" class="greyishBtn submitForm" />
                                        <div class="fix"></div>

                                    </div>
                                </fieldset>

						  </form>		


                        </div>
                        
                        
                        
              
                <div class="table">
                    <div class="head">
                  <h5 class="iFrames">Tax List</h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                      <thead>
                            <tr>
                                <th>Tax ID</th>
                                <th>Tax Title</th>
                                <th>Tax Percent</th>
                                <th>Tax Last Updated</th>
                                <th>Tax Updated By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$taxsql = mysqli_query($con,"SELECT * FROM tax_classes");
while($taxrow = mysqli_fetch_array($taxsql))
{
?>                        
                        
                          <tr class="gradeA">
                                <td><?php echo $taxrow['TC_id']; ?></td>
                                <td><?php echo $taxrow['TC_title']; ?></td>
                                <td class="center"><?php echo $taxrow['TC_percent']; ?> %</td>
                                <td><?php echo $taxrow['TC_updated']; ?></td>
                            	<td><?php
								$aid = $taxrow['TC_updated_by'];
								$adminsql = mysqli_query($con,"SELECT (admin_full_name) FROM admins WHERE admin_id='$aid'");
								$adminrow = mysqli_fetch_array($adminsql);
								echo $adminrow[0];
								?></td>
                                <td class="center"><a href="edit.php?id=<?php echo $taxrow['TC_id']; ?>" title="Edit"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png')?>" height="14" width="14" alt="Edit" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<!--<a href="javascript:del(<?php echo $taxrow['TC_id']; ?>);"><img src="../images/delete.png" /></a>--></td>
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

        <?php include  basePath('admin/footer.php'); ?>
