<?php
include ('config/config.php');
$email = $_SESSION["mail_address"];
$uid = $_SESSION['user_id'];

if(!checkUserLogin()) {
	  $link="sign_in.php";
	  redirect($link);
  }
$title = '';
$fname = '';
$mname = '';
$lname = '';
$phone = '';
$calltime = '';
$street = '';
$country = '';
$zip = '';
$city = '';


extract($_POST);

if(base64_decode($_GET['address_id']) > 0){
	if(isset($_POST['update'])){
		echo 1;
	
		if($title == "") {
			echo "Address Title is required.";
		} elseif($fname == "") {
			echo "First Name is required.";
		} elseif($lname == "") {
			echo "Last Name is required.";
		} elseif($phone == "") {
			echo "Phone No. is required.";
		} elseif($street == "") {
			echo "Street Address is required.";
		} elseif($country == "") {
			echo "Country is required.";
		} elseif($zip == "") {
			echo "Zip Code is required.";
		} elseif($city == "") {
			echo "City is required.";
		} else {
			$uid = $_SESSION['user_id'];
			$address_id = base64_decode($_GET['address_id']);
			
			
			$UpdateAddress = '';
			$UpdateAddress .= ' UA_title = "' . mysqli_real_escape_string($con, $title) . '"';
			$UpdateAddress .= ', UA_first_name = "' . mysqli_real_escape_string($con, $fname) . '"';
			$UpdateAddress .= ', UA_middle_name = "' . mysqli_real_escape_string($con, $mname) . '"';
			$UpdateAddress .= ', UA_last_name = "' . mysqli_real_escape_string($con, $lname) . '"';
			$UpdateAddress .= ', UA_phone = "' . mysqli_real_escape_string($con, $phone) . '"';
			$UpdateAddress .= ', UA_best_call_time = "' . mysqli_real_escape_string($con, $calltime) . '"';
			$UpdateAddress .= ', UA_country_id = "' . mysqli_real_escape_string($con, $country) . '"';
			$UpdateAddress .= ', UA_city_id = "' . mysqli_real_escape_string($con, $city) . '"';
			$UpdateAddress .= ', UA_zip = "' . mysqli_real_escape_string($con, $zip) . '"';
			$UpdateAddress .= ', UA_address = "' . mysqli_real_escape_string($con, $street) . '"';
			
			$SqlUpdateAddress = "UPDATE user_addresses SET $UpdateAddress WHERE UA_user_id='$uid' AND UA_id='$address_id'";
			$ExecuteUpdateAddress = mysqli_query($con,$SqlUpdateAddress);
			
			if($ExecuteUpdateAddress) {
				$msg = "Your address updated successfully";
				$link = 'addresses.php?msg='.base64_encode($msg);
				redirect($link);
			} else {
				if(DEBUG){
					echo mysqli_error($con);
				}
				$err = "Your address update failed. Try again.";
			}
			
		}
	}
} else {
	if(isset($_POST['update'])){
		if($title == "") {
			$err = "Address Title is required.";
		} elseif($fname == "") {
			$err = "First Name is required.";
		} elseif($lname == "") {
			$err = "Last Name is required.";
		} elseif($phone == "") {
			$err = "Phone No. is required.";
		} elseif($street == "") {
			$err = "Street Address is required.";
		} elseif($country == "") {
			$err = "Country is required.";
		} elseif($zip == "") {
			$err = "Zip Code is required.";
		} elseif($city == "") {
			$err = "City is required.";
		} else {
			$uid = $_SESSION['user_id'];
			$address_id = $_GET['address_id'];
			
			
			$AddAddress = '';
			$AddAddress .= ' UA_user_id = "' . mysqli_real_escape_string($con, $uid) . '"';
			$AddAddress .= ', UA_title = "' . mysqli_real_escape_string($con, $title) . '"';
			$AddAddress .= ', UA_first_name = "' . mysqli_real_escape_string($con, $fname) . '"';
			$AddAddress .= ', UA_middle_name = "' . mysqli_real_escape_string($con, $mname) . '"';
			$AddAddress .= ', UA_last_name = "' . mysqli_real_escape_string($con, $lname) . '"';
			$AddAddress .= ', UA_phone = "' . mysqli_real_escape_string($con, $phone) . '"';
			$AddAddress .= ', UA_best_call_time = "' . mysqli_real_escape_string($con, $calltime) . '"';
			$AddAddress .= ', UA_country_id = "' . mysqli_real_escape_string($con, $country) . '"';
			$AddAddress .= ', UA_city_id = "' . mysqli_real_escape_string($con, $city) . '"';
			$AddAddress .= ', UA_zip = "' . mysqli_real_escape_string($con, $zip) . '"';
			$AddAddress .= ', UA_address = "' . mysqli_real_escape_string($con, $street) . '"';
			
			$SqlUpdateAddress = "INSERT INTO user_addresses SET $AddAddress";
			$ExecuteUpdateAddress = mysqli_query($con,$SqlUpdateAddress);
			
			if($ExecuteUpdateAddress) {
				$msg = "Your address added successfully";
				$link = 'addresses.php?msg='.base64_encode($msg);
				redirect($link);
			} else {
				if(DEBUG){
					echo mysqli_error($con);
				}
				$err = "Your address add failed. Try again.";
			}
			
		}
	}
	
}



if(!isset($_GET['address_id'])){
	$link="account.php?err=".base64_encode("Incorrect information.");
	redirect($link);
} else {
	if(base64_decode($_GET['address_id']) > 0){
		$address_id = base64_decode($_GET['address_id']);
		$uid = $_SESSION['user_id'];
		$GetAddress = mysqli_query($con,"SELECT * FROM user_addresses WHERE UA_user_id='$uid' AND UA_id='$address_id'");
		$Count = mysqli_num_rows($GetAddress);
		if($Count > 0){
			$SetAddress = mysqli_fetch_object($GetAddress);
			 
		} else {
			$link="account.php?err=".base64_encode("Incorrect information.");
			redirect($link);
		}
	} else {
		
	}
	
	
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tuizo||Edit User Account</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
 <link rel='stylesheet' media='screen and (min-width: 1024px)' href='css/pc.css' />
  <link rel='stylesheet' media='screen and (max-width: 480px)' href='css/mobile.css' />
  <link rel='stylesheet' media='screen and (min-width: 320px) and (max-width: 700px)' href='css/mobile.css' />
  <link rel='stylesheet' media='screen and (min-width: 480px) and (max-width: 700px)' href='css/mobile.css' />
  <link rel='stylesheet' media='screen and (min-width: 701px) and (max-width: 900px)' href='css/tablet.css' />
  <link rel='stylesheet' media='screen and (min-width: 901px) and (max-width: 1024px)' href='css/tabletwidth.css' />
<script type="text/javascript" src="js/jquery.min.js"></script>

<!--- bootstrap ----->

<!--[if IE 8]>
	<link href="css/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/script.js"></script>
    </head>

    <body>
    
    <div id="wrapper">
    
        	<div class="header clearfix"> 
            	<div class="HeaderLeft">
                
                    <a class="logo" href="#"> 
               <img height="56" src="images/logo.png" alt="tuizo">
                    </a>
                    
                    <div class="searchBox">
                    
                    	<div class="searcInput">
                            <button type="submit">search</button>
                        	<input class="search" type="text" value="search">
                        </div>
                    
                    
                    </div>
                    
                </div>
                
                <div class="headerRight">
                	<div class="headerRightContent">
                	
                    <ul class="mainMenu">
                    	<li class="mMenuID1"> <a href="#"> tuizo </a></li>
                        <li class="mMenuID2"> <a href="#"> help </a></li>
                    </ul>
                    
                    
                    
                    <div class="cartContent">
                    <p class="userInfo">
                   <a href="#">sign in</a> /  <a href="#">registration </a></p>
               
                    <div class="cart"> 
                    <a href="#">
                    4
                    </a>
                    </div>
                    	
                    </div>
                   </div>
                
                </div>
                
                
            </div> <!-- header end -->
            
         		<div id="innerContainer" >
                			<div class="cartContainner signUpContainner">
                            	<div class="signUpContainerLft">
                                	<h1 class="x-large">edit your latest info</h1>
                                    <div class="formError"><p>The details you have entered are incorrect, please check and try again.</p></div><!--formError-->
                                    <div class="formcondition">
                                    <h3>ENTER YOUR INFORMATION BELOW</h3>
                                    <p>Note: your name and billing address must be accurate.</p>
                                    <span><sup>*</sup> = required</span></div><!--formcondition-->
									
                                    <div class="signUpForm">
                                    <form name="signup" method="post" action="address_edit.php?address_id=<?php echo $_GET['address_id']; ?>">
                                    	<table width="100%" border="0">
                                  <tr>
                                    <td width="30%"><label for="title">Address Title*</label> </td>
                                    <td width="70%"><input id="title" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_title; } ?>" type="text" name="title"></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="fname">First Name*</label> </td>
                                    <td width="70%"><input id="fname" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_first_name; } ?>" type="text" name="fname"></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="mname">Middle Name*</label> </td>
                                    <td width="70%"><input id="mname" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_middle_name; } ?>" type="text" name="mname"></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="lname">Last Name*</label> </td>
                                    <td width="70%"><input id="lname" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_last_name; } ?>" type="text" name="lname"></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="phone"> Phone*</label> </td>
                                    <td width="70%"><input id="phone" type="text" name="phone" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_phone; } ?>"></td>
                                  </tr>
                                  
                                  <tr>
                                    <td width="30%"><label for="calltime">Best Call Time</label> </td>
                                    <td width="70%">
                                    <select id="calltime" name="calltime">
                                    <option value="">Select Time</option>
									<?php
                                    $y = "";
                                    for($x = 1; $x <= 12; $x++) {
                                        if($x.':00 AM' == $SetAddress -> UA_best_call_time) {
                                            $y = "selected";
                                        }
                                        ?>
                                    <option value="<?php echo $x.':00 AM'; ?>" <?php echo $y; ?>><?php echo $x.':00 AM'; ?></option>
                                    <?php
                                    }
                                    for($x = 1; $x <= 12; $x++) {
                                        if($x.':00 PM' == $SetAddress -> UA_best_call_time) {
                                            $y = "selected";
                                        }
                                        ?>
                                    <option value="<?php echo $x.':00 PM'; ?>" <?php echo $y; ?>><?php echo $x.':00 PM'; ?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                    </td>
                                  </tr>
                                  <tr>
                                   <td width="30%"><label for="street">Address *</label></td>
                                    <td width="70%"><input id="street" type="text" name="street" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_address; } ?>"></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="zipcode">Zip Code*</label></td>
                                    <td width="70%"><input id="zip" type="text" name="zip" value="<?php if(base64_decode($_GET['address_id']) == 0) { echo $title; } else { echo $SetAddress -> UA_zip; } ?>"></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="city">City*</label></td>
                                    <td width="70%"><select id="city" style="width:100%" name="city">
                                    <option value="">Select City</option>
									<?php
                                    $SqlCity = "SELECT * FROM cities WHERE city_status='allow'";
                                    $ExecuteCity = mysqli_query($con,$SqlCity);
                                    while($GetCity = mysqli_fetch_object($ExecuteCity)) {	
                                        if($GetCity -> city_id == $SetAddress -> UA_city_id) {
                                                    $select = "selected";
                                        }
                                        
                                        echo '<option value="'.$GetCity -> city_id.'" '.$select.'>'.$GetCity -> city_name.'</option>';
                                    }
                                    ?>
                                    </select></td>
                                  </tr>
                                  <tr>
                                    <td width="30%"><label for="country">Country*</label></td>
                                    <td width="70%">
                                    <select id="country" name="country" style="width:100%">
                                    <option value="">Select Country</option>
									<?php
                                    $SqlCountry = "SELECT * FROM countries WHERE country_status='allow' ORDER BY country_name ASC";
                                    $ExecuteCountry = mysqli_query($con,$SqlCountry);
                                    while($GetCountry = mysqli_fetch_object($ExecuteCountry)) {	
                                        if($GetCountry -> country_id == $SetAddress -> UA_country_id) {
                                                    $select = "selected";
                                        }	  
                                         echo '<option value="'.$GetCountry -> country_id.'" '.$select.'>'.$GetCountry -> country_name.'</option>';
                                    }
                                    ?>
                                    </select>
                                    </td>
                                  </tr>
                                </table>
                                <?php if(base64_decode($_GET['address_id']) == 0) { ?>
                                <button type="submit" style="float:right;" class="signUpNext" name="update">add</button>
                                <?php } else { ?>
                                <button type="submit" style="float:right;" class="signUpNext" name="update">update</button>
                                <?php } ?>
                                
                                </form>

                                    </div><!--signUpForm-->
                                    
                                   
                                    
                                </div><!--signUpContainerLft-->
                                	
									
									<?php include('user_side_menu.php'); ?>
                            	
                            
                            </div><!--cartContainner-->
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