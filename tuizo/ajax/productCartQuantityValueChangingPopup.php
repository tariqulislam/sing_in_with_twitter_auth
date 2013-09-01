<?php
include '../config/config.php';
$category_product_quantity = '';
$Product_ID ='';
$Package_ID = '';
$product_color = '';
$product_size = '';
$cart_id = session_id();

extract($_POST);

if($category_product_quantity > 0){
	
	$CheckPackegeTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
	$CountPackageTempCart = mysqli_num_rows($CheckPackegeTempCart);
	
	if($CountPackageTempCart > 0){
		//getting product information from product_temp_cart
              $query_of_getProductTempCart ="SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_color_id='$product_color' AND ProTC_size_id='$product_size'";
		$GetProductTempCart = mysqli_query($con,$query_of_getProductTempCart);
		 mysqli_num_rows($GetProductTempCart);
                 
              
		
		
		if(mysqli_num_rows($GetProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($GetProductTempCart);
			$CurrentPackageID = $SetProductTempCart -> ProTC_PTC_package_id;
                        $Current_Product_temp_cart_id=$SetProductTempCart ->ProTC_id;
                        $current_product_category_id = $SetProductTempCart->ProTC_product_category_id;
			
			$UpdateProduct = '';
			$UpdateProduct .= ' ProTC_product_quantity = "' . mysqli_real_escape_string($con, $category_product_quantity) . '"';
			
			$UpdateProductTempCart = mysqli_query($con,"UPDATE product_temp_cart SET $UpdateProduct WHERE ProTC_id=$Current_Product_temp_cart_id");
			
			//getting new package quantity and updating table
			$NewPackageCount = GetPackageQuantity($CurrentPackageID);
			$UpdatePackageCart = mysqli_query($con,"UPDATE package_temp_cart SET PTC_package_quantity='$NewPackageCount' WHERE PTC_package_id='$CurrentPackageID' AND PTC_session_id='$cart_id'");
			
			
			//getting package information from package_temp_cart db
			$GetPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$CurrentPackageID' AND PTC_session_id='$cart_id'");
			$SetPackageInfo = mysqli_fetch_object($GetPackageInfo);
			$TotalPackagePrice = $SetPackageInfo -> PTC_package_price * $NewPackageCount;
			$TotalPackageDiscount = $SetPackageInfo -> PTC_package_discount * $NewPackageCount;
			
			$GetAllPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' AND PTC_package_id NOT IN($CurrentPackageID)");
			
			$TotalTempPackagePrice = 0;
			$TotalTempPackageDiscount = 0;
			while($SetAllPackageInfo = mysqli_fetch_object($GetAllPackageInfo)){
				$TotalTempPackagePrice += $SetAllPackageInfo -> PTC_package_price * $SetAllPackageInfo -> PTC_package_quantity;
				$TotalTempPackageDiscount += $SetAllPackageInfo -> PTC_package_discount * $SetAllPackageInfo -> PTC_package_quantity;
			}
			
			$TotalPrice = $TotalTempPackagePrice + $TotalPackagePrice;
			$TotalDiscount = $TotalTempPackageDiscount + $TotalPackageDiscount;
			/** Start generating  the mini cart **/
                        $session_id = session_id();
			$query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
			 
			$tempPackageArray = array(); 
			/* package sql start*/
			$tempPackageSql = "SELECT PTC_id,PTC_package_id,PTC_package_name,PTC_package_price,PTC_package_discount,PTC_package_quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$tempPackageSqlResult=  mysqli_query($con, $tempPackageSql);
			if($tempPackageSqlResult){
				while ($tempPackageSqlResultRowObj = mysqli_fetch_object($tempPackageSqlResult)){
					/* Start: category sql */
					$tempPackageCatSql ="SELECT `ProTC_product_category_id`,COUNT(ProTC_product_category_id) AS category_count, SUM(`ProTC_product_quantity`) AS total_product_added,(SELECT PC_catagory_quantity FROM package_categories WHERE package_categories.PC_package_id = product_temp_cart.ProTC_PTC_package_id AND package_categories.PC_catagory_id = product_temp_cart.ProTC_product_category_id) AS category_limit,  (SELECT category_name FROM categories WHERE  categories.category_id = product_temp_cart.ProTC_product_category_id) AS category_name  
			FROM product_temp_cart 
			WHERE ProTC_PTC_package_id = ".$tempPackageSqlResultRowObj->PTC_package_id." AND  ProTC_session_id ='" . $cart_id . "' GROUP BY ProTC_product_category_id";
			  
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
							$total_cart = '<table class="cartResult" width="100%" border="0">';
                           $tempPackageArrayCounter= count($tempPackageArray);
                            if( $tempPackageArrayCounter > 0):
                            for($i=0; $i < $tempPackageArrayCounter ; $i++):
                            
							$total_cart .= '<!--heading-->
                                <tr class="trhead">
                                     <td width="50%">Package</td><td>Quantity</td><td>Price</td>
                                </tr>
                                <!--package data-->
                              <tr> 
                                  <td>'.$tempPackageArray[$i]->PTC_package_name.'</td>
                                  <td class="QiuanityTd" id="packageQuantity_'.$tempPackageArray[$i]->PTC_package_id.'">'.$tempPackageArray[$i]->PTC_package_quantity.'</td>
                                  <td>'.($tempPackageArray[$i]->PTC_package_price * $tempPackageArray[$i]->PTC_package_quantity).'</td>
                              </tr>';
                              $categoryCounter = count($tempPackageArray[$i]->categories);
                              if($categoryCounter > 0):
                              for($j=0; $j < $categoryCounter; $j++):
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td>'.$tempPackageArray[$i]->categories[$j]->category_name.'</td>';
                                    $left = (($tempPackageArray[$i]->categories[$j]->category_limit*$tempPackageArray[$i]->categories[$j]->category_count )* $tempPackageArray[$i]->PTC_package_quantity)-$tempPackageArray[$i]->categories[$j]->total_product_added ;
                              $total_cart .= '<td>Added <font id="addedQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$tempPackageArray[$i]->categories[$j]->total_product_added.'</font> <br>left <font id="leftQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$left.'</font></td>
                              </tr>';
                              endfor; /* ($j=0; $j < $categoryCounter; $j++)*/
                              
                              else: /*($categoryCounter > 0):*/
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td colspan="2" > <p style="color:red;" >Product not added</p> </td>
                              </tr>';
                              endif; /*($categoryCounter > 0):*/       
                                
                            endfor; /* ($i=0; $i < $tempPackageArrayCounter ; $i++):*/
                            else: /*(count($tempPackageArray) > 0)*/
                             $total_cart .= '<tr> 
                                  
                                    <td> <p style="color:red;" >Package not added</p> </td>
                              </tr>';
                            endif; /*(count($tempPackageArray) > 0)*/

                           
                          $total_cart .= '</table>';
	
			
			/** End generating  the mini cart **/
                          		 /** start: count product for product Qunatity **/
                   //initializing new product count
                        $NewProductQuantity = 0;

                        //get product_temp_cart information
                        $ProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$current_product_category_id");

                        //getting information from product_temp_cart table
                        if(mysqli_num_rows($ProductTempCart) > 0){
                                $SetProductTempCart = mysqli_fetch_object($ProductTempCart);
                                $ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
                                $NewProductQuantity = $ProductCurrentQuantity;
                        } else {

                                $NewProductQuantity = 1;
                        }
               /** end: count product for product Qunatity **/
                         
                          
			$data=array("output_type"=>1, "package_quantity"=>$NewPackageCount, "product_quantity"=>$NewProductQuantity, "package_price"=>number_format((float)$TotalPackagePrice,2,'.',''), "total_temp_cart_price"=>number_format((float)$TotalPrice,2,'.',''), "total_temp_cart_discount"=>number_format((float)$TotalDiscount,2,'.',''),"full_cart"=>$total_cart); // This is your data array/result
			echo json_encode($data);
		} else {
			
			$SelectPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
			$GetPackageTempCart = mysqli_fetch_object($SelectPackageTempCart);
			
			$InsertProduct = '';
			$InsertProduct .= ' ProTC_PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
			$InsertProduct .= ', ProTC_product_category_id = "' . mysqli_real_escape_string($con, $Category_id) . '"';
			$InsertProduct .= ', ProTC_product_inventory_id = "' . mysqli_real_escape_string($con, $Inventory_ID) . '"';
			$InsertProduct .= ', ProTC_color_id = "' . mysqli_real_escape_string($con, $product_color) . '"';
			$InsertProduct .= ', ProTC_size_id = "' . mysqli_real_escape_string($con, $product_size) . '"';
			$InsertProduct .= ', ProTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
			$InsertProduct .= ', ProTC_product_id = "' . mysqli_real_escape_string($con, $Product_ID) . '"';
			$InsertProduct .= ', ProTC_product_quantity = "' . mysqli_real_escape_string($con, $category_product_quantity) . '"';
			$InsertProduct .= ', ProTC_PTC_id = "' . mysqli_real_escape_string($con, $GetPackageTempCart -> PTC_id) . '"';
			
			
			$InsertProductTempCart = mysqli_query($con,"INSERT INTO product_temp_cart SET $InsertProduct");
			
			//getting new package quantity and updating table
			$NewPackageCount = GetPackageQuantity($Package_ID);
			$UpdatePackageCart = mysqli_query($con,"UPDATE package_temp_cart SET PTC_package_quantity='$NewPackageCount' WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
			
			
			//getting package information from package_temp_cart db
			$GetPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
			$SetPackageInfo = mysqli_fetch_object($GetPackageInfo);
			$TotalPackagePrice = $SetPackageInfo -> PTC_package_price * $NewPackageCount;
			$TotalPackageDiscount = $SetPackageInfo -> PTC_package_discount * $NewPackageCount;
			
			$GetAllPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' AND PTC_package_id NOT IN($Package_ID)");
			
			$TotalTempPackagePrice = 0;
			$TotalTempPackageDiscount = 0;
			while($SetAllPackageInfo = mysqli_fetch_object($GetAllPackageInfo)){
				$TotalTempPackagePrice += $SetAllPackageInfo -> PTC_package_price * $SetAllPackageInfo -> PTC_package_quantity;
				$TotalTempPackageDiscount += $SetAllPackageInfo -> PTC_package_discount * $SetAllPackageInfo -> PTC_package_quantity;
			}
			
			$TotalPrice = $TotalTempPackagePrice + $TotalPackagePrice;
			$TotalDiscount = $TotalTempPackageDiscount + $TotalPackageDiscount;
			
			/** Start generating  the mini cart **/
                        $session_id = session_id();
			$query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
			 
			$tempPackageArray = array(); 
			/* package sql start*/
			$tempPackageSql = "SELECT PTC_id,PTC_package_id,PTC_package_name,PTC_package_price,PTC_package_discount,PTC_package_quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$tempPackageSqlResult=  mysqli_query($con, $tempPackageSql);
			if($tempPackageSqlResult){
				while ($tempPackageSqlResultRowObj = mysqli_fetch_object($tempPackageSqlResult)){
					/* Start: category sql */
					$tempPackageCatSql ="SELECT `ProTC_product_category_id`,COUNT(ProTC_product_category_id) AS category_count, SUM(`ProTC_product_quantity`) AS total_product_added,(SELECT PC_catagory_quantity FROM package_categories WHERE package_categories.PC_package_id = product_temp_cart.ProTC_PTC_package_id AND package_categories.PC_catagory_id = product_temp_cart.ProTC_product_category_id) AS category_limit,  (SELECT category_name FROM categories WHERE  categories.category_id = product_temp_cart.ProTC_product_category_id) AS category_name  
			FROM product_temp_cart 
			WHERE ProTC_PTC_package_id = ".$tempPackageSqlResultRowObj->PTC_package_id." AND  ProTC_session_id ='" . $cart_id . "' GROUP BY ProTC_product_category_id";
			  
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
							$total_cart = '<table class="cartResult" width="100%" border="0">';
                           $tempPackageArrayCounter= count($tempPackageArray);
                            if( $tempPackageArrayCounter > 0):
                            for($i=0; $i < $tempPackageArrayCounter ; $i++):
                            
							$total_cart .= '<!--heading-->
                                <tr class="trhead">
                                     <td width="50%">Package</td><td>Quantity</td><td>Price</td>
                                </tr>
                                <!--package data-->
                              <tr> 
                                  <td>'.$tempPackageArray[$i]->PTC_package_name.'</td>
                                  <td class="QiuanityTd" id="packageQuantity_'.$tempPackageArray[$i]->PTC_package_id.'">'.$tempPackageArray[$i]->PTC_package_quantity.'</td>
                                  <td>'.($tempPackageArray[$i]->PTC_package_price * $tempPackageArray[$i]->PTC_package_quantity).'</td>
                              </tr>';
                              $categoryCounter = count($tempPackageArray[$i]->categories);
                              if($categoryCounter > 0):
                              for($j=0; $j < $categoryCounter; $j++):
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td>'.$tempPackageArray[$i]->categories[$j]->category_name.'</td>';
                                    $left = (($tempPackageArray[$i]->categories[$j]->category_limit*$tempPackageArray[$i]->categories[$j]->category_count )* $tempPackageArray[$i]->PTC_package_quantity)-$tempPackageArray[$i]->categories[$j]->total_product_added ;
                              $total_cart .= '<td>Added <font id="addedQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$tempPackageArray[$i]->categories[$j]->total_product_added.'</font> <br>left <font id="leftQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$left.'</font></td>
                              </tr>';
                              endfor; /* ($j=0; $j < $categoryCounter; $j++)*/
                              
                              else: /*($categoryCounter > 0):*/
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td colspan="2" > <p style="color:red;" >Product not added</p> </td>
                              </tr>';
                              endif; /*($categoryCounter > 0):*/       
                                
                            endfor; /* ($i=0; $i < $tempPackageArrayCounter ; $i++):*/
                            else: /*(count($tempPackageArray) > 0)*/
                             $total_cart .= '<tr> 
                                  
                                    <td> <p style="color:red;" >Package not added</p> </td>
                              </tr>';
                            endif; /*(count($tempPackageArray) > 0)*/

                           
                          $total_cart .= '</table>';
			
			
			/** End generating  the mini cart **/
                        //initializing new product count
		$NewProductQuantity = 0;
		
		//get product_temp_cart information
		$ProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_id");
		
		//getting information from product_temp_cart table
		if(mysqli_num_rows($ProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($ProductTempCart);
			$ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
			$NewProductQuantity = $ProductCurrentQuantity;
		} else {
			
			$NewProductQuantity = 1;
		}
			
			
			$data=array("output_type"=>1, "product_quantity"=>$NewProductQuantity, "package_quantity"=>$NewPackageCount, "package_price"=>number_format((float)$TotalPackagePrice,2,'.',''), "total_temp_cart_price"=>number_format((float)$TotalPrice,2,'.',''), "total_temp_cart_discount"=>number_format((float)$TotalDiscount,2,'.',''),"full_cart"=>$total_cart); // This is your data array/result
			echo json_encode($data);
		}
		
	} else {
		
		//getting package information from db
		$GetPackage = mysqli_query($con,"SELECT * FROM packages WHERE package_id=$Package_ID");
		$SetPackage = mysqli_fetch_object($GetPackage);
		
		//inserting package into package_temp_cart
		$InsertPackageTempCart = '';
		$InsertPackageTempCart .= ' PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
		$InsertPackageTempCart .= ', PTC_package_name = "' . mysqli_real_escape_string($con, $SetPackage -> package_name) . '"';
		$InsertPackageTempCart .= ', PTC_package_price = "' . mysqli_real_escape_string($con, $SetPackage -> package_price) . '"';
		$InsertPackageTempCart .= ', PTC_package_discount = "' . mysqli_real_escape_string($con, $SetPackage -> package_discount) . '"';
		$InsertPackageTempCart .= ', PTC_package_tax_class_id = "' . mysqli_real_escape_string($con, $SetPackage -> package_tax_class_id) . '"';
		$InsertPackageTempCart .= ', PTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
		$InsertPackageTempCart .= ', PTC_package_category_id = "' . mysqli_real_escape_string($con, $SetPackage -> package_catagory_id) . '"';
		$InsertPackageTempCart .= ', PTC_package_quantity = "' . mysqli_real_escape_string($con, 1) . '"';
		
		//if user logged in then his/her id will be saved
		if (checkUserLogin()) {
			$InsertPackageTempCart .= ', PTC_user_id = "' . mysqli_real_escape_string($con, $_SESSION["user_id"]) . '"';
		}
		
		//getting product information from product_temp_cart
		$GetProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_color_id='$product_color' AND ProTC_size_id='$product_size'");
		 mysqli_num_rows($GetProductTempCart);
		
		if(mysqli_num_rows($GetProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($GetProductTempCart);
			$CurrentPackageID = $SetProductTempCart -> ProTC_PTC_package_id;
		        $Current_Product_temp_cart_id=$SetProductTempCart ->ProTC_id;
                       // $current_category_id                                                                                              
                        
			$UpdateProduct = '';
			$UpdateProduct .= ' ProTC_product_quantity = "' . mysqli_real_escape_string($con, $category_product_quantity) . '"';
			
			$UpdateProductTempCart = mysqli_query($con,"UPDATE product_temp_cart SET $UpdateProduct WHERE ProTC_id=$Current_Product_temp_cart_id");
			
			//getting new package quantity and updating table
			$NewPackageCount = GetPackageQuantity($CurrentPackageID);
			$UpdatePackageCart = mysqli_query($con,"UPDATE package_temp_cart SET PTC_package_quantity='$NewPackageCount' WHERE PTC_package_id='$CurrentPackageID' AND PTC_session_id='$cart_id'");
			
			
			//getting package information from package_temp_cart db
			$GetPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$CurrentPackageID' AND PTC_session_id='$cart_id'");
			$SetPackageInfo = mysqli_fetch_object($GetPackageInfo);
			$TotalPackagePrice = $SetPackageInfo -> PTC_package_price * $NewPackageCount;
			$TotalPackageDiscount = $SetPackageInfo -> PTC_package_discount * $NewPackageCount;
			
			$GetAllPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' AND PTC_package_id NOT IN($CurrentPackageID)");
			
			$TotalTempPackagePrice = 0;
			$TotalTempPackageDiscount = 0;
			while($SetAllPackageInfo = mysqli_fetch_object($GetAllPackageInfo)){
				$TotalTempPackagePrice += $SetAllPackageInfo -> PTC_package_price * $SetAllPackageInfo -> PTC_package_quantity;
				$TotalTempPackageDiscount += $SetAllPackageInfo -> PTC_package_discount * $SetAllPackageInfo -> PTC_package_quantity;
			}
			
			$TotalPrice = $TotalTempPackagePrice + $TotalPackagePrice;
			$TotalDiscount = $TotalTempPackageDiscount + $TotalPackageDiscount;
			
                        	/** Start generating  the mini cart **/
                        $session_id = session_id();
			$query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
			 
			$tempPackageArray = array(); 
			/* package sql start*/
			$tempPackageSql = "SELECT PTC_id,PTC_package_id,PTC_package_name,PTC_package_price,PTC_package_discount,PTC_package_quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$tempPackageSqlResult=  mysqli_query($con, $tempPackageSql);
			if($tempPackageSqlResult){
				while ($tempPackageSqlResultRowObj = mysqli_fetch_object($tempPackageSqlResult)){
					/* Start: category sql */
					$tempPackageCatSql ="SELECT `ProTC_product_category_id`,COUNT(ProTC_product_category_id) AS category_count, SUM(`ProTC_product_quantity`) AS total_product_added,(SELECT PC_catagory_quantity FROM package_categories WHERE package_categories.PC_package_id = product_temp_cart.ProTC_PTC_package_id AND package_categories.PC_catagory_id = product_temp_cart.ProTC_product_category_id) AS category_limit,  (SELECT category_name FROM categories WHERE  categories.category_id = product_temp_cart.ProTC_product_category_id) AS category_name  
			FROM product_temp_cart 
			WHERE ProTC_PTC_package_id = ".$tempPackageSqlResultRowObj->PTC_package_id." AND  ProTC_session_id ='" . $cart_id . "' GROUP BY ProTC_product_category_id";
			  
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
							$total_cart = '<table class="cartResult" width="100%" border="0">';
                           $tempPackageArrayCounter= count($tempPackageArray);
                            if( $tempPackageArrayCounter > 0):
                            for($i=0; $i < $tempPackageArrayCounter ; $i++):
                            
							$total_cart .= '<!--heading-->
                                <tr class="trhead">
                                     <td width="50%">Package</td><td>Quantity</td><td>Price</td>
                                </tr>
                                <!--package data-->
                              <tr> 
                                  <td>'.$tempPackageArray[$i]->PTC_package_name.'</td>
                                  <td class="QiuanityTd" id="packageQuantity_'.$tempPackageArray[$i]->PTC_package_id.'">'.$tempPackageArray[$i]->PTC_package_quantity.'</td>
                                  <td>'.($tempPackageArray[$i]->PTC_package_price * $tempPackageArray[$i]->PTC_package_quantity).'</td>
                              </tr>';
                              $categoryCounter = count($tempPackageArray[$i]->categories);
                              if($categoryCounter > 0):
                              for($j=0; $j < $categoryCounter; $j++):
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td>'.$tempPackageArray[$i]->categories[$j]->category_name.'</td>';
                                    $left = (($tempPackageArray[$i]->categories[$j]->category_limit*$tempPackageArray[$i]->categories[$j]->category_count )* $tempPackageArray[$i]->PTC_package_quantity)-$tempPackageArray[$i]->categories[$j]->total_product_added ;
                              $total_cart .= '<td>Added <font id="addedQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$tempPackageArray[$i]->categories[$j]->total_product_added.'</font> <br>left <font id="leftQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$left.'</font></td>
                              </tr>';
                              endfor; /* ($j=0; $j < $categoryCounter; $j++)*/
                              
                              else: /*($categoryCounter > 0):*/
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td colspan="2" > <p style="color:red;" >Product not added</p> </td>
                              </tr>';
                              endif; /*($categoryCounter > 0):*/       
                                
                            endfor; /* ($i=0; $i < $tempPackageArrayCounter ; $i++):*/
                            else: /*(count($tempPackageArray) > 0)*/
                             $total_cart .= '<tr> 
                                  
                                    <td> <p style="color:red;" >Package not added</p> </td>
                              </tr>';
                            endif; /*(count($tempPackageArray) > 0)*/

                          $total_cart .= '</table>';
                          
                          /** End generating  the mini cart **/
                        //initializing new product count
		$NewProductQuantity = 0;
		
		//get product_temp_cart information
		$ProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_id");
		
		//getting information from product_temp_cart table
		if(mysqli_num_rows($ProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($ProductTempCart);
			$ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
			$NewProductQuantity = $ProductCurrentQuantity;
		} else {
			
			$NewProductQuantity = 1;
		}
		
			/** End generating  the mini cart **/
			$data=array("output_type"=>1, "package_quantity"=>$NewPackageCount, "package_price"=>number_format((float)$TotalPackagePrice,2,'.',''), "total_temp_cart_price"=>number_format((float)$TotalPrice,2,'.',''), "total_temp_cart_discount"=>number_format((float)$TotalDiscount,2,'.',''),"full_cart"=>$total_cart); // This is your data array/result
			echo json_encode($data);
		} else {
                       
                       /* Start: check Package Exists **/
                        $checktemppackages = mysqli_query($con, "SELECT * FROM package_temp_cart WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
                        $packageExists = mysqli_num_rows($checktemppackages);
                       
                        if($packageExists ==0)
                        {
                       //getting package information from db
                            $GetPackage = mysqli_query($con,"SELECT * FROM packages WHERE package_id=$Package_ID");
                            $SetPackage = mysqli_fetch_object($GetPackage);

                            //inserting package into package_temp_cart
                            $InsertPackageTempCart = '';
                            $InsertPackageTempCart .= ' PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
                            $InsertPackageTempCart .= ', PTC_package_name = "' . mysqli_real_escape_string($con, $SetPackage -> package_name) . '"';
                            $InsertPackageTempCart .= ', PTC_package_price = "' . mysqli_real_escape_string($con, $SetPackage -> package_price) . '"';
                            $InsertPackageTempCart .= ', PTC_package_discount = "' . mysqli_real_escape_string($con, $SetPackage -> package_discount) . '"';
                            $InsertPackageTempCart .= ', PTC_package_tax_class_id = "' . mysqli_real_escape_string($con, $SetPackage -> package_tax_class_id) . '"';
                            $InsertPackageTempCart .= ', PTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
                            $InsertPackageTempCart .= ', PTC_package_category_id = "' . mysqli_real_escape_string($con, $SetPackage -> package_catagory_id) . '"';
                            $InsertPackageTempCart .= ', PTC_package_quantity = "' . mysqli_real_escape_string($con, 1) . '"';

                            //if user logged in then his/her id will be saved
                            if (checkUserLogin()) {
                                    $InsertPackageTempCart .= ', PTC_user_id = "' . mysqli_real_escape_string($con, $_SESSION["user_id"]) . '"';
                            }


                            $InsertPackage= mysqli_query($con,"INSERT INTO package_temp_cart SET $InsertPackageTempCart");
                            //$PackageTempCartID = mysqli_insert_id($con);
                        }
                       /* End: check Package Exists **/
                       
                       
			
			$SelectPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
			$GetPackageTempCart = mysqli_fetch_object($SelectPackageTempCart);
			
			$InsertProduct = '';
			$InsertProduct .= ' ProTC_PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
			$InsertProduct .= ', ProTC_product_category_id = "' . mysqli_real_escape_string($con, $Category_id) . '"';
			$InsertProduct .= ', ProTC_product_inventory_id = "' . mysqli_real_escape_string($con, $Inventory_ID) . '"';
			$InsertProduct .= ', ProTC_color_id = "' . mysqli_real_escape_string($con, $product_color) . '"';
			$InsertProduct .= ', ProTC_size_id = "' . mysqli_real_escape_string($con, $product_size) . '"';
			$InsertProduct .= ', ProTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
			$InsertProduct .= ', ProTC_product_id = "' . mysqli_real_escape_string($con, $Product_ID) . '"';
			$InsertProduct .= ', ProTC_product_quantity = "' . mysqli_real_escape_string($con, $category_product_quantity) . '"';
			$InsertProduct .= ', ProTC_PTC_id = "' . mysqli_real_escape_string($con, $GetPackageTempCart -> PTC_id) . '"';
			
			
			$InsertProductTempCart = mysqli_query($con,"INSERT INTO product_temp_cart SET $InsertProduct");
			
                       
			//getting new package quantity and updating table
			$NewPackageCount = GetPackageQuantity($Package_ID);
			$UpdatePackageCart = mysqli_query($con,"UPDATE package_temp_cart SET PTC_package_quantity='$NewPackageCount' WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
			
			
			//getting package information from package_temp_cart db
			$GetPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id='$Package_ID' AND PTC_session_id='$cart_id'");
			$SetPackageInfo = mysqli_fetch_object($GetPackageInfo);
			$TotalPackagePrice = $SetPackageInfo -> PTC_package_price * $NewPackageCount;
			$TotalPackageDiscount = $SetPackageInfo -> PTC_package_discount * $NewPackageCount;
			
			$GetAllPackageInfo = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' AND PTC_package_id NOT IN($Package_ID)");
			
			$TotalTempPackagePrice = 0;
			$TotalTempPackageDiscount = 0;
			while($SetAllPackageInfo = mysqli_fetch_object($GetAllPackageInfo)){
				$TotalTempPackagePrice += $SetAllPackageInfo -> PTC_package_price * $SetAllPackageInfo -> PTC_package_quantity;
				$TotalTempPackageDiscount += $SetAllPackageInfo -> PTC_package_discount * $SetAllPackageInfo -> PTC_package_quantity;
			}
			
			$TotalPrice = $TotalTempPackagePrice + $TotalPackagePrice;
			$TotalDiscount = $TotalTempPackageDiscount + $TotalPackageDiscount;
			
			/** Start generating  the mini cart **/
                        $session_id = session_id();
			$query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
			 
			$tempPackageArray = array(); 
			/* package sql start*/
			$tempPackageSql = "SELECT PTC_id,PTC_package_id,PTC_package_name,PTC_package_price,PTC_package_discount,PTC_package_quantity FROM package_temp_cart WHERE PTC_session_id='" . $cart_id . "'";
			$tempPackageSqlResult=  mysqli_query($con, $tempPackageSql);
			if($tempPackageSqlResult){
				while ($tempPackageSqlResultRowObj = mysqli_fetch_object($tempPackageSqlResult)){
					/* Start: category sql */
					$tempPackageCatSql ="SELECT `ProTC_product_category_id`,COUNT(ProTC_product_category_id) AS category_count, SUM(`ProTC_product_quantity`) AS total_product_added,(SELECT PC_catagory_quantity FROM package_categories WHERE package_categories.PC_package_id = product_temp_cart.ProTC_PTC_package_id AND package_categories.PC_catagory_id = product_temp_cart.ProTC_product_category_id) AS category_limit,  (SELECT category_name FROM categories WHERE  categories.category_id = product_temp_cart.ProTC_product_category_id) AS category_name  
			FROM product_temp_cart 
			WHERE ProTC_PTC_package_id = ".$tempPackageSqlResultRowObj->PTC_package_id." AND  ProTC_session_id ='" . $cart_id . "' GROUP BY ProTC_product_category_id";
			  
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
							$total_cart = '<table class="cartResult" width="100%" border="0">';
                           $tempPackageArrayCounter= count($tempPackageArray);
                            if( $tempPackageArrayCounter > 0):
                            for($i=0; $i < $tempPackageArrayCounter ; $i++):
                            
							$total_cart .= '<!--heading-->
                                <tr class="trhead">
                                     <td width="50%">Package</td><td>Quantity</td><td>Price</td>
                                </tr>
                                <!--package data-->
                              <tr> 
                                  <td>'.$tempPackageArray[$i]->PTC_package_name.'</td>
                                  <td class="QiuanityTd" id="packageQuantity_'.$tempPackageArray[$i]->PTC_package_id.'">'.$tempPackageArray[$i]->PTC_package_quantity.'</td>
                                  <td>'.($tempPackageArray[$i]->PTC_package_price * $tempPackageArray[$i]->PTC_package_quantity).'</td>
                              </tr>';
                              $categoryCounter = count($tempPackageArray[$i]->categories);
                              if($categoryCounter > 0):
                              for($j=0; $j < $categoryCounter; $j++):
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td>'.$tempPackageArray[$i]->categories[$j]->category_name.'</td>';
                                    $left = (($tempPackageArray[$i]->categories[$j]->category_limit*$tempPackageArray[$i]->categories[$j]->category_count )* $tempPackageArray[$i]->PTC_package_quantity)-$tempPackageArray[$i]->categories[$j]->total_product_added ;
                              $total_cart .= '<td>Added <font id="addedQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$tempPackageArray[$i]->categories[$j]->total_product_added.'</font> <br>left <font id="leftQuantity_'.$tempPackageArray[$i]->PTC_package_id.'_'.$tempPackageArray[$i]->categories[$j]->ProTC_product_category_id.'">'.$left.'</font></td>
                              </tr>';
                              endfor; /* ($j=0; $j < $categoryCounter; $j++)*/
                              
                              else: /*($categoryCounter > 0):*/
                              $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td colspan="2" > <p style="color:red;" >Product not added</p> </td>
                              </tr>';
                              endif; /*($categoryCounter > 0):*/       
                                
                            endfor; /* ($i=0; $i < $tempPackageArrayCounter ; $i++):*/
                            else: /*(count($tempPackageArray) > 0)*/
                             $total_cart .= '<tr> 
                                  
                                    <td> <p style="color:red;" >Package not added</p> </td>
                              </tr>';
                            endif; /*(count($tempPackageArray) > 0)*/

                           
                          $total_cart .= '</table>';
			
			
			/** End generating  the mini cart **/
			//initializing new product count
		$NewProductQuantity = 0;
		
		//get product_temp_cart information
		$ProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_id");
		
		//getting information from product_temp_cart table
		if(mysqli_num_rows($ProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($ProductTempCart);
			$ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
			$NewProductQuantity = $ProductCurrentQuantity;
		} else {
			
			$NewProductQuantity = 1;
		}
		  $GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
		   $TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);	
			
			$data=array("output_type"=>1, "product_quantity"=>$NewProductQuantity, "package_quantity"=>$NewPackageCount, "package_price"=>number_format((float)$TotalPackagePrice,2,'.',''), "total_temp_cart_price"=>number_format((float)$TotalPrice,2,'.',''), "total_temp_cart_discount"=>number_format((float)$TotalDiscount,2,'.',''),"full_cart"=>$total_cart,"total_count"=>$TotalPackageQuantity); // This is your data array/result
			echo json_encode($data);
			
		}
		
	}
		
} else {
	$GetProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id'");
	if(mysqli_num_rows($GetProductTempCart) > 0){
		$SetProductTempCart = mysqli_fetch_object($GetProductTempCart);
		$CurrentProductQuantity = $SetProductTempCart -> ProTC_product_quantity;
		
		$data=array("output_type"=>2, "product_quantity"=>$CurrentProductQuantity);
		echo json_encode($data);
	} else {
		$data=array("output_type"=>2, "product_quantity"=>0);
		echo json_encode($data);
	}
}

?>
