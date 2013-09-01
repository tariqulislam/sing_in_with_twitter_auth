<?php
include ('../../../../config/config.php');
$id = $_GET['id'];
$pid = $_GET['pid'];


$delimg = mysqli_query($con,"DELETE FROM product_images WHERE PI_id='$id'");


//getting images from db


if($delimg)
{
	
	echo '<font color="green"><b>Image deleted successfully.</b></font>';
	echo '<ul>';
	$selimage = mysqli_query($con,"SELECT * FROM product_images WHERE PI_product_id='$pid'");
	while($showimg = mysqli_fetch_assoc($selimage))
	{                               
										echo '<li><a href="#" title=""><img src="'.baseUrl('upload/product/small/' . $showimg['PI_file_name']).'" alt="" height="84px" width="100px" /></a>
											<div class="actions">
												<a href="#" title=""><img src="'.baseUrl('admin/images/edit.png').'" alt="" /></a>
												<a href="javascript:delid('.$showimg['PI_id'].');"title=""><img src="'.baseUrl("admin/images/delete.png").'" alt="" /></a>
											</div>
										</li>';
	}
    echo '</ul>'; 
}
else
{
	echo '<font color="red"><b>Image delete failed.</b></font>';
	echo '<ul>';
	$selimage = mysqli_query($con,"SELECT * FROM product_images WHERE PI_product_id='$pid'");
	while($showimg = mysqli_fetch_assoc($selimage))
	{                               
										echo '<li><a href="#" title=""><img src="'.baseUrl('upload/product/small/' . $showimg['PI_file_name']).'" alt="" height="84px" width="100px" /></a>
											<div class="actions">
												<a href="#" title=""><img src="'.baseUrl('admin/images/edit.png').'" alt="" /></a>
												<a href="javascript:delid('.$showimg['PI_id'].');"title=""><img src="'.baseUrl("admin/images/delete.png").'" alt="" /></a>
											</div>
										</li>';
	}
    echo '</ul>'; 
}
?>