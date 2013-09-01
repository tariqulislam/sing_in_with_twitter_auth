<?php
include ('../config/config.php');
if (AdminLogout()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('You successfully logged out.'));
    redirect($link);
} else {
    $link = baseUrl('admin/dashboard.php?err=' . base64_encode('Could not logout.'));
    redirect($link);
}
?>
