<?php
include 'config/config.php';
unset($_SESSION['user_email']);
session_unset();
session_destroy();
redirect("http://testserver.bscheme.com/social_login/index.php");
?>
