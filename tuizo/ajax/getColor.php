<?php
include '../config/config.php';
    
$PI_product_id = '';
$PI_color_id = '';
$product_image = '';
if (isset($_POST['PI_product_id'])) {
    $p_data = array();
    extract($_POST);
    /*Start: image query */
    $query_for_product_image = "SELECT PI_file_name FROM product_images WHERE PI_product_id='" . $PI_product_id . "' AND PI_color='" . $PI_color_id . "'";
    $result_for_product_image = mysqli_query($con, $query_for_product_image);
    if ($result_for_product_image) {
        $product_image = mysqli_fetch_object($result_for_product_image);
        $p_data['product_image'] = $product_image->PI_file_name;
    } else {
        if (DEBUG) {
            echo 'result_for_product_image Error: ' . mysqli_error($con);
        } else {
            echo 'result_for_product_image error';
        }
    }
    /*End: image query */
    /*Start: size query */
    $query_for_product_size = "SELECT
                                s.size_id,s.size_title,piv.PI_quantity
                            FROM
                                 product_inventories piv,
                                 sizes s
                            WHERE
                                piv.PI_size_id= s.size_id AND piv.PI_product_id='".$PI_product_id."' AND piv.PI_color_id='".$PI_color_id."'";
    $result_for_product_size=  mysqli_query($con, $query_for_product_size);
    if ($result_for_product_size) {
        $p_data['product_sizes']= array();
        $product_size= array();
       while ($rows = mysqli_fetch_object($result_for_product_size))
       {
           $product_size[] = array(
               "size_id"=>$rows->size_id,
               "size_title"=>$rows->size_title,
               "PI_quantity"=>$rows->PI_quantity
           );
                   
       }
       $p_data['product_sizes']=$product_size;

       //array_push(, $product_size);
       //printDie($p_data['paroduct_sizes']);
        //$p_data['product_image'] = $product_image->PI_file_name;
    } else {
        if (DEBUG) {
            echo 'result_for_product_image Error: ' . mysqli_error($con);
        } else {
            echo 'result_for_product_image error';
        }
    }
    
    echo json_encode($p_data);
    /*End: size query */
}
?>
