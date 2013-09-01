<?php
include("config/config.php");
$pageTitle = 'Tuizo | Package';
$pageDescription = 'Tuizo package';
$pageKeywords = 'Tuizo, bangladesh, Garments';
$session_id = session_id();
$package_id = '';
$package_name = '';
$package_description = '';
$package_logo = '';
$data = array();
if (isset($_GET['package_id']) && isset($_GET['package_name'])) {
    //getting package details from package id
    $SqlGetPackage = "SELECT * FROM packages WHERE package_id=" . mysqli_real_escape_string($con, $_GET['package_id']);
    $ExecuteGetPackage = mysqli_query($con, $SqlGetPackage);
    $SetGetPackage = mysqli_fetch_object($ExecuteGetPackage);
} else {
    /* without id cant not access this page */
    redirect(baseUrl("index.php"));
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle; ?> </title>
            <meta name="description" content="<?php echo $pageDescription; ?>">
                <meta name="keywords" content="<?php echo $pageKeywords ?>">
                    <meta name="author" content="Ståle Refsnes">
                        <!-- Script From ColorBox / Litebox Bar -->
                        <?php include(basePath('header.php')); ?>

                        <!-- Start: Add to Cart Function ------>
                        <script type="text/javascript">

                            function PackageAddToCart(Package_ID) {
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/PackageAddToCart.php",
                                    data: {Package_ID: Package_ID},
                                    success: function(output) {
                                        //alert(output);
                                        var result = jQuery.parseJSON(output);

                                        if (result.output_type == 0) {
                                            $('#packageQuantity_' + Package_ID).text(result.current_package);
                                            $('#packagePrice_' + Package_ID).text(result.package_price);
                                            $('#wholeCart').html(result.total_cart);
                                            $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                            $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});
                                        } else if (result.output_type == 2) {
                                            $('#wholeCart').html(result.total_cart);
                                            $('#packageQuantity_' + Package_ID).text(result.current_package);
                                            $('#packagePrice_' + Package_ID).text(result.package_price);
                                            $('#totalPackageCount').text(result.total_package);
                                            $('#wholeCart').html(result.total_cart);
                                            $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                            $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});
                                        }

                                    }
                                });
                            }
                        </script>
                        <!-- End: Add to Cart Function --------->


                        <script src="js/jquery.colorbox.js"></script>
                        <link rel="stylesheet" href="css/colorbox.css" />
                        <script type="text/javascript">
                            function equalHeight(group) {

                                tallest = 0;
                                group.each(function() {
                                    thisHeight = $(this).height();
                                    if (thisHeight > tallest) {
                                        tallest = thisHeight;
                                    }
                                });
                                group.height(tallest);
                            }
                            $(document).ready(function() {
                                equalHeight($("div.packagecontent div.productBox"));
                            });
                        </script>
                        <script>
                            $(document).ready(function() {
                                //Examples of how to assign the Colorbox event to elements
                                $(".inline").colorbox({inline: true, width: "80%"});
                            });
                        </script>
                        <script>

                            function UpdatePackageQuantity(Category_ID, Package_ID, Package_Quantity, Product_ID, NewProductQuantity, Category_Limit) {
                                $.ajax({url: 'ajax/UpdatePackageQuantity.php',
                                    data: {Category_ID: Category_ID, Package_ID: Package_ID, Package_Quantity: Package_Quantity, Product_ID: Product_ID, NewProductQuantity: NewProductQuantity, Category_Limit: Category_Limit}, //Modify this
                                    type: 'post',
                                    success: function(output) {
//alert(output);
                                        var result = jQuery.parseJSON(output);
                                        $('#addedQuantity_' + Package_ID + '_' + Category_ID).text(result.added_quantity);
                                        $('#leftQuantity_' + Package_ID + '_' + Category_ID).text(result.left_quantity);
                                        $('#wholeCart').html(result.full_cart);
                                        $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                        $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});
                                    }
                                });
                            }




                            function addToPackageCart(Product_ID, Category_Limit, Category_ID, Package_ID, Color_ID, Size_ID, Inventory_ID) {
                                $.ajax({url: 'ajax/ProductAddToCart.php',
                                    data: {Product_ID: Product_ID, Category_Limit: Category_Limit, Category_ID: Category_ID, Package_ID: Package_ID, Color_ID: Color_ID, Size_ID: Size_ID, Inventory_ID: Inventory_ID}, //Modify this
                                    type: 'post',
                                    success: function(output) {
                                        //alert(output);
                                        var result = jQuery.parseJSON(output);

                                        if (result.output_type == 1) {
                                            var c = confirm("A new package will be added. Do you want to continue?")
                                            var packageQuan = result.package_quantity;
                                            var newquantity = result.product_quantity;

                                            if (c == true) {
                                                UpdatePackageQuantity(Category_ID, Package_ID, packageQuan, Product_ID, newquantity, Category_Limit);

                                                $('#packagePrice_' + Package_ID).text(result.package_price);
                                                $('#packageQuantity_' + Package_ID).text(packageQuan);
                                                $('#proTempQuan_' + Product_ID).text('(' + result.product_quantity + ')');
                                                $('#txtQuanPopUp_' + Product_ID).val(result.product_quantity);
                                            }

                                        } else if (result.output_type == 2) {
                                            $('#addedQuantity_' + Package_ID + '_' + Category_ID).text(result.added_quantity);
                                            $('#leftQuantity_' + Package_ID + '_' + Category_ID).text(result.left_quantity);
                                            $('#proTempQuan_' + Product_ID).text('(' + result.product_quantity + ')');
                                            $('#txtQuanPopUp_' + Product_ID).val(result.product_quantity);
                                            $('#wholeCart').html(result.full_cart);
                                            $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                            $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});
                                        } else if (result.output_type == 3) {
                                            var c = confirm("A new package will be added. Do you want to continue?")
                                            var packageQuan = result.package_quantity;
                                            var newquantity = result.product_quantity;

                                            if (c == true) {
                                                UpdatePackageQuantity(Category_ID, Package_ID, packageQuan, Product_ID, newquantity, Category_Limit);

                                                $('#packageQuantity_' + Package_ID).text(result.package_quantity);
                                                $('#packagePrice_' + Package_ID).text(result.package_price);
                                                $('#totalPackageCount').text(result.total_package);
                                                $('#proTempQuan_' + Product_ID).text('(' + result.product_quantity + ')');
                                                $('#txtQuanPopUp_' + Product_ID).val(result.product_quantity);
                                                $('#wholeCart').html(result.full_cart);
                                                $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                                $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});
                                            }

                                        } else {
                                            $('#addedQuantity_' + Package_ID + '_' + Category_ID).text(result.added_quantity);
                                            $('#leftQuantity_' + Package_ID + '_' + Category_ID).text(result.left_quantity);
                                            $('#totalPackageCount').text(result.total_package);
                                            $('#proTempQuan_' + Product_ID).text('(' + result.product_quantity + ')');
                                            $('#txtQuanPopUp_' + Product_ID).val(result.product_quantity);
                                            $('#wholeCart').html(result.full_cart);
                                            $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                            $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});
                                        }

                                    }
                                });
                            }
                        </script>

                        </head>

                        <body>

                            <div id="wrapper">

                                <?php include("menu.php"); ?>

                                <div style="clear:both"></div>


                                <div id="packageContent" class="clearfix">

                                    <div class="intorotitlepkg">
                                        <h2><?php echo $SetGetPackage->package_name; ?></h2>
                                        <p class="sub"><?php echo $SetGetPackage->package_description; ?></p>
                                        <p align="center" class="buypkgp">

                                            <a title="Buy <?php echo $SetGetPackage->package_name; ?> Package"  class="viewpkg transitionall"  href="javascript:PackageAddToCart(<?php echo $SetGetPackage->package_id; ?>)"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">buy this package </a>
                                        </p>
                                    </div>

                                </div><!-- header end -->

                                <div class="MainContainer clearfix">

                                    <div class="packagecontent">
                                        <div class="pkgboxLeft">
                                            <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/packages/' . $SetGetPackage->package_id . '/' . $SetGetPackage->package_logo; ?>" alt="<?php echo $SetGetPackage->package_name; ?>">
                                        </div>

                                        <div class="pkgboxRight">
                                            <?php
                                            $SqlProducts = "SELECT *,
	(SELECT PI_file_name 
	FROM product_images 
	WHERE product_images.PI_product_id=products.product_id 
	ORDER BY product_images.PI_priority DESC LIMIT 1) AS product_image,
	(SELECT PI_color 
	FROM product_images 
	WHERE product_images.PI_product_id=products.product_id
	AND product_images.PI_file_name=product_image
	ORDER BY product_images.PI_priority DESC LIMIT 1) AS product_color,
	(SELECT PI_size_id
	 FROM product_inventories
	 WHERE product_inventories.PI_product_id= products.product_id 
	 AND product_inventories.PI_color_id= product_color
	 ORDER BY product_inventories.PI_size_id DESC LIMIT 1) AS product_size,
	(SELECT PI_quantity 
	FROM product_inventories 
	WHERE product_inventories.PI_product_id=products.product_id
	AND product_inventories.PI_color_id=product_color
	AND product_inventories.PI_size_id=product_size) AS product_quantity,
	(SELECT PI_id 
	FROM product_inventories 
	WHERE product_inventories.PI_product_id=products.product_id
	AND product_inventories.PI_color_id=product_color
	AND product_inventories.PI_size_id=product_size) AS product_inventory_id 
				FROM products,package_category_products,product_categories,package_categories
				WHERE package_category_products.PCP_package_id='" . mysqli_real_escape_string($con, $_GET['package_id']) . "' 
				AND products.product_id=package_category_products.PCP_product_id
				AND product_categories.PC_product_id=products.product_id
				AND product_categories.PC_category_id=package_category_products.PCP_package_category_id
				AND package_categories.PC_package_id='" . mysqli_real_escape_string($con, $_GET['package_id']) . "'
				AND package_categories.PC_catagory_id=package_category_products.PCP_package_category_id";
                                            $ExecuteProducts = mysqli_query($con, $SqlProducts);
                                            $GetProducts = array();
                                            while ($SetProducts = mysqli_fetch_object($ExecuteProducts)) {
                                                $GetProducts[] = $SetProducts;
                                            }
                                            $ShowFirstSixProducts = array_slice($GetProducts, 0, 6);
                                            ?>
                                            <?php
                                            if (count($ShowFirstSixProducts) >= 1) {
                                                foreach ($ShowFirstSixProducts as $ShowFirstSixProduct) {
                                                    ?>
                                                    <div class="productBox">
                                                        <div class="innerPro">
                                                            <p class="pkgmenu mobileHide" align="center">
                                                                <a class='inline viewpkg' href="#popUpContainer_<?php echo $ShowFirstSixProduct->PCP_id; ?>"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                <a title="pro"  class="addtocart poplink" href="javascript:addToPackageCart(<?php echo $ShowFirstSixProduct->PCP_product_id; ?>,<?php echo $ShowFirstSixProduct->PC_catagory_quantity; ?>,<?php echo $ShowFirstSixProduct->PCP_package_category_id; ?>,<?php echo $SetGetPackage->package_id; ?>,<?php echo $ShowFirstSixProduct->product_color; ?>,<?php echo $ShowFirstSixProduct->product_size; ?>,<?php echo $ShowFirstSixProduct->product_inventory_id; ?>);"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">
                                                                        <?php
                                                                        if ($ShowFirstSixProduct->product_quantity == 0) {
                                                                            echo 'OUT OF STOCK';
                                                                        } else {
                                                                            echo 'ADD TO PACKAGE';
                                                                        }
                                                                        ?>
                                                                </a>
                                                            </p>
                                                            <!--<a title="pro"  href="#">-->
                                                            <img align="middle" class="productThumb" width="110" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/product/mid/' . $ShowFirstSixProduct->product_image; ?>"  alt="<?php echo $ShowFirstSixProduct->product_title; ?>">

                                                                <span class="productTitle"><?php echo $ShowFirstSixProduct->product_title; ?>&nbsp;&nbsp;<font id="proTempQuan_<?php echo $ShowFirstSixProduct->PCP_product_id; ?>">

                                                                        <?php
//getting product quantity from product_temp_cart
                                                                        $SqlProductTempCart = "SELECT * FROM product_temp_cart 
					  WHERE ProTC_PTC_package_id='" . mysqli_real_escape_string($con, $_GET['package_id']) . "'
					  AND ProTC_product_id='" . mysqli_real_escape_string($con, $ShowFirstSixProduct->product_id) . "'
					  AND ProTC_session_id='$session_id'";
                                                                        $ExecuteProductTempCart = mysqli_query($con, $SqlProductTempCart);
                                                                        $CountProductTempCart = mysqli_fetch_object($ExecuteProductTempCart);
                                                                        ?>																	

                                                                        <?php
                                                                        if (@$CountProductTempCart->ProTC_product_quantity > 0) {
                                                                            echo '(' . $CountProductTempCart->ProTC_product_quantity . ')';
                                                                        }
                                                                        ?></font></span> 
                                                                <!--</a>-->


                                                                <p class=" tabletHide pcHide" align="center">
                                                                    <a class='inline viewpkg' href="#popUpContainer_<?php echo $ShowFirstSixProduct->PCP_id; ?>"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                    <a title="pro"  class="addtocart poplink" href="javascript:addToPackageCart(<?php echo $ShowFirstSixProduct->PCP_product_id; ?>,<?php echo $ShowFirstSixProduct->PC_catagory_quantity; ?>,<?php echo $ShowFirstSixProduct->PCP_package_category_id; ?>,<?php echo $SetGetPackage->package_id; ?>,<?php echo $ShowFirstSixProduct->product_color; ?>,<?php echo $ShowFirstSixProduct->product_size; ?>,<?php echo $ShowFirstSixProduct->product_inventory_id; ?>);"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">
                                                                            <?php
                                                                            if ($ShowFirstSixProduct->product_quantity == 0) {
                                                                                echo 'OUT OF STOCK';
                                                                            } else {
                                                                                echo 'ADD TO PACKAGE';
                                                                            }
                                                                            ?>
                                                                    </a>
                                                                </p>

                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div> <!--  package container left end -->


                                        <div style="clear:both"></div>

                                        <div class="pkgboxContainer">
                                            <?php
                                            $ShowRestProducts = array_slice($GetProducts, 6);
                                            $i = 0;
                                            if (count($ShowRestProducts) >= 1) {
                                                foreach ($ShowRestProducts as $ShowRestProduct) {
                                                    if (($i != 0) AND ($i % 10 == 0)) {
                                                        echo '</div><div style="clear:both"></div><div class="pkgboxContainer">';
                                                    }
                                                    ?>
                                                    <div class="productBox">
                                                        <div class="innerPro">
                                                            <p class="pkgmenu mobileHide" align="center">
                                                                <a class='inline viewpkg' href="#popUpContainer_<?php echo $ShowRestProduct->PCP_id; ?>"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                <a title="pro"  class="addtocart poplink" href="javascript:addToPackageCart(<?php echo $ShowRestProduct->PCP_product_id; ?>,<?php echo $ShowRestProduct->PC_catagory_quantity; ?>,<?php echo $ShowRestProduct->PCP_package_category_id; ?>,<?php echo $SetGetPackage->package_id; ?>,<?php echo $ShowRestProduct->product_color; ?>,<?php echo $ShowRestProduct->product_size; ?>,<?php echo $ShowRestProduct->product_inventory_id; ?>);"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">
                                                                        <?php
                                                                        if ($ShowRestProduct->product_quantity == 0) {
                                                                            echo 'OUT OF STOCK';
                                                                        } else {
                                                                            echo 'ADD TO PACKAGE';
                                                                        }
                                                                        ?>
                                                                </a>
                                                            </p>

                                                            <a title="<?php echo $ShowRestProduct->product_title; ?>"  href="#">
                                                                <img class="productThumb" width="110" src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/product/mid/' . $ShowRestProduct->product_image; ?>"  alt="<?php echo $ShowRestProduct->product_title; ?>">

                                                                    <span class="productTitle">
                                                                        <?php echo $od->product_title; ?> </span> 
                                                            </a>

                                                            <p class=" tabletHide pcHide" align="center">
                                                                <a class='inline viewpkg' href="#popUpContainer_<?php echo $ShowRestProduct->PCP_id; ?>"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                <a title="pro"  class="addtocart poplink" href="javascript:addToPackageCart(<?php echo $ShowRestProduct->PCP_product_id; ?>,<?php echo $ShowRestProduct->PC_catagory_quantity; ?>,<?php echo $ShowRestProduct->PCP_package_category_id; ?>,<?php echo $SetGetPackage->package_id; ?>,<?php echo $ShowRestProduct->product_color; ?>,<?php echo $ShowRestProduct->product_size; ?>,<?php echo $ShowRestProduct->product_inventory_id; ?>);"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">
                                                                        <?php
                                                                        if ($ShowRestProduct->product_quantity == 0) {
                                                                            echo 'OUT OF STOCK';
                                                                        } else {
                                                                            echo 'ADD TO PACKAGE';
                                                                        }
                                                                        ?>
                                                                </a>
                                                            </p>
                                                        </div>


                                                    </div>
                                                    <?php
                                                    $i++;
                                                }
                                            }
                                            ?>


                                        </div> <!-- pkgboxcontainer --->

                                        <!--                                        <div style="clear:both"></div>
                                        
                                                                                <div class=" ProductContainerSpecial">
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/42_077069005.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/24_077069006.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/13_077069002.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/64_077069001.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="spcialInfo">
                                                                                        <div class="spcialInfoInner">
                                                                                            <h3>Nike Apparel: Buy Nike Apparel at Macy's</h3>
                                                                                            <p>
                                                                                                Sport a cool, stylish look with the Nike Short sleeve Camp tee. Classic T-shirt cut. ... Choose a Design ... About Nike All Purpose Short Sleeve Tee; Nike Sizing.</p>
                                        
                                                                                        </div>
                                                                                    </div>
                                        
                                        
                                        
                                        
                                                                                </div>  pkgcotent end 
                                        
                                                                                <div style="clear:both"></div>
                                        
                                                                                <div class=" ProductContainerSpecial">
                                        
                                                                                    <div class="spcialInfoLeft">
                                                                                        <div class="spcialInfoInner">
                                                                                            <h3>Nike Apparel: Buy Nike Apparel at Macy's</h3>
                                                                                            <p>
                                                                                                Sport a cool, stylish look with the Nike Short sleeve Camp tee. Classic T-shirt cut. ... Choose a Design ... About Nike All Purpose Short Sleeve Tee; Nike Sizing.</p>
                                        
                                                                                        </div>
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/03_076612.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/55_075417.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/61_075417.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                                                                    <div class="productBox">
                                                                                        <div class="innerPro">
                                                                                            <p class="pkgmenu mobileHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"><img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                        
                                                                                            <a title="pro"  href="#">
                                                                                                <img class="productThumb" width="455" src="images/65_076612.jpg"  alt="">
                                        
                                                                                                    <span class="productTitle">
                                                                                                        MEN PREMIUM COTTON CREW   		</span> 
                                                                                            </a>
                                                                                            <p class=" tabletHide pcHide" align="center">
                                                                                                <a class='inline viewpkg' href="#popUpContainer"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="">VIEW details </a>
                                                                                                <a title="pro"  class="addtocart" href="#"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="">ADD TO PACKAGE </a>
                                                                                            </p>
                                                                                        </div>
                                        
                                        
                                                                                    </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                                                                </div>
                                        
                                        -->




                                    </div>








                                </div>

                            </div>

                            <?php include(basePath('footer.php')); ?>
                            <!--=============== All Hidden Content ================-->

                            <script type="text/javascript">


                                function AddToPackageFromchangingValue(Product_ID, Package_ID, Category_id, Inventory_ID) {
                                    //alert('working');
                                    var category_product_quantity = $('#txtQuanPopUp_' + Product_ID).val();
                                    var product_color = $('#productColor_' + Product_ID).val();
                                    var product_size = $('#productSize_' + Product_ID).val();
                                    //var package_quantity = $('#QiuanityTd_' + ProTC_PTC_package_id).html();
                                    if (product_color == 0) {
                                        alert("Please select Product Color.");
                                    } else if (product_size == 0) {
                                        alert("Please select Product Size.");
                                    } else {
                                        $.ajax({url: 'ajax/productCartQuantityValueChangingPopup.php',
                                            data: {Product_ID: Product_ID, Package_ID: Package_ID, category_product_quantity: category_product_quantity, product_color: product_color, product_size: product_size, Category_id: Category_id, Inventory_ID: Inventory_ID}, //Modify this
                                            type: 'post',
                                            success: function(output) {
                                                //alert(output);
                                                var result = $.parseJSON(output);
                                                if (result.output_type == 1) {
                                                    $('#totalPackageCount').text(result.total_count);
                                                    //{"output_type":1,"package_quantity":2,"package_price":"3600.00","total_temp_cart_price":"3600.00","total_temp_cart_discount":"198.00","full_cart":"
                                                    $('#proTempQuan_' + Product_ID).text('(' + result.product_quantity + ')');
                                                    //$('#txtQuanPopUp_' + Product_ID).val(result.product_quantity);
                                                    $('#wholeCart').html(result.full_cart);
                                                    $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                                    $('#cartHref_viewCart').attr({href: '<?php echo baseUrl('cart.php'); ?>'});

                                                }
                                                else if (result.output_type == 2)
                                                {
                                                    //$('#txtQuanPopUp_' + Product_ID).val(result.product_quantity);
                                                }

                                            }

                                        });
                                         $.ajax({
                                               type:"POST",
                                               url : "ajax/GetproductQuantityBySize.php",
                                               data:{Category_id:Category_id,Product_id:Product_ID,Size_id:product_size,Color_id:product_color},
                                               success : function(output){
                                                   var obj = jQuery.parseJSON(output);
                                                   $('#txtQuanPopUp_' + productid).val(obj.product_quantity);
                                               }
                                            });
                                    }
                                }

                                function sizeSelected(sizeId, productid,Category_id)
                                {
                                    var quantity = 0;
                                    $('#Pro_size_' + productid + ' ' + 'a').removeClass('active');
                                    $('#Pro_size_' + productid + '_' + sizeId).addClass(' active');
                                    var product_color = $('#productColor_' + productid).val();
                                    
                                    $('#productSize_' + productid).attr({value: sizeId});
                                    var tempQunatity = $('#Pro_size_' + productid + ' li a.active').attr('class').split(' ')[0];

                                    if (tempQunatity)
                                    {
                                        quantity = parseInt(tempQunatity.replace("quantity_", ""));
                                    }
                                    if (quantity == 0)
                                    {
                                        $('#AddToPackage_' + productid).html('out of stock');
                                    }
                                    else
                                    {
                                        $('#AddToPackage_' + productid).html('add to package');
                                    }
                                    $.ajax({
                                       type:"POST",
                                       url : "ajax/GetproductQuantityBySize.php",
                                       data:{Category_id:Category_id,Product_id:productid,Size_id:sizeId,Color_id:product_color},
                                       success : function(output){
                                           var obj = jQuery.parseJSON(output);
                                           $('#txtQuanPopUp_' + productid).val(obj.product_quantity);
                                       }
                                    });
                                }
                                function setColor(proId, colorId,Category_id) {
                                    //alert(proId+'pro:color'+colorId);
                                    $.ajax({
                                        type: "POST",
                                        url: "ajax/getColor.php",
                                        data: {PI_product_id: proId, PI_color_id: colorId},
                                        success: function(result) {
                                            var obj = jQuery.parseJSON(result);

                                            var imgUrl = '<?php echo $config['IMAGE_UPLOAD_URL'] . '/product/large/'; ?>';

                                            $('#prodColor_' + proId + ' ' + 'a').removeClass('active');
                                            $('#prodColor_' + proId + '_' + colorId).addClass(' active');
                                            $('img#Pro_image_' + proId).attr('src', imgUrl + obj.product_image);
                                            $('#Pro_size_' + proId).html('');
                                            $('#productColor_' + proId).attr({value: colorId});
                                            var htmlstr = '';
                                            $.each(obj.product_sizes, function(entryIndex, entry) {

                                                htmlstr += '<li>' + '<a ' + 'id="Pro_size_' + proId + '_' + entry.size_id + '" ';
                                                htmlstr += ' class="quantity_' + entry.PI_quantity + '"';
                                                htmlstr += ' href="javascript:sizeSelected(' + entry.size_id + ',' + proId + ','+ Category_id + ');">' + entry.size_title + '</a></li>';

                                            });
                                            $('#Pro_size_' + proId).html(htmlstr);
                                        }
                                    });

                                }


                            </script>   

                            <div id="popupRefresh" style="display:none">

                                <!--start: poup boxes-->

                                <?php $CountProductArray = count($GetProducts); ?>
                                <?php if ($CountProductArray > 0): ?>
                                    <?php foreach ($GetProducts AS $GetProduct): ?>
                                        <div id="popUpContainer_<?php echo $GetProduct->PCP_id; ?>" >

                                            <div class="popupImage">
                                                <a class="viewZoom" href="#">Click here to zoom</a>
                                                <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/product/large/' . $GetProduct->product_image; ?>" id="Pro_image_<?php echo $GetProduct->PCP_product_id; ?>" width="455" height="555" alt="imagfe"></div><!--popupImage-->
                                            <div class="popupdescription">
                                                <div class="popupProductTitle">
                                                    <h3><?php echo ucfirst($package_name); ?></h3>
                                                    <h4><?php echo ucfirst($GetProduct->product_title); ?></h4>
                                                    <p id="msg_<?php echo $GetProduct->PCP_product_id; ?>"></p>
                                                </div><!--popupProductTitle-->
                                                <div class="productColor" >
                                                    <p>color</p>
                                                    <div id="prodColor_<?php echo $GetProduct->PCP_product_id; ?>">
                                                        <?php
                                                        /** Start: Query for product color * */
                                                        $query_of_color_by_product = "SELECT
                                                        c.color_id,c.color_code,c.color_title
                                                      FROM
                                                           product_inventories piv,
                                                           colors c
                                                      WHERE
                                                          piv.PI_color_id= c.color_id AND piv.PI_product_id='" . $GetProduct->PCP_product_id . "'
                                                      GROUP BY piv.PI_color_id ORDER BY piv.PI_color_id ASC";
                                                        $result_of_color_by_product = mysqli_query($con, $query_of_color_by_product);
                                                        if ($result_of_color_by_product) {
                                                            if (mysqli_num_rows($result_of_color_by_product)) {
                                                                while ($row = mysqli_fetch_object($result_of_color_by_product)) {
                                                                    ?>
                                                                    <a id="prodColor_<?php echo $GetProduct->PCP_product_id; ?>_<?php echo $row->color_id ?>" <?php
                                                                    if ($GetProduct->product_color == $row->color_id) {
                                                                        echo 'class="active"';
                                                                        $CurrentColorID = $row->color_id;
                                                                    }
                                                                    ?> style="background:#<?php echo $row->color_code; ?>" href="javascript:setColor(<?php echo $GetProduct->PCP_product_id; ?>,<?php echo $row->color_id; ?>,<?php echo $GetProduct->PCP_package_category_id; ?>);"></a>

                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        /** End: Query for product color * */
                                                        ?>
                                                        <input type="hidden" name="productColor" id="productColor_<?php echo $GetProduct->PCP_product_id; ?>" value="<?php echo $CurrentColorID; ?>">                        
                                                    </div> 
                                                </div><!--productColor-->

                                                <div class="productColor productSize">
                                                    <p>size</p>

                                                    <?php
                                                    $query_of_size_by_product = "SELECT
                                                                                s.size_id,s.size_title,piv.PI_quantity
                                                                            FROM
                                                                                 product_inventories piv,
                                                                                 sizes s
                                                                            WHERE
                                                                               piv.PI_size_id= s.size_id AND piv.PI_product_id='" . $GetProduct->PCP_product_id . "' AND  piv.PI_color_id=" . $GetProduct->product_color . "
                                                                            GROUP BY piv.PI_size_id";
                                                    $result_of_size_by_product = mysqli_query($con, $query_of_size_by_product);
                                                    echo '<ul id="Pro_size_' . $GetProduct->PCP_product_id . '">';
                                                    if ($result_of_size_by_product) {
                                                        if (mysqli_num_rows($result_of_size_by_product)) {
                                                            while ($row1 = mysqli_fetch_object($result_of_size_by_product)) {
                                                                ?>
                                                                <li><a class="quantity_<?php echo $row1->PI_quantity; ?>" id="Pro_size_<?php echo $GetProduct->PCP_product_id; ?>_<?php echo $row1->size_id; ?>" href="javascript:sizeSelected(<?php echo $row1->size_id; ?>,<?php echo $GetProduct->PCP_product_id; ?>,<?php echo $GetProduct->PCP_package_category_id; ?>);"><?php echo $row1->size_title; ?></a></li>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    echo '</ul>';
                                                    ?>
                                                    <input type="hidden" name="productSize" id="productSize_<?php echo $GetProduct->PCP_product_id; ?>" value="0">        
                                                </div><!--productColor-->

                                                <div class="productQuantity cartDiv">
                                                    <p>quantity</p>
                                                    <?php
                                                    $Product_ID = $GetProduct->PCP_product_id;
                                                    $cart_id = session_id();
                                                    $GetProductTempCart = mysqli_query($con, "SELECT * FROM product_temp_cart WHERE ProTC_product_id=$Product_ID AND ProTC_session_id='$cart_id'");
                                                    $SetProductTempCart = mysqli_fetch_object($GetProductTempCart);
                                                    ?>
                                                    <input class="disableQuantity" id="txtQuanPopUp_<?php echo $GetProduct->PCP_product_id; ?>" type="number" min="1"  value="<?php
                                                    if (@$SetProductTempCart->ProTC_product_quantity > 0) {
                                                        echo $SetProductTempCart->ProTC_product_quantity;
                                                    } else {
                                                        echo 0;
                                                    }
                                                    ?>" onchange="change(27)" name="quan">
                                                        <a id="AddToPackage_<?php echo $GetProduct->PCP_product_id; ?>" style="float:right;" class="addtocart" href="javascript:AddToPackageFromchangingValue(<?php echo $GetProduct->PCP_product_id; ?>,<?php echo $GetProduct->PCP_package_id; ?>,<?php echo $GetProduct->PCP_package_category_id; ?>,<?php echo $GetProduct->product_inventory_id; ?>);" >

                                                            <?php
                                                            if ($GetProduct->product_quantity == 0) {
                                                                echo 'out of stock';
                                                            } else {
                                                                echo 'add to package';
                                                            }
                                                            ?>

                                                        </a>


                                                </div>


                                                <!--productQuantity-->
                                                <div class="favourite">
                                                    <a href="#">SAVE TO FAVES</a>
                                                </div><!--favourite-->
                                            </div><!--popupdescription-->
                                            <div class="clear"></div>
                                            <div class="fullViewLink " style="display:none">
                                                <a href="#">VIEW DETAILS</a>
                                            </div>
                                        </div>
                                    <?php endforeach; /*  ($data AS $d): */ ?>
                                <?php endif; /* ($dataCounter > 0): */ ?>


                                <!--end: poup boxes-->


                            </div>






                        </body>
                        </html>
