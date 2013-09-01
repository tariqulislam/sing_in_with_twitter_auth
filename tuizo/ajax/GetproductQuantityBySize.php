<?php
include('../config/config.php');
$Size_id ='';
$Category_id='';
$Product_id='';
$Color_id='';
$session_id = session_id();
if(isset($_POST['Size_id']))
{
    extract($_POST);
               //initializing new product count
		$NewProductQuantity = 0;
		
		//get product_temp_cart information
		$ProductTempCart = mysqli_query($con,"SELECT * FROM product_temp_cart WHERE ProTC_color_id=$Color_id AND ProTC_size_id=$Size_id  AND ProTC_product_id=$Product_id AND ProTC_session_id='$session_id' AND ProTC_product_category_id=$Category_id");
		
		//getting information from product_temp_cart table
		if(mysqli_num_rows($ProductTempCart) > 0){
			$SetProductTempCart = mysqli_fetch_object($ProductTempCart);
			$ProductCurrentQuantity = $SetProductTempCart -> ProTC_product_quantity;
			$NewProductQuantity = $ProductCurrentQuantity;
		} else {
			
			$NewProductQuantity = 0;
		}
                $data =array("output"=>1,"product_quantity"=>$NewProductQuantity);
                echo json_encode($data);
}
?>
