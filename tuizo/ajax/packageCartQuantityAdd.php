<?php
include '../config/config.php';
//exit();
$PTC_package_id='';
$PTC_Package_quantity='';
$PTC_session_id=  session_id();

if($_POST['PTC_package_id'])
{
       extract($_POST);
       $packages= array();
    
       $packages['package_quantity']='';
       $updatePackageField ='';
       if($PTC_Package_quantity == '')
       {
          $updatePackageField .= " PTC_package_quantity =PTC_package_quantity+1";
       }
       else
       {
           $updatePackageField .= " PTC_package_quantity =PTC_package_quantity+".intval($PTC_Package_quantity);
       }
       
       $query_of_add_package_cart_quantity ="UPDATE package_temp_cart SET $updatePackageField WHERE PTC_package_id='".$PTC_package_id."' 
                                             AND PTC_session_id='".$PTC_session_id."'";
       $result_of_add_Package_quantity =  mysqli_query($con, $query_of_add_package_cart_quantity);
       if($result_of_add_Package_quantity)
       {
           $query_of_package_quantity ="SELECT PTC_package_quantity FROM package_temp_cart WHERE PTC_package_id='".$PTC_package_id."' 
                                             AND PTC_session_id='".$PTC_session_id."'";
           $result_of_package_cart_quantity =  mysqli_query($con, $query_of_package_quantity);
           if($result_of_package_cart_quantity)
           {
               $quantity_row = mysqli_fetch_object($result_of_package_cart_quantity);
               $packages['package_quantity']=$quantity_row->PTC_package_quantity;
           }
       }
    echo json_encode($packages);
}
?>
