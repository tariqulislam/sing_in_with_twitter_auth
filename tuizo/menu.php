<?php
$session_id = session_id();
$query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "'";
$result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
 
$tempPackageArray = array(); 
/* package sql start*/
$tempPackageSql = "SELECT PTC_id,PTC_package_id,PTC_package_name,PTC_package_price,PTC_package_discount,PTC_package_quantity FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "'";
$tempPackageSqlResult=  mysqli_query($con, $tempPackageSql);
if($tempPackageSqlResult){
    while ($tempPackageSqlResultRowObj = mysqli_fetch_object($tempPackageSqlResult)){
        /* Start: category sql */
        $tempPackageCatSql ="SELECT `ProTC_product_category_id`,COUNT(ProTC_product_category_id) AS category_count, SUM(`ProTC_product_quantity`) AS total_product_added,(SELECT PC_catagory_quantity FROM package_categories WHERE package_categories.PC_package_id = product_temp_cart.ProTC_PTC_package_id AND package_categories.PC_catagory_id = product_temp_cart.ProTC_product_category_id) AS category_limit,  (SELECT category_name FROM categories WHERE  categories.category_id = product_temp_cart.ProTC_product_category_id) AS category_name  
FROM product_temp_cart 
WHERE ProTC_PTC_package_id = ".$tempPackageSqlResultRowObj->PTC_package_id." AND  ProTC_session_id ='" . $session_id . "' GROUP BY ProTC_product_category_id";
  
        $tempPackageCatSqlResult = mysqli_query($con, $tempPackageCatSql);
        
        if($tempPackageCatSqlResult){
             $tempPackageSqlResultRowObj->categories= array();
            if(mysqli_num_rows($tempPackageCatSqlResult) > 0){
                while ($tempPackageCatSqlResultRowObj = mysqli_fetch_object($tempPackageCatSqlResult))
                $tempPackageSqlResultRowObj->categories[] = $tempPackageCatSqlResultRowObj;
            }
            
            mysqli_free_result($tempPackageCatSqlResult);
        }else{
            if(DEBUG){
                echo 'tempPackageCatSqlResult Error : '. mysqli_error($con);
            }else{
                echo 'tempPackageCatSqlResult Fail';
            }
        }
         /* End: category sql */
        $tempPackageArray[] = $tempPackageSqlResultRowObj;
    }
    mysqli_free_result($tempPackageSqlResult);
    
}else{
    if(DEBUG){
        echo 'tempPackageSqlResult Error: '.  mysqli_error($con);
    }else{
        echo 'tempPackageSqlResult Fail';
    }
}

//printDie($tempPackageArray);
/* package sql end*/
?><div class="header clearfix">

    <div class="HeaderLeft">

        <a class="logo" href="<?php echo baseUrl(); ?>">

            <img height="56" src="images/logo.png" alt="tuizo">

        </a>
        <ul class="mainMenu pcHide tabletHide">
                   <li  class="mMenuID1 <?php 
                if(isset($_GET['package_user_type']))
                {
                        if($_GET['package_user_type']=='man')
                        {
                           echo 'active';
                        }
                    
                }
                else
                {
                    echo '';
                }
                
                    ?>">
                  
                    <a  href="<?php echo baseUrl('index.php?package_user_type=man') ?>">
                    Men 
                  </a>
                </li>
                <li class="mMenuID2 <?php 
                if(isset($_GET['package_user_type']))
                {
                        if($_GET['package_user_type']=='woman')
                        {
                           echo 'active';
                        }
                    
                }
                else
                {
                    echo '';
                }
                
                    ?>">
                  
                  <a  href="<?php echo baseUrl('index.php?package_user_type=woman') ?>">
                    Women 
                  </a>
                </li>
        </ul>
        <div class="searchBox pcSearch">
          
            <div class="searcInput">
                <form method="get" action="search.php">
                    <button type="submit" id="searchButton" type="submit" >
                    search
                </button>
                    <input name="SearchString" class="search" id="SearchString" placeholder="Search.." value="<?php if(isset($_GET['SearchString'])){ echo $SearchString;} ?>" type="text"/>
                </form>
            </div>
        </div>

    </div>

    <div class="headerRight">
        <div class="headerRightContent">
           
           <ul class="mainMenu mobileHide tabletShow">
                <li  class="mMenuID1 <?php 
                if(isset($_GET['package_user_type']))
                {
                        if($_GET['package_user_type']=='man')
                        {
                           echo 'active';
                        }
                    
                }
                else
                {
                    echo '';
                }
                
                    ?>">
                  
                    <a  href="<?php echo baseUrl('index.php?package_user_type=man') ?>">
                    Men 
                  </a>
                </li>
                <li class="mMenuID2 <?php 
                if(isset($_GET['package_user_type']))
                {
                        if($_GET['package_user_type']=='woman')
                        {
                           echo 'active';
                        }
                    
                }
                else
                {
                    echo '';
                }
                
                    ?>">
                  
                  <a  href="<?php echo baseUrl('index.php?package_user_type=woman') ?>">
                    Women 
                  </a>
                </li>
              </ul>



            <div class="cartContent">
                <div class="cartContentMouseover">
                    <div class="cartContentInner">
                        <p class="userInfo">
                            <?php
                            if(!checkUserLogin()) {
                                ?>
                            <a href="<?php echo baseUrl("sign_in.php");?>">
                                sign in
                            </a>
                            /  
                            <a href="<?php echo baseUrl("sign_up.php");?>">
                                registration 	                   
                            </a>
                            <?php
                            }
                            elseif(checkUserLogin()) {
                                $style='font-size:13px;';
                                if(strlen($_SESSION["user_name"]) > 10){
                                    $style='font-size:11px;'; 
                                }
                                ?>
                            <a style="<?php echo $style; ?>" href="<?php echo baseUrl("account.php");?>">
                            <?php echo $_SESSION["user_name"]; ?>
                            </a>
                            /  
                            <a style="<?php echo $style; ?>" href="<?php echo baseUrl("index.php?logout=true");?>">
                                Logout 	                   
                            </a>
                           <?php
                           }
                            ?>
                        </p>

                        <div id="topcart" class="cart">
                            <?php
                            if ($result_of_package_cart_count) {
                                if (mysqli_num_rows($result_of_package_cart_count) >= 1) {
                                    $count_rows = mysqli_fetch_object($result_of_package_cart_count);
                                    ?>
                                    <a href="javascript:void(0)" id="totalPackageCount">
                                    <?php echo $count_rows->Package_Quantity; ?>
                                    </a>
                                        <?php
                                    } else {
                                        ?>
                                    <a href="javascript:void(0)" id="totalPackageCount">0</a>
                                    <?php
                                }
                                mysqli_free_result($result_of_package_cart_count);
                            } else {
                                if (DEBUG) {
                                    echo 'result_of_package_cart_count Error: ' . mysqli_error($con);
                                } else {
                                    echo 'result_of_package_cart_count error';
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="cartDropDown" style="display:none">
                        <h5>
                            ITEMS ADDED TO BAG
                        </h5>


<span id="wholeCart">
                        <table class="cartResult" width="100%" border="0">
                           <?php $tempPackageArrayCounter= count($tempPackageArray);?>
                            <?php if( $tempPackageArrayCounter > 0):?>
                            <?php for($i=0; $i < $tempPackageArrayCounter ; $i++):?>
                                <!--heading-->
                                <tr class="trhead">
                                     <td width="50%">Package</td><td>Quantity</td><td>Price</td>
                                </tr>
                                <!--package data-->
                              <tr> 
                                  <td> <?php echo $tempPackageArray[$i]->PTC_package_name;?> </td>
                                  <td class="QiuanityTd" id="packageQuantity_<?php echo $tempPackageArray[$i]->PTC_package_id; ?>"> <?php echo $tempPackageArray[$i]->PTC_package_quantity;?> </td>
                                  <td><font id="packagePrice_<?php echo $tempPackageArray[$i]->PTC_package_id; ?>"><?php echo ($tempPackageArray[$i]->PTC_package_price * $tempPackageArray[$i]->PTC_package_quantity);?></font></td>
                              </tr>
                              <?php $categoryCounter = count($tempPackageArray[$i]->categories); ?>
                              <?php if($categoryCounter > 0):?>
                              <?php for($j=0; $j < $categoryCounter; $j++):?>
                               <tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td> <?php echo $tempPackageArray[$i]->categories[$j]->category_name; ?> </td>
                                    <?php  $left = (($tempPackageArray[$i]->categories[$j]->category_limit* $tempPackageArray[$i]->categories[$j]->category_count)* $tempPackageArray[$i]->PTC_package_quantity)-$tempPackageArray[$i]->categories[$j]->total_product_added ; ?>
                                    <td><?php echo  'Added <font id="addedQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$tempPackageArray[$i]->categories[$j]->total_product_added.'</font> <br>left <font id="leftQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$left.'</font>';   ?></td>
                              </tr>
                              <?php endfor; /* ($j=0; $j < $categoryCounter; $j++)*/ ?>
                              
                              <?php else: /*($categoryCounter > 0):*/ ?>
                              <tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td colspan="2" > <p style="color:red;" >Product not added</p> </td>
                              </tr>
                              <?php endif; /*($categoryCounter > 0):*/ ?>        
                                
                            <?php endfor; /* ($i=0; $i < $tempPackageArrayCounter ; $i++):*/ ?>
                            <?php else: /*(count($tempPackageArray) > 0)*/ ?>
                               <tr class="packageNotAdded"> 
                                  
                                    <td> <p style="color:red;" >Package not added</p> </td>
                              </tr>
                            <?php endif; /*(count($tempPackageArray) > 0)*/ ?>

                           
                          </table>
                          
                          
</span>                          
                          
                        <div style="clear:both">
                        </div>

                        <p align="center">
                            <?php if($count_rows->Package_Quantity > 0){ ?>
                            <a class="topvcart" id="cartHref_viewCart" href="<?php echo baseUrl('cart.php');?>">View Cart</a>
                            <a class="topvcart topvcartpro" id="cartHref" href="<?php echo baseUrl('shipping_billing.php'); ?>">Check out</a>
                            <?php } else { ?>
                            <a class="topvcart" id="cartHref_viewCart" href="javascript:void(0)">View Cart</a>
                            <a class="topvcart topvcartpro"  id="cartHref" href="javascript:void(0)">Check out</a>
                            <?php } ?>
                                
                        </p>

                    </div>

                </div>

            </div>
        </div>

    </div>


</div> <!-- header end -->
