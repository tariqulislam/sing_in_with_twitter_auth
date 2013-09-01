<?php
include 'config/config.php';
$userEmailAddress = $_REQUEST["email"];
//$session_id = session_id();
$userHashKey = $_REQUEST["hashKey"];

//if($_REQUEST["hashKey"] != $userHashKey) {
//    $link = "index.php";
//    redirect($link);
//}
$linkAlreadyClickedSql = "SELECT * FROM users WHERE user_hash='$userEmailAddress'";
$linkAlreadyClickedSqlResult = mysqli_query($con, $linkAlreadyClickedSql);
if(mysqli_num_rows($linkAlreadyClickedSqlResult) <= 0) {
    $link = "index.php?redirect=true";
    redirect($link);
}
$userActiveSql = "UPDATE users SET user_hash = '$userHashKey', user_verification='yes' WHERE user_email='" . $userEmailAddress . "'";
$userActiveSqlResult = mysqli_query($con, $userActiveSql);
if ($userActiveSqlResult) {
    $userInfoSql = "SELECT * FROM users WHERE user_email='$userEmailAddress'";
    $userInfoSqlResult = mysqli_query($con, $userInfoSql);
    $userInfoSqlResultRowObj = mysqli_fetch_object($userInfoSqlResult);
    $_SESSION["user_name"] = $userInfoSqlResultRowObj->user_first_name . " " . $userInfoSqlResultRowObj->user_last_name;
    $_SESSION["mail_address"] = $userInfoSqlResultRowObj->user_email;
    $_SESSION["user_id"] = $userInfoSqlResultRowObj->user_id;
    $link = baseUrl("account.php");
    redirect($link);
} else {
    echo 'some error'.  mysqli_error($con);
}