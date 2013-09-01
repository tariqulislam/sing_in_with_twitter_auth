<?php
$GetCount = mysqli_query($con,"SELECT * FROM product_review WHERE PRE_read='no'");
$Count = mysqli_num_rows($GetCount);
?>
<div class="leftNav">
                <ul id="menu">
                	<li class="dash"><a href="#" title="" class="exp" style="text-decoration:none !important"><span>Customer Activity Module</span><!--<span class="numberLeft">6</span>--></a>
                        <ul class="sub">
                            <li><a href="<?php echo baseUrl('admin/customer_manage/');?>" title="" style="text-decoration:none !important">Customer List<?php if($Count > 0) { echo '<span class="numberLeft">'.$Count.'</span>'; } ?></a></li>
                            
                        </ul>
                    </li>
                </ul>

                <div class="leftCol">
                    <div class="title">
                        <h5>Note</h5>

                    </div>
                    <div class="leftColInner">
                       This is Customer Activity Module.
                    </div>
                </div>

            </div>