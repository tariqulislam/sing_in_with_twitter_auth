<?php
include ('../../config/config.php');
if(!checkAdminLogin()){
    $link=  baseUrl('admin/index.php?err='.  base64_encode('Please login to access admin panel'));
    redirect($link);
}
$aid = @$_SESSION['admin_id'];


extract($_POST);
if(isset($_POST['send'])){
	if(count(@$email) <= 0 && !isset($_POST['all'])){
		$err = "Please select atleast one email address to send.";
	} elseif($subject == ''){
		$err = "Email Subject is required.";
	} elseif(!isset($_POST['chbox']) && $newemail == ''){
		$err = "Please enter new email address.";
	} elseif($body == ''){
		$err = "Email Body is required.";	
	} else {
		
		if(isset($_POST['all'])){
			$SqlEmail = "SELECT * FROM subscribe_information";
			$ExecuteEmail = mysqli_query($con,$SqlEmail);
			
			//sending email to all
			$count = 0;
			while($GetEmail = mysqli_fetch_object($ExecuteEmail)){
					include_once("../../class.phpmailer.php");
			
					$mail = new PHPMailer();
					
					$mail->Host = $config['CONFIG_SETTINGS']['SMTP_SERVER_ADDRESS'];
					$mail->Port = $config['CONFIG_SETTINGS']['SMTP_PORT_NO'];
					$mail->SMTPSecure = 'ssl';
			
					$mail->IsSMTP(); // send via SMTP
			
					$mail->SMTPDebug = 1;
			
					//IsSMTP(); // send via SMTP
			
					$mail->SMTPAuth = true; // turn on SMTP authentication
			
					$mail->Username = $config['CONFIG_SETTINGS']['HOSTING_ID']; // Enter your SMTP username
			
					$mail->Password = $config['CONFIG_SETTINGS']['HOSTING_PASS']; // SMTP password
			
					$webmaster_email = "no-reply@tuizo.com"; //Add reply-to email address
			
					$email=$GetEmail -> subscribe_email; // Add recipients email address
			
					$name=$GetEmail -> subscribe_email; // Add Your Recipient's name
			
					if(isset($_POST['chbox'])){
						$mail->From = $newemail;
						$mail->FromName = $newemail;
					} else {
						$mail->From = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_NEWS'];
						$mail->FromName = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_NEWS'];
					}
			
					$mail->AddAddress($email,$name);
			
					$mail->AddReplyTo($webmaster_email,"Webmaster");
			
					//$mail->extension=php_openssl.dll;
			
					$mail->WordWrap = 50; // set word wrap
			
					/*$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
			
					$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment*/
			
					$mail->IsHTML(true); // send as HTML
					
					$mail->Subject = $subject;
					
					$mail->Body = $body;      //HTML Body
			
			
					$mail->AltBody = $mail->Body;     //Plain Text Body
			
					if(!$mail->Send()){
						$count ++;
					} else {
						
					}
			}
			
			if($count > 0){
				$err = "Newsletter sending failed. Please try again.";
			} else {
				$msg =  "Your newsletter sent successfully.";
			}
			
		} else {
			foreach($email as $email_add){
			
			//sending email
			include_once("../../class.phpmailer.php");
	
			$mail = new PHPMailer();
			
			$mail->Host = $config['CONFIG_SETTINGS']['SMTP_SERVER_ADDRESS'];
			$mail->Port = $config['CONFIG_SETTINGS']['SMTP_PORT_NO'];
			$mail->SMTPSecure = 'ssl';
	
			$mail->IsSMTP(); // send via SMTP
	
			$mail->SMTPDebug = 1;
	
			//IsSMTP(); // send via SMTP
	
			$mail->SMTPAuth = true; // turn on SMTP authentication
	
			$mail->Username = $config['CONFIG_SETTINGS']['HOSTING_ID']; // Enter your SMTP username
	
			$mail->Password = $config['CONFIG_SETTINGS']['HOSTING_PASS']; // SMTP password
	
			$webmaster_email = "no-reply@tuizo.com"; //Add reply-to email address
	
			$email=$email_add; // Add recipients email address
	
			$name=$email_add; // Add Your Recipient's name
	
			if(isset($_POST['chbox'])){
				$mail->From = $newemail;
				$mail->FromName = $newemail;
			} else {
				$mail->From = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_NEWS'];
				$mail->FromName = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_NEWS'];
			}
	
			$mail->AddAddress($email,$name);
	
			$mail->AddReplyTo($webmaster_email,"Webmaster");
	
			//$mail->extension=php_openssl.dll;
	
			$mail->WordWrap = 50; // set word wrap
	
			/*$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	
			$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment*/
	
			$mail->IsHTML(true); // send as HTML
			
			$mail->Subject = $subject;
			
			$mail->Body = $body;      //HTML Body
	
	
			$mail->AltBody = $mail->Body;     //Plain Text Body
	
			if(!$mail->Send()){
				$err = "Newsletter sending failed. Please try again.";
			} else {
				$msg =  "Your newsletter sent successfully.";
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
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin Panel | Customer Activity</title>

              <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <!--<link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />-->
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
<!--delete tags-->
<script type="text/javascript">
function chngAttr(){	
	if ($('#chngAttri').is(":checked")) {
		$('#newemail').attr('readonly','readonly');
		$('#newemail').val('<?php echo $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_NEWS']; ?>');
	} else {
		$('#newemail').removeAttr('readonly','readonly');
		$('#newemail').val('');
	}
	
	
	if ($('#sendall').is(":checked")) {
		$('#emailaddress').attr('disabled','disabled');
	} else {
		$('#emailaddress').removeAttr('disabled','disabled');
	}
	
}
</script>
<!--end delete tags-->


    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>

    <body>

<?php include basePath('admin/top_navigation.php'); ?>

<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
        <?php include basePath('admin/customer_activity/customer_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>User Activity Module</h5></div>

                <!-- Notification messages -->
            <?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Newsletter</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="newsletter.php" method="post" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Send Newsletter</h5></div>
                                        
                                        <div class="rowElem">
                                        <label>Select Email Address:</label>
                                        <div class="formRight">
                                            
                                            <select multiple="multiple" class="multiple" name="email[]" id="emailaddress">
                                            <?php 
											$SqlEmail = "SELECT * FROM subscribe_information";
											$ExecuteEmail = mysqli_query($con,$SqlEmail);
											while($GetEmail = mysqli_fetch_object($ExecuteEmail)){
											?>
                                            <option value="<?php echo $GetEmail -> subscribe_email; ?>"><?php echo $GetEmail -> subscribe_email; ?></option>
                                            <?php
											}
											?>
                                            </select>
                                            <br /><br /><input type="checkbox" name="all" id="sendall" onchange="chngAttr();" />&nbsp;&nbsp;&nbsp;<font style="vertical-align:middle;">Send to all</font>
                                        </div>
                                        
                                        <div class="fix"></div>
                                    </div>
                                        
                                    <div class="rowElem noborder"><label>Email Subject:</label><div class="formRight"><input type="text" name="subject"/></div><div class="fix"></div></div>
                                        
                                    
                                        
                                        
                                    <div class="widget">    
                                        <div class="head"><h5 class="iPencil">Email Body:</h5></div>
                                        <textarea class="wysiwyg" rows="5" cols="" name="body"></textarea>                
                                    </div>
                                    
                                    
                                    <div class="rowElem noborder"><label>Use Default Email:</label><div class="formRight">
                                    <input type="checkbox" name="chbox" checked="checked" onchange="chngAttr();" id="chngAttri" /><label></label>
                                    </div><div class="fix"></div></div>
                                    
                                    
                                    <div class="rowElem noborder"><label>New Email Address:</label><div class="formRight">
                                    <input type="text" name="newemail" readonly="readonly" id="newemail" value="<?php echo $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_NEWS']; ?>"/>
                                    </div><div class="fix"></div></div>
                                    
                                    
                                    <input type="submit" name="send" value="Send Newsletter" class="greyishBtn submitForm" />
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

        <?php include  basePath('admin/footer.php'); ?>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "custom", {pattern:"XXXX000000"});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency");
    </script>
