<?php
//session_start();
include("config/config.php");
//$session_id = session_id();
//exit();
$pageTitle = 'Tuizo | Home';
$pageDescription = 'Tuizo Home';
$pageKeywords = 'Tuizo, bangladesh, Garments';
$package_show_query = '';

if (isset($_REQUEST["logout"]) && $_REQUEST["logout"] == 'true') {

    //session_regenerate_id(true);
    $hashKeyUpdateSql = "UPDATE users SET user_hash='0' WHERE user_id=".intval($_SESSION["user_id"]);
    $hashKeyUpdateSqlResult = mysqli_query($con, $hashKeyUpdateSql);
    if ($hashKeyUpdateSqlResult) {
        UserLogout();
    }
}
if (isset($_REQUEST["redirect"]) && $_REQUEST["redirect"] == 'true') {

        UserLogout();
}


if (isset($_GET['package_user_type']) && $_GET['package_user_type'] == 'man') {

    //getting all man's package information
    $SqlPackageResult = "SELECT * FROM packages WHERE package_status='active' AND package_user_type='man' AND package_expiery >= CURDATE() ORDER BY package_order_by";
} elseif (isset($_GET['package_user_type']) && $_GET['package_user_type'] == 'woman') {

    //getting all woman's package information
    $SqlPackageResult = "SELECT * FROM packages WHERE package_status='active' AND package_user_type='woman' AND package_expiery >= CURDATE() ORDER BY package_order_by";
} else {

    //getting all package information
    $SqlPackageResult = "SELECT * FROM packages WHERE package_status='active' AND package_expiery >= CURDATE() ORDER BY package_order_by";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>

        <title><?php echo $pageTitle; ?> </title>
        <meta name="description" content="<?php echo $pageDescription; ?>">
            <meta name="keywords" content="<?php echo $pageKeywords ?>">
                <meta name="author" content="Ståle Refsnes">
                    <?php include(basePath('header.php')); ?>

                    </head>

                    <body >

                        <div id="wrapper">

                            <?php include("menu.php"); ?>

                            <div style="clear:both"></div>


                            <div id="homeContent" class="clearfix">

                                <div class="intorotitle">
                                    <h3>choose your package</h3>
                                    <p class="sub">Delivered to your door in 1 days</p>
                                </div>

                            </div><!-- header end -->

                            <div class="MainContainer clearfix">
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
                                                    $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                                    $('#cartHref_viewCart').attr({ href:'<?php echo baseUrl('cart.php'); ?>'});
                                                } else if (result.output_type == 2) {
                                                    $('#wholeCart').html(result.total_cart);
                                                    $('#packageQuantity_' + Package_ID).text(result.current_package);
                                                    $('#packagePrice_' + Package_ID).text(result.package_price);
                                                    $('#totalPackageCount').text(result.total_package);
                                                    $('#cartHref').attr({href: '<?php echo baseUrl('shipping_billing.php'); ?>'});
                                                    $('#cartHref_viewCart').attr({ href:'<?php echo baseUrl('cart.php'); ?>'});
                                                }

                                            }
                                        });
                                    }
                                </script>
                                <!-- End: Add to Cart Function --------->

                                <?php
                                $GetPackageResult = mysqli_query($con, $SqlPackageResult);
                                if ($GetPackageResult) {
                                    if (mysqli_num_rows($GetPackageResult) > 0) {
                                        while ($SetPackageResult = mysqli_fetch_object($GetPackageResult)) {
                                            ?>
                                            <div class="package">
                                                <img src="images/<?php if ($SetPackageResult->package_catagory_id == 3) {
                                    echo 'premimum.png';
                                } else {
                                    echo 'basic.png';
                                } ?>" alt="<?php if ($SetPackageResult->package_catagory_id == 3) {
                                    echo 'basic';
                                } else {
                                    echo 'premimum';
                                } ?>" class="<?php if ($SetPackageResult->package_catagory_id == 3) {
                                    echo 'basic';
                                } else {
                                    echo 'premimum';
                                } ?>" />

                                                <p class="pkgmenu" align="center">
                                                    <a class="viewpkg" href="package.php?package_id=<?php echo $SetPackageResult->package_id ?>&package_name=<?php echo clean($SetPackageResult->package_name); ?>"> <img align="absmiddle" width="" src="images/pkg_icon.png" alt="pkg_icon">VIEW PACKAGE </a>
                                                    <a class="addtocart" href="javascript:PackageAddToCart(<?php echo $SetPackageResult->package_id; ?>)"> <img align="absmiddle" width="" src="images/cart_icon.png" alt="cart_icon">ADD TO CART </a>
                                                </p>            
                                                <div class="imginner">
                                                    <img src="<?php echo $config['IMAGE_UPLOAD_URL'] . '/packages/' . $SetPackageResult->package_id . '/' . $SetPackageResult->package_banner; ?>" alt="<?php echo $SetPackageResult->package_name; ?>">
                                                </div>
                                            </div>
            <?php
        }
    }
}
?>

                            </div>
                        </div>

<?php include(basePath('footer.php')); ?>




                    </body>
                    </html>