<?php

include '../config/config.php';
$session_id = session_id();
$package_id = '';
if (isset($_POST['package_id'])) {
    extract($_POST);
    /** start: delete temp package from cart * */
    $delete_package_temp_cart_sql = "DELETE FROM package_temp_cart WHERE PTC_session_id='$session_id' AND PTC_package_id=" . intval($package_id);
    $result_delete_package_temp_cart = mysqli_query($con, $delete_package_temp_cart_sql);
    if ($result_delete_package_temp_cart) {
        // Delete the package temp cart data
        /** start: delete product  from temp cart * */
        $delete_product_temp_cart_sql = "DELETE FROM product_temp_cart WHERE ProTC_session_id='$session_id' AND ProTC_PTC_package_id=" . intval($package_id);
        $result_delete_product_temp_cart = mysqli_query($con, $delete_product_temp_cart_sql);
        if ($result_delete_product_temp_cart) {
            // delete the product temp cart
        } else {
            echo "result_delete_product_temp_cartError" . mysqli_error($con);
        }
        /** end: delete product  from temp cart * */
    } else {
        echo "result_delete_package_temp_cartError:" . mysqli_error($con);
    }
    /** End: delete temp package from cart * */
    $check_count_temp_packages = "SELECT count(PTC_package_id) AS package_count FROM package_temp_cart WHERE PTC_session_id='$session_id'";
    $result_count_temp_packages = mysqli_query($con, $check_count_temp_packages);
    if ($result_count_temp_packages) {
        $rows = mysqli_fetch_object($result_count_temp_packages);
        $count_packages = intval($rows->package_count);
        if ($count_packages == 0) {
            $data = array("output_type" => 0, "redirect" => "index.php"); // This is your data array/result
            echo json_encode($data);
        } else {
            /** start:if the package is not empty for product * */
            $GetAllPackageInfo = mysqli_query($con, "SELECT * FROM package_temp_cart WHERE PTC_session_id='$session_id'");
            $TotalTempPackagePrice = 0;
            $TotalTempPackageDiscount = 0;
            while ($SetAllPackageInfo = mysqli_fetch_object($GetAllPackageInfo)) {
                $TotalTempPackagePrice += $SetAllPackageInfo->PTC_package_price * $SetAllPackageInfo->PTC_package_quantity;
                $TotalTempPackageDiscount += $SetAllPackageInfo->PTC_package_discount * $SetAllPackageInfo->PTC_package_quantity;
            }



            //start of whole cart generation=================================================================================================


            $query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "'";
            $result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);

            $tempPackageArray = array();
            /* package sql start */
            $tempPackageSql = "SELECT PTC_id,PTC_package_id,PTC_package_name,PTC_package_price,PTC_package_discount,PTC_package_quantity FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "'";
            $tempPackageSqlResult = mysqli_query($con, $tempPackageSql);
            if ($tempPackageSqlResult) {
                while ($tempPackageSqlResultRowObj = mysqli_fetch_object($tempPackageSqlResult)) {
                    /* Start: category sql */
                    $tempPackageCatSql = "SELECT `ProTC_product_category_id`, SUM(`ProTC_product_quantity`) AS total_product_added,(SELECT PC_catagory_quantity FROM package_categories WHERE package_categories.PC_package_id = product_temp_cart.ProTC_PTC_package_id AND package_categories.PC_catagory_id = product_temp_cart.ProTC_product_category_id) AS category_limit,  (SELECT category_name FROM categories WHERE  categories.category_id = product_temp_cart.ProTC_product_category_id) AS category_name  
			FROM product_temp_cart 
			WHERE ProTC_PTC_package_id = " . $tempPackageSqlResultRowObj->PTC_package_id . " AND  ProTC_session_id ='" . $session_id . "' GROUP BY ProTC_product_category_id";

                    $tempPackageCatSqlResult = mysqli_query($con, $tempPackageCatSql);

                    if ($tempPackageCatSqlResult) {
                        $tempPackageSqlResultRowObj->categories = array();
                        if (mysqli_num_rows($tempPackageCatSqlResult) > 0) {
                            while ($tempPackageCatSqlResultRowObj = mysqli_fetch_object($tempPackageCatSqlResult))
                                $tempPackageSqlResultRowObj->categories[] = $tempPackageCatSqlResultRowObj;
                        }

                        mysqli_free_result($tempPackageCatSqlResult);
                    } else {
                        if (DEBUG) {
                            echo 'tempPackageCatSqlResult Error : ' . mysqli_error($con);
                        } else {
                            echo 'tempPackageCatSqlResult Fail';
                        }
                    }
                    /* End: category sql */
                    $tempPackageArray[] = $tempPackageSqlResultRowObj;
                }
                mysqli_free_result($tempPackageSqlResult);
            } else {
                if (DEBUG) {
                    echo 'tempPackageSqlResult Error: ' . mysqli_error($con);
                } else {
                    echo 'tempPackageSqlResult Fail';
                }
            }

            //printDie($tempPackageArray);
            /* package sql end */
            $total_cart = '<table class="cartResult" width="100%" border="0">';
            $tempPackageArrayCounter = count($tempPackageArray);
            if ($tempPackageArrayCounter > 0):
                for ($i = 0; $i < $tempPackageArrayCounter; $i++):

                    $total_cart .= '<!--heading-->
                                <tr class="trhead">
                                     <td width="50%">Package</td><td>Quantity</td><td>Price</td>
                                </tr>
                                <!--package data-->
                              <tr> 
                                  <td>' . $tempPackageArray[$i]->PTC_package_name . '</td>
                                  <td class="QiuanityTd" id="packageQuantity_' . $tempPackageArray[$i]->PTC_package_id . '">' . $tempPackageArray[$i]->PTC_package_quantity . '</td>
                                  <td>' . ($tempPackageArray[$i]->PTC_package_price * $tempPackageArray[$i]->PTC_package_quantity) . '</td>
                              </tr>';
                    $categoryCounter = count($tempPackageArray[$i]->categories);
                    if ($categoryCounter > 0):
                        for ($j = 0; $j < $categoryCounter; $j++):
                            $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td>' . $tempPackageArray[$i]->categories[$j]->category_name . '</td>';
                            $left = ($tempPackageArray[$i]->categories[$j]->category_limit * $tempPackageArray[$i]->PTC_package_quantity) - $tempPackageArray[$i]->categories[$j]->total_product_added;
                            $total_cart .= '<td>Added <font id="addedQuantity_' . $tempPackageArray[$i]->PTC_package_id . '_' . $tempPackageArray[$i]->categories[$j]->ProTC_product_category_id . '">' . $tempPackageArray[$i]->categories[$j]->total_product_added . '</font> <br>left <font id="leftQuantity_' . $tempPackageArray[$i]->PTC_package_id . '_' . $tempPackageArray[$i]->categories[$j]->ProTC_product_category_id . '">' . $left . '</font></td>
                              </tr>';
                        endfor; /* ($j=0; $j < $categoryCounter; $j++) */

                    else: /* ($categoryCounter > 0): */
                        $total_cart .= '<tr> 
                                    <td>  &ShortRightArrow;</td>
                                    <td colspan="2" > <p style="color:red;" >Product not added</p> </td>
                              </tr>';
                    endif; /* ($categoryCounter > 0): */

                endfor; /* ($i=0; $i < $tempPackageArrayCounter ; $i++): */
            else: /* (count($tempPackageArray) > 0) */
                $total_cart .= '<tr> 
                                  
                                    <td> <p style="color:red;" >Package not added</p> </td>
                              </tr>';
            endif; /* (count($tempPackageArray) > 0) */


            $total_cart .= '</table>';


            //end of whole cart generation=================================================================================================

            /** start: count total package added * */
            $GetTotalPackage = mysqli_query($con, "SELECT * FROM package_temp_cart WHERE PTC_session_id='$session_id' GROUP BY PTC_package_id");
            $TotalPackageQuantity = mysqli_num_rows($GetTotalPackage);
            /** end: count total package added * */
            $data = array("output_type" => 1, "total_temp_cart_price" => number_format((float) $TotalTempPackagePrice, 2, '.', ''), "total_temp_cart_discount" => number_format((float) $TotalTempPackageDiscount, 2, '.', ''), "full_cart" => $total_cart,"total_package"=>$TotalPackageQuantity); // This is your data array/result
            echo json_encode($data);
            /** END: if the package is not empty for product * */
        }
    }
}
?>
