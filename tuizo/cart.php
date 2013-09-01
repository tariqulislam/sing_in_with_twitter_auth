<?php
include 'config/config.php';
$pageTitle = 'Tuizo | Cart';
$pageDescription = 'Tuizo Cart';
$pageKeywords = 'Tuizo, bangladesh, Garments';

if(getTotalPackageAdd() == 0){
	$link = "index.php";
	redirect($link);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>

        <title>Tuizo | Shopping Cart</title>
        <meta name="description" content="<?php echo $pageDescription; ?>">
        <meta name="keywords" content="<?php echo $pageKeywords ?>">
        <meta name="author" content="Ståle Refsnes">
        <?php include(basePath('header.php')); ?>
<script type="text/javascript">
	function deleteTempPackageCart(package_id) {
                            
                            $.ajax({
                                url: 'ajax/packageDeleteCart.php',
                                type: 'post',
                                data: {package_id: package_id},
                                success: function(output) {
                                    
                                    var result = $.parseJSON(output);
                                    if(result.output_type == 0)
                                    {
                                         window.location.replace(result.redirect);   
                                    }
                                    else if (result.output_type == 1)
                                    {
                                       $('.cartDetails_'+package_id).fadeOut('slow');
                                       $(".packageSummery_" + package_id).fadeOut('slow');
                                       $("#packageSubtotal").text(result.total_temp_cart_price);
                                       $("#packageDiscount").text(result.total_temp_cart_discount);
                                       var Total = result.total_temp_cart_price - result.total_temp_cart_discount;
                                       $("#packageTotal").text(Total.toFixed(2));
                                       $('#wholeCart').html(result.full_cart);
                                       $('#totalPackageCount').text(result.total_package);
                                    }
                                }
                            });
                        }


	function deleteTempProductAddToCart(Product_temp_cart_id,Package_ID){
		var category_product_quantity = $('#txtProductQunatity_' + Product_temp_cart_id).val();
		//var package_quantity = $('#QiuanityTd_' + ProTC_PTC_package_id).html();
		$.ajax({ url: 'ajax/productDeleteCart.php',
             data: {category_product_quantity:category_product_quantity,Product_temp_cart_id:Product_temp_cart_id,Package_ID:Package_ID}, //Modify this
             type: 'post',
             success:   function(output) {
				//alert(output); 
				var result=$.parseJSON(output);
				
				if(result.output_type == 1){
					$("#cartDetails_"+Product_temp_cart_id).fadeOut('slow');
					$(".packageSummery_"+Package_ID).fadeOut('slow');
					$("#packageSubtotal").text(result.total_temp_cart_price);
					$("#packageDiscount").text(result.total_temp_cart_discount);
					var Total = result.total_temp_cart_price - result.total_temp_cart_discount;
					$("#packageTotal").text(Total.toFixed(2));
					$('#wholeCart').html(result.full_cart);
					$('#totalPackageCount').text(result.total_package);
					
					if(result.total_package == 0) {
						document.location.href='index.php';
					}
					
				} else if (result.output_type == 2){
					$("#cartDetails_"+Product_temp_cart_id).fadeOut('slow');
					$("#packageQuan_"+Package_ID).text(result.package_quantity);
					$("#packagePrice_"+Package_ID).text(result.package_price);
					$("#packageSubtotal").text(result.total_temp_cart_price);
					$("#packageDiscount").text(result.total_temp_cart_discount);
					var Total = result.total_temp_cart_price - result.total_temp_cart_discount;
					$("#packageTotal").text(Total.toFixed(2));	
					$('#wholeCart').html(result.full_cart);
					$('#totalPackageCount').text(result.total_package);
				} else {
					
					
				}
				 
			 }
		});	 
			 
	}
	function AddToPackageFromchangingValue(Product_temp_cart_id,Package_ID){
		
		var category_product_quantity = $('#txtProductQunatity_' + Product_temp_cart_id).val();
		//var package_quantity = $('#QiuanityTd_' + ProTC_PTC_package_id).html();
		$.ajax({ url: 'ajax/productCartQuantityValueChanging.php',
             data: {category_product_quantity:category_product_quantity,Product_temp_cart_id:Product_temp_cart_id}, //Modify this
             type: 'post',
             success:   function(output) {
				//alert(output);
				var result=$.parseJSON(output);
				if(result.output_type == 0){
					$("#packageQuan_"+Package_ID).text(result.package_quantity);
					$("#packagePrice_"+Package_ID).text(result.package_price);
					$("#packageSubtotal").text(result.total_temp_cart_price);
					$("#packageDiscount").text(result.total_temp_cart_discount);
					var Total = result.total_temp_cart_price - result.total_temp_cart_discount;
					$("#packageTotal").text(Total.toFixed(2));
					$('#wholeCart').html(result.full_cart);	
				} else if(result.output_type == 1){
					//alert("Invalid quantity.");
					$('#txtProductQunatity_' + Product_temp_cart_id).val(result.product_quantity);
				}
				
			 }
			 
		});
	}

</script>

                    </head>
                    <body >

                        <div id="wrapper">

                            <?php include("menu.php"); ?>

                            <div style="clear:both"></div>

                            <?php
                            $cart_id = session_id();
                            $ChkTempPackageProduct = "SELECT *,(SELECT
                     product_images.PI_file_name
                  FROM
                    product_images
                  WHERE
                   product_images.PI_product_id= product_temp_cart.ProTC_product_id
                   ORDER BY product_images.PI_priority DESC
                   LIMIT 1
                  ) AS product_image 
				  FROM package_temp_cart,product_temp_cart,products,colors,sizes 
				  WHERE package_temp_cart.PTC_session_id='$cart_id' 
				  AND package_temp_cart.PTC_package_id=product_temp_cart.ProTC_PTC_package_id 
				  AND products.product_id=product_temp_cart.ProTC_product_id 
				  AND product_temp_cart.ProTC_color_id=colors.color_id 
				  AND product_temp_cart.ProTC_size_id=sizes.size_id 
				  AND product_temp_cart.ProTC_session_id='$cart_id'";
                            $ExecuteChkTempPackageProduct = mysqli_query($con, $ChkTempPackageProduct);
                            ?>
                            <!-- header end -->

                            <div id="innerContainer" >
                                <div class="cartContainner">
                                    <div class="cartContainnerLft">
                                        <h2>MY SHOPPING BAG</h2>
                                        <div class="cartTop">
                                            <table width="100%" border="0" id="productCartRender">
                                                <tr class="cartHeding">
                                                    <td width="14%">PRODUCT</td>
                                                    <td width="6%">COLOR</td>
                                                    <td width="9%">SIZE</td>
                                                    <td>QUANTITY</td>
                                                    <td width="11%">PACKAGE</td>
                                                    <td width="12%">REMOVE</td>
                                                </tr>
                                                <?php
                                                while ($GetChkTempPackageProduct = mysqli_fetch_object($ExecuteChkTempPackageProduct)) {
                                                    ?>
                                                    <tr class="cartDetails cartDetails_<?php echo $GetChkTempPackageProduct->ProTC_PTC_package_id; ?>" id="cartDetails_<?php echo $GetChkTempPackageProduct->ProTC_id; ?>">
                                                        <td class="col_1_row_2">
                                                            <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/product/mid/' . $GetChkTempPackageProduct->product_image; ?>" width="50" height="60" alt="<?php echo $GetChkTempPackageProduct->product_title; ?>"><p><?php echo $GetChkTempPackageProduct->product_title; ?></p>
                                                        </td>
                                                        <td class="col_2_row_2">
                                                            <?php
                                                            if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/color_img/' . $GetChkTempPackageProduct->color_image_name)) {
                                                                echo '<img src="' . $config['IMAGE_UPLOAD_URL'] . '/color_img/' . $GetChkTempPackageProduct->color_image_name . '" style="height:25px; width:25px; float:left;" />';
                                                            } else {
                                                                echo '<span style="background:#' . $GetChkTempPackageProduct->color_code . ';float: left;height: 25px;margin: 0 5px 0 0;width: 25px;"></span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="col_3_row_2"><?php echo $GetChkTempPackageProduct->size_title; ?></td>
                                                        <td width="11%" class="col_6_row_2">
                                                            <input id="txtProductQunatity_<?php echo $GetChkTempPackageProduct->ProTC_id; ?>" value="<?php echo $GetChkTempPackageProduct->ProTC_product_quantity; ?>" type="text" name="cart" />
                                                            <a class="button" name="updatebtn" onClick="AddToPackageFromchangingValue(<?php echo $GetChkTempPackageProduct->ProTC_id; ?>,<?php echo $GetChkTempPackageProduct->ProTC_PTC_package_id; ?>);">update</a>
                                                        </td>
                                                        <td class="col_8_row_2"> <p><?php echo $GetChkTempPackageProduct->PTC_package_name; ?> </p></td>
                                                        <td class="col_9_row_2"><a href="javascript:deleteTempProductAddToCart(<?php echo $GetChkTempPackageProduct->ProTC_id; ?>,<?php echo $GetChkTempPackageProduct->ProTC_PTC_package_id; ?>);"><img src="images/cancel.png" width="16" height="16" alt="cancel"></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>

                                        </div><!--cartTop-->

                                        <div class="cartShipping">
                                            <table width="100%" border="0" id="cartShipping_package">
                                                <tr class="packageSummeryHeading">
                                                    <td>&nbsp;Package</td>
                                                    <td width="30%">Quantity</td>
                                                    <td width="27%" align="right">Price&nbsp;</td>
                                                    <td width="9%" align="right">&nbsp;</td>
                                                </tr> 

                                                <tr>
                                                    <td colspan="4">&nbsp;</td>

                                                </tr>
                                                <?php
                                                $ChkTempPackage = "SELECT * FROM package_temp_cart WHERE package_temp_cart.PTC_session_id='$cart_id'";
                                                $ExecuteTempPackage = mysqli_query($con, $ChkTempPackage);

//declaring total package variable
                                                $Package_price_sub_total = 0;
                                                $Package_discount_total = 0;
                                                while ($GetTempPackage = mysqli_fetch_object($ExecuteTempPackage)) {
                                                    ?>
                                                    <tr class="packageSummery_<?php echo $GetTempPackage->PTC_package_id; ?>">
                                                        <td><?php echo $GetTempPackage->PTC_package_name; ?></td>
                                                        <td id="packageQuan_<?php echo $GetTempPackage->PTC_package_id; ?>"><?php echo $GetTempPackage->PTC_package_quantity; ?></td>
                                                        <td align="right" id="packagePrice_<?php echo $GetTempPackage->PTC_package_id; ?>"><?php echo number_format((float) ($GetTempPackage->PTC_package_price * $GetTempPackage->PTC_package_quantity), 2, '.', ''); ?></td>
                                                        <td align="right"><a href="javascript:deleteTempPackageCart(<?php echo $GetTempPackage->PTC_package_id; ?>);"><img src="images/cancel.png" width="16" height="16" alt="cancel"></a></td>
                                                    </tr> 
    <?php
    $Package_price_sub_total += $GetTempPackage->PTC_package_price * $GetTempPackage->PTC_package_quantity;
    $Package_discount_total += $GetTempPackage->PTC_package_discount * $GetTempPackage->PTC_package_quantity;
}
?>
                                                <tr class="cartShippingContent" style="height:40px;">
                                                    <td width="34%"></td>
                                                    <td valign="top"><h4 style="margin-top: 8px; text-align: right;">SUBTOTAL</h4>
                                                    </td>
                                                    <td id="subTotal" valign="top" align="right"><strong><font id="packageSubtotal"><?php echo number_format((float) $Package_price_sub_total, 2, '.', ''); ?></font></strong>
                                                    </td>
                                                    <td  valign="top" align="right">&nbsp;</td>
                                                </tr>
                                                <tr class="cartShippingContent" style="height:40px; color:#900;">
                                                    <td width="34%"></td>
                                                    <td valign="top"><h4 style="margin-top: 8px; text-align: right;">DISCOUNT</h4>
                                                    </td>
                                                    <td id="subTotal" valign="top" align="right"><strong><font id="packageDiscount"><?php echo number_format((float) $Package_discount_total, 2, '.', ''); ?></font></strong>
                                                    </td>
                                                    <td  valign="top" align="right">&nbsp;</td>
                                                </tr>
                                                <tr style="height:40px;">
                                                    <td width="34%"></td>
                                                    <td valign="top" class="packageSummeryHeading"><h4 style="margin-top: 8px; text-align: right;">TOTAL</h4>
                                                    </td>
                                                    <td id="subTotal" valign="top" align="right" class="packageSummeryHeading"><strong><font id="packageTotal"><?php echo number_format((float) ($Package_price_sub_total - $Package_discount_total), 2, '.', ''); ?></font></strong>
                                                    </td>
                                                    <td  valign="top" align="right" class="packageSummeryHeading">&nbsp;</td>
                                                </tr>


                                                <tr class="cartShippingContent" style="height:130px">
                                                    <td height="89" colspan="2" style="vertical-align:top"><img height="80px" class="shippingImg" src="images/shipping_free.png" alt="free"><img height="80px" src="images/pay_card.jpg" alt="card"></td>
                                                                <td colspan="2" align="right"  valign="top"><a class="signUpNext" href="sign_in.php?action=<?php echo "checkout"; ?>">PROCEED TO CHECKOUT</a></td>
                                                                </tr>
                                                                </table>

                                                                </div><!--cartShipping-->
                                                                </div><!--cartContainnerLft-->
<?php
include('right_menu.php');
?>

                                                                </div><!--cartContainner-->
                                                                </div><!--innerContainer-->
                                                                </div>

<?php include(basePath('footer.php')); ?>

                                                                </body>
                                                                </html>