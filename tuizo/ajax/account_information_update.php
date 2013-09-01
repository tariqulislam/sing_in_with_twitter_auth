<?php
include("../config/config.php");
$id = $_POST["userid"];
$userAccountInfoSql = "SELECT * FROM users WHERE user_id =" . intval($id);
$userAccountInfoSqlResult = mysqli_query($con, $userAccountInfoSql);
$userAccountInfoSqlResultRowObj = mysqli_fetch_object($userAccountInfoSqlResult);
$user_email = $userAccountInfoSqlResultRowObj->user_email;
$user_phone = $userAccountInfoSqlResultRowObj->user_phone;
$user_address = $userAccountInfoSqlResultRowObj->user_address;
?>
<form action="<?php echo baseUrl("account.php"); ?>" method="post" onsubmit=" return validation();">
    <p>Email <strong><input type="text" id="user_email" class="input_edit" style="width:60%" name="user_email" value="<?php echo $user_email; ?>"></strong>
    <p>Password <strong><input type='password' class="input_edit" style="width:60%" id='user_password' name='user_password'></strong></p>
    <p>Confirm Password <strong><input type='password' class="input_edit" style="width:60%" id='confirm_password' name='confirm_password'></strong></p>
    <p>Phone <strong><input type="text" id="user_phone" class="input_edit" style="width:60%" name="user_phone" value="<?php echo $user_phone; ?>"></strong></p>
    <button class="userEditBtn" style="float:right; " type="submit" action="<?php echo baseUrl("account.php"); ?>" name="account_update">Update</button><br/><br/><br/>
</form>
<script>
    function validation() {
        var email = document.getElementById('user_email');
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        if (!filter.test(email.value)) {
            alert('Please provide a valid Email address');
            email.focus;
            return false;
        } else if (document.getElementById('user_password').value.length < 1) {
            alert('Please provide your Password');
            return false;
        } else if (document.getElementById('user_password').value != document.getElementById('confirm_password').value) {
            alert('Confirm Password does not match');
            return false;
        } else if (document.getElementById('user_phone').value.length < 1) {
            alert('Please provide your Phone number');
            return false;
        }
    }
</script>