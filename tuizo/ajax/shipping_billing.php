<?php

include_once("../config/config.php");

//Start Of Getting Cities With Country id
if (isset($_POST["countryid"])) {
    $cityArray = array();
    $countryId = $_POST["countryid"];
    $selectCitySql = "SELECT * FROM cities WHERE city_country_id='$countryId' && city_status='allow'";
    $selectCitySqlResult = mysqli_query($con, $selectCitySql);
    if ($selectCitySqlResult) {
        while ($selectCitySqlResultRowObj = mysqli_fetch_object($selectCitySqlResult)) {
            $cityArray[] = $selectCitySqlResultRowObj;
        }
    }
    echo"<option value = '-1'>Select</option>";
    $cityArrayCount = count($cityArray);
    if ($cityArrayCount > 0) {

        for ($i = 0; $i < $cityArrayCount; $i++) {
            $cities .="<option value=" . $cityArray[$i]->city_id . ">" . $cityArray[$i]->city_name . "</option>";
        }
        echo $cities;
    }
}

//End Of Getting Cities With Country id

if (isset($_POST["useraddressid"])) {
    $userAddressInfoArray = array();
    $userAddressId = $_POST["useraddressid"];
    $userAddressInfoArraySql = "SELECT * FROM user_addresses WHERE UA_id=" . intval($userAddressId);
    $userAddressInfoArraySqlResult = mysqli_query($con, $userAddressInfoArraySql);
    if ($userAddressInfoArraySqlResult) {
        while ($userAddressInfoArraySqlResultRowObj = mysqli_fetch_object($userAddressInfoArraySqlResult)) {
            $userAddressInfoArray = $userAddressInfoArraySqlResultRowObj;
        }
    }
    /* get cities of given country */
    if (isset($userAddressInfoArray->UA_country_id)) {
        // city_status = 'allow' AND
        $citySql = "SELECT * FROM cities WHERE city_status = 'allow' AND city_country_id=" . $userAddressInfoArray->UA_country_id;
        $citySqlResult = mysqli_query($con, $citySql);
        if ($citySqlResult) {

            while ($citySqlResultRowObj = mysqli_fetch_object($citySqlResult)) {
                $userAddressInfoArray->cities[] = $citySqlResultRowObj;
            }
        } else {
            if (DEBUG) {
                echo 'citySqlResult ERROR : ' . mysqli_error($con);
            } else {
                echo 'citySqlResult error';
            }
        }
    }

    echo json_encode($userAddressInfoArray);
}
?>
