<?php
include("../config/config.php");
$id = $_POST["userid"];
$userGeneralInfoSql = "SELECT * FROM users WHERE user_id =" . intval($id);
$userGeneralInfoSqlResult = mysqli_query($con, $userGeneralInfoSql);
$userGeneralInfoSqlResultRowObj = mysqli_fetch_object($userGeneralInfoSqlResult);

$user_first_name = $userGeneralInfoSqlResultRowObj->user_first_name;
$user_middle_name = $userGeneralInfoSqlResultRowObj->user_middle_name;
$user_last_name = $userGeneralInfoSqlResultRowObj->user_last_name;
$user_DOB = $userGeneralInfoSqlResultRowObj->user_DOB;
$user_gender = $userGeneralInfoSqlResultRowObj->user_gender;
$user_aboutme = $userGeneralInfoSqlResultRowObj->user_aboutme;
$user_DOB_year = $user_DOB[0] . $user_DOB[1] . $user_DOB[2] . $user_DOB[3];
$user_DOB_month = $user_DOB[5] . $user_DOB[6];
$user_DOB_day = $user_DOB[8] . $user_DOB[9];
?>
<form action="<?php echo baseUrl("account.php"); ?>" method="post" onsubmit=" return validation();">
    <p>First Name <input type="text" class="input_edit" style="width:60%" id="user_first_name" name="user_first_name" value="<?php echo $user_first_name ?>"></p>
    <p>Middle Name <input type="text" class="input_edit" style="width:60%" id="user_middle_name" name="user_middle_name" value="<?php echo $user_middle_name ?>"></p>
    <p>Last Name <input type="text" class="input_edit" style="width:60%" id="user_last_name" name="user_last_name" value="<?php echo $user_last_name ?>"></p>
    <p>DOB 
        <select style="width:25%;margin-left:2%;" class="input_edit" type="text" id="year" name="year">
            <option value='-1'>Year</option>
            <?php
            for ($j = (date("Y") - 80); $j <= (date("Y")); $j++) {
                echo"<option value = '$j' " . (($user_DOB_year == $j) ? 'selected' : '') . ">$j</option>";
            }
            ?>
        </select>
        <select style="width:25%;margin-right:2%;" class="input_edit" type="text" id="month" name="month">
            <option value="-1"> Month</option>
            <option value="01" <?php if ($user_DOB_month == '01') echo 'selected'; ?>>January</option> 
            <option value="02" <?php if ($user_DOB_month == '02') echo 'selected'; ?>>February</option> 
            <option value="03" <?php if ($user_DOB_month == '03') echo 'selected'; ?>>March</option> 
            <option value="04" <?php if ($user_DOB_month == '04') echo 'selected'; ?>>April</option> 
            <option value="05" <?php if ($user_DOB_month == '05') echo 'selected'; ?>>May</option> 
            <option value="06" <?php if ($user_DOB_month == '06') echo 'selected'; ?>>June</option> 
            <option value="07" <?php if ($user_DOB_month == '07') echo 'selected'; ?>>July</option> 
            <option value="08" <?php if ($user_DOB_month == '08') echo 'selected'; ?>>August</option> 
            <option value="09" <?php if ($user_DOB_month == '09') echo 'selected'; ?>>September</option> 
            <option value="10" <?php if ($user_DOB_month == '10') echo 'selected'; ?>>October</option> 
            <option value="11" <?php if ($user_DOB_month == '11') echo 'selected'; ?>>November</option> 
            <option value="12" <?php if ($user_DOB_month == '12') echo 'selected'; ?>>December</option> 
        </select>
        <select style="width:25%;margin-right:2%;" class="input_edit" type="text" id="day" name="day">
            <option value="-1" <?php if($user_DOB_day == '-1') echo'selected'?>>day</option>
            <option value="01" <?php if($user_DOB_day == '01') echo'selected'?>>01</option>
            <option value="02" <?php if($user_DOB_day == '02') echo'selected'?>>02</option>
            <option value="03" <?php if($user_DOB_day == '03') echo'selected'?>>03</option>
            <option value="04" <?php if($user_DOB_day == '04') echo'selected'?>>04</option>
            <option value="05" <?php if($user_DOB_day == '05') echo'selected'?>>05</option>
            <option value="06" <?php if($user_DOB_day == '06') echo'selected'?>>06</option>
            <option value="07" <?php if($user_DOB_day == '07') echo'selected'?>>07</option>
            <option value="08" <?php if($user_DOB_day == '08') echo'selected'?>>08</option>
            <option value="09" <?php if($user_DOB_day == '09') echo'selected'?>>09</option>
            <?php
            for ($i = 10; $i < 32; $i++) {
                echo"<option value='$i' " . (($user_DOB_day == $i) ? 'selected' : '') . ">$i</option>";
            }
            ?>
        </select>
    </p>
    <p>Gender<strong><select id="user_gender" name="user_gender" class="input_edit" style="width: 25%">
                <option value="male" <?php if ($user_gender == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if ($user_gender == 'female') echo 'selected'; ?>>Female</option></select></strong></p>
    <p>About Me<strong><textarea name="user_aboutme" class="input_edit"><?php echo $user_aboutme; ?></textarea></strong></p>

    <button class="userEditBtn" style="float:right; " type="submit" id="general_information_update" name="general_update">Update</button>
</form>
<script>
    function validation() {
var DOB = (document.getElementById('month').value+"/"+document.getElementById('day').value+"/"+document.getElementById('year').value);
        if (document.getElementById('user_first_name').value.length < 1) {
            alert('Please provide your First Name');
            return false;
        } else if (document.getElementById('user_middle_name').value.length < 1) {
            alert('Please provide your Middle Name');
            return false;
        } else if (document.getElementById('user_last_name').value.length < 1) {
            alert('Please provide your Last Name');
            return false;
        } else if (document.getElementById('year').value == '-1' || document.getElementById('month').value == '-1' || document.getElementById('day').value == '-1') {
            alert('Please provide a valid DOB');
            return false;
        } else if (!isDate(DOB)) {
            alert('Please provide a valid DOB');
            return false;
        } else if (document.getElementById('gender').value == '-1') {
            alert('Please select your Gender');
            return false;
        }
    }


    function isDate(dateStr) {

        var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
        var matchArray = dateStr.match(datePat); // is the format ok?

        if (matchArray == null) {
            alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
            return false;
        }

        month = matchArray[1]; // p@rse date into variables
        day = matchArray[3];
        year = matchArray[5];

        if (month < 1 || month > 12) { // check month range
            alert("Month must be between 1 and 12.");
            return false;
        }

        if (day < 1 || day > 31) {
            alert("Day must be between 1 and 31.");
            return false;
        }

        if ((month == 4 || month == 6 || month == 9 || month == 11) && day == 31) {
            alert("Month " + month + " doesn`t have 31 days!")
            return false;
        }

        if (month == 2) { // check for february 29th
            var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
            if (day > 29 || (day == 29 && !isleap)) {
                alert("February " + year + " doesn`t have " + day + " days!");
                return false;
            }
        }
        return true; // date is valid
    }