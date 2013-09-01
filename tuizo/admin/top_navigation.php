<!-- Top navigation bar -->
<div id="topNav">
    <div class="fixed">
        <div class="wrapper">
            <div class="welcome"><a href="<?php
                if (isset($_SESSION['admin_id'])) {
                    echo baseUrl('admin/admin/change_password.php?id=' . base64_encode($_SESSION['admin_id']));
                } else {
                    echo 'javascript:void(0);';
                }
                ?>" title=""><img src="<?php echo baseUrl('admin/images/userPic.png'); ?>" alt="" /></a><span><?php
                                        if (isset($_SESSION['admin_name'])) {
                                            echo $_SESSION['admin_name'];
                                        } else {
                                            echo 'Unknown Admin';
                                        }
                                        ?></span></div>
            <div class="userNav">
                <ul>



                    <li class="dd"><img src="<?php echo baseUrl('admin/images/icons/topnav/settings.png'); ?>" alt="profile" /><span>Settings</span>
                        <ul class="menu_body">
                            <?php if(isset($_SESSION['admin_type']) AND ($_SESSION['admin_type'] =='super' OR $_SESSION['admin_type']=='master')):?>
                            <li><a href="<?php echo baseUrl('admin/settings/index.php'); ?>" >Website Settings</a></li>
                            <?php endif; /* isset($_SESSION['admin_type']) AND ($_SESSION['admin_type'] =='super' OR $_SESSION['admin_type']=='master') */ ?>
                            
                        </ul>
                    </li>
                    <li class="dd1"><img src="<?php echo baseUrl('admin/images/icons/topnav/profile.png'); ?>" alt="profile" />
                        <span><?php
                            if (isset($_SESSION['admin_name'])) {
                                echo $_SESSION['admin_email'];
                            } else {
                                echo 'Unknown Admin';
                            }
                                        ?>
                        </span>
                        <ul class="menu_body">

                            <li><a href="<?php echo baseUrl('admin/admin/admin_change_password.php'); ?>" >Change Password</a></li>
                            <li><a href="<?php echo baseUrl('admin/logout.php'); ?>" >Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="fix"></div>
        </div>
    </div>
</div>