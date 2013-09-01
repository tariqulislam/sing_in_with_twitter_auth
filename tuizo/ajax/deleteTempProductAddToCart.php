<?php

include '../config/config.php';
$session_id = session_id();
$product_id = '';
$package_id = '';

if (isset($_POST['product_id'])) {
    extract($_POST);
    $cartJsonArray = array();

    $cartJsonArray['product_carts'] = '';
    $updated_package = array();
    $product_carts = array();
    $package_carts= array();
    $cartJsonArray['upper_package_cart'] = '';
    $cartJsonArray['upper_package_quantity'] = '';
    $cartJsonArray['package_carts'] = '';

    $query_of_delete_temp_product = "DELETE FROM product_temp_cart WHERE ProTC_session_id='" . $session_id . "' AND ProTC_PTC_package_id=" . intval($package_id) . " AND ProTC_product_id=" . intval($product_id);
    $result_of_delete_temp_product = mysqli_query($con, $query_of_delete_temp_product);
    if ($result_of_delete_temp_product) {
        /** start check the product * */
        $query_of_Check_product = "SELECT * FROM product_temp_cart WHERE ProTC_session_id='" . $session_id . "' AND ProTC_PTC_package_id=" . intval($package_id);
        $result_query_check_product = mysqli_query($con, $query_of_Check_product);
        if ($result_query_check_product) {
            $count_rows_for_check_product = mysqli_num_rows($result_query_check_product);
            if ($count_rows_for_check_product == 0) {
               $delete_package_query = "DELETE FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "' AND PTC_package_id=" . intval($package_id);
              
                $result_delete_package_query = mysqli_query($con, $delete_package_query);
                if ($result_delete_package_query) {
                    
                    /** start: query for product by session id * */
                    $query_of_product_by_session = "SELECT
                            protc.ProTC_PTC_package_id,p.package_name,protc.ProTC_product_id,pro.product_title,COUNT(protc.ProTC_product_id) AS product_quantity,c.color_image_name,s.size_title,
                            (SELECT
                                             pp.PI_file_name
                                          FROM
                                            product_images pp
                                          WHERE
                                           pp.PI_product_id= protc.ProTC_product_id
                                           ORDER BY pp.PI_priority DESC
                                           LIMIT 1
                                          ) AS product_image

                        FROM
                            product_temp_cart protc,
                            packages p,
                            products pro,
                            colors c,
                            sizes s


                        WHERE
                           protc.ProTC_PTC_package_id =p.package_id AND
                           protc.ProTC_product_id = pro.product_id AND
                           protc.ProTC_color_id = c.color_id AND
                           protc.ProTC_size_id = s.size_id

                           AND protc.ProTC_session_id = '" . $session_id . "'
                        GROUP BY protc.ProTC_PTC_package_id,protc.ProTC_product_id";
                    $result_of_product_cart_by_session = mysqli_query($con, $query_of_product_by_session);
                    while ($rows1 = mysqli_fetch_object($result_of_product_cart_by_session)) {
                        $product_carts[] = $rows1;
                    }
                    $cartJsonArray['product_carts'] = $product_carts;
                    /** end: query for product by session id * */
                    /** Start: Query for add the package to updated package array * */
                    $query_of_package_cart_array = "SELECT ptc.PTC_id,ptc.PTC_package_id,ptc.PTC_package_name,ptc.PTC_package_quantity,
                                            (SELECT SUM(pc.PC_catagory_quantity)
                                             FROM
                                                package_categories pc

                                             WHERE
                                                pc.PC_package_id=ptc.PTC_package_id
                                             ) AS Product_Quantity,
                                             (
                                             SELECT 
                                                  COUNT(protc.ProTC_product_category_id)
                                              FROM
                                                 product_temp_cart protc
                                              WHERE
                                                 protc.ProTC_PTC_id= ptc.PTC_id AND
                                                 protc.ProTC_PTC_package_id = ptc.PTC_package_id
                                             ) AS Product_Added
                                              FROM package_temp_cart ptc 
                                              WHERE ptc.PTC_session_id='" . $session_id . "'";
                    $result_of_package_cart_array = mysqli_query($con, $query_of_package_cart_array);
                    if ($result_of_package_cart_array) {
                        if (mysqli_num_rows($result_of_package_cart_array)) {
                            while ($updated_package_rows = mysqli_fetch_object($result_of_package_cart_array)) {
                                $updated_package[] = array(
                                    "package_cart_id" => $updated_package_rows->PTC_id,
                                    "package_id" => $updated_package_rows->PTC_package_id,
                                    "package_name" => $updated_package_rows->PTC_package_name,
                                    "package_quantity" => $updated_package_rows->PTC_package_quantity,
                                    "product_quantity" => $updated_package_rows->Product_Quantity,
                                    "product_added" => $updated_package_rows->Product_Added
                                );
                            }
                            mysqli_free_result($result_of_package_cart_array);
                        }
                    }
                    $cartJsonArray['upper_package_cart'] = $updated_package;
                    /** End: Query for add the package to updated package array * */
                    $query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "'";
                    $result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
                    if ($result_of_package_cart_count) {
                        $count_rows = mysqli_fetch_object($result_of_package_cart_count);
                        $package_count = $count_rows->Package_Quantity;
                        mysqli_free_result($result_of_package_cart_count);
                    } else {
                        if (DEBUG) {
                            echo 'result_of_package_cart_count Error: ' . mysqli_error($con);
                        } else {
                            echo 'result_of_package_cart_count error';
                        }
                    }
                    /** start: query for package by session id * */
                    $query_of_packages_by_session = "SELECT p.package_id,
                        p.package_name,ptc.PTC_package_quantity,p.package_price,ptc.PTC_package_id
                    FROM
                        packages p,
                        package_temp_cart ptc
                    WHERE
                       p.package_id = ptc.PTC_package_id
                       AND ptc.PTC_session_id = '" . $session_id . "'";
                    $result_package_by_session = mysqli_query($con, $query_of_packages_by_session);
                     if($result_package_by_session)
                     {
                         while($row=  mysqli_fetch_object($result_package_by_session))
                         {
                             $package_carts[]=$row;
                         }
                     }
                    /** end: query for package by session id * */
                    $cartJsonArray['package_carts']=$package_carts;
                    $cartJsonArray['upper_package_quantity'] = $package_count;
                    /** Start: Query for count package to  package array * */
                }
            } else {
                /** start: query for product by session id * */
                $query_of_product_by_session = "SELECT
                            protc.ProTC_PTC_package_id,p.package_name,protc.ProTC_product_id,pro.product_title,COUNT(protc.ProTC_product_id) AS product_quantity,c.color_image_name,s.size_title,
                            (SELECT
                                             pp.PI_file_name
                                          FROM
                                            product_images pp
                                          WHERE
                                           pp.PI_product_id= protc.ProTC_product_id
                                           ORDER BY pp.PI_priority DESC
                                           LIMIT 1
                                          ) AS product_image

                        FROM
                            product_temp_cart protc,
                            packages p,
                            products pro,
                            colors c,
                            sizes s


                        WHERE
                           protc.ProTC_PTC_package_id =p.package_id AND
                           protc.ProTC_product_id = pro.product_id AND
                           protc.ProTC_color_id = c.color_id AND
                           protc.ProTC_size_id = s.size_id

                           AND protc.ProTC_session_id = '" . $session_id . "'
                        GROUP BY protc.ProTC_PTC_package_id,protc.ProTC_product_id";
                $result_of_product_cart_by_session = mysqli_query($con, $query_of_product_by_session);
                while ($rows1 = mysqli_fetch_object($result_of_product_cart_by_session)) {
                    $product_carts[] = $rows1;
                }
                $cartJsonArray['product_carts'] = $product_carts;
                /** end: query for product by session id * */
                /** Start: Query for add the package to updated package array * */
                $query_of_package_cart_array = "SELECT ptc.PTC_id,ptc.PTC_package_id,ptc.PTC_package_name,ptc.PTC_package_quantity,
                                            (SELECT SUM(pc.PC_catagory_quantity)
                                             FROM
                                                package_categories pc

                                             WHERE
                                                pc.PC_package_id=ptc.PTC_package_id
                                             ) AS Product_Quantity,
                                             (
                                             SELECT 
                                                  COUNT(protc.ProTC_product_category_id)
                                              FROM
                                                 product_temp_cart protc
                                              WHERE
                                                 protc.ProTC_PTC_id= ptc.PTC_id AND
                                                 protc.ProTC_PTC_package_id = ptc.PTC_package_id
                                             ) AS Product_Added
                                              FROM package_temp_cart ptc 
                                              WHERE ptc.PTC_session_id='" . $session_id . "'";
                $result_of_package_cart_array = mysqli_query($con, $query_of_package_cart_array);
                if ($result_of_package_cart_array) {
                    if (mysqli_num_rows($result_of_package_cart_array)) {
                        while ($updated_package_rows = mysqli_fetch_object($result_of_package_cart_array)) {
                            $updated_package[] = array(
                                "package_cart_id" => $updated_package_rows->PTC_id,
                                "package_id" => $updated_package_rows->PTC_package_id,
                                "package_name" => $updated_package_rows->PTC_package_name,
                                "package_quantity" => $updated_package_rows->PTC_package_quantity,
                                "product_quantity" => $updated_package_rows->Product_Quantity,
                                "product_added" => $updated_package_rows->Product_Added
                            );
                        }
                        mysqli_free_result($result_of_package_cart_array);
                    }
                }
                $cartJsonArray['upper_package_cart'] = $updated_package;
                /** End: Query for add the package to updated package array * */
                $query_of_package_cart_count = "SELECT count(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='" . $session_id . "'";
                $result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
                if ($result_of_package_cart_count) {
                    $count_rows = mysqli_fetch_object($result_of_package_cart_count);
                    $package_count = $count_rows->Package_Quantity;
                    mysqli_free_result($result_of_package_cart_count);
                } else {
                    if (DEBUG) {
                        echo 'result_of_package_cart_count Error: ' . mysqli_error($con);
                    } else {
                        echo 'result_of_package_cart_count error';
                    }
                }
                
                 /** start: query for package by session id * */
                    $query_of_packages_by_session = "SELECT p.package_id,
                        p.package_name,ptc.PTC_package_quantity,p.package_price,ptc.PTC_package_id
                    FROM
                        packages p,
                        package_temp_cart ptc
                    WHERE
                       p.package_id = ptc.PTC_package_id
                       AND ptc.PTC_session_id = '" . $session_id . "'";
                    $result_package_by_session = mysqli_query($con, $query_of_packages_by_session);
                     if($result_package_by_session)
                     {
                         while($row=  mysqli_fetch_object($result_package_by_session))
                         {
                             $package_carts[]=$row;
                         }
                     }
                    /** end: query for package by session id * */
                    $cartJsonArray['package_carts']=$package_carts;
                $cartJsonArray['upper_package_quantity'] = $package_count;
                /** Start: Query for count package to  package array * */
            }
        }

        /** start check the product * */
    }
    echo json_encode($cartJsonArray);
}
?>
