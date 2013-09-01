<?php
include '../config/config.php';
$SL_provider='';
$SL_user_id='';
$SL_user_email='';
$SL_user_first_name='';
$SL_user_last_name='';

if (isset($_POST['SL_provider']) && isset($_POST['SL_user_email'])) {
    extract($_POST);
    if ($SL_provider == 'twitter') {
        /** Start save if not Exists for twitter * */
        $inser_sql="";
        $inser_sql.= " SL_provider='".mysqli_real_escape_string($con,$SL_provider)."'";
        $inser_sql.= " ,SL_user_id='".mysqli_real_escape_string($con,$SL_user_id)."'";
        $inser_sql.=" ,SL_user_first_name='".mysqli_real_escape_string($con,$SL_user_first_name)."'";
        $inser_sql.=" ,SL_user_last_name='".mysqli_real_escape_string($con,$SL_user_last_name)."'";
        $insert_query_sql ="INSERT INTO social_login SET $inser_sql";
        $insert_query = mysqli_query($con,$insert_query_sql);
        if($insert_query)
        {
               $_SESSION['user_id']=$SL_user_id;
               redirect("http://testserver.bscheme.com/social_login/logininfo.php");
//               $data = array("output_type" => 0, "user_id" => $SL_user_id, "SL_user_first_name" => $SL_user_first_name, "SL_user_last_name" => $SL_user_last_name); // This is your data array/result
//               echo json_encode($data);
        }
        else
        {
            echo "insert_queryError:". mysqli_error($con);
        }
         /** End save if not Exists for twitter * */
        
    } else {
         /** Start save if not Exists * */
        
        $inser_sql="";
        $inser_sql.= " SL_provider='". mysqli_real_escape_string($con,$SL_provider)."'";
        $inser_sql.= " ,SL_user_email='".mysqli_real_escape_string($con,$SL_user_email)."'";
        $inser_sql.= " ,SL_user_id='".mysqli_real_escape_string($con,$SL_user_id)."'";
        $inser_sql.=" ,SL_user_first_name='".mysqli_real_escape_string($con,$SL_user_first_name)."'";
        $inser_sql.=" ,SL_user_last_name='".mysqli_real_escape_string($con,$SL_user_last_name)."'";
        $insert_query_sql ="INSERT INTO social_login SET $inser_sql";
        $insert_query = mysqli_query($con,$insert_query_sql);
        if($insert_query)
        {
               $_SESSION['user_email']=$SL_user_email;
               redirect("http://testserver.bscheme.com/social_login/logininfo.php");
               //$data = array("output_type" => 1, "user_email" => $SL_user_id, "SL_user_first_name" => $SL_user_first_name, "SL_user_last_name" => $SL_user_last_name); // This is your data array/result
               //echo json_encode($data);
        }
        else
        {
            echo "insert_queryError:". mysqli_error($con);
        }
         /** End save if not Exists * */
    }
}
?>
