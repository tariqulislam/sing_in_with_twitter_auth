<?php

include '../config/config.php';
$PI_product_id = '';
$PI_size_id = '';
if (isset($_POST['PI_size_id'])) {
    extract($_POST);
    $p_data = array();
    /* Start: color query */
    $query_for_product_color = "SELECT
                             c.color_id,c.color_code
                            FROM
                             product_inventories piv,
                            colors c
                            WHERE
                            piv.PI_color_id= c.color_id AND piv.PI_product_id='" . intval($PI_product_id);
    $result_for_product_color = mysqli_query($con, $query_for_product_color);
    if ($result_for_product_color) {
        $p_data['product_colors'] = array();
        $product_color = array();
        while ($rows = mysqli_fetch_object($result_for_product_color)) {
            $product_color[] = array(
                $rows->color_id => $rows->color_code,
            );
        }
        $p_data['product_colors'] = $product_color;

        
    } else {
        if (DEBUG) {
            echo 'result_for_product_color Error: ' . mysqli_error($con);
        } else {
            echo 'result_for_product_color error';
        }
    }
    
    /* End: color query */
}
?>
