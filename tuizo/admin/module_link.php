        <!-- Header -->
        <div id="header" class="wrapper">
            <div class="logo"><a href="<?php echo baseUrl('admin/dashboard.php');?>" title=""><img width="160" src="<?php echo baseUrl('admin/images/loginLogo.png');?>" alt="" /></a></div>
            <div class="middleNav">
                <ul>
                <?php
				//checking for new order
				$CHkOrder = mysqli_query($con,"SELECT * FROM orders WHERE order_read='no'");
				$CountOrder = mysqli_num_rows($CHkOrder);
				?>
                	<li class="iStat"><a href="<?php echo baseUrl('admin/order/');?>" title=""><span>Order</span></a><span class="numberMiddle"><?php echo $CountOrder; ?></span></li>
                    <li class="iUser"><a href="<?php echo baseUrl('admin/admin/');?>" title=""><span>Admin</span></a></li>
                    <li class="iStat"><a href="<?php echo baseUrl('admin/product_settings/package');?>" title=""><span>Package</span></a></li>
                    <li class="iStat"><a href="<?php echo baseUrl('admin/country/');?>" title=""><span>Country</span></a></li>
                    <li class="iStat"><a href="<?php echo baseUrl('admin/customer_activity/');?>" title=""><span>User Activity</span></a></li>
                    <li class="iStat"><a href="<?php echo baseUrl('admin/customer_manage/');?>" title=""><span>User Management</span></a></li>
                    <li class="iStat"><a href="<?php echo baseUrl('admin/faq/');?>" title=""><span>FAQ</span></a></li>
                    <li class="iStat"><a href="<?php echo baseUrl('admin/pages/');?>" title=""><span>Page</span></a></li>
					</ul>
            </div>
            <div class="fix"></div>
        </div>