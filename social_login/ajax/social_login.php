<?php
include '../config/config.php';
$SL_provider = '';
$SL_user_id = '';
$SL_user_email = '';
$SL_user_first_name = '';
$SL_user_last_name = '';

if (isset($_POST['SL_provider']) && isset($_POST['SL_user_email'])) {
    extract($_POST);
    if ($SL_provider == 'twitter') {
        /** Start Check the user if Exists * */
        $check_user_sql = "SELECT * FROM social_login WHERE SL_provider='" . mysqli_real_escape_string($con, $SL_provider) . "' AND SL_user_id='" . mysqli_real_escape_string($con, $SL_user_id) . "'";
        $result_check_user = mysqli_query($con, $check_user_sql);

        if ($result_check_user) {
            $count_user = mysqli_num_rows($result_check_user);
            if ($count_user > 0) {
                $getUserSql = mysqli_fetch_object($result_check_user);
                $SL_user_id = $getUserSql->SL_user_id;
                //$SL_user_first_name = $getUserSql->SL_user_first_name;
                //$SL_user_last_name = $getUserSql->SL_user_last_name;
                $_SESSION['user_id'] = $SL_user_id;
                $data = array("output_type" => 0, "provider"=>$SL_provider, "user_id" => $SL_user_id, "SL_user_first_name" => $SL_user_first_name, "SL_user_last_name" => $SL_user_last_name); // This is your data array/result
                echo json_encode($data);
            } else {
                $data = array("output_type" => 1,"provider"=>$SL_provider, "user_id" => $SL_user_id, "SL_user_first_name" => $SL_user_first_name, "SL_user_last_name" => $SL_user_last_name);
                echo json_encode($data);
            }
        } else {
            echo "result_check_userError" . mysqli_error($con);
        }
    } else {
        /** Start Check the user if Exists * */
        $check_user_sql = "SELECT * FROM social_login WHERE SL_provider='" . mysqli_real_escape_string($con, $SL_provider) . "' AND SL_user_email='" . mysqli_real_escape_string($con, $SL_user_email) . "'";
        $result_check_user = mysqli_query($con, $check_user_sql);

        if ($result_check_user) {
            $count_user = mysqli_num_rows($result_check_user);
            if ($count_user > 0) {
                $getUserSql = mysqli_fetch_object($result_check_user);
                $SL_user_email = $getUserSql->SL_user_email;
                $SL_user_first_name = $getUserSql->SL_user_first_name;
                $SL_user_last_name = $getUserSql->SL_user_last_name;
                $SL_user_id=$getUserSql->SL_user_id;
                $_SESSION['user_email'] = $SL_user_email;
                $data = array("output_type" => 0, "provider"=>$SL_provider, "user_email" => $SL_user_email, "SL_user_id"=>$SL_user_id, "SL_user_first_name" => $SL_user_first_name, "SL_user_last_name" => $SL_user_last_name); // This is your data array/result
                echo json_encode($data);
            } else {
                $data = array("output_type" => 1, "provider"=>$SL_provider, "user_email" => $SL_user_email, "SL_user_id"=>$SL_user_id, "SL_user_first_name" => $SL_user_first_name, "SL_user_last_name" => $SL_user_last_name);
                echo json_encode($data);
            }
        } else {
            echo "result_check_userError" . mysqli_error($con);
        }
    }
}
?>
