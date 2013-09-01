<?php
include("../config/config.php");
$id = $_POST["userid"];
$userAccountInfoSql = "SELECT * FROM users WHERE user_id =" . intval($id);
$userAccountInfoSqlResult = mysqli_query($con, $userAccountInfoSql);
$userAccountInfoSqlResultRowObj = mysqli_fetch_object($userAccountInfoSqlResult);
$user_address = $userAccountInfoSqlResultRowObj->user_address;
?>
<form action="<?php echo baseUrl("account.php");?>" method="post" onsubmit="return validation();">
<p>User Address <strong><input type="text" class="input_edit" style="width:60%" id="user_address" name="user_address" value="<?php echo $user_address; ?>"></strong></p>
<button class="userEditBtn" style="float:right; " type="submit" action="<?php echo baseUrl("account.php");?>" name="address_update">Update</button><br/><br/><br/>
</form>
<script>
    function validation() {
        if(document.getElementById("user_address").value.length < 1) {
            alert('Please provide your Address');
            return false;
        }
    }
</script>