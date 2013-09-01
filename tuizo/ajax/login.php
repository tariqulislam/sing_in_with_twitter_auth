<?php

include ('../config/config.php');
$user = $_POST['u'];
$pass = $_POST['p'];

if ($user == "") {
    $type = 1;
    $data = array("type" => $type); // This is your data array/result
    echo json_encode($data);
} elseif ($pass == "") {
    $type = 2;
    $data = array("type" => $type); // This is your data array/result
    echo json_encode($data);
} elseif (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
    $type = 3;
    $data = array("type" => $type); // This is your data array/result
    echo json_encode($data);
} else {


    $spass = securedPass($pass);
    $SqlLogin = "SELECT * FROM users WHERE user_email='$user' AND user_password='$spass'";
    $ExecuteLogin = mysqli_query($con, $SqlLogin);
    $Count = mysqli_num_rows($ExecuteLogin);

    if ($Count > 0) {
        $type = 0;

        //getting user information and setting session variable
        $GetLogin = mysqli_fetch_object($ExecuteLogin);
        $_SESSION['user_name'] = $GetLogin->user_first_name;
        $_SESSION['user_id'] = $GetLogin->user_id;
        $_SESSION['user_email'] = $user;

        //setting user cookies
        $cookie_time = time() + $config['USER_COOKIE_EXPIRE_DURATION'];
        setcookie("user_email", $_SESSION['user_email'], $cookie_time);
        setcookie("user_name", $_SESSION['user_id'], $cookie_time);
        setcookie("user_id", $_SESSION['user_name'], $cookie_time);


        //checking if user already added any product in the cart
        $cart_id = session_id();
        $SqlChkCart = "SELECT * FROM temp_carts WHERE TC_session_id='$cart_id'";
        $ExecuteChk = mysqli_query($con, $SqlChkCart);
        $ChkCount = mysqli_num_rows($ExecuteChk);
        if ($ChkCount > 0) {
            while ($SetUserCart = mysqli_fetch_object($ExecuteChk)) {
                $TempCartID = $SetUserCart->TC_id;
                $user_id = $_SESSION['user_id'];

                //updating cart by setting user id
                $UpdateCart = '';
                $UpdateCart .= ' TC_user_id = "' . mysqli_real_escape_string($con, $user_id) . '"';

                $UpdateCartUser = "UPDATE temp_carts SET $UpdateCart WHERE TC_id='$TempCartID'";
                $ExecuteCartUpdate = mysqli_query($con, $UpdateCartUser);
            }
        }


        $data = array("type" => $type, "name" => $_SESSION['user_name'], "id" => $_SESSION['user_id']); // This is your data array/result
        echo json_encode($data);
    } else {
        $type = 5;
        $data = array("type" => $type); // This is your data array/result
        echo json_encode($data);
    }
}
?>