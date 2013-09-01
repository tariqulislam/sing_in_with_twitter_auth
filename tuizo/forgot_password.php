<?php
include("config/config.php");
$session_id = session_id();
if(isset($_POST["submit"])) {
    $givenEmail = $_POST["email"];
    if($givenEmail == '') {
        $err = 'Email Field Must Not Blank';
    } elseif(!filter_var($givenEmail, FILTER_VALIDATE_EMAIL)) {
        $err = 'Email Must Not Invalid';
    }
    if($err == '') {
        $checkNameSql = "SELECT * FROM users WHERE user_email='".$givenEmail."' AND user_status='active'";
        $checkNameSqlResult = mysqli_query($con, $checkNameSql);
        if(mysqli_num_rows($checkNameSqlResult)>0) {
            $updateHashSql = "UPDATE users SET user_hash ='$session_id' WHERE user_email='".$givenEmail."'";
            $updateHashSqlResult = mysqli_query($con, $updateHashSql);
            if($updateHashSqlResult) {
                $userHashKeySql = "SELECT user_hash FROM users WHERE user_email='".$givenEmail."'";
                $userHashKeySqlResult = mysqli_query($con, $userHashKeySql);
                $userHashKeySqlResultRowObj = mysqli_fetch_object($userHashKeySqlResult);
                $userHashKey = $userHashKeySqlResultRowObj->user_hash;
                
                require(basePath("lib/class.phpmailer.php"));

		$mail = new PHPMailer();

		$mail->IsSMTP(); // send via SMTP

		$mail->SMTPDebug = 1;

		//IsSMTP(); // send via SMTP

		$mail->SMTPAuth = true; // turn on SMTP authentication

		$mail->Username = "bluetest"; // Enter your SMTP username

		$mail->Password = "bluepass2012"; // SMTP password

		$webmaster_email = "no-reply@lyric.com"; //Add reply-to email address

		$email=$givenEmail; // Add recipients email address

		$name=$email; // Add Your Recipient's name

		$mail->From = 'murad@bscheme.com';

		$mail->FromName = 'murad@bscheme.com';

		$mail->AddAddress($email,$name);

		$mail->AddReplyTo($webmaster_email,"Webmaster");

		//$mail->extension=php_openssl.dll;

		$mail->WordWrap = 50; // set word wrap

		/*$mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment

		$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment*/

		$mail->IsHTML(true); // send as HTML

		

		$mail->Subject = "You have a message from murad@bscheme.com";

	

		$mail->Body = '

		<html>

		'.$email.' has sent below message to you.<br /><br />
		
		<a href='. baseUrl("pass_change_check.php?email=$givenEmail&hashKey=$userHashKey").'>Please Click This Link<br /><br />
		
		

		</html>';      //HTML Body

		

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
        } else{
            $err = 'Your Given Email Account Is Not Found';
        }
        
    }
    
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tuizo||Sign in</title>
<?php
include("header.php");
?>
    </head>

    <body>
    
    <div id="wrapper">
    
        	<?php
                include("menu.php");
                ?>
            
         		<div id="innerContainer" >
                			<div class="signInContainner">
                            	<div class="signInTop clearfix">
                                		<h1>forgot password!</h1>
                                        
                                </div><!--signInTop-->
                                <div class="forgot_pass">
                                     
                               <form action="<?php echo baseUrl("forgot_password.php")?>" name="forgot_password" method="post">
                                   <?php if ($err != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: red;"><?php echo $err; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                        <?php if ($msg != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: green;"><?php echo $msg; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                               <p>Please Enter Your Email Address</p>
                           		<input placeholder="Enter Your Email" type="text" name="email">
                                        <button type="submit" name="submit" class="button">submit</button>
                                        
                                    
                        </form>
                                </div><!--forgot_pass-->
                            	
                            </div><!--signInContainner-->
                </div><!--innerContainer-->
    </div>
    
    <div class="footer">
            	<div class="footerBar bgcolor11">
                
                
                	<div class="inner">
<p class="show_hide icon-plus-sign"> about tuizo </p>

					</div>

                </div>
                
            	<div class="fcontainer footerContainer" style="display:none">
                 <div class="inner">
                
                      <div class="footer-section">
                      
                     
            <h1 class="h3">Shop Secure</h1>
            <ul>
                <li><a href="">Order Online</a></li>
            <li><a href="">FAQ</a></li>
                <li><a href="">Payments</a></li>
                <li><a href="">Delivery</a></li>
                <li><a href="">Returns</a></li>
                <li><a href="">General Conditions</a></li>
                <li><a href="">Privacy Policy</a></li>
            <li><a href="">Sitemap</a></li>
            <li><a href="">Glossary</a></li>
            </ul>
            </div>
    
              <div class="footer-section">
            <h1 class="h3">Customer Care</h1>
            <ul>
                <li>+31 20 2150073 </li>
                <li>Mon - Fri between 9am - 9pm CET</li>
                <li>Saturday between 9am - 5pm CET</li>
                <li><a class="arrow-right" href="">Country specific phone numbers</a></li>
                <li>&nbsp;</li>
                <li><a href="mailto:service@tuizo.com" class="arrow-right">Email Customer Care</a></li>
            </ul>
            </div>
            
            
            
            <div class="footer-section">
            <h1 class="h3">Follow Us</h1>
            <ul>
              
                <li><a class="arrow-right" href="">Country specific phone numbers</a></li>
                <li>&nbsp;</li>
                <li><a href="mailto:service@tuizo.com" class="arrow-right">Email Customer Care</a></li>
            </ul>
            </div>
	
    
   

                 
           
	 


	

 
	
            
           <section>
             <h1 class="h3">Newsletter</h1>

		        <form class="newsletter" action="/on/demandware.store/Sites-US-Site/en/Newsletter-Register" methode="get">
		            <fieldset>
		            	<input name="email" placeholder="Your email" type="text">
		            	<input value="BD" class="newsLetterLang" name="lang" type="hidden">
		            	<input name="subscribe" value="true" type="hidden">
		            	<input value="" type="submit">
		            </fieldset>
		        </form>
		        
            </section>
            
            </div>
  			 </div>
             
             
                </div>
    



    </body>
</html>