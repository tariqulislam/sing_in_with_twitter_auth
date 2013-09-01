<?php
include '../config/config.php';
$session_id = session_id();
$ProTC_product_id='';
$ProTC_PTC_package_id ='';
if(isset($_POST['ProTC_product_id']))
{
    extract($_POST);
    $arr = array();
   
    
    $query_of_product_quantity ="SELECT
    COUNT(protc.ProTC_product_id) AS product_count
FROM
    product_temp_cart protc
    
WHERE
    protc.ProTC_PTC_package_id =".intval($ProTC_PTC_package_id)." AND
    protc.ProTC_session_id = '".$session_id."' AND
    protc.ProTC_product_id =".intval($ProTC_product_id);
    $result_of_product_quantity = mysqli_query($con, $query_of_product_quantity);
    $product_count =0;
    if($result_of_product_quantity)
    {
        $row = mysqli_fetch_object($result_of_product_quantity);
        $product_count=  intval($row->product_count);
    }
    
    $query_of_subtotal ="SELECT
   (ptc.PTC_package_quantity*ptc.PTC_package_price) AS package_price 
FROM

    package_temp_cart ptc
WHERE

   ptc.PTC_session_id = '".$session_id."'
GROUP BY ptc.PTC_package_id";
    $subtotal = 0;
    $result_of_subtotal = mysqli_query($con, $query_of_subtotal);
    if($result_of_subtotal)
    {
        while($row=  mysqli_fetch_object($result_of_subtotal))
        {
            $subtotal += $row->package_price;
        }
    }
      $carts[]=array( 
        "product_count"=> $product_count,
        "subTotal" =>$subtotal);
      $arr["carts"]=$carts;
     
    
    
    echo json_encode($arr);
}
?>
