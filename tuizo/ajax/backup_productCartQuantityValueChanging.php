<?php
include '../config/config.php';
/*$ProTC_PTC_package_id = '';
$ProTC_product_category_id = '';
$ProTC_product_inventory_id = '';
$ProTC_color_id = '';
$ProTC_size_id = '';
$ProTC_session_id = session_id();
$ProTC_PTC_id = '';
$ProTC_product_id = '';
$Package_quantity = '';*/
$category_product_quantity = '';
$temp_product_category_quantity = '';
$temp_product_cart_category_quantity = '';
if (isset($_POST['ProTC_PTC_package_id'])) {
    extract($_POST);
    
    $AddToPackage = array();
    $AddToPackage['cross_limit'] = '';
    $AddToPackage['product_cart'] = '';
    $AddToPackage['product_added'] = '';
    $AddToPackage['product_left'] = '';
    $product_category_quantity = '';
    $query_of_product_category_quantity = "SELECT PC_catagory_quantity FROM package_categories WHERE PC_package_id=" . intval($ProTC_PTC_package_id) . " AND PC_catagory_id=" . intval($ProTC_product_category_id);

    $result_of_product_category_quantity = mysqli_query($con, $query_of_product_category_quantity);

    if ($result_of_product_category_quantity) {
        $rows = mysqli_fetch_object($result_of_product_category_quantity);
        $product_category_quantity = $rows->PC_catagory_quantity;
    }

    $query_of_count_product_of_temp_product_cart = "SELECT COUNT(ProTC_product_id) AS Product_category_quantity FROM product_temp_cart WHERE ProTC_PTC_package_id=" . intval($ProTC_PTC_package_id) . " AND ProTC_product_category_id=" . intval($ProTC_product_category_id) . " AND ProTC_session_id='" . $ProTC_session_id . "' AND ProTC_product_id=" . intval($ProTC_product_id);

    $result_of_count_product_of_temp_product_cart = mysqli_query($con, $query_of_count_product_of_temp_product_cart);

    if ($result_of_count_product_of_temp_product_cart) {
        $rows = mysqli_fetch_object($result_of_count_product_of_temp_product_cart);
        $temp_product_category_quantity = $rows->Product_category_quantity;
        $temp_product_cart_category_quantity = $rows->Product_category_quantity;
    }

    $query_of_PTC_id = "SELECT PTC_id FROM package_temp_cart WHERE PTC_package_id=" . intval($ProTC_PTC_package_id) . " AND PTC_session_id='" . $ProTC_session_id . "'";
    $result_of_PTC_id = mysqli_query($con, $query_of_PTC_id);

    if ($result_of_PTC_id) {
        $PTC_id_row = mysqli_fetch_object($result_of_PTC_id);
        $ProTC_PTC_id = $PTC_id_row->PTC_id;
    }
    //$temp_product_category_quantity;
    //$temp_product_category_quantity += $category_product_quantity;
   
    if ($category_product_quantity >= ($product_category_quantity * $Package_quantity)) {
        $AddToPackage['cross_limit'] = '0'; //'If you Add the another product,another same package will add to cart';
        $query_of_product_cart_edit = "SELECT 
                                        SUM(pc.PC_catagory_quantity) AS product_quantity,
                                        (
                                        SELECT 
                                          COUNT(protc.ProTC_product_category_id)
                                        FROM
                                         product_temp_cart protc
                                        WHERE
                                         protc.ProTC_PTC_id=" . intval($ProTC_PTC_id) . " AND
                                         protc.ProTC_PTC_package_id = " . intval($ProTC_PTC_package_id) . "
                                        ) AS Product_Added
                                                        FROM
                                                        package_categories pc

                                                        WHERE
                                                        pc.PC_package_id=" . intval($ProTC_PTC_package_id);
        $result_of_product_cart_edit = mysqli_query($con, $query_of_product_cart_edit);
        $productLeft = '';
        if ($result_of_product_cart_edit) {
            $addToCartProduct_row = mysqli_fetch_object($result_of_product_cart_edit);

            $productLeft = ($addToCartProduct_row->product_quantity * $Package_quantity) - $addToCartProduct_row->Product_Added;
            $AddToPackage['product_added'] = $addToCartProduct_row->Product_Added;
            $AddToPackage['product_left'] = $productLeft;
        }
    } else {
        $query_for_delete_product = "DELETE FROM product_temp_cart WHERE ProTC_PTC_package_id=$ProTC_PTC_package_id AND ProTC_session_id='".$ProTC_session_id."' AND ProTC_product_id=$ProTC_product_id";
       
        $result_of_delete_product = mysqli_query($con, $query_for_delete_product);
        if ($result_of_delete_product) {
            $insertField = "";
            $insertField .= " ProTC_PTC_package_id =" . intval($ProTC_PTC_package_id);
            $insertField .= " ,ProTC_product_category_id =" . intval($ProTC_product_category_id);
            $insertField .= " ,ProTC_product_inventory_id =" . intval($ProTC_product_inventory_id);
            $insertField .= " ,ProTC_color_id =" . intval($ProTC_color_id);
            $insertField .= " ,ProTC_size_id =" . intval($ProTC_size_id);
            $insertField .= " ,ProTC_session_id='" . $ProTC_session_id . "'";
            $insertField .= " ,ProTC_product_id =" . intval($ProTC_product_id);
            $insertField .= " ,ProTC_PTC_id =" . intval($ProTC_PTC_id);
            $i = 0;
            $quantity_rows = 0;

        
            $result_of_add_to_package ='';
            for ($i = 0; $i < $category_product_quantity; $i++) {
                $query_of_add_to_package = "INSERT INTO product_temp_cart SET $insertField";
                $result_of_add_to_package = mysqli_query($con, $query_of_add_to_package);
            }


            if ($result_of_add_to_package) {
                $AddToPackage['cross_limit'] = '1'; //Product is Added to Package;

                $query_of_product_cart_edit = "SELECT 
                                                                SUM(pc.PC_catagory_quantity) AS product_quantity,
                                                                (
                                                                SELECT 
                                                                  COUNT(protc.ProTC_product_category_id)
                                                                FROM
                                                                 product_temp_cart protc
                                                                WHERE
                                                                 protc.ProTC_PTC_id=" . intval($ProTC_PTC_id) . " AND
                                                                 protc.ProTC_PTC_package_id = " . intval($ProTC_PTC_package_id) . "
                                                                ) AS Product_Added
                                                        FROM
                                                        package_categories pc

                                                        WHERE
                                                        pc.PC_package_id=" . intval($ProTC_PTC_package_id);

                $result_of_product_cart_edit = mysqli_query($con, $query_of_product_cart_edit);
                $productLeft = '';
                if ($result_of_product_cart_edit) {
                    $addToCartProduct_row = mysqli_fetch_object($result_of_product_cart_edit);

                    $productLeft = ($addToCartProduct_row->product_quantity * $Package_quantity) - $addToCartProduct_row->Product_Added;
                    $AddToPackage['product_added'] = $addToCartProduct_row->Product_Added;
                    $AddToPackage['product_left'] = $productLeft;
                }
            }
        }
    }
    echo json_encode($AddToPackage);

}

?>

