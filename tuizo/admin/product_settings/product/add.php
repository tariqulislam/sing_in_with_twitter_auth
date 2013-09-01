<?php
include ('../../../config/config.php');
if(!checkAdminLogin()){
    $link=  baseUrl('admin/index.php?err='.  base64_encode('Please login to access admin panel'));
    redirect($link);
}
//deleting tags from db
if(!empty($_GET['del']))
{
	$did = $_GET['del'];
	mysqli_query($con,"DELETE FROM `tax_classes` WHERE tag_id='$did'");
}


//saving tags in database
extract($_POST);
$aid = @$_SESSION['admin_id']; //getting admin id

if(isset($_POST['submit']))
{
	if($title == "" || $percent == "")
	{
		$err = "Please enter text";
	}
	else
	{
		$sql = mysqli_query($con,"INSERT INTO `tax_classes`(TC_title,TC_percent,TC_updated_by) VALUES('$title','$percent',$aid)");
		
		
		if($sql)
		{
			$msg = "Tax saved successfully";
		}
		else
		{
			$err = "Tax was not saved";
		}
	}
}
else
{
	$msg = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin Panel | Product</title>

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

        <?php /*include basePath('admin/module_link.php');*/ ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php /*include basePath('admin/product_left_navigation.php');*/ ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Product Module</h5></div>

                <!-- Notification messages -->
               <?php include basePath('admin/message.php'); ?>
               
                <!-- Charts -->
          <div class="widget">       
            <ul class="tabs">
                <li><a href="#tab3">Tab active</a></li>
                <li><a href="#tab4">Tab inactive</a></li>
            </ul>
            
            <div class="tab_container">
                <div id="tab3" class="tab_content">Widget1</div>
                <div id="tab4" class="tab_content">Widget2</div>
            </div>	
            <div class="fix"></div>	 
        </div>
                        
                        
              
                <div class="table">
                    <div class="head">
                  <h5 class="iFrames">Tags List</h5></div>
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
                                <td class="center"><a href="edit.php?id=<?php echo $taxrow['TC_id']; ?>" title="Edit"><img src="../images/pencil-grey-icon.png" height="14" width="14" alt="Edit" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<!--<a href="javascript:del(<?php echo $taxrow['TC_id']; ?>);"><img src="../images/delete.png" /></a>--></td>
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
