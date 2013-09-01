<?php
include '../config/config.php';
$Product_ID = '';
$Category_Limit = '';
$Size_ID = '';
$Color_ID = '';
$Inventory_ID = '';
$cart_id = session_id();
extract($_POST);

$CheckPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id=$Package_ID AND PTC_session_id='$cart_id'");
$CountPackage = mysqli_num_rows($CheckPackageTempCart);

if($CountPackage > 0){
	
	if($Product_ID > 0){
		
		//initializing new product count
		$NewProductQuantity = 0;
		
		//get product_temp_cart information
		$GetProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_ID");
		
		//getting information from product_temp_cart table
		if(mysqli_num_rows($GetProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($GetProductTempCart);
			$ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
			$NewProductQuantity = $ProductCurrentQuantity+1;
		} else {
			
			$NewProductQuantity = 1;
		}
		
		
		//getting total product added under this category
		$GetCategoryCount = mysqli_query($con,"SELECT SUM(ProTC_product_quantity) as total_quantity_added FROM product_temp_cart WHERE ProTC_product_category_id=$Category_ID AND ProTC_session_id='$cart_id' AND ProTC_PTC_package_id=$Package_ID");
		$SetCategoryCount = mysqli_fetch_object($GetCategoryCount);
		
		
		$CheckFullPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id=$Package_ID AND PTC_session_id='$cart_id'");
		$SetFullPackageTempCart = mysqli_fetch_object($CheckFullPackageTempCart);
		$OldPackageCount = $SetFullPackageTempCart->PTC_package_quantity;
		
		$NewPackageCount=0;
		if((($SetCategoryCount->total_quantity_added)+1) > ($Category_Limit*$OldPackageCount)){
			$NewPackageCount = $OldPackageCount+1;
			$GetPackageCount = GetPackageQuantity($Package_ID);
			
			if($NewPackageCount > $GetPackageCount){
				$data=array("output_type"=>1, "added_quantity"=>$SetCategoryCount->total_quantity_added, "package_quantity"=>$NewPackageCount, "left_quantity"=>(($Category_Limit*$NewPackageCount)-$SetCategoryCount->total_quantity_added), "product_quantity"=>$NewProductQuantity,"package_price"=>($SetFullPackageTempCart->PTC_package_price)*$NewPackageCount); // This is your data array/result
			echo json_encode($data);
			} else {
				
				
				//updating product count in db table
				if(mysqli_num_rows($GetProductTempCart) > 0){
					$UpdateProductTempCart = mysqli_query($con,"UPDATE product_temp_cart SET ProTC_product_quantity='$NewProductQuantity' WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_ID");
				} else {
					//get product details from join query
					$GetProduct = mysqli_query($con,"SELECT * FROM products,package_category_products,product_inventories WHERE products.product_id=$Product_ID AND package_category_products.PCP_product_id=$Product_ID AND package_category_products.PCP_package_id=$Package_ID AND product_inventories.PI_product_id=$Product_ID");
					$SetProduct = mysqli_fetch_object($GetProduct);
					
					$CheckPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id=$Package_ID AND PTC_session_id='$cart_id'");
					$SetPackageTempCart = mysqli_fetch_object($CheckPackageTempCart);
					
					//getting products details information from db
					$InsertProductTempCart = '';
					$InsertProductTempCart .= ' ProTC_PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
					$InsertProductTempCart .= ', ProTC_product_category_id = "' . mysqli_real_escape_string($con,$Category_ID) . '"';
					$InsertProductTempCart .= ', ProTC_product_inventory_id = "' . mysqli_real_escape_string($con, $Inventory_ID) . '"';
					$InsertProductTempCart .= ', ProTC_color_id = "' . mysqli_real_escape_string($con, $Color_ID) . '"';
					$InsertProductTempCart .= ', ProTC_size_id = "' . mysqli_real_escape_string($con, $Size_ID) . '"';
					$InsertProductTempCart .= ', ProTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
					$InsertProductTempCart .= ', ProTC_product_id = "' . mysqli_real_escape_string($con, $Product_ID) . '"';
					$InsertProductTempCart .= ', ProTC_product_quantity = "' . mysqli_real_escape_string($con, 1) . '"';
					$InsertProductTempCart .= ', ProTC_PTC_id = "' . mysqli_real_escape_string($con, $SetPackageTempCart->PTC_id) . '"';
					
					$InsertProduct = mysqli_query($con,"INSERT INTO product_temp_cart SET $InsertProductTempCart");
				}
				
				
				
//start of whole cart generation=================================================================================================
			
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
			
			
			//end of whole cart generation=================================================================================================
			
				
				
				//getting total product added under this category
		$GetCategoryCount = mysqli_query($con,"SELECT SUM(ProTC_product_quantity) as total_quantity_added FROM product_temp_cart WHERE ProTC_product_category_id=$Category_ID AND ProTC_session_id='$cart_id' AND ProTC_PTC_package_id=$Package_ID");
		$SetCategoryCount = mysqli_fetch_object($GetCategoryCount);
				
				
					
				
				
				
				$data=array("output_type"=>2, "added_quantity"=>$SetCategoryCount->total_quantity_added, "left_quantity"=>(($Category_Limit*$NewPackageCount)-$SetCategoryCount->total_quantity_added), "product_quantity"=>$NewProductQuantity, "full_cart"=>$total_cart);// This is your data array/result
				echo json_encode($data);
				
			}
		} else {
			$NewPackageCount = $OldPackageCount;
			
			//updating product count in db table
			if(mysqli_num_rows($GetProductTempCart) > 0){
				$UpdateProductTempCart = mysqli_query($con,"UPDATE product_temp_cart SET ProTC_product_quantity='$NewProductQuantity' WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_ID");
			} else {
				//get product details from join query
				$GetProduct = mysqli_query($con,"SELECT * FROM products,package_category_products,product_inventories WHERE products.product_id=$Product_ID AND package_category_products.PCP_product_id=$Product_ID AND package_category_products.PCP_package_id=$Package_ID AND product_inventories.PI_product_id=$Product_ID");
				$SetProduct = mysqli_fetch_object($GetProduct);
				
				$CheckPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id=$Package_ID AND PTC_session_id='$cart_id'");
				$SetPackageTempCart = mysqli_fetch_object($CheckPackageTempCart);
				
				//getting products details information from db
				$InsertProductTempCart = '';
				$InsertProductTempCart .= ' ProTC_PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
				$InsertProductTempCart .= ', ProTC_product_category_id = "' . mysqli_real_escape_string($con,$Category_ID) . '"';
				$InsertProductTempCart .= ', ProTC_product_inventory_id = "' . mysqli_real_escape_string($con, $Inventory_ID) . '"';
				$InsertProductTempCart .= ', ProTC_color_id = "' . mysqli_real_escape_string($con, $Color_ID) . '"';
				$InsertProductTempCart .= ', ProTC_size_id = "' . mysqli_real_escape_string($con, $Size_ID) . '"';
				$InsertProductTempCart .= ', ProTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
				$InsertProductTempCart .= ', ProTC_product_id = "' . mysqli_real_escape_string($con, $Product_ID) . '"';
				$InsertProductTempCart .= ', ProTC_product_quantity = "' . mysqli_real_escape_string($con, 1) . '"';
				$InsertProductTempCart .= ', ProTC_PTC_id = "' . mysqli_real_escape_string($con, $SetPackageTempCart->PTC_id) . '"';
				
				$InsertProduct = mysqli_query($con,"INSERT INTO product_temp_cart SET $InsertProductTempCart");
			}
			
			
			
			//start of whole cart generation=================================================================================================
			
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
			
			
			//end of whole cart generation=================================================================================================
			
			
			
			//getting total product added under this category
		$GetCategoryCount = mysqli_query($con,"SELECT SUM(ProTC_product_quantity) as total_quantity_added FROM product_temp_cart WHERE ProTC_product_category_id=$Category_ID AND ProTC_session_id='$cart_id' AND ProTC_PTC_package_id=$Package_ID");
		$SetCategoryCount = mysqli_fetch_object($GetCategoryCount);
			
			
			
			$data=array("output_type"=>2, "added_quantity"=>$SetCategoryCount->total_quantity_added, "left_quantity"=>(($Category_Limit*$NewPackageCount)-$SetCategoryCount->total_quantity_added), "product_quantity"=>$NewProductQuantity, "full_cart"=>$total_cart);// This is your data array/result
			echo json_encode($data);
			
		}
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
	
	
	$InsertPackage= mysqli_query($con,"INSERT INTO package_temp_cart SET $InsertPackageTempCart");
	$PackageTempCartID = mysqli_insert_id($con);
	
	
	if($Product_ID > 0){
		
		//getting information from product_temp_cart table
		$GetProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id' AND ProTC_product_category_id=$Category_ID");
		if(mysqli_num_rows($GetProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($GetProductTempCart);
			$ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
			$NewProductQuantity = $ProductCurrentQuantity+1;
		} else {
			$NewProductQuantity = 1;
		}
		
		
		//getting package_temp_cart information
		$CheckFullPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_id=$PackageTempCartID AND PTC_session_id='$cart_id'");
		$SetFullPackageTempCart = mysqli_fetch_object($CheckFullPackageTempCart);
		$OldPackageCount = $SetFullPackageTempCart->PTC_package_quantity;
		
		
		//getting total product added under this category
		$GetCategoryCount = mysqli_query($con,"SELECT SUM(ProTC_product_quantity) as total_quantity_added FROM product_temp_cart WHERE ProTC_product_category_id=$Category_ID AND ProTC_session_id='$cart_id' AND ProTC_PTC_package_id=$Package_ID");
		$SetCategoryCount = mysqli_fetch_object($GetCategoryCount);
		
		
		
		$NewPackageCount=0;
		if((($SetCategoryCount->total_quantity_added)+1) > ($Category_Limit*$OldPackageCount)){
			
			$NewPackageCount = $OldPackageCount+1;
			
			$GetPackageCount = GetPackageQuantity($Package_ID);
			
			
			
			
			//start of whole cart generation=================================================================================================
			
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
			
			
			//end of whole cart generation=================================================================================================
			
			
			
			if($NewPackageCount > $GetPackageCount){
				//getting total different package count
				$GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
				$TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
				
				$data=array("output_type"=>3, "added_quantity"=>$SetCategoryCount->total_quantity_added, "left_quantity"=>(($Category_Limit*$NewPackageCount)-$SetCategoryCount->total_quantity_added), "package_quantity"=>$NewPackageCount, "total_package"=>$TotalPackageQuantity, "full_cart"=>$total_cart, "product_quantity"=>$NewProductQuantity,"package_price"=>($SetFullPackageTempCart->PTC_package_price)*$NewPackageCount); // This is your data array/result
				echo json_encode($data);
			} else {
				//updating product count in db table
				if(mysqli_num_rows($GetProductTempCart) > 0){
					$UpdateProductTempCart = mysqli_query($con,"UPDATE product_temp_cart SET ProTC_product_quantity='$NewProductQuantity' WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id'");
				} else {
					//get product details from join query
					$GetProduct = mysqli_query($con,"SELECT * FROM products,package_category_products,product_inventories WHERE products.product_id=$Product_ID AND package_category_products.PCP_product_id=$Product_ID AND package_category_products.PCP_package_id=$Package_ID AND product_inventories.PI_product_id=$Product_ID");
					$SetProduct = mysqli_fetch_object($GetProduct);
					
					$CheckPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id=$Package_ID AND PTC_session_id='$cart_id'");
					$SetPackageTempCart = mysqli_fetch_object($CheckPackageTempCart);
					
					//getting products details information from db
					$InsertProductTempCart = '';
					$InsertProductTempCart .= ' ProTC_PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
					$InsertProductTempCart .= ', ProTC_product_category_id = "' . mysqli_real_escape_string($con,$Category_ID) . '"';
					$InsertProductTempCart .= ', ProTC_product_inventory_id = "' . mysqli_real_escape_string($con, $SetProduct -> PI_id) . '"';
					$InsertProductTempCart .= ', ProTC_color_id = "' . mysqli_real_escape_string($con, $SetProduct -> PI_color_id) . '"';
					$InsertProductTempCart .= ', ProTC_size_id = "' . mysqli_real_escape_string($con, $SetProduct -> PI_size_id) . '"';
					$InsertProductTempCart .= ', ProTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
					$InsertProductTempCart .= ', ProTC_product_id = "' . mysqli_real_escape_string($con, $Product_ID) . '"';
					$InsertProductTempCart .= ', ProTC_product_quantity = "' . mysqli_real_escape_string($con, 1) . '"';
					$InsertProductTempCart .= ', ProTC_PTC_id = "' . mysqli_real_escape_string($con, $SetPackageTempCart->PTC_id) . '"';
					
					$InsertProduct = mysqli_query($con,"INSERT INTO product_temp_cart SET $InsertProductTempCart");
				}
				
				
				//getting total different package count
				$GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
				$TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
				
				
				$data=array("output_type"=>4,"added_quantity"=>$SetCategoryCount->total_quantity_added, "package_quantity"=>$NewPackageCount, "left_quantity"=>(($Category_Limit*$NewPackageCount)-$SetCategoryCount->total_quantity_added), "total_package"=>$TotalPackageQuantity, "full_cart"=>$total_cart, "product_quantity"=>$NewProductQuantity);// This is your data array/result
				echo json_encode($data);
				
				
			}
			
			
			
			
		} else {
			
			
			
			
			$NewPackageCount = $OldPackageCount;
			
			
			//updating product count in db table
			if(mysqli_num_rows($GetProductTempCart) > 0){
				$UpdateProductTempCart = mysqli_query($con,"UPDATE product_temp_cart SET ProTC_product_quantity='$NewProductQuantity' WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id'");
			} else {
				//get product details from join query
				$GetProduct = mysqli_query($con,"SELECT * FROM products,package_category_products,product_inventories WHERE products.product_id=$Product_ID AND package_category_products.PCP_product_id=$Product_ID AND package_category_products.PCP_package_id=$Package_ID AND product_inventories.PI_product_id=$Product_ID");
				$SetProduct = mysqli_fetch_object($GetProduct);
				
				$CheckPackageTempCart = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_package_id=$Package_ID AND PTC_session_id='$cart_id'");
				$SetPackageTempCart = mysqli_fetch_object($CheckPackageTempCart);
				
				//getting products details information from db
				$InsertProductTempCart = '';
				$InsertProductTempCart .= ' ProTC_PTC_package_id = "' . mysqli_real_escape_string($con, $Package_ID) . '"';
				$InsertProductTempCart .= ', ProTC_product_category_id = "' . mysqli_real_escape_string($con,$Category_ID) . '"';
				$InsertProductTempCart .= ', ProTC_product_inventory_id = "' . mysqli_real_escape_string($con, $SetProduct -> PI_id) . '"';
				$InsertProductTempCart .= ', ProTC_color_id = "' . mysqli_real_escape_string($con, $SetProduct -> PI_color_id) . '"';
				$InsertProductTempCart .= ', ProTC_size_id = "' . mysqli_real_escape_string($con, $SetProduct -> PI_size_id) . '"';
				$InsertProductTempCart .= ', ProTC_session_id = "' . mysqli_real_escape_string($con, $cart_id) . '"';
				$InsertProductTempCart .= ', ProTC_product_id = "' . mysqli_real_escape_string($con, $Product_ID) . '"';
				$InsertProductTempCart .= ', ProTC_product_quantity = "' . mysqli_real_escape_string($con, 1) . '"';
				$InsertProductTempCart .= ', ProTC_PTC_id = "' . mysqli_real_escape_string($con, $SetPackageTempCart->PTC_id) . '"';
				
				$InsertProduct = mysqli_query($con,"INSERT INTO product_temp_cart SET $InsertProductTempCart");
			}
			
			
			
			//getting total different package count
			$GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
			$TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
				
			//start of whole cart generation=================================================================================================
			
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
			
			
			//end of whole cart generation=================================================================================================
				
		
			
			//getting total product added under this category
		$GetCategoryCount = mysqli_query($con,"SELECT SUM(ProTC_product_quantity) as total_quantity_added FROM product_temp_cart WHERE ProTC_product_category_id=$Category_ID AND ProTC_session_id='$cart_id' AND ProTC_PTC_package_id=$Package_ID");
		$SetCategoryCount = mysqli_fetch_object($GetCategoryCount);
			
			
			
			$data=array("output_type"=>4,"added_quantity"=>$SetCategoryCount->total_quantity_added, "package_quantity"=>$NewPackageCount, "left_quantity"=>(($Category_Limit*$NewPackageCount)-$SetCategoryCount->total_quantity_added), "total_package"=>$TotalPackageQuantity, "full_cart"=>$total_cart, "product_quantity"=>$NewProductQuantity);// This is your data array/result
			echo json_encode($data);
			
		}
	}
}

?>
