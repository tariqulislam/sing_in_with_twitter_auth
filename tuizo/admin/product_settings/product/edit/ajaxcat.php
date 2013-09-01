<?php
include ('../../../../config/config.php');
$c = $_GET['c'];
$pid = $_GET['id'];

$chk = mysqli_query($con,"SELECT * FROM product_categories WHERE PC_product_id='$pid' AND PC_category_id='$c'");
$count = mysqli_fetch_array($chk);

if($count > 0)
{
	$del = mysqli_query($con,"DELETE FROM product_categories WHERE PC_product_id='$pid' AND PC_category_id='$c'");
	echo 'Category deleted successfully.';
}
else
{
	$add = mysqli_query($con,"INSERT INTO product_categories(PC_product_id,PC_category_id) VALUES('$pid','$c')");
	echo "Category added successfully.";
}
?>