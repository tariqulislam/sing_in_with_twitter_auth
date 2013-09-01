<?php
include ('../../config/config.php');
include basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

$product_avg_rating = '';
//saving tags in database
$aid = @$_SESSION['admin_id'];
$order_id = base64_decode($_GET['oid']);


if (isset($_POST['update'])) {
	extract($_POST);
	$GetOrder = mysqli_query($con,"SELECT * FROM orders WHERE order_id='$order_id'");
	$SetOrderStatus = mysqli_fetch_object($GetOrder);
	$errid = '';
	
	if($SetOrderStatus -> order_status != 'booking'){
		if($status == 'booking'){
			$errid = 1;
		}
	}
	if($status == 'approved' || $status == 'delivered' || $status == 'paid'){
		if($orderid == ''){
			$errid = 2;
		}
	} 
	if($errid == 1){
		$err = 'You cannot change order status back to BOOKING.';
	} elseif($errid == 2){
		$err = 'Order No. is required';
	} else {
		
		if($SetOrderStatus -> order_status == 'booking'){
			$OrderProduct = "SELECT * FROM order_products WHERE OP_order_id='$order_id'";
			$ExecuteOrderProduct = mysqli_query($con,$OrderProduct);
			while($GetOrderProduct = mysqli_fetch_object($ExecuteOrderProduct)){
				$product_id = $GetOrderProduct -> OP_product_id;
				$SelectProduct = mysqli_query($con,"SELECT * FROM products WHERE product_id='$product_id'");
				$GetProduct = mysqli_fetch_object($SelectProduct);
				$CurrentQuantity = $GetProduct -> product_quantity;
				$OrderedQuantity = $GetOrderProduct -> OP_product_quantity;
				$NewQuantity = $CurrentQuantity - $OrderedQuantity;
				
				$UpdateQuantity = mysqli_query($con,"UPDATE products SET product_quantity='$NewQuantity' WHERE product_id='$product_id'");
			}
			
			$User_ID = $SetOrderStatus -> order_user_id;
			$SqlUser = "SELECT * FROM users WHERE user_id='$User_ID'";
			$ExecuteUser = mysqli_query($con,$SqlUser);
			$GetUser = mysqli_fetch_object($ExecuteUser);
			
			$User_Email = $GetUser -> user_email;
			
			//sending email
			require("../../class.phpmailer.php");

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
	
			$webmaster_email = "no-reply@lyric.com"; //Add reply-to email address
	
			$email=$User_Email; // Add recipients email address
	
			$name=$User_Email; // Add Your Recipient's name
	
			$mail->From = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_ORDER'];
	
			$mail->FromName = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_ORDER'];
	
			$mail->AddAddress($email,$name);
	
			$mail->AddReplyTo($webmaster_email,"Webmaster");
	
			//$mail->extension=php_openssl.dll;
	
			$mail->WordWrap = 50; // set word wrap
	
			/*$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	
			$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment*/
	
			$mail->IsHTML(true); // send as HTML
			
			$mail->Subject = "Order Current Status Confirmation";
			
			$mail->Body = "Dear ".$User_Email.",<br><br>
			Below is your order status:<br><br>
			<strong>Order ID:</strong> ".$orderid."<br>
			<strong>Order Status:</strong> ".$status."<br><br>
			We will keep you update about the status of your order.<br><br>
			Thank you.<br><br>
			NuVista Team";      //HTML Body
	
	
			$mail->AltBody = $mail->Body;     //Plain Text Body
	
			if(!$mail->Send())
	
			{
	
				$err = "Internal error. Try again later.";
	
			} 
	
			else 
	
			{
	
				$msg =  "Thank you. Please check your email.";
	
			}
		}
	
		$UpdateOrder = '';
		$UpdateOrder .= ' order_status ="' . mysqli_real_escape_string($con, $status) . '"';
		$UpdateOrder .= ', order_number ="' . mysqli_real_escape_string($con, $orderid) . '"';
		$UpdateOrder .= ', order_updated_by ="' . mysqli_real_escape_string($con, $aid) . '"';
		
		$SqlUpdateOrder = "UPDATE orders SET $UpdateOrder WHERE order_id='$order_id'";
		$ExecuteUpdateOrder = mysqli_query($con,$SqlUpdateOrder);
		
		if($ExecuteUpdateOrder){
			$msg = "Order information updated successfully";
			
			$User_ID = $SetOrderStatus -> order_user_id;
			$SqlUser = "SELECT * FROM users WHERE user_id='$User_ID'";
			$ExecuteUser = mysqli_query($con,$SqlUser);
			$GetUser = mysqli_fetch_object($ExecuteUser);
			
			$User_Email = $GetUser -> user_email;
			
			//sending email
			require("../../class.phpmailer.php");

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
	
			$webmaster_email = "no-reply@lyric.com"; //Add reply-to email address
	
			$email=$User_Email; // Add recipients email address
	
			$name=$User_Email; // Add Your Recipient's name
	
			$mail->From = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_ORDER'];
	
			$mail->FromName = $config['CONFIG_SETTINGS']['EMAIL_ADDRESS_ORDER'];
	
			$mail->AddAddress($email,$name);
	
			$mail->AddReplyTo($webmaster_email,"Webmaster");
	
			//$mail->extension=php_openssl.dll;
	
			$mail->WordWrap = 50; // set word wrap
	
			/*$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment
	
			$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment*/
	
			$mail->IsHTML(true); // send as HTML
			
			$mail->Subject = "Order Current Status Confirmation";
			
			$mail->Body = "Dear ".$User_Email.",<br><br>
			Below is your order status:<br><br>
			<strong>Order ID:</strong> ".$orderid."<br>
			<strong>Order Status:</strong> ".$status."<br><br>
			We will keep you update about the status of your order.<br><br>
			Thank you.<br><br>
			NuVista Team";      //HTML Body
	
	
			$mail->AltBody = $mail->Body;     //Plain Text Body
	
			if(!$mail->Send())
	
			{
	
				$err = "Internal error. Try again later.";
	
			} 
	
			else 
	
			{
	
				$msg =  "Thank you. Please check your email.";
	
			}
			
			$link = "index.php?msg=".base64_encode($msg);
			redirect($link);
			
		} else {
			$err = "Order information could not update.";
		}
	}
}



$ExecuteOrder = mysqli_query($con,"SELECT * FROM orders WHERE order_id='$order_id'");
$SetOrder = mysqli_fetch_object($ExecuteOrder);

$UpdateReadStatus = mysqli_query($con,"UPDATE orders SET order_read='yes' WHERE order_id='$order_id'")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
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
            function redirect()
            {
                if (confirm('Do you want to leave Product Editing Module?'))
                {
                    window.location = "../index.php";
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
            <div class="leftNav">
<?php include('order_left_navigation.php'); ?>
            </div>
            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Order Details Information</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>

                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Order Information</h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="order_details.php?oid=<?php echo $_GET['oid']; ?>" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Order Status - <font color="#FF0000" style="text-transform:capitalize;"><?php echo $SetOrder -> order_status; ?></font></h5></div>
                                        
                                        
                                                                                    
                                        <div class="rowElem noborder"><label>Order No.:</label><div class="formRight">
                                                <input name="orderid" type="text" maxlength="20" value="<?php echo $SetOrder -> order_number; ?>"/>
                                            </div><div class="fix"></div></div>
                                            
                                                                               <div class="rowElem noborder"><label>Order Status:</label><div class="formRight">
                                       <select name="status">
                                       <option value="booking" <?php if($SetOrder -> order_status == 'booking') { echo 'selected'; } ?>>Booking</option>
                                       <option value="approved" <?php if($SetOrder -> order_status == 'approved') { echo 'selected'; } ?>>Approved</option>
                                       <option value="delivered" <?php if($SetOrder -> order_status == 'delivered') { echo 'selected'; } ?>>Delivered</option>
                                       <option value="paid" <?php if($SetOrder -> order_status == 'paid') { echo 'selected'; } ?>>Paid</option>
                                       </select>
                                                </div><div class="fix"></div></div>        


                                        <input type="submit" name="update" value="Update Order Status" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                 	</div>    
                                 </fieldset>
                                 
                                 
                                 
                                 <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Order Details</h5></div>      
                                        <div class="rowElem noborder"><label>Ordered By:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_user_id; ?>"/>
                                        </div><div class="fix"></div></div>
                                            
                                        <div class="rowElem noborder"><label>Order Placed On:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo date('d M Y h.i.s A',strtotime($SetOrder -> order_created)); ?>"/></div><div class="fix"></div></div>

                                            
                                        <div class="rowElem noborder"><label>Order Status:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_status; ?>"/>
                                        </div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Payment Method Used:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_payment_type; ?>"/>
                                        </div><div class="fix"></div></div>
                                                
                                        <div class="rowElem noborder"><label>Total Item Ordered:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_total_item; ?>"/>
                                        </div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Sub-Total:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_total_amount; ?>"/>
                                        </div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>VAT Amount:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_vat_amount; ?>"/>
                                        </div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Discount Amount:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_discount_amount; ?>"/>
                                        </div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Discount Amount:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo number_format((float)($SetOrder -> order_total_amount - $SetOrder -> order_discount_amount),2,'.',''); ?>"/>
                                        </div><div class="fix"></div></div>
                                        
                                        <div class="rowElem noborder"><label>Special Note:</label><div class="formRight">
                                        <textarea name="title" disabled="disabled"><?php echo $SetOrder -> order_note; ?></textarea>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>                
                                                
                                        <div class="rowElem noborder"><label>Order Billing Person:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_billing_first_name; ?> <?php echo $SetOrder -> order_billing_middle_name; ?> <?php echo $SetOrder -> order_billing_last_name; ?>"/>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>
                                                
                                                
                                        <div class="rowElem noborder"><label>Order Billing Address:</label><div class="formRight">
                                        <textarea rows="4" name="title" disabled="disabled"><?php echo $SetOrder -> order_billing_address; ?>&#13;&#10;<?php echo $SetOrder -> order_billing_city; ?>, <?php echo $SetOrder -> order_billing_zip; ?>&#13;&#10;<?php echo $SetOrder -> order_billing_country; ?></textarea>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>
                                        
                                        
                                        <div class="rowElem noborder"><label>Order Shipping Person:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_shipping_first_name; ?> <?php echo $SetOrder -> order_shipping_middle_name; ?> <?php echo $SetOrder -> order_shipping_last_name; ?>"/>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>
                                        
                                        
                                        <div class="rowElem noborder"><label>Order Shipping Address:</label><div class="formRight">
                                        <textarea rows="4" name="title" disabled="disabled"><?php echo $SetOrder -> order_shipping_address; ?>&#13;&#10;<?php echo $SetOrder -> order_shipping_city; ?>, <?php echo $SetOrder -> order_shipping_zip; ?>&#13;&#10;<?php echo $SetOrder -> order_shipping_country; ?></textarea>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>
                                        
                                        
                                        <div class="rowElem noborder"><label>Order Updated On:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo date('d M Y h.i.s A',strtotime($SetOrder -> order_updated_on)); ?>"/>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>
                                        
                                        
                                        <div class="rowElem noborder"><label>Order Updated By:</label><div class="formRight">
                                        <input name="title" type="text" maxlength="20" disabled="disabled" value="<?php echo $SetOrder -> order_shipping_first_name; ?> <?php echo $SetOrder -> order_shipping_middle_name; ?> <?php echo $SetOrder -> order_shipping_last_name; ?>"/>
                                        </div><div class="fix"></div><span style="position:relative; left:160px;"></span></div>
                                        
                                        


                                    </div>
                                </fieldset>

                            </form>		
							
                            
                            
                            <div class="table">
                    <div class="head">
                  <h5 class="iFrames">Booking Order List</h5></div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                      <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Amount</th>
                                <th>Product Unit Price</th>
                                <th>Sub-Total Price</th>
                                <th>Total Discount</th>
                                <th>Total Price</th>
                             </tr>
                      </thead>
                        <tbody>
<?php
$OrderProduct = mysqli_query($con,"SELECT * FROM order_products WHERE OP_order_id='$order_id'");
$GrandTotal = 0;
while($GetOrderProduct = mysqli_fetch_object($OrderProduct))
{
	$ExecuteProduct = mysqli_query($con,"SELECT * FROM products WHERE product_id='".$GetOrderProduct -> OP_product_id."'");
	$GetProduct = mysqli_fetch_object($ExecuteProduct);
?>                        
                        
                          <tr>
                                <td><?php echo $GetProduct -> product_title; ?></td>
                                <td><?php echo $GetOrderProduct -> OP_product_quantity; ?></td>
                                <td><?php echo $GetOrderProduct -> OP_price; ?></td>
                                <td><?php echo $TotalPrice = number_format((float)$GetOrderProduct -> OP_price * $GetOrderProduct -> OP_product_quantity,2,'.',''); ?></td>
                                <td><?php echo $TotalDiscount = number_format((float)$GetOrderProduct -> OP_discount * $GetOrderProduct -> OP_product_quantity,2,'.',''); ?></td>
                                <td><?php echo $WholeTot = number_format((float)$TotalPrice - $TotalDiscount,2,'.',''); $GrandTotal += $WholeTot; ?></td>
                                
                          </tr>
                          
<?php
}
?>
								
                          		<tr>
                                <td colspan="5" align="right"><strong>Grand Total</strong></td>
                                <td><strong><?php echo number_format((float)$GrandTotal,2,'.',''); ?></strong></td>
                                </tr>
                      </tbody>
                    </table>
              </div>

            </div>
            
                            
                            

                        </div>










                    </div>
                </div>

            </div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>

<?php include basePath('admin/footer.php'); ?>
        <script type="text/javascript">
            var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
            var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
            var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>
    </body>
</html>