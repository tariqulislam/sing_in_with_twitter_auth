<?php
include ('../../config/config.php');
if(!checkAdminLogin()){
    $link=  baseUrl('admin/index.php?err='.  base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin Panel | Customer Review</title>

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
        
    <!--Effect on left error menu, top message menu, body-->

<!--review-->

<style type="text/css">
/* popup_box DIV-Styles*/
#popup_box { 
    display:none; /* Hide the DIV */
    position:fixed;  
    _position:absolute; /* hack for internet explorer 6 */  
    height:auto;  
    width:500px;  
    background:#CCC;  
    left: 450px;
    top: 110px;
    z-index:100; /* Layering ( on-top of others), if you have lots of layers: I just maximized, you can change it yourself */
    margin:0 auto;  
    
    /* additional features, can be omitted */
    border:5px solid #000000;      
    padding:5px;  
    font-size:18px;  
    
    
}

#container {
    background: #000; /*Sample*/
    width:100%;
    height:100%;
}

a{  
cursor: pointer;  
text-decoration:none;  
} 

/* This is for the positioning of the Close Link */
#popupBoxClose {
    font-size:20px;  
    line-height:15px;  
    right:5px;  
    top:5px;  
    position:absolute;  
    color:#424242; ;      
}
</style>  
<script type="text/javascript">
function show(str){
	
$.ajax({ url: 'showreview.php',
		 data: {rid:str}, //Modify this
		 type: 'get',
		 success:   function(output) {
			 
		 if(output != "") {		//error: everything was successful
			$('#popup_box').fadeIn("slow");
				$("#container").css({ // this is just for style
					"opacity": "0.3"  
				});
			$("#popup_box .message").text(output);	 
			
			$('#closepop').click(
			function () {
				$('#popup_box').fadeOut();}, 1000); 
				$("#container").css({ // this is just for style        
				"opacity": "1"
			}); 
				
			setTimeout(function(){
			$('#popup_box').fadeOut();}, 400000); 
			$("#container").css({ // this is just for style        
				"opacity": "1"  
			}); 
		 }
	  }
});
}


</script>
<!--end review-->


    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div id="popup_box">
<div style="height:30px; width:auto; background-color:#333"><span style="float:left; width:300px; font-family:Cuprum; color:#FFF; padding:5px; display:block"><strong>Review Details</strong></span><span style="float:right; width:50px;"><a href="#" id="closepop" style="text-decoration:none; color:#FFF; padding:5px; display:block"><img src="../images/icons/notifications/exclamation.png" height="20px" width="20px" /></a></span></div>
<div style="clear:both; width:auto"></div>    <!-- OUR PopupBox DIV-->
<font class="message" align="center" style="font-family:Cuprum; color:#424242; line-height:20px; padding:10px; display:block"></font>
</div>
       
        <?php include basePath('admin/top_navigation.php'); ?>

    	<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
          <?php include ('customer_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Customer Activity Module</h5></div>

                <!-- Notification messages -->
               <?php include basePath('admin/message.php'); ?>
               
                <!-- Charts -->
         
                        
              
                <div class="table">
                    <div class="head">
                  <h5 class="iFrames">Customer Review List</h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                      <thead>
                            <tr>
                                <th>Product Title</th>
                                <th>Customer's Name</th>
                                <th>Customer's Review</th>
                                <th>Comment Status</th>
                                <th>Action</th>
                            </tr>
                      </thead>
                        <tbody>
<?php
$SqlReview = mysqli_query($con,"SELECT * FROM product_review ORDER BY PRE_id");
while($GetReview = mysqli_fetch_array($SqlReview))
{
?>                        
                        
                          <tr class="gradeA">
                                <td><?php 
								$proid = $GetReview['PRE_product_id'];
								$prosql = mysqli_query($con,"SELECT * FROM products WHERE product_id='$proid'");
								$prorow = mysqli_fetch_array($prosql);
								echo $prorow['product_title'];
								?>
                                </td>
                                <td><?php
								$userid = $GetReview['PRE_user_id'];
								$usersql = mysqli_query($con,"SELECT * FROM users WHERE user_id='$userid'");
								$userrow = mysqli_fetch_array($usersql);
								echo $userrow['user_first_name']." ".$userrow['user_middle_name']." ".$userrow['user_last_name'];
								?></td>
                                <td><?php echo substr($GetReview['PRE_comment'],0,60); ?>...... <a href="javascript:show(<?php echo $GetReview['PRE_id']; ?>);">Read Full</a></td>
                            	<td><?php echo $GetReview['PRE_status']; ?></td>
                                <td class="center"><a href="edit.php?rid=<?php echo base64_encode($GetReview['PRE_id']); ?>" title="Edit"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png')?>" height="14" width="14" alt="Edit" /></a><!--&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" title="Edit"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png')?>" height="14" width="14" alt="Edit" />--></a></td>
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
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "custom", {pattern:"XXXX000000"});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency");
    </script>
