<?php
include("../config/config.php");
$email = $_POST["emailcheck"];
if (!isValidEmail($email)) {
    $err = 'invalid email';
    echo"<p style='color:red'>Please Enter A Valid Email Address</p>";
} else {
    $emailCheckSql = "SELECT user_email FROM users WHERE user_email='$email'";
    $emailCheckSqlResult = mysqli_query($con, $emailCheckSql);
    $emailCheckSqlResultRowObj = mysqli_fetch_object($emailCheckSqlResult);
    if ($emailCheckSqlResultRowObj) {
        if (isset($emailCheckSqlResultRowObj->user_email) && $emailCheckSqlResultRowObj->user_email == "$email") {
            echo'<p style="color:red">This Email Address Is Already In Used Please Select Another</p>';
        }
    } else {
        echo 'You Can Use This Email Address';
    }
}
?>
