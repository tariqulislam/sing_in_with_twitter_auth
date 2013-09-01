<?php
//session_start();
include("config/config.php");
$email = $_SESSION["mail_address"];
$uid = $_SESSION['user_id'];
if(!isset($_GET['order_id'])){
	$link = 'order_history.php';
	redirect($link);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tuizo||User Account</title>
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
            
            <a class="logo" href="index.html">
              
              <img height="56" src="images/logo.png" alt="tuizo">
              
            </a>
            <ul class="mainMenu pcHide tabletHide">
              <li class="mMenuID1">
                
                <a href="#">
                  tuizo 
                </a>
              </li>
              <li class="mMenuID2">
                
                <a href="#">
                  help 
                </a>
              </li>
            </ul>
            <div class="searchBox pcSearch">
              
              <div class="searcInput">
                <button type="submit">
                  search
                </button>
                <input class="search" type="text" value="search">
              </div>
              
              
            </div>
            
          </div>
          
          <div class="headerRight">
            <div class="headerRightContent">
              
              <ul class="mainMenu mobileHide tabletShow">
                <li class="mMenuID1">
                  
                  <a href="#">
                    tuizo 
                  </a>
                </li>
                <li class="mMenuID2">
                  
                  <a href="#">
                    help 
                  </a>
                </li>
              </ul>
              
              
              
              <div class="cartContent">
                <div class="cartContentMouseover">
                  <div class="cartContentInner">
                    <p class="userInfo">
                      <a href="#">
                        sign in
                      </a>
                      /  
                      <a href="#">
                        registration 	                   
                      </a>
                    </p>
                    
                    <div id="topcart" class="cart">
                      
                      <a href="#">
                        4
                      </a>
                    </div>
                  </div>
                  
                  <div class="cartDropDown" style="display:none">
                    <h5>
                      ITEMS ADDED TO BAG
                    </h5>
                    
                    
                    <table class="cartResult" width="100%" border="0">
                      <tr class="trhead">
                        <td width="50%">
                          Package
                        </td>
                        <td>
                          Qiuanity
                        </td>
                        <td>
                          Products
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Tuizo Eid Collection For Men
                        </td>
                        <td class="QiuanityTd">
                          2
                        </td>
                        <td>
                          Added 2
                          <br>
                          
                          left 2
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Tuizo Eid Collection
                        </td>
                        <td class="QiuanityTd">
                          1
                        </td>
                        <td>
                          Added 0
                          left 5
                        </td>
                      </tr>
                    </table>
                    
                    <div style="clear:both">
                    </div>
                    
                    <p align="center">
                      <a class="topvcart" href="#">
                        View Cart 
                      </a>
                      <a class="topvcart topvcartpro" href="#">
                        View Product 
                      </a>
                    </p>
                    
                  </div>
                  
                </div>
                
              </div>
            </div>
            
          </div>
          
          
        </div> <!-- header end -->
            
         		<div id="innerContainer" >
                			<div class="cartContainner">
                            	<div class="cartContainnerLft">
												<div class="userAccountRight">
                    	
                        
                        
                        
                        <div class="orderContainner">
                            	 <div class="faqTop clearfix">
                                		<h1 class="x-large">My Orders</h1>
                                        
                                </div><!--faqTop-->
                            	<table class="orderList" width="100%" border="1">
                  <tr class="orderHeading">
                  	<td width="15%">Package Name</td>
                    <td width="15%">Package Quantity</td>
                    <td width="15%">Product Quantity</td>
                    <td width="15%">Package Category</td>
                    <td width="15%">Package Tax</td>
                    <td width="15%">Package Price</td>
                    <td width="15%">Package Discount</td>
                  </tr>
                  
<?php
 $user_id = $_SESSION['user_id'];
 $order_id = $_GET['order_id'];
 $GetPackageOrder = "SELECT * FROM order_packages WHERE OPA_order_id='$order_id'";
 $ExecutePackageOrder = mysqli_query($con,$GetPackageOrder);
 while($SetPackageOrder = mysqli_fetch_object($ExecutePackageOrder)){
 ?>                                 
                                  <tr height="22">
                                    <td><?php echo $SetPackageOrder -> OPA_package_name; ?></td>
                                    <td><?php echo $SetPackageOrder -> OPA_package_quantity; ?></td>
                                    <?php
									$SqlProduct = "SELECT * FROM order_products WHERE OP_order_id='$order_id' AND OP_package_id='".$SetPackageOrder -> OPA_package_id."'";
									$ExecuteProduct = mysqli_query($con,$SqlProduct);
									$CountProduct = mysqli_num_rows($ExecuteProduct);
									?>
                                    <td><?php if($CountProduct == 0){ ?><a href="javascript:void(0)"><?php }else{ ?><a href="order_product.php?order_id=<?php echo $_GET['order_id']; ?>&package_id=<?php echo $SetPackageOrder -> OPA_package_id; ?>"><?php } ?><?php echo $CountProduct; ?> product(s)</a></td>
                                    <?php
									$SqlCategory = "SELECT * FROM categories WHERE category_id='".$SetPackageOrder -> OPA_package_category_id."'";
									$ExecuteCategory = mysqli_query($con,$SqlCategory);
									$GetCategory = mysqli_fetch_object($ExecuteCategory);
									?>
                                    <td><?php echo $GetCategory -> category_name; ?></td>
                                    <?php
									$SqlTax = "SELECT * FROM tax_classes WHERE TC_id='".$SetPackageOrder -> OPA_package_tax_class_id."'";
									$ExecuteTax = mysqli_query($con,$SqlTax);
									$GetTax = mysqli_fetch_object($ExecuteTax);
									?>
                                    <td><?php echo $GetTax -> TC_title; ?></td>
                                    <td><?php echo $SetPackageOrder -> OPA_package_price; ?></td>
                                    <td><?php echo $SetPackageOrder -> OPA_package_quantity; ?></td>
                                  </tr>
<?php
 }
 ?>
                  
                </table>
                <p style="float:right; text-decoration:underline; font-size:14px; font-weight:bold;"><a href="order_history.php">Back</a></p>

                            </div>
                        
                        
                        
                        
                                            </div><!--userAccountRight-->
                                        
                                </div><!--cartContainnerLft-->
                                
                                	
									
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