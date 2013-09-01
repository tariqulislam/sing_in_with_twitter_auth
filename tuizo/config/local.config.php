<?php

/* change when upload to different domain 
 * setting site hosting  data 
 */

if ($_SERVER['HTTP_HOST'] == 'testserver.bscheme.com' OR $_SERVER['HTTP_HOST'] == 'http://testserver.bscheme.com') {
    $config['SITE_NAME'] = 'Tuizo';
    $config['BASE_URL'] = 'http://testserver.bscheme.com/tuizo/';
    $config['DB_TYPE'] = 'mysql';
    $config['DB_HOST'] = 'localhost';
    $config['DB_NAME'] = 'bluetest_tuizo';
    $config['DB_USER'] = 'bluetest_tuizo';
    $config['DB_PASSWORD'] = "u8cLqFM^tUby";
} else {
    $config['SITE_NAME'] = 'Tuizo';
    $config['BASE_URL'] = 'http://localhost/tuizo/';
    $config['DB_TYPE'] = 'mysql';
    $config['DB_HOST'] = 'localhost';
    $config['DB_NAME'] = 'tuizo';
    $config['DB_USER'] = 'root';
    $config['DB_PASSWORD'] = '';
}

date_default_timezone_set('Asia/Dhaka');
$config['MASTER_ADMIN_EMAIL'] = "faruk@bscheme.com"; /* Developer */
$_SESSION['pass_key'] = $config['PASSWORD_KEY'] = "#t1u1i1z1o1*"; /* If u want to change PASSWORD_KEY value first of all make the admin table empty */
$config['ADMIN_PASSWORD_LENGTH_MAX'] = 15; /* Max password length for admin user  */
$config['ADMIN_PASSWORD_LENGTH_MIN'] = 5; /* Min password length for admin user  */
$config['ADMIN_COOKIE_EXPIRE_DURATION'] = (60 * 60 * 24 * 30); /* Min password length for admin user  */

$config['ITEMS_PER_PAGE'] = 20; /* Pagination */
$config['IMAGE_PATH'] = $config['BASE_DIR'] . '/images'; /* system image path */
$config['IMAGE_URL'] = $config['BASE_URL'] . 'images'; /* Upload system path */
$config['IMAGE_UPLOAD_PATH'] = $config['BASE_DIR'] . '/upload'; /* Upload files go here */
$config['IMAGE_UPLOAD_URL'] = $config['BASE_URL'] . 'upload'; /* Upload link with this */
$config['MAX_CATEGORY_LEVEL'] = 2; /* to control category level */
$config['IMAGE_RATIO'] = 2; /* Image Maximum height : This ratio will multiply with image width , if Image height exceed Image Maximum height then Image cann't upload */
$config['CURRENCY'] = "Taka"; /* Image Maximum height : This ratio will multiply with image width , if Image height exceed Image Maximum height then Image cann't upload */
$config['CURRENCY_SIGN'] = "&#2547"; /* Image Maximum height : This ratio will multiply with image width , if Image height exceed Image Maximum height then Image cann't upload */



/**
 * SET REDIRECT_ME_FROM
 * if checkAdminLogin() at $config['BASE_DIR'] . '/lib/helper_functions.php' return false 
 * set REDIRECT_ME_FROM where you wanted to access 
 * after success full login 
 * it will redirect to that url
 */
if (!isset($_SESSION['REDIRECT_ME_FROM_TUIZO_ADMIN'])) {
    $_SESSION['REDIRECT_ME_FROM_TUIZO_ADMIN'] = '';
}

if (!isset($_SESSION['REDIRECT_ME_FROM_TUIZO_FRONT'])) {
    $_SESSION['REDIRECT_ME_FROM_TUIZO_FRONT'] = '';
}
/* Start of magic quote remover function
  This function is used for removing magic quote, Thats means using this function no slash will add automatically before quotations */
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

/* End of magic quote remover function */
?>