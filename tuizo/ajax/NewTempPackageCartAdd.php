<?php
include '../config/config.php';
 $session_id= session_id();

$PTC_package_id='';
if(isset($_POST['PTC_package_id']))
{
      
        extract($_POST);
        $package_cart_array=array();
        $package_cart_array['updated_packages']=array();
        $package_cart_array['package_count'] =0;
        $package_count =0;
        $updated_package = array();
        /** Start: insert the package cart **/
        /** Start: query for package information **/
        $query_of_package_info = "SELECT * FROM packages WHERE package_id ='".$PTC_package_id."'";
        $result_of_package_info = mysqli_query($con, $query_of_package_info);
        if($result_of_package_info)
        {
            $package_row = mysqli_fetch_object($result_of_package_info);
            $PTC_package_id = $package_row->package_id;
            $PTC_package_name=$package_row->package_name;
            $PTC_package_price= $package_row->package_price;
            $PTC_package_discount =$package_row->package_discount;
            $PTC_package_tax_class_id=$package_row->package_tax_class_id;
            $PTC_package_category_id=$package_row->package_catagory_id;
            
        }
        /** End: query for package information **/
        $PTC_package_quantity =1;
        $PTC_session_id = $session_id;
        $insert_field_of_package_temp_cart = '';
        $insert_field_of_package_temp_cart .='  PTC_package_id = "' . mysqli_real_escape_string($con, $PTC_package_id) . '"';
        $insert_field_of_package_temp_cart.=',  PTC_package_name ="' . mysqli_real_escape_string($con, $PTC_package_name) . '"';
        $insert_field_of_package_temp_cart .=', PTC_package_price ="' . mysqli_real_escape_string($con, $PTC_package_price) . '"';
        $insert_field_of_package_temp_cart .=', PTC_package_discount ="'. mysqli_real_escape_string($con,$PTC_package_discount).'"';
        $insert_field_of_package_temp_cart .=', PTC_package_tax_class_id ="'. mysqli_real_escape_string($con,$PTC_package_tax_class_id).'"';
        $insert_field_of_package_temp_cart .=', PTC_session_id ="'. mysqli_real_escape_string($con,$PTC_session_id).'"';
        $insert_field_of_package_temp_cart .=', PTC_package_category_id ="'. mysqli_real_escape_string($con,$PTC_package_category_id).'"';
        $insert_field_of_package_temp_cart .=', PTC_package_quantity ="'. mysqli_real_escape_string($con,$PTC_package_quantity).'"';
        
       $query_of_package_temp_cart ="INSERT INTO package_temp_cart SET $insert_field_of_package_temp_cart";
       
        $result_of_package_temp_cart = mysqli_query($con, $query_of_package_temp_cart);
        if($result_of_package_temp_cart)
        {
           
            /** Start: Query for add the package to updated package array **/ 
            $query_of_package_cart_array="SELECT ptc.PTC_id,ptc.PTC_package_id,ptc.PTC_package_name,ptc.PTC_package_quantity,
      (SELECT SUM(pc.PC_catagory_quantity)
       FROM
          package_categories pc
       
       WHERE
          pc.PC_package_id=ptc.PTC_package_id
       ) AS Product_Quantity,
       (SELECT 
            SUM(protc.ProTC_product_quantity) AS ProTC_product_quantity
        FROM
           product_temp_cart protc
        WHERE
           protc.ProTC_PTC_id= ptc.PTC_id AND
           protc.ProTC_PTC_package_id = ptc.PTC_package_id
       ) AS Product_Added  
FROM package_temp_cart ptc
WHERE ptc.PTC_session_id='".$PTC_session_id."'";
             $result_of_package_cart_array= mysqli_query($con, $query_of_package_cart_array);
             if($result_of_package_cart_array)
             {
                 if(mysqli_num_rows($result_of_package_cart_array))
                 {
                   while( $updated_package_rows= mysqli_fetch_object($result_of_package_cart_array))
                   {
                    $updated_package[]=array(
                             "package_cart_id"=>$updated_package_rows->PTC_id,
                             "package_id"=>$updated_package_rows->PTC_package_id,
                             "package_name"=>$updated_package_rows->PTC_package_name,
                             "package_quantity"=>$updated_package_rows->PTC_package_quantity,
                             "product_quantity"=>$updated_package_rows->Product_Quantity,
                             "product_added"=>$updated_package_rows->Product_Added
                            );
                   }
                   mysqli_free_result($result_of_package_cart_array);
                 }
             }
            $package_cart_array['updated_packages']= $updated_package;
            /** End: Query for add the package to updated package array **/
             $query_of_package_cart_count="SELECT COUNT(PTC_package_id) AS Package_Quantity FROM package_temp_cart WHERE PTC_session_id='".$PTC_session_id."'";
             $result_of_package_cart_count = mysqli_query($con, $query_of_package_cart_count);
             if($result_of_package_cart_count)
             {
                 $count_rows =  mysqli_fetch_object($result_of_package_cart_count);
                 $package_count = $count_rows->Package_Quantity;
                 mysqli_free_result($result_of_package_cart_count);
             }
             else
             {
                 if (DEBUG) {
                     echo 'result_of_package_cart_count Error: ' . mysqli_error($con);
                 } else {
                     echo 'result_of_package_cart_count error';
                 }
             }
             $package_cart_array['package_count']=$package_count;
            /** Start: Query for count package to  package array **/
        }
        else
        {
             if (DEBUG) {
                     echo 'result_of_update_package_temp_cart Error: ' . mysqli_error($con);
                 } else {
                     echo 'result_of_update_package_temp_cart error';
                 }
        }
       /** END: insert the package cart **/
        /** echo json array **/
        echo json_encode($package_cart_array);
}
?>
