<?php

include '../config/config.php';
$Package_ID = '';
$cart_id = session_id();

extract($_POST);
if (isset($_POST['Package_ID'])) {
    
	/** Start : Check same package  in same session * */
    $SqlCheckPackageInTempCart = "SELECT * FROM package_temp_cart WHERE PTC_package_id='" . $Package_ID . "' AND PTC_session_id='" . $cart_id . "'";
    $GetCheckPackageInTempCart = mysqli_query($con, $SqlCheckPackageInTempCart);
	$CountCheckPackageInTempCart = mysqli_num_rows($GetCheckPackageInTempCart);
	
    if ($CountCheckPackageInTempCart > 0) {
       
	   $SetCheckPackageInTempCart = mysqli_fetch_object($GetCheckPackageInTempCart);
	   $ExistingPackageQuantity = $SetCheckPackageInTempCart -> PTC_package_quantity;
	   $ExistingPackageTempCartID = $SetCheckPackageInTempCart -> PTC_id;
	   $NewPackageTempCartQuantity = $ExistingPackageQuantity + 1;
	   
	   $UpdatePackageTempCart = '';
	   $UpdatePackageTempCart .=' PTC_package_quantity = "' . mysqli_real_escape_string($con, $NewPackageTempCartQuantity) . '"';
	   
	   if(checkUserLogin()){
			$UpdatePackageTempCart .=', PTC_user_id ="' . mysqli_real_escape_string($con, $_SESSION["user_id"]) . '"';
		}
	   
	   $SqlUpdatePackageTempCart = "UPDATE package_temp_cart SET $UpdatePackageTempCart WHERE PTC_id=$ExistingPackageTempCartID";
	   $ExecuteUpdatePackageTempCart = mysqli_query($con,$SqlUpdatePackageTempCart);
	   
	   if($ExecuteUpdatePackageTempCart){
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
			
		   
		   		 
			
		   $GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
		   $TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
		   
		   
		   $data=array("output_type"=>0, "current_package"=>$NewPackageTempCartQuantity, "package_price"=>$SetCheckPackageInTempCart->PTC_package_price*$NewPackageTempCartQuantity, "total_cart"=>$total_cart, "total_package"=>$TotalPackageQuantity); // This is your data array/result
		   echo json_encode($data);
		   
	   } else {
		   
		   $GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
		   $TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
		   
		   $data=array("output_type"=>1, "current_package"=>$NewPackageTempCartQuantity, "total_package"=>$TotalPackageQuantity); // This is your data array/result
		   echo json_encode($data);
	   }
    } else {


        /** Start: query for package information * */
        $SqlSelectPackageInfo = "SELECT * FROM packages WHERE package_id ='" . $Package_ID . "'";
        $ExecuteSelectPackageInfo = mysqli_query($con, $SqlSelectPackageInfo);
        if ($ExecuteSelectPackageInfo) {
            $GetSelectPackageInfo = mysqli_fetch_object($ExecuteSelectPackageInfo);
            $PTC_package_id = $GetSelectPackageInfo->package_id;
            $PTC_package_name = $GetSelectPackageInfo->package_name;
            $PTC_package_price = $GetSelectPackageInfo->package_price;
            $PTC_package_discount = $GetSelectPackageInfo->package_discount;
            $PTC_package_tax_class_id = $GetSelectPackageInfo->package_tax_class_id;
            $PTC_package_category_id = $GetSelectPackageInfo->package_catagory_id;
        }
        /** End: query for package information * */
        $InsertPackageIntoTempCart = '';
        $InsertPackageIntoTempCart .='  PTC_package_id = "' . mysqli_real_escape_string($con, $PTC_package_id) . '"';
        $InsertPackageIntoTempCart .=',  PTC_package_name ="' . mysqli_real_escape_string($con, $PTC_package_name) . '"';
        $InsertPackageIntoTempCart .=', PTC_package_price ="' . mysqli_real_escape_string($con, $PTC_package_price) . '"';
        $InsertPackageIntoTempCart .=', PTC_package_discount ="' . mysqli_real_escape_string($con, $PTC_package_discount) . '"';
        $InsertPackageIntoTempCart .=', PTC_package_tax_class_id ="' . mysqli_real_escape_string($con, $PTC_package_tax_class_id) . '"';
        $InsertPackageIntoTempCart .=', PTC_session_id ="' . mysqli_real_escape_string($con, $cart_id) . '"';
        $InsertPackageIntoTempCart .=', PTC_package_category_id ="' . mysqli_real_escape_string($con, $PTC_package_category_id) . '"';
        $InsertPackageIntoTempCart .=', PTC_package_quantity ="' . mysqli_real_escape_string($con, 1) . '"';
		
		if(checkUserLogin()){
			$InsertPackageIntoTempCart .=', PTC_user_id ="' . mysqli_real_escape_string($con, $_SESSION["user_id"]) . '"';
		}

        $SqlInsertPackageIntoTempCart = "INSERT INTO package_temp_cart SET $InsertPackageIntoTempCart";
        $ExecuteInsertPackageIntoTempCart = mysqli_query($con, $SqlInsertPackageIntoTempCart);
        if ($ExecuteInsertPackageIntoTempCart) {
			
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
				
			
			
					   
		   
		
			
			
			
			$GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
		    $TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
			
            $data=array("output_type"=>2, "total_package"=>$TotalPackageQuantity, "total_cart"=>$total_cart); // This is your data array/result
		    echo json_encode($data);
        } else {
			
			$GetTotalPackage = mysqli_query($con,"SELECT * FROM package_temp_cart WHERE PTC_session_id='$cart_id' GROUP BY PTC_package_id");
		    $TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
			
            $data=array("output_type"=>3, "total_package"=>$TotalPackageQuantity); // This is your data array/result
		    echo json_encode($data);
        }
        /** END: insert the package cart * */
        /** echo json array * */
    }
    
}
?>
