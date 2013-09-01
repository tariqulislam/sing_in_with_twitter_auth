<?php
include ('../../config/config.php');
$rid = $_GET['rid'];
$UpdateStatus = mysqli_query($con,"UPDATE product_review SET PRE_read='yes' WHERE PRE_id='$rid'");
$SelectReview = mysqli_query($con,"SELECT * FROM product_review WHERE PRE_id='$rid'");
$ShowReview = mysqli_fetch_array($SelectReview);
echo $ShowReview['PRE_comment'];
?>