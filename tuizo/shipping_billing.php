<?php
include("config/config.php");
$session_id = session_id();
$subTotalPrice = 0;
if (!checkUserLogin()) {
    $err = "You need to login first.";
    $link = "sign_in.php?err=" . base64_encode($err);
    redirect($link);
}

/*  start package name retrieve */
$packageInformation = array();
$productQuantity = array();
$packageNameSql = "SELECT * FROM package_temp_cart WHERE PTC_session_id='$session_id' AND PTC_user_id = ".intval($_SESSION["user_id"]);
$packageNameSqlResult = mysqli_query($con, $packageNameSql);
if ($packageNameSqlResult) {
    while ($packageNameSqlResultRowObj = mysqli_fetch_object($packageNameSqlResult)) {
        $packageInformation[] = $packageNameSqlResultRowObj;
        $productQuantitySql = "SELECT count(*) AS number FROM product_temp_cart WHERE ProTC_PTC_package_id=" . $packageNameSqlResultRowObj->PTC_package_id . " AND ProTC_session_id='$session_id'";
        $productQuantitySqlResult = mysqli_query($con, $productQuantitySql);
        $productQuantitySqlResultRowObj = mysqli_fetch_object($productQuantitySqlResult);
        $productQuantity[] = $productQuantitySqlResultRowObj->number;
        //$pricePerpackageSql = "SELECT package_price FROM packages WHERE package_id = (SELECT )";
    }
}
/*  end package name retrieve */


/* start of retrieving package price */
$pricePerPackage = array();
$packagePriceSql = "SELECT package_price FROM packages WHERE package_id=(SELECT PTC_package_id FROM package_temp_cart WHERE PTC_session_id='$session_id')";
$packagePriceSqlResult = mysqli_query($con, $packagePriceSql);
if ($packagePriceSqlResult) {
    while ($packagePriceSqlResultRowObj = mysqli_fetch_object($packagePriceSqlResult)) {
        $pricePerPackage[] = $packagePriceSqlResultRowObj;
    }
}
/* end of retrieving package price */


/* Initializing Variables */
$datetime = date("Y-m-d H:i");
$user_address_title_shipping = '';
$first_name_shipping = '';
$middle_name_shipping = '';
$last_name_shipping = '';
$phone_number_shipping = '';
$best_call_time_shipping = '';
$counrty_id_shipping = '';
$city_id_shipping = '';
$zip_code_shipping = '';
$address_shipping = '';
$user_address_title_billing = '';
$first_name_billing = '';
$middle_name_billing = '';
$last_name_billing = '';
$phone_number_billing = '';
$best_call_time_billing = '';
$counrty_id_billing = '';
$city_id_billing = '';
$zip_code_billing = '';
$address_billing = '';
//Start of----------Retrieve User Address Title from Database
$userAddressTitleArray = array();
$userAddressTitleSql = "SELECT * FROM user_addresses";
$userAddressTitleSqlResult = mysqli_query($con, $userAddressTitleSql);
if ($userAddressTitleSqlResult) {
    while ($userAddressTitleSqlResultRowObj = mysqli_fetch_object($userAddressTitleSqlResult)) {
        $userAddressTitleArray[] = $userAddressTitleSqlResultRowObj;
    }
}
//End of----------Retrieve User Address Title from Database
//Start of----------Retrieve User country from Database
$userCountryArray = array();
$userCountrySql = "SELECT * FROM countries WHERE country_status='allow'";
$userCountrySqlResult = mysqli_query($con, $userCountrySql);
if ($userCountrySqlResult) {
    while ($userCountrySqlResultRowObj = mysqli_fetch_object($userCountrySqlResult)) {
        $userCountryArray[] = $userCountrySqlResultRowObj;
    }
}

//End of----------Retrieve User country from Database

if (isset($_POST["continue"]) && $_POST["continue"] == 'CONTINUE') {
    //print $_POST["first_name"];
    extract($_POST);
    if ($user_address_title_shipping == '') {
        $err = 'user_address_title_shipping is empty';
    } elseif ($first_name_shipping == '') {
        $err = 'First Name of Shipping is empty';
    } elseif ($last_name_shipping == '') {
        $err = 'Last Name of Shipping is empty';
    } elseif ($phone_number_shipping == '') {
        $err = 'Phone Number of Shipping is empty';
    } elseif ($counrty_id_shipping == '') {
        $err = 'Please select a country for Shipping';
    } elseif ($city_id_shipping == '') {
        $err = 'please select a city for Shipping';
    } elseif ($zip_code_shipping == '') {
        $err = 'Zip Code of Shipping is empty';
    } elseif ($address_shipping == '') {
        $err = 'Address of Shipping is empty';
    } elseif ($user_address_title_billing == '') {
        $err = 'user_address_title_shipping is empty';
    } elseif ($first_name_billing == '') {
        $err = 'First Name of billing is empty';
    } elseif ($last_name_billing == '') {
        $err = 'Last Name of billing is empty';
    } elseif ($phone_number_billing == '') {
        $err = 'Phone Number of billing is empty';
    }
//     elseif ($counrty_id_billing == '') {
//        $err = 'Please select a country for billing';
//    } elseif ($city_id_billing == '') {
//         $err = 'Please select a city for billing';
//    } 
    elseif ($zip_code_billing == '') {
        $err = 'Zip Code of billing is empty';
    } elseif ($address_billing == '') {
        $err = 'Address of billing is empty';
    }
    if ($err == '') {
        $userAddress = '';
        $userAddress .= ' UA_title = "' . mysqli_real_escape_string($con, $user_address_title_shipping) . '"';
        $userAddress .= ', UA_first_name = "' . mysqli_real_escape_string($con, $first_name_shipping) . '"';
        $userAddress .= ', UA_middle_name = "' . mysqli_real_escape_string($con, $middle_name_shipping) . '"';
        $userAddress .= ', UA_last_name = "' . mysqli_real_escape_string($con, $last_name_shipping) . '"';
        $userAddress .= ', UA_phone = "' . mysqli_real_escape_string($con, $phone_number_shipping) . '"';
        $userAddress .= ', UA_best_call_time = "' . mysqli_real_escape_string($con, $best_call_time_shipping) . '"';
        $userAddress .= ', UA_country_id = "' . mysqli_real_escape_string($con, $counrty_id_shipping) . '"';
        $userAddress .= ', UA_city_id = "' . mysqli_real_escape_string($con, $city_id_shipping) . '"';
        $userAddress .= ', UA_zip = "' . mysqli_real_escape_string($con, $zip_code_shipping) . '"';
        $userAddress .= ', UA_address = "' . mysqli_real_escape_string($con, $address_shipping) . '"';


        $userAddressBilling = '';
        $userAddressBilling .= ' UA_title = "' . mysqli_real_escape_string($con, $user_address_title_billing) . '"';
        $userAddressBilling .= ', UA_first_name = "' . mysqli_real_escape_string($con, $first_name_billing) . '"';
        $userAddressBilling .= ', UA_middle_name = "' . mysqli_real_escape_string($con, $middle_name_billing) . '"';
        $userAddressBilling .= ', UA_last_name = "' . mysqli_real_escape_string($con, $last_name_billing) . '"';
        $userAddressBilling .= ', UA_phone = "' . mysqli_real_escape_string($con, $phone_number_billing) . '"';
        $userAddressBilling .= ', UA_best_call_time = "' . mysqli_real_escape_string($con, $best_call_time_billing) . '"';
        $userAddressBilling .= ', UA_country_id = "' . mysqli_real_escape_string($con, $counrty_id_billing) . '"';
        $userAddressBilling .= ', UA_city_id = "' . mysqli_real_escape_string($con, $city_id_billing) . '"';
        $userAddressBilling .= ', UA_zip = "' . mysqli_real_escape_string($con, $zip_code_billing) . '"';
        $userAddressBilling .= ', UA_address = "' . mysqli_real_escape_string($con, $address_billing) . '"';
        //$userAddressBilling .= ', UA_updated = "' . mysqli_real_escape_string($con, $address_billing) . '"';

        if ($user_address_title_select_shipping != -1 && isset($same_address_generator)) {
            $userAddresssSql = "UPDATE user_addresses SET " . $userAddress . " WHERE UA_id=" . $user_address_title_select_shipping;
            $userAddresssSqlResult = mysqli_query($con, $userAddresssSql);
            if ($userAddresssSqlResult) {
                echo"<script>alert('only one address is updated');</script>";
            } else {
                echo 'error' . mysqli_error($con);
            }
        } elseif ($user_address_title_select_shipping == -1 && isset($same_address_generator)) {
            $userAddresssSql = "INSERT INTO user_addresses SET " . $userAddress;
            $userAddresssSqlResult = mysqli_query($con, $userAddresssSql);
            if ($userAddresssSqlResult) {
                echo"<script>alert('only one address is inserted');</script>";
            } else {
                echo 'error' . mysqli_error($con);
            }
        } elseif ($user_address_title_select_shipping != -1 && $user_address_title_select_billing != -1) {
            $userAddresssSql = "UPDATE user_addresses SET " . $userAddress . " WHERE UA_id=" . $user_address_title_select_shipping;
            $userAddresssBillingSql = "UPDATE user_addresses SET " . $userAddressBilling . " WHERE UA_id=" . $user_address_title_select_billing;
            $userAddresssSqlResult = mysqli_query($con, $userAddresssSql);
            $userAddresssBillingSqlResult = mysqli_query($con, $userAddresssBillingSql);
            if ($userAddresssBillingSqlResult && $userAddresssSqlResult) {
                echo"<script>alert('Shipping And Billing Both Updated');</script>";
            } else {
                echo 'error' . mysqli_error($con);
            }
        } elseif ($user_address_title_select_shipping != -1 && $user_address_title_select_billing == -1) {
            $userAddresssSql = "UPDATE user_addresses SET " . $userAddress . " WHERE UA_id=" . $user_address_title_select_shipping;
            $userAddresssBillingSql = "INSERT INTO user_addresses SET " . $userAddressBilling;
            $userAddresssSqlResult = mysqli_query($con, $userAddresssSql);
            $userAddresssBillingSqlResult = mysqli_query($con, $userAddresssBillingSql);
            if ($userAddresssBillingSqlResult && $userAddresssSqlResult) {
                echo"<script>alert('Shipping Updated And Billing Inserted');</script>";
            } else {
                echo 'error' . mysqli_error($con);
            }
        } elseif ($user_address_title_select_shipping == -1 && $user_address_title_select_billing == -1) {
            $userAddresssSql = "INSERT INTO user_addresses SET " . $userAddress;
            $userAddresssBillingSql = "INSERT INTO user_addresses SET " . $userAddressBilling;
            $userAddresssSqlResult = mysqli_query($con, $userAddresssSql);
            $userAddresssBillingSqlResult = mysqli_query($con, $userAddresssBillingSql);
            if ($userAddresssBillingSqlResult && $userAddresssSqlResult) {
                echo"<script>alert('Both address are Inserted');</script>";
            } else {
                echo 'error' . mysqli_error($con);
            }
        } elseif ($user_address_title_select_shipping == -1 && $user_address_title_select_billing != -1) {
            $userAddresssSql = "INSERT INTO user_addresses SET " . $userAddress;
            $userAddresssBillingSql = "UPDATE user_addresses SET " . $userAddressBilling . " WHERE UA_id=" . $user_address_title_select_shipping;
            $userAddresssSqlResult = mysqli_query($con, $userAddresssSql);
            $userAddresssBillingSqlResult = mysqli_query($con, $userAddresssBillingSql);
            if ($userAddresssBillingSqlResult && $userAddresssSqlResult) {
                echo"<script>alert('Shipping Inserted And Billing Updated');</script>";
            } else {
                echo 'error' . mysqli_error($con);
            }
        }

        // Start Of Data Passing to the orders Table
        //Shipping Address Insertion
        $ordersInsert = '';
        // $ordersInsert .= '  = "' . mysqli_real_escape_string($con, $user_address_title_shipping) . '"';

        $ordersInsert .= ' order_user_id = "' . mysqli_real_escape_string($con, $_SESSION["user_id"]) . '"';
        $ordersInsert .= ', order_created = "' . mysqli_real_escape_string($con, $datetime) . '"';
        $ordersInsert .= ', order_session_id = "' . mysqli_real_escape_string($con, $session_id) . '"';
        $ordersInsert .= ', order_shipping_first_name = "' . mysqli_real_escape_string($con, $first_name_shipping) . '"';
        $ordersInsert .= ', order_shipping_middle_name = "' . mysqli_real_escape_string($con, $middle_name_shipping) . '"';
        $ordersInsert .= ', order_shipping_last_name = "' . mysqli_real_escape_string($con, $last_name_shipping) . '"';
        $ordersInsert .= ', order_shipping_phone = "' . mysqli_real_escape_string($con, $phone_number_shipping) . '"';
        //$ordersInsert .= ', UA_best_call_time = "' . mysqli_real_escape_string($con, $best_call_time_shipping) . '"';
        $ordersInsert .= ', order_shipping_country = "' . mysqli_real_escape_string($con, $counrty_id_shipping) . '"';
        $ordersInsert .= ', order_shipping_city = "' . mysqli_real_escape_string($con, $city_id_shipping) . '"';
        $ordersInsert .= ', order_shipping_zip = "' . mysqli_real_escape_string($con, $zip_code_shipping) . '"';
        $ordersInsert .= ', order_shipping_address = "' . mysqli_real_escape_string($con, $address_shipping) . '"';

        //Billing Address Insertion
        //$ordersInsert .= ' UA_title = "' . mysqli_real_escape_string($con, $user_address_title_billing) . '"';
        $ordersInsert .= ', order_billing_first_name = "' . mysqli_real_escape_string($con, $first_name_billing) . '"';
        $ordersInsert .= ', order_billing_middle_name = "' . mysqli_real_escape_string($con, $middle_name_billing) . '"';
        $ordersInsert .= ', order_billing_last_name = "' . mysqli_real_escape_string($con, $last_name_billing) . '"';
        $ordersInsert .= ', order_billing_phone = "' . mysqli_real_escape_string($con, $phone_number_billing) . '"';
        //$userAddressBilling .= ', UA_best_call_time = "' . mysqli_real_escape_string($con, $best_call_time_billing) . '"';
        $ordersInsert .= ', order_billing_country = "' . mysqli_real_escape_string($con, $counrty_id_billing) . '"';
        $ordersInsert .= ', order_billing_city = "' . mysqli_real_escape_string($con, $city_id_billing) . '"';
        $ordersInsert .= ', order_billing_zip = "' . mysqli_real_escape_string($con, $zip_code_billing) . '"';
        $ordersInsert .= ', order_billing_address = "' . mysqli_real_escape_string($con, $address_billing) . '"';
        //$userAddressBilling .= ', UA_updated = "' . mysqli_real_escape_string($con, $address_billing) . '"';
        $ordersInsertSql = 'INSERT INTO orders SET ' . $ordersInsert;
        $ordersInsertSqlResult = mysqli_query($con, $ordersInsertSql);
        if ($ordersInsertSqlResult) {
           
            /** Start get order_id Order_user_id * */
            $last_order_id = mysqli_insert_id($con);
            $last_order_user_id = '';
            $get_order_user_sql = "SELECT * FROM orders WHERE order_id=" . intval($last_order_id);
            $result_order_user = mysqli_query($con, $get_order_user_sql);
            if ($result_order_user) {
                while ($order_user_row = mysqli_fetch_object($result_order_user)) {
                    $last_order_user_id = $order_user_row->order_user_id;
                }
            } else {
                echo "result_order_userError:" . mysqli_error($con);
            }
            /** Start get order_id Order_user_id * */
             /** Start: Find the package temp cart data for order package table * */
            $temp_package_cart_of_order_package_sql = "SELECT * FROM package_temp_cart WHERE PTC_session_id='$session_id'";
            $result_of_order_package = mysqli_query($con, $temp_package_cart_of_order_package_sql);
            if ($result_of_order_package) {
                while ($getOrderPackage = mysqli_fetch_object($result_of_order_package)) {
                    $package_order_insert_field = '';
                    $package_order_insert_field .= ' OPA_order_id = "' . mysqli_real_escape_string($con, $last_order_id) . '"';
                    $package_order_insert_field .= '  ,OPA_user_id = "' . mysqli_real_escape_string($con, $last_order_user_id) . '"';
                    $package_order_insert_field .= '  ,OPA_package_id= "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_id) . '"';
                    $package_order_insert_field .= '  ,OPA_package_name= "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_name) . '"';
                    $package_order_insert_field .= '  ,OPA_package_price = "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_price) . '"';
                    $package_order_insert_field .= '  ,OPA_package_discount = "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_discount) . '"';
                    $package_order_insert_field .= '  ,OPA_package_tax_class_id = "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_tax_class_id) . '"';
                    $package_order_insert_field .= '  ,OPA_package_category_id = "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_category_id) . '"';
                    $package_order_insert_field .= '  ,OPA_package_quantity = "' . mysqli_real_escape_string($con, $getOrderPackage->PTC_package_quantity) . '"';

                    $order_packages_sql = "INSERT INTO order_packages SET $package_order_insert_field";
                    $result_order_package = mysqli_query($con, $order_packages_sql);
                    if (!$result_order_package) {
                        echo "result_of_order_packageError:" . mysqli_error($con);
                    }
                }
            } else {
                echo "result_of_order_packageError:" . mysqli_error($con);
            }
            /** End: Find the package temp cart data for order package table * */
            /** Start : find the product temp cart data and insert ot order product * */
            $temp_product_cart_sql = "SELECT * FROM product_temp_cart WHERE ProTC_session_id='$session_id'";

            $result_temp_cart = mysqli_query($con, $temp_product_cart_sql);
            if ($result_temp_cart) {
                while ($getProduct = mysqli_fetch_object($result_temp_cart)) {
                    $product_order_insert_field = '';
                    $product_order_insert_field .= '   OP_order_id = "' . mysqli_real_escape_string($con, $last_order_id) . '"';
                    $product_order_insert_field .= '  ,OP_user_id = "' . mysqli_real_escape_string($con, $last_order_user_id) . '"';
                    $product_order_insert_field .= '  ,OP_product_id = "' . mysqli_real_escape_string($con, $getProduct->ProTC_product_id) . '"';
                    $product_order_insert_field .= '  ,OP_package_id = "' . mysqli_real_escape_string($con, $getProduct->ProTC_PTC_package_id) . '"';
                    $product_order_insert_field .= '  ,OP_category_id = "' . mysqli_real_escape_string($con, $getProduct->ProTC_product_category_id) . '"';
                    $product_order_insert_field .= '  ,OP_product_inventory_id = "' . mysqli_real_escape_string($con, $getProduct->ProTC_product_inventory_id) . '"';
                    $product_order_insert_field .= '  ,OP_color_id = "' . mysqli_real_escape_string($con, $getProduct->ProTC_color_id) . '"';
                    $product_order_insert_field .= '  ,OP_size_id = "' . mysqli_real_escape_string($con, $getProduct->ProTC_size_id) . '"';
                    $product_order_insert_field .= '  ,OP_product_quantity = "' . mysqli_real_escape_string($con, $getProduct->ProTC_product_quantity) . '"';

                    $product_order_sql = "INSERT INTO order_products SET $product_order_insert_field";
                    $result_order_product = mysqli_query($con, $product_order_sql);
                    if (!$result_order_product) {
                        echo "result_order_productError:" . mysqli_error($con);
                    }
                }
            } else {
                echo 'result_temp_cartError:' . mysqli_error($con);
            }
            /** End : find the product temp cart data and insert ot order product * */
            echo"<script>alert('successfully inserted to the order table,order package, order product')</script>";
        } else {
            echo "problem" . mysqli_error($con);
        }
        
        // End Of Data Passing to the orders Table
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title> </title>
<?php
include("header.php");
?>
    </head>

    <body>

        <div id="wrapper">

<?php
include("menu.php");
?>

            <div id="innerContainer">
                <div class="shippingBillingContainner">

                    <form action="<?php echo baseUrl('shipping_billing.php'); ?>" name="shippingBilling" method="post">


<?php if ($err != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage"><?php echo $err; ?></p></div><!--formError-->
<?php endif; /* ($err !='') */ ?>
<?php if ($msg != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: green;"><?php echo $msg; ?></p></div><!--formError-->
            <?php endif; /* ($err !='') */ ?>
                        <div class="shippingBillingForm">
                            <h3 style="padding-bottom:50px;">Shipping Address </h3>

                            <table width="100%" border="0" id='user_address_title_select_shipping' >
                                <tr>
                                    <td width="30%"><span>Select User Address Title</span></td>
                                    <td width="70%">
                                        <select name="user_address_title_select_shipping" class="addresses" >
                                            <option value='-1'>Add New</option>
                        <?php
                        $userAddressTitleArrayCount = count($userAddressTitleArray);
                        if ($userAddressTitleArray > 0) {
                            for ($i = 0; $i < $userAddressTitleArrayCount; $i++) {
                                echo"<option value=" . $userAddressTitleArray[$i]->UA_id . ">" . $userAddressTitleArray[$i]->UA_title . "</option>";
                            }
                        }
                        ?>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>User Address Title*</span></td>
                                    <td width="70%"><input type="text" name="user_address_title_shipping" class='user_address_title'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>First Name*</span></td>
                                    <td width="70%"><input type="text" name="first_name_shipping" class='first_name'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Middle Name*</span></td>
                                    <td width="70%"><input type="text" name="middle_name_shipping" class='middle_name'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Last Name*</span></td>
                                    <td width="70%"><input type="text" name="last_name_shipping" class='last_name'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Phone Number*</span></td>
                                    <td width="70%"><input type="text" name="phone_number_shipping" class='phone_number'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Best Call Time*</span></td>
                                    <td width="70%"><input type="text" name="best_call_time_shipping" class='best_call_time'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Select Country</span></td>
                                    <td width="70%">
                                        <select name="counrty_id_shipping" class='counrty_id'>
                                            <option value="-1">Select</option>
<?php
$userCountryArrayCount = count($userCountryArray);
if ($userCountryArrayCount > 0) {
    for ($i = 0; $i < $userCountryArrayCount; $i++) {
        echo"<option value=" . $userCountryArray[$i]->country_id . ">" . $userCountryArray[$i]->country_name . "</option>";
    }
}
?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Select City*</span></td>
                                    <td width="70%">
                                        <select name="city_id_shipping" class="city_id">
                                            <option value="-1">Select</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Zip Code</span></td>
                                    <td width="70%"><input type="text"  name="zip_code_shipping" class="zip_code"></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Address</span></td>
                                    <td width="70%"><textarea   name="address_shipping" class="address"></textarea></td>
                                </tr>


                            </table>


                        </div><!--shippingBillingForm-->


                        <div class="shippingBillingForm billingForm">
                            <h3>Billing Address </h3>
                            <div class="billingcheckbox">
                                <input type="checkbox" value="-1" id="checkboxForInput" name="same_address_generator" />
                                <label for="checkboxForInput"></label>
                            </div>
                            <label for="checkboxForInput">  
                                <p class="billingCheck">Deliver to the same address</p>
                            </label>



                            <table width="100%" border="0" id='user_address_title_select_billing'>
                                <tr>
                                    <td width="30%"><span>Select User Address Title</span></td>
                                    <td width="70%">
                                        <select name="user_address_title_select_billing" class="addresses" >
                                            <option value='-1'>Add New</option>
<?php
$userAddressTitleArrayCount = count($userAddressTitleArray);
if ($userAddressTitleArray > 0) {
    for ($i = 0; $i < $userAddressTitleArrayCount; $i++) {
        echo"<option value=" . $userAddressTitleArray[$i]->UA_id . ">" . $userAddressTitleArray[$i]->UA_title . "</option>";
    }
}
?>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>User Address Title*</span></td>
                                    <td width="70%"><input type="text" name="user_address_title_billing" class='user_address_title'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>First Name*</span></td>
                                    <td width="70%"><input type="text" name="first_name_billing" class='first_name'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Middle Name*</span></td>
                                    <td width="70%"><input  type="text" name="middle_name_billing" class='middle_name'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Last Name*</span></td>
                                    <td width="70%"><input type="text" name="last_name_billing" class='last_name'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Phone Number*</span></td>
                                    <td width="70%"><input type="text" name="phone_number_billing" class='phone_number'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Best Call Time*</span></td>
                                    <td width="70%"><input type="text" name="best_call_time_billing" class='best_call_time'></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Select Country</span></td>
                                    <td width="70%">
                                        <select name="counrty_id_billing" class='counrty_id'>
                                            <option value="-1">Select</option>
<?php
$userCountryArrayCount = count($userCountryArray);
if ($userCountryArrayCount > 0) {
    for ($i = 0; $i < $userCountryArrayCount; $i++) {
        echo"<option value=" . $userCountryArray[$i]->country_id . ">" . $userCountryArray[$i]->country_name . "</option>";
    }
}
?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Select City*</span></td>
                                    <td width="70%">
                                        <select name="city_id_billing" class="city_id">
                                            <option value="-1">Select</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Zip Code</span></td>
                                    <td width="70%"><input type="text"  name="zip_code_billing" class="zip_code"></td>
                                </tr>
                                <tr>
                                    <td width="30%"><span>Address</span></td>
                                    <td width="70%"><textarea   name="address_billing" class="address"></textarea></td>
                                </tr>

                            </table>
                        </div><!--shippingBillingForm-->
                        <div class="clear"></div>


                        <div class="checkoutHeading"><h6>payment method</h6></div><!--checkoutHeading-->
                        <div class="paymentMethod">
                            <ul id="paymentMethod" class="accordion">
                                <li>
                                    <input type="radio" name="creditCard" /><a style="text-transform:uppercase; padding-left:5px; font-size:12px;" href="#">Credit card</a>
                                    <div class="panel loading">
                                        <div class="creditCard">
                                            <div class="cartBottomInnerRight paymentCard">
                                                <a class="visa" href="#">visa</a>
                                                <a class="mscard" href="#">mscard</a>
                                                <a class="mscard2" href="#">visa</a>
                                                <a class="discover" href="#">pay</a>
                                            </div>
                                            <span>Supported</span>
                                            <span>Credit Cards</span>
                                            <div class="paymentInput">
                                                <label for="Number">Credit Card Number *</label><br />
                                                <input type="text" name="Number" />
                                            </div><!--paymentInput-->
                                            <div class="paymentInput">
                                                <label for="name">Name on Credit Card *</label><br />
                                                <input type="text" name="name" />
                                            </div><!--paymentInput-->
                                            <div class="paymentInput">
                                                <label for="name">Expiration date *</label><br />
                                                <select name="expire">
                                                    <option selected="selected" value="">Month</option>
                                                    <option value="1">01 - January</option>
                                                    <option value="2">02 - February</option>
                                                    <option value="3">03 - March</option>
                                                    <option value="4">04 - April</option>
                                                    <option value="5">05 - May</option>
                                                    <option value="6">06 - June</option>
                                                    <option value="7">07 - July</option>
                                                    <option value="8">08 - August</option>
                                                    <option value="9">09 - September</option>
                                                    <option value="10">10 - October</option>
                                                    <option value="11">11 - November</option>
                                                    <option value="12">12 - December</option>
                                                </select>

                                                <select name="year" style="width:100px;">
                                                    <option selected="selected" value="">Year</option>
                                                    <option value="2013">2013</option>
                                                    <option value="2014">2014</option>
                                                    <option value="2015">2015</option>
                                                    <option value="2016">2016</option>
                                                    <option value="2017">2017</option>
                                                    <option value="2018">2018</option>
                                                    <option value="2019">2019</option>
                                                    <option value="2020">2020</option>
                                                    <option value="2021">2021</option>
                                                    <option value="2022">2022</option>
                                                    <option value="2023">2023</option>
                                                </select>
                                            </div><!--paymentInput-->

                                            <div class="paymentInput">
                                                <label for="name">Verification Code *</label><br />
                                                <input style="width:30px;float:left;" type="text" name="name" />
                                                <a href="#">What is Verification Code?</a>
                                            </div><!--paymentInput-->

                                            <div class="savinfobtm"> <input id="saveInfoid" type="checkbox" name="saveInfo" /><label for="saveInfoid">&nbsp;Save my Card information</label>

                                            </div>


                                        </div><!--creditCard-->
                                    </div>
                                </li>
                                <li>
                                    <input type="radio" name="creditCard" checked/><a style="text-transform:uppercase; padding-left:5px; font-size:12px;" href="#">CASH ON DELIVERY</a>
                                    <ul class="panel loading">

                                        <p style="padding:10px 20px;
                                           font-size:12px;
                                           color:#666;">Pay using our Cash On Delivery service. <br />Payment is done directly to the courier<br /> upon delivery.</p>

                                    </ul>
                                </li>
                                <li>
                                    <input type="radio" name="creditCard" /><a style="text-transform:uppercase; padding-left:5px; font-size:12px;" href="#">PAYPAL</a>

                                    <ul class="panel loading">
                                        <a style="padding:5px 5px 0 5px;
                                           font-size:12px;
                                           color:#247298;" href="#">What is Paypal ?</a>
                                        <p style="padding:5px 20px;
                                           font-size:12px;
                                           color:#666;">Pay using your PayPal account. <br />You will be redirected to the PayPal<br /> system to complete the payment</p>
                                    </ul>

                                </li>

                            </ul>
                        </div><!--paymentMethod-->
                        <div class="clear"></div>
                        <div class="checkoutHeading"><h6>summery</h6></div><!--checkoutHeading-->
                        <div class="productSummery">
                            <table class="productSummeryTable" width="50%" border="0">
                                <tr class="summeryHeading">
                                    <td>Package</td>
                                    <td>Total Package</td>
                                    <td>Total Product</td>
                                    <td>Price</td>
                                </tr>
                                <tr class="summeryDescription"><td>
<?php
$packageInformationCount = count($packageInformation);
if ($packageInformationCount > 0) {
    for ($i = 0; $i < $packageInformationCount; $i++) {
        echo "<p>" . $packageInformation[$i]->PTC_package_name . "</p>";
    }
}
?>
                                    </td>
                                    <td><?php
for ($i = 0; $i < $packageInformationCount; $i++) {
    echo "<p>" . $packageInformation[$i]->PTC_package_quantity . "</p>";
}
?>
                                    </td>
                                    <td><strong><?php
                                        $productQuantityCount = count($productQuantity);
                                        if ($productQuantityCount > 0) {
                                            for ($i = 0; $i < $productQuantityCount; $i++) {
                                                echo("<p>" . $productQuantity[$i] . "</p>");
                                            }
                                        }
                                        ?></strong></td>
                                    <td><?php
                                        if ($packageInformationCount > 0) {
                                            for ($i = 0; $i < $packageInformationCount; $i++) {
                                                echo( "<p>" . $packageInformation[$i]->PTC_package_price * $packageInformation[$i]->PTC_package_quantity . " tk</p>");
                                                $subTotalPrice += ( $packageInformation[$i]->PTC_package_price * $packageInformation[$i]->PTC_package_quantity);
                                            }
                                        }
                                        ?></td>
                                </tr>

                                <tr class="summerytotal">
                                    <td><span>sub total</span></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><?php echo$subTotalPrice; ?> tk</td>
                                </tr>
                            </table>
                            <div style="clear:both"></div>

                        </div><!--productSummery-->
                        <input type="submit" name="continue" style="float:right;" class="signUpNext" value="CONTINUE">
                    </form>

                </div><!--shippingBillingContainner-->
            </div><!--innerContainer-->
        </div>

<?php
include("footer.php");
?>


        <script type="text/javascript">

            /* Start: block if import*/
            /* if change any input */
            $("#user_address_title_select_shipping input, textarea").on('keyup', function() {
                /* if both address title dropdown value same  */
                if ($("#user_address_title_select_shipping .addresses").val() == $("#user_address_title_select_billing .addresses").val() && $("#user_address_title_select_billing .addresses").val() != -1) {
                    var inputClass = $(this).attr('class');
                    $('.' + inputClass).val($(this).val());
                }
            });

            /* if change any dropdown except address title dropdown */
            $("#user_address_title_select_shipping select").not("#user_address_title_select_shipping select[name='user_address_title_select_shipping']").on('change', function() {
                /* if both address title dropdown value same  */
                if ($("#user_address_title_select_shipping .addresses").val() == $("#user_address_title_select_billing .addresses").val() && $("#user_address_title_select_billing .addresses").val() != -1) {
                    var selectClass = $(this).attr('class');
                    $('.' + selectClass + " option[value=" + $(this).val() + "]").attr('selected', 'selected');
                }
            });

            /* End: block if import*/


            /* Start: import process */
            /* if want to import from shipping data  */
            $("#checkboxForInput").on('change', function() {
                if ($("#user_address_title_select_shipping .addresses").val() != -1) {
                    if ((this.checked)) {
                        var shippingAddreesId = $(this).val();

                        $("#user_address_title_select_billing .addresses option[value=" + shippingAddreesId + "]").attr('selected', 'selected');
                        $("#user_address_title_select_billing .addresses").trigger('change');
                        $("#user_address_title_select_billing .addresses").blur();

                        $("#checkboxForInput").attr('checked', true);
                        $("#user_address_title_select_billing input").attr('readonly', true);
                        $("#user_address_title_select_billing textarea").attr('readonly', true);
                        $("#user_address_title_select_billing select").attr("disabled", "disabled");
                        /* readonly:  both billing and shipping should be same because imported from billing  */



                    } else {
                        var shippingAddreesId = $(this).val();
                        $("#user_address_title_select_billing .addresses option[value='-1']").attr('selected', 'selected');
                        $("#user_address_title_select_billing .addresses").trigger('change');
                        $("#user_address_title_select_billing .addresses").blur();
                        $("#checkboxForInput").attr('checked', false);
                        $("#user_address_title_select_billing input").attr('readonly', false);
                        $("#user_address_title_select_billing textarea").attr('readonly', false);
                        $("#user_address_title_select_billing select").attr("disabled", false);
                        $(this).val(shippingAddreesId); /* set shipping address again to import if want  */
                    }
                } else {
                    if ($("#checkboxForInput").attr('checked', true)) {
                        //Start code for input and input And textbox

                        var inputCount = $('#user_address_title_select_shipping input').length;
                        for (var i = 0; i < inputCount; i++) {
                            var shippingValue = $("#user_address_title_select_shipping input:eq(" + i + ")").val();
                            $("#user_address_title_select_billing input:eq(" + i + ")").val(shippingValue);
                            $("#user_address_title_select_billing input:eq(" + i + ")").attr('readonly', true);
                        }
                        var userAddress = $("#user_address_title_select_shipping textarea").val();
                        $("#user_address_title_select_billing textarea").val(userAddress);
                        $("#user_address_title_select_billing textarea").attr('readonly', true);

                        //End code for input and input And textbox

                        //Start code for select

                        var shippingCountryId = $("#user_address_title_select_shipping .counrty_id").val();
                        $("#user_address_title_select_billing .counrty_id option[value=" + shippingCountryId + "]").attr('selected', 'selected');
                        $("#user_address_title_select_billing .counrty_id").trigger('change');
                        var shippingCityId = $("#user_address_title_select_shipping .city_id").val();
                        $("#user_address_title_select_billing .city_id option[value=" + shippingCityId + "]").attr('selected', 'selected');
                        //End code for select
                    } else {
                        var inputCount = $('#user_address_title_select_shipping input').length;
                        for (var i = 0; i < inputCount; i++) {
                            //var shippingValue = $("#user_address_title_select_shipping input:eq(" + i + ")").val();
                            $("#user_address_title_select_billing input:eq(" + i + ")").val('');
                            $("#user_address_title_select_billing input:eq(" + i + ")").attr('readonly', false);
                            $("#user_address_title_select_billing textarea").val('');
                            $("#user_address_title_select_billing textarea").attr('readonly', false);
                        }
                    }
                }

            });
            /* End : import process */



            //Start of Getting Information form users_address table according to user address title for shipping
            $(".addresses").on("change", function() {
                var addressId = $(this).val();

                // checkboxForInput for import for shipping
                $("#checkboxForInput").val(addressId);
                $("#checkboxForInput").attr('checked', false);

                $table = $(this).closest('table');
                var tableId = $table.attr('id');

                $.post("ajax/shipping_billing.php", {useraddressid: addressId}, function(result) {
                    var obj = jQuery.parseJSON(result);
                    //console.log(obj);
                    if (obj.UA_title) {

                        $("#" + tableId + " .user_address_title").val(obj.UA_title);
                        $("#" + tableId + " .first_name").val(obj.UA_first_name);
                        $("#" + tableId + " .middle_name").val(obj.UA_middle_name);
                        $("#" + tableId + " .last_name").val(obj.UA_last_name);
                        $("#" + tableId + " .phone_number").val(obj.UA_phone);
                        $("#" + tableId + " .best_call_time").val(obj.UA_best_call_time);
                        $("#" + tableId + " .counrty_id option[value=" + obj.UA_country_id + "]").attr('selected', 'selected');
                        $("#" + tableId + " .zip_code").val(obj.UA_zip);
                        $("#" + tableId + " .address").val(obj.UA_address);
                        //$("#" + tableId + " .city_id").val(obj.UA_city_id);
                        if (obj.cities) {
                            $("#" + tableId + " .city_id").html('<option value="-1">Select</option>');
                            for (var i = 0; i < obj.cities.length; i++) {
                                // $("#" + tableId + " .city_id").val(obj.UA_city_id);
                                if (obj.cities[i].city_id == obj.UA_city_id) {
                                    $("#" + tableId + " .city_id").append('<option value="' + obj.cities[i].city_id + '" selected="selected">' + obj.cities[i].city_name + '</option>');
                                } else {
                                    $("#" + tableId + " .city_id").append('<option value="' + obj.cities[i].city_id + '">' + obj.cities[i].city_name + '</option>');
                                }

                            }
                        }
                    } else {
                        /* if ajax return empty array  */
                        $("#" + tableId + " .user_address_title").val('');
                        $("#" + tableId + " .first_name").val('');
                        $("#" + tableId + " .middle_name").val('');
                        $("#" + tableId + " .last_name").val('');
                        $("#" + tableId + " .phone_number").val('');
                        $("#" + tableId + " .best_call_time").val('');
                        $("#" + tableId + " .counrty_id option[value='-1']").attr('selected', 'selected');
                        $("#" + tableId + " .city_id").html('<option value="-1">Select</option>');
                        $("#" + tableId + " .zip_code").val('');
                        $("#" + tableId + " .address").val('');
                    }
                });
            });
            //End of Getting Information form users_address table according to user address title for shipping


            //Start of Getting Cities form country table for Shipping

            $(".counrty_id").on("change", function() {
                if ($("#checkboxForInput").attr('checked', false)) {
                    var countryId = $(this).val();
                    $table = $(this).closest('table');
                    var tableId = $table.attr('id');
                    $.post("ajax/shipping_billing.php", {countryid: countryId}, function(result) {
                        $("#" + tableId + " .city_id").html(result);
                    });
                }
            });
            //End of Getting Cities form country table for Shipping

//            //Start of Getting Cities form country table for billing
//            $("#counrty_id_billing").on("change", function() {
//                txt = $(this).val();
//                $.post("ajax/shipping_billing.php", {countryid: txt}, function(result) {
//                    $("#city_id_billing").html(result);
//                });
//            });
//            //End of Getting Cities form country table for billing


            //my part
            $("#user_address_title_select_shipping input, textarea").on('keyup', function() {
                if ($("#checkboxForInput").attr('checked')) {
                    /* if both address title dropdown value same  */
                    var inputClass = $(this).attr('class');
                    $('.' + inputClass).val($(this).val());
                    $('#user_address_title_select_billing .' + inputClass).attr('readonly', true);
                }
            });
            $("#checkboxForInput").on('click', function() {
                if ($("#checkboxForInput").attr('checked')) {
                    var inputCount = $('#user_address_title_select_shipping input').length;
                    for (var i = 0; i < inputCount; i++) {
                        var shippingValue = $("#user_address_title_select_shipping input:eq(" + i + ")").val();
                        $("#user_address_title_select_billing input:eq(" + i + ")").val(shippingValue);
                        //document.write($('input',"#user_address_title_select_shipping:nth-child("+i+")").val());
                    }
                }
            });
            //my part





        </script>
        <script type="text/javascript" src="js/jquery.accordion.2.0.js" charset="utf-8"></script>
        <script type="text/javascript">
            $('#paymentMethod').accordion({
                canToggle: true
            });

            $(".loading").removeClass("loading");
        </script>

    </body>
</html>