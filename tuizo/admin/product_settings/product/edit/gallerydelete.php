<?php

include ('../../../../config/config.php');
$PI_id = '';
$PI_product_id = '';
if (isset($_POST['PI_id'])) {
    extract($_POST);
    $jsonArray = array();


//getting image path and unlinking
    $unlinkimg = mysqli_query($con, "SELECT * FROM product_images WHERE PI_id='$PI_id'");
    $unlinkrow = mysqli_fetch_object($unlinkimg);
    $imgname = $unlinkrow->PI_file_name;
    unlink('../../../../upload/product/original/' . $imgname); //deleting original image
    unlink('../../../../upload/product/large/' . $imgname); //deleting large image
    unlink('../../../../upload/product/mid/' . $imgname); //deleting medium image
    unlink('../../../../upload/product/small/' . $imgname); //deleting small image
//deleting image details from db
    $delimg = mysqli_query($con, "DELETE FROM product_images WHERE PI_id='$PI_id'");

    $jsonArray['msg'] = '';
    $jsonArray['error'] = '';
    $jsonArray['product_gallery'] = '';
    //$jsonArray['product_colors'] = '';
    $product_gallery = '';
    //$product_colors ='';
//getting images from db

    if ($delimg) {
        $selimage = mysqli_query($con, "SELECT * FROM product_images WHERE PI_product_id='$PI_product_id'");
        //echo $msg = 'Image deleted successfully';
        $jsonArray['msg'] = 'Image deleted successfully';
       
        /** Start: colors array **/
                            // $product_colors.= '<select name="color"  onchange="getColorCodeOrImage(this.value)">
                                                    //<option value="0">Select Product Color</option>';	
                                                   
                                                    /** Start: get Color by product id * */
                                                    //$query_for_product_color = "SELECT
                               // c.color_id,c.color_title,c.color_code
                            //FROM
                               // colors c
                            //WHERE c.color_id NOT IN(SELECT PI_color FROM product_images WHERE PI_product_id='" . intval($PI_product_id) . "')";
                                                    //$result_of_product_color = mysqli_query($con, $query_for_product_color);
                                                    /** End: get Color by product id * */
                                                   // if ($result_of_product_color) {
                                                        //while ($rows = mysqli_fetch_object($result_of_product_color)) {
                                                         
                                                         //$product_colors .=   '<option value="'.$rows->color_id.'">'.$rows->color_title.'</option>';
                                                            
                                                       // }
                                                    //}
                                                  
                                           //$product_colors.= '</select>';
                                               
        
        
        /** End: colors array **/
        
        $product_gallery.= '<ul>';

        while ($showimg = mysqli_fetch_object($selimage)) {

            $product_gallery.= '<li><a href = "' . baseUrl('upload/product/large/' . $showimg->PI_file_name) . '" data-lightbox = "roadtrip" title = ""><img src = "' . baseUrl('upload/product/small/' . $showimg->PI_file_name) . '" alt = "" height = "84px" width = "100px" /></a>';
            $product_gallery.= '<div class = "actions">';
            $product_gallery.= '<a href = ""><img src = "' . baseUrl('admin/images/edit.png') . '" alt = "" /></a>&nbsp;';
            $product_gallery.= '<a href = "javascript:delete_product_image(' . $showimg->PI_id . ',' . $showimg->PI_product_id . ')"><img src = "' . baseUrl('admin/images/delete.png') . '" alt = "" /></a>';
            $product_gallery.= '</div></li>';
        }
        $product_gallery.= '</ul>';
    } else {
        $selimage = mysqli_query($con, "SELECT * FROM product_images WHERE PI_product_id='$PI_product_id'");
        //$err = 'Image deleted successfully';
        $jsonArray['error'] = 'Image deleted successfully';
        
        /** Start: colors array **/
                             //$product_colors.= '<select name="color"  onchange="getColorCodeOrImage(this.value)">
                                                    //<option value="0">Select Product Color</option>';	
                                                   
                                                    /** Start: get Color by product id * */
                                                   // $query_for_product_color = "SELECT
                               // c.color_id,c.color_title,c.color_code
                           // FROM
                               // colors c
                           // WHERE c.color_id NOT IN(SELECT PI_color FROM product_images WHERE PI_product_id='" . intval($PI_product_id) . "')";
                                                    //$result_of_product_color = mysqli_query($con, $query_for_product_color);
                                                    /** End: get Color by product id * */
                                                    //if ($result_of_product_color) {
                                                        //while ($rows = mysqli_fetch_object($result_of_product_color)) {
                                                         
                                                        // $product_colors .=   '<option value="'.$rows->color_id.'">'.$rows->color_title.'</option>';
                                                            
                                                        //}
                                                    //}
                                                  
                                           //$product_colors.= '</select>';
                                               
        
        
        /** End: colors array **/
        $product_gallery.= '<ul>';

        while ($showimg = mysqli_fetch_object($selimage)) {

            $product_gallery.= '<li><a href = "' . baseUrl('upload/product/large/' . $showimg->PI_file_name) . '" data-lightbox = "roadtrip" title = ""><img src = "' . baseUrl('upload/product/small/' . $showimg->PI_file_name) . '" alt = "" height = "84px" width = "100px" /></a>';
            $product_gallery.= '<div class = "actions">';
            $product_gallery.= '<a href = ""><img src = "' . baseUrl('admin/images/edit.png') . '" alt = "" /></a>&nbsp;';
            $product_gallery.= '<a href = "javascript:delete_product_image(' . $showimg->PI_id . ',' . $showimg->PI_product_id . ')"><img src = "' . baseUrl('admin/images/delete.png') . '" alt = "" /></a>';
            $product_gallery.= '</div></li>';
        }
        $product_gallery.= '</ul>';
    }
    $jsonArray['product_gallery'] = $product_gallery;
    //$jsonArray['product_colors']=$product_colors;
    echo json_encode($jsonArray);
}
?>