<?php

include ('../config/config.php');
if (isset($_REQUEST['id']) AND $_REQUEST['id'] !='') {
    echo 'id is :', base64_decode($_REQUEST['id']);
}
?>
