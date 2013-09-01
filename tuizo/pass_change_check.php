<?php
include 'config/config.php';
$userEmailAddress = $_REQUEST["email"];
$session_id = session_id();
$userHashKey = $session_id;

if (isset($_POST["update"]) && $_POST["update"] == 'update') {
    if ($_POST["password"] == '') {
        $err = 'Password Field Must Not Empty';
    } elseif ($_POST["password"] != $_POST["confirm_password"]) {
        $err = 'Comfirm Password Does Not Match';
    }
    if ($err == '') {
        $newPassword = securedPass($_POST["password"]);
        $passUpdateSql = "UPDATE users SET user_password ='$newPassword', user_hash='$session_id' WHERE user_email='$userEmailAddress'";
        $passUpdateSqlResult = mysqli_query($con, $passUpdateSql);
        if ($passUpdateSqlResult) {
            redirect(baseUrl("sign_in.php?msg=". base64_encode("Password Successfully Updated!!")));
        }
    }
}

$passCheckSql = "SELECT * FROM users WHERE user_email='$userEmailAddress' AND user_hash='$userHashKey'";
$passCheckSqlResult = mysqli_query($con, $passCheckSql);
if (mysqli_num_rows($passCheckSqlResult) == 0) {    
    $link = baseUrl("index.php?redirect=true");
    redirect($link);
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
                               <form action="<?php echo baseUrl("pass_change_check.php?email=$userEmailAddress&hashKey=$userHashKey") ?>" name="forgot_password" method="post">
                               <?php if ($err != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: red;"><?php echo $err; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                        <?php if ($msg != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: green;"><?php echo $msg; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                                   <p>Please Enter Your New Password</p>
                           		<input placeholder="Enter New Password" type="password" name="password">
                                            <p>Please Confirm Your Password</p>
                           		<input placeholder="Confirm Password " type="password" name="confirm_password">
                                        <button type="submit" name="update" value="update" class="button">update</button>
                                        
                                    
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
