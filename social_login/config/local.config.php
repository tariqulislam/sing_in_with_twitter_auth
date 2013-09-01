<?php

/* change when upload to different domain 
 * setting site hosting  data 
 */
if ($_SERVER['HTTP_HOST'] == 'testserver.bscheme.com' OR $_SERVER['HTTP_HOST'] == 'http://testserver.bscheme.com') {
    $config['SITE_NAME'] = 'social_login';
    $config['BASE_URL'] = 'http://testserver.bscheme.com/social_login/';
    $config['DB_TYPE'] = 'mysql';
    $config['DB_HOST'] = 'localhost';
    $config['DB_NAME'] = 'bluetest_social_login';
    $config['DB_USER'] = 'bluetest_social';
    $config['DB_PASSWORD'] = "social";
} else {
    $config['SITE_NAME'] = 'social_login';
    $config['BASE_URL'] = 'http://localhost/social_login/';
    $config['DB_TYPE'] = 'mysql';
    $config['DB_HOST'] = 'localhost';
    $config['DB_NAME'] = 'bluetest_social_login';
    $config['DB_USER'] = 'root';
    $config['DB_PASSWORD'] = '';
}

    date_default_timezone_set('Asia/Dhaka');
    $config['MASTER_ADMIN_EMAIL'] = "mizan@bscheme.com"; /* Developer*/
    $config['PASSWORD_KEY'] = "#s1q1g1r1o1u1p1*"; /* If u want to change PASSWORD_KEY value first of all make the admin table empty */
    $config['ADMIN_PASSWORD_LENGTH_MAX'] = 15; /* Max password length for admin user  */
    $config['ADMIN_PASSWORD_LENGTH_MIN'] = 5; /* Min password length for admin user  */
    $config['ADMIN_COOKIE_EXPIRE_DURATION'] = (60 * 60 * 24 * 30); /* Min password length for admin user  */
    
    $config['ITEMS_PER_PAGE'] = 20; /* Pagination */
    $config['IMAGE_PATH'] = $config['BASE_DIR'].'/images'; /* system image path */
    $config['IMAGE_URL'] = $config['BASE_URL'].'images'; /* Upload system path */
    $config['IMAGE_UPLOAD_PATH'] = $config['BASE_DIR'].'/upload'; /* Upload files go here */
    $config['IMAGE_UPLOAD_URL'] = $config['BASE_URL'].'upload'; /* Upload link with this */
    
    /*========================html head section setting Start ==============================*/
    $config['SITE_DEFAULT_META_AUTHOR'] = "";
    $config['SITE_DEFAULT_META_TITLE'] = "";
    $config['SITE_DEFAULT_META_DESCRIPTION'] = "";
    $config['SITE_DEFAULT_META_KEYWORDS'] = "";
    /*========================html head section setting Start ==============================*/
   
    
    /* Start of magic quote remover function
   This function is used for removing magic quote, Thats means using this function no slash will add automatically before quotations*/
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

/* End of magic quote remover function*/
