<?php
include ('../../../../config/config.php');
$id = $_GET['id'];

//fetching product id
$SelectPID = "SELECT * FROM product_discounts WHERE PD_id=$id";
$ExecutePID = mysqli_query($con,$SelectPID);
$GetPID = mysqli_fetch_assoc($ExecutePID);
$PID = $GetPID['PD_product_id'];


$DeleteID = "UPDATE product_discounts SET PD_status=0 WHERE PD_id=$id";
$ExecuteQuery = mysqli_query($con,$DeleteID);
$getdiscount = mysqli_query($con, "SELECT * FROM product_discounts WHERE PD_product_id='$PID' AND PD_status=1");
$CountRows = mysqli_num_rows($getdiscount);



if($CountRows >= 1){
	
	echo '<fon color="green" size="2">Successfully Deleted.</font>';
	echo '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                                    <thead>
                                        <tr>
                                            <td style="mystyle">ID</td>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Discount Amount</th>
                                            <th>Added on</th>
                                            <th>Added By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        while ($showdiscount = mysqli_fetch_array($getdiscount)) {
                                        echo '<tr class="gradeA">
                                                <td>'.$showdiscount['PD_id'].'</td>
                                                <td>'.$showdiscount['PD_start_date'].'</td>
                                                <td>'.$showdiscount['PD_end_date'].'</td>
                                                
                                                <td>'.$showdiscount['PD_amount'].'</td>
                                                
                                                <td>'.$showdiscount['PD_updated'].'</td>
                                                
                                                <td>';
                                                
												$adminid = $showdiscount['PD_updated_by'];
												$adminsql = mysqli_query($con,"SELECT (admin_full_name) FROM admins WHERE admin_id='$adminid'");
												$adminrow = mysqli_fetch_array($adminsql);
												echo $adminrow[0];
												
                                                echo '</td>
                                                
                                                <td><a href="javascript:sgnup('.$showdiscount['PD_id'].');" title="Edit"><img src="'.baseUrl('admin/images/deleteFile.png').'" height="14" width="14" alt="Edit" /></a></td>
                                            </tr>';
										}
						
                                echo '</tbody>
                            </table>';
} else {
	
	echo '<fon color="red" size="2">Successfully Deleted.</font>';
	
}

?>