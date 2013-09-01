<?php
include ('../../../config/config.php');

$pid = $_GET['pid'];
//getting image path and unlinking
$unlinkimg = mysqli_query($con,"SELECT * FROM category_banners WHERE CB_id='$pid'");
$unlinkrow = mysqli_fetch_assoc($unlinkimg);
$imgname = $unlinkrow['CB_image_name'];
unlink ('../../../upload/category_banner/' . $imgname);//deleting original image

//deleting image details from db
$delimg = mysqli_query($con,"DELETE FROM category_banners WHERE CB_id='$pid'");


//getting images from db


if($delimg)
{
	echo '<font color="green"><b>Image deleted successfully.</b></font>';
	echo '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                <thead>
                                    <tr>
                                        <th>Banner ID</th>
                                        <th>Banner Title</th>
                                        <th>Banner Category</th>
                                        <th>Banner Image</th>
                                        <th>Banner Last Updated</th>
                                        <th>Banner Updated By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
	$catbansql = mysqli_query($con, "SELECT * FROM category_banners");
	while ($catbanrow = mysqli_fetch_array($catbansql)) 
	{
	                                        echo '<tr class="gradeA">
                                            <td>'.$catbanrow['CB_id'].'</td>
                                            <td>'.$catbanrow['CB_title'].'</td>
                                            <td>'.$catbanrow['CB_category_id'].'</td>
                                            <td align="center"><img src="'.baseUrl('upload/category_banner/').$catbanrow['CB_image_name'].'" width="40px" style="margin:0 auto !important;" /></td>
                                            <td>'.$catbanrow['CB_updated'].'</td>
                                            <td>';
											$aid = $catbanrow['CB_updated_by'];
											$adminsql = mysqli_query($con, "SELECT (admin_full_name) FROM admins WHERE admin_id='$aid'");
											$adminrow = mysqli_fetch_array($adminsql);
											echo $adminrow[0];
											echo '</td>
                                            <td><a href="edit.php?pid='.base64_encode($catbanrow['CB_id']).'" title="Edit"><img src="../images/edit.png" height="12px" width="12px" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delid('.$catbanrow['CB_id'].');" title="Delete"><img src="../images/delete.png" height="12px" width="12px" /></a></td>
                                        </tr>';
	}
                                 echo '</tbody>
                            </table>';
	
}
else
{
	echo '<font color="red"><b>Image could not be deleted.</b></font>';
	echo '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                <thead>
                                    <tr>
                                        <th>Banner ID</th>
                                        <th>Banner Title</th>
                                        <th>Banner Category</th>
                                        <th>Banner Image</th>
                                        <th>Banner Last Updated</th>
                                        <th>Banner Updated By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
	$catbansql = mysqli_query($con, "SELECT * FROM category_banners");
	while ($catbanrow = mysqli_fetch_array($catbansql)) 
	{
	                                        echo '<tr class="gradeA">
                                            <td>'.$catbanrow['CB_id'].'</td>
                                            <td>'.$catbanrow['CB_title'].'</td>
                                            <td>'.$catbanrow['CB_category_id'].'</td>
                                            <td align="center"><img src="'.baseUrl('upload/category_banner/').$catbanrow['CB_image_name'].'" width="40px" style="margin:0 auto !important;" /></td>
                                            <td>'.$catbanrow['CB_updated'].'</td>
                                            <td>';
											$aid = $catbanrow['CB_updated_by'];
											$adminsql = mysqli_query($con, "SELECT (admin_full_name) FROM admins WHERE admin_id='$aid'");
											$adminrow = mysqli_fetch_array($adminsql);
											echo $adminrow[0];
											echo '</td>
                                            <td><a href="edit.php?pid='.base64_encode($catbanrow['CB_id']).'" title="Edit"><img src="../images/edit.png" height="12px" width="12px" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delid('.$catbanrow['CB_id'].');" title="Delete"><img src="../images/delete.png" height="12px" width="12px" /></a></td>
                                        </tr>';
	}
                                 echo '</tbody>
                            </table>';
}
?>