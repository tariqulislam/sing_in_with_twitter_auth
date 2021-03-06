<?php

/* ===============================default function START=============================== */

/**
 * redirect by Javascript to given link
 *
 * @return string
 */
function redirect($link = NULL) {

    if ($link) {
        echo "<script language=Javascript>document.location.href='$link';</script>";
    } else {
        /* echo '$link does not specified'; */
    }
}

/**
 * Give your file name as suffix it will return full base path
 * @return string 
 */
function basePath($suffix = '') {
    global $config;
    $suffix = ltrim($suffix, '/');
    return $config['BASE_DIR'] . '/' . trim($suffix);
}

/**
 * Give your file name as suffix it will return full base url
 * @return string 
 */
function baseUrl($suffix = '') {
    global $config;
    $suffix = ltrim($suffix, '/');
    return $config['BASE_URL'] . trim($suffix);
}

/**
 * cpunt user character and make a limit
 * @return string
 */
function charLimiter($string = '', $limit = null, $suffix = '..') {
    if ($limit AND strlen($string) > $limit) {
        return substr($string, 0, $limit) . $suffix;
    } else {
        return $string;
    }
}

/**
 * Click able Url  str_replace('http://','',str_replace('https://','',$url))
 * @return string
 */
function clickableUrl($url = '') {
    $url = strtolower($url);
    $url = str_replace('http://', '', str_replace('https://', '', $url));
    $url = 'http://' . $url;
    return $url;
}

/**
 * Clean a string for 
 * @return string
 * */
function myUrlEncode($string) {
    /* source = http://php.net/manual/en/function.urlencode.php */
    $entities = array(' ', '--', '&quot;', '!', '@', '#', '%', '^', '&', '*', '_', '(', ')', '+', '{', '}', '|', ':', '"', '<', '>', '?', '[', ']', '\\', ';', "'", ',', '.', '/', '*', '+', '~', '`', '=');
    $replacements = array('-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-');
    return str_replace($entities, $replacements, urlencode(strtolower(trim($string))));
}

/**
 * Check the mail is valid or not
 * 
 * @return string
 */
function isValidEmail($email = '') {
    return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
}

/**
 * 1.  This function will convert to md5() and inject our secure $saltKeyWord <br/>
 * 2.  The password_key is mention at $saltKeyWord variable at top of the class <br/>
 * 3.  This password_key is not changeable after create some user with the keyoerd <br/>
 * 4.  If u want to change $saltKeyWord value first of all make the user table empty<br/>
 * 
 * @return string
 */
function securedPass($pass = '') {

    global $config;
    $saltKeyWord = $config['PASSWORD_KEY']; /* If u want to change $saltKeyWord value first of all make the admin table empty */

    if ($pass != '') {
        $pass = md5($pass);
        /* created md5 hash */
        $length = strlen($pass);
        /* calculating the lengh of the value */
        $password_code = $saltKeyWord;
        if ($password_code != '') {
            $security_code = trim($password_code);
        } else {
            $security_code = '';
        }
        /* checking set $password_code or not */
        $start = floor($length / 2);
        /* dividing the lenght */
        $search = substr($pass, 1, $start);
        /* $search = which part will replace */
        $secur_password = str_replace($search, $search . $security_code, $pass);

        /* $search.$security_code replacing a part this password_code */
        return $secur_password;
    } else {
        return '';
    }
}

/**
 * Auto creates a 6 char string [a-z A-Z 0-9]
 *
 * @return string
 */
function passwordGenerator() {
    $buchstaben = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $pw_gen = '';

    for ($i = 1; $i <= 6; $i++) {
        mt_srand((double) microtime() * 1000000);
        $tmp = mt_rand(0, count($buchstaben) - 1);
        $pw_gen.=$buchstaben[$tmp];
    }

    return $pw_gen;
}

/**
 * show an array with pre tag<br/>
 * Default die false 
 * @return string  
 */
function printDie($array = array(), $die = FALSE) {
    /* this function used for print a array */

    echo '<pre>';
    print_r($array);
    echo '</pre>';

    if ($die) {
        die("<b>This Die exicute from printDie function at helpers_functions file</b>");
    }
}

/* ===============================default function END=============================== */

/* ===============================admin/logout.php function START=============================== */

/**
 * unset : $_SESSION['admin_login'], $_SESSION['admin_id'] , $_SESSION['admin_email'], $_SESSION['admin_type'], $_SESSION['admin_hash'],  $_SESSION['admin_password'],  $_SESSION['admin_name']
 * @return bool 
 */
function AdminLogout() {

    unset($_SESSION['admin_name']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_type']);
    unset($_SESSION['admin_hash']);
    unset($_SESSION['admin_password']);
    unset($_SESSION['admin_login']);
    return TRUE;
}

/**
 * Check: $_SESSION['admin_login'], $_SESSION['admin_id'] , $_SESSION['admin_email'], $_SESSION['admin_type'], $_SESSION['admin_hash'],  $_SESSION['admin_password'],  $_SESSION['admin_name']
 * @return bool 
 */
function checkAdminLogin() {
    $status = array();
    if (isset($_SESSION['admin_login']) AND $_SESSION['admin_login'] == TRUE) {
        $status[] = 1;
    }

    if (isset($_SESSION['admin_id']) AND $_SESSION['admin_id'] > 0) {
        $status[] = 2;
    }

    if (isset($_SESSION['admin_email']) AND $_SESSION['admin_email'] != '') {
        $status[] = 3;
    }

    if (isset($_SESSION['admin_type']) AND $_SESSION['admin_type'] != '') {
        $status[] = 4;
    }

    if (isset($_SESSION['admin_hash']) AND $_SESSION['admin_hash'] != '') {
        $status[] = 5;
    }
    if (isset($_SESSION['admin_password']) AND $_SESSION['admin_password'] != '') {
        $status[] = 6;
    }
    if (isset($_SESSION['admin_name']) AND $_SESSION['admin_name'] != '') {
        $status[] = 7;
    }
    //print_die($status);
    if (count($status) < 7 OR in_array(0, $status)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

/* ===============================admin/index.php function END=============================== */
/* ===============================SRART Global SQL =============================== */

/**
 * query from databse for max value 
 * Example: Max id of a table
 * @return string or number 
 */
function getMaxValue($tableNmae = '', $fieldName = '') {
    global $con;
    if ($tableNmae != '' AND $fieldName != '') {
        $sql = "SELECT MAX($fieldName) AS max_value FROM $tableNmae";
        $sqlResult = mysqli_query($con, $sql);
        if ($sqlResult) {
            $sqlResultObjRow = mysqli_fetch_object($sqlResult);
            if (isset($sqlResultObjRow->max_value)) {
                return $sqlResultObjRow->max_value;
            } else {
                return 0; /* no max value set in object   */
            }
        } else {

            if (DEBUG) {
                echo 'Max value sqlResult error: ' . mysqli_error($con);
            } else {
                return 0; /* sql error  */
            }
        }
    } else {
        return 0; /* table or filed missing */
    }
}

/**
 * query from databse for a field value<br> 
 * Example: id to title, id to email<br>
 * Example: $where : id=34 <br>
 * if no where return first value <br>
 * $tableNmae = '', $fieldName = '', $where = ''
 * @return string 
 */
function getFieldValue($tableNmae = '', $fieldName = '', $where = '') {
    global $con;
    if ($tableNmae != '' AND $fieldName != '') {

        if ($where != '') {
            $sql = "SELECT $fieldName AS field_value FROM $tableNmae WHERE " . $where;
        } else {
            $sql = "SELECT $fieldName AS field_value FROM $tableNmae";
        }

        $sqlResult = mysqli_query($con, $sql);
        if ($sqlResult) {
            $sqlResultObjRow = mysqli_fetch_object($sqlResult);
            if (isset($sqlResultObjRow->field_value)) {
                return $sqlResultObjRow->field_value;
            } else {
                return 'Unknown'; /* no value in object   */
            }
        } else {

            if (DEBUG) {
                echo 'getFieldValue error: ' . mysqli_error($con);
            } else {
                return 'Unknown'; /* sql error  */
            }
        }
    } else {
        return 'Unknown'; /* table or filed missing */
    }
}

/*===============================END Global SQL ===============================*/
/*===============================END Global SQL ===============================*/
/**
 * This removes special characters from a string<br> 
 * @return string 
 */
function clean($string) {
    $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
/** Error hendling for Zebra image lib
 * 
 * @param type $error
 * @return string
 */
function zebraImageErrorHandaling($error=0) {
    switch ($error) {

        case 1:
            return 'Source file could not be found!';
            break;
        case 2:
           return 'Source file is not readable!';
            break;
        case 3:
            return  'Could not write target file!';
            break;
        case 4:
            return  'Unsupported source file format!';
            break;
        case 5:
            return  'Unsupported target file format!';
            break;
        case 6:
           return  'GD library version does not support target file format!';
            break;
        case 7:
            return  'GD library is not installed!';
            break;
        default :
            return '';
    }
}
