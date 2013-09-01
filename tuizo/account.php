<?php
include 'config/config.php';
if (!checkUserLogin()) {
    $link = baseUrl("sign_in.php");
    redirect($link);
}
$user_id = $_SESSION["user_id"];
$email_address = $_SESSION["mail_address"];
$newsletterStatus = '';
$newsletterCondition = '';
$userNewsletterStatus = '';

if (isset($_POST["account_update"])) {
    if ($_POST["user_email"] == '') {
        $err = 'Email Address Must Not Empty';
    } elseif ($_POST["user_password"] == '') {
        $err = 'Password Field Must Not Empty';
    } elseif ($_POST["user_password"] != $_POST["confirm_password"]) {
        $err = 'Confirm Password Field Does Not Matches';
    } elseif ($_POST["user_phone"] == '') {
        $err = 'Phone Field Must Not Empty';
    }
    if ($err == '') {
        $securedPass = securedPass($_POST["user_password"]);
        $updateUser = '';
        $updateUser .= 'user_email = ' . "'$_POST[user_email]'";
        $updateUser .= ',user_password = ' . "'$securedPass'";
        $updateUser .= ',user_phone = ' . "'$_POST[user_phone]'";

        $updateuserSql = "UPDATE users SET $updateUser WHERE user_id=" . intval($user_id);
        $updateuserSqlResult = mysqli_query($con, $updateuserSql);
        if ($updateuserSqlResult) {
            $msg = "Account Information Updated Succesfully";
            $_SESSION["mail_address"] = $_POST["user_email"];
        } else {
            echo"Some Error" . mysqli_error($con);
        }
    }
}


if (isset($_POST["general_update"])) {
    $updateUser = '';
    $updateUser .= 'user_first_name = ' . "'$_POST[user_first_name]'";
    $updateUser .= ',user_middle_name = ' . "'$_POST[user_middle_name]'";
    $updateUser .= ',user_last_name = ' . "'$_POST[user_last_name]'";
    $updateUser .= ',user_DOB = ' . "'$_POST[year]'" . "'$_POST[month]'" . "'$_POST[day]'";
    $updateUser .= ',user_gender = ' . "'$_POST[user_gender]'";
    $updateUser .= ',user_aboutme = ' . "'$_POST[user_aboutme]'";

    $updateuserSql = "UPDATE users SET $updateUser WHERE user_id=" . intval($user_id);
    $updateuserSqlResult = mysqli_query($con, $updateuserSql);
    if ($updateuserSqlResult) {
        $_SESSION["user_name"] = $_POST["user_last_name"];
        $msg = "General Information Updated Succesfully";
    } else {
        echo"Some Error" . mysqli_error($con);
    }
}

if (isset($_POST["address_update"])) {
    $updateUser = '';
    $updateUser .= 'user_address = ' . "'$_POST[user_address]'";

    $updateuserSql = "UPDATE users SET $updateUser WHERE user_id=" . intval($user_id);
    $updateuserSqlResult = mysqli_query($con, $updateuserSql);
    if ($updateuserSqlResult) {
        $msg = "Address Information Updated Succesfully";
    } else {
        echo"Some Error" . mysqli_error($con);
    }
}



$newsletterSql = "SELECT * FROM subscribe_information WHERE subscribe_email='$email_address'";
$newsletterSqlResult = mysqli_query($con, $newsletterSql);
if ($newsletterSqlResult) {
    $newsletterSqlResultRowObj = mysqli_fetch_object($newsletterSqlResult);
    if (isset($newsletterSqlResultRowObj->status)) {
        $userNewsletterStatus = $newsletterSqlResultRowObj->status;
    }
}
if (mysqli_num_rows($newsletterSqlResult) > 0) {
    $newsletterCondition = 'yes';
} else {
    $newsletterCondition = 'no';
}

if ($userNewsletterStatus == 'active') {
    $newsletterStatus = 'active';
} elseif ($userNewsletterStatus == 'inactive') {
    $newsletterStatus = 'inactive';
}
if (isset($_POST["newsletter_update"])) {
    if ($newsletterCondition == 'yes') {
        $newsletterUpdateSql = "UPDATE subscribe_information SET status = '$_POST[newsletter]' WHERE subscribe_email = '$email_address'";
        $newsletterCondition = 'yes';
        $newsletterStatus = $_POST["newsletter"];
    } else {
        $newsletterUpdateSql = "INSERT INTO subscribe_information(subscribe_email,subscribe_date,status) VALUES('$email_address','" . gmdate("Y-m-d H:i:s") . "','$_POST[newsletter]')";
        $newsletterCondition = 'yes';
        $newsletterStatus = $_POST["newsletter"];
    }
    $newsletterUpdateSqlResult = mysqli_query($con, $newsletterUpdateSql);
    if ($newsletterUpdateSqlResult) {
        $msg = 'Your Newsletter Information Saved Successfully';
    }
    else {
        echo 'soething went wrong' . mysqli_error($con);
    }
}

$userAccountInfoSql = "SELECT * FROM users WHERE user_id=" . intval($user_id);
$userAccountInfoSqlResult = mysqli_query($con, $userAccountInfoSql);
if ($userAccountInfoSqlResult) {
    $userAccountInfoSqlResultRowObj = mysqli_fetch_object($userAccountInfoSqlResult);
    $user_email = $userAccountInfoSqlResultRowObj->user_email;
    $user_first_name = $userAccountInfoSqlResultRowObj->user_first_name;
    $user_middle_name = $userAccountInfoSqlResultRowObj->user_middle_name;
    $user_last_name = $userAccountInfoSqlResultRowObj->user_last_name;
    $user_gender = $userAccountInfoSqlResultRowObj->user_gender;
    $user_DOB = $userAccountInfoSqlResultRowObj->user_DOB;
    $user_aboutme = $userAccountInfoSqlResultRowObj->user_aboutme;
    $user_phone = $userAccountInfoSqlResultRowObj->user_phone;
    $user_address = $userAccountInfoSqlResultRowObj->user_address;
    $user_last_login = $userAccountInfoSqlResultRowObj->user_last_login;
    $user_verification = $userAccountInfoSqlResultRowObj->user_verification;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Tuizo||User Account</title>
            <?php
            include 'header.php';
            ?>
    </head>

    <body>

        <div id="wrapper">

            <?php
            include 'menu.php';
            ?>

            <div id="innerContainer" >
                <div class="cartContainner">
                    <?php if ($err != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage"><?php echo $err; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                        <?php if ($msg != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: green;"><?php echo $msg; ?></p></div><!--formError-->
<?php endif; /* ($err !='') */ ?>
                    <div class="cartContainnerLft">
                        <div class="userAccountRight">
                            <div class="userAccountRightTop">
                                
                                <h4>Account Information</h4>
                                 
                                <span>Hello <?php echo $_SESSION["user_name"]; ?></span>
                                <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                            </div><!--userAccountRightTop-->
                            <div class="userEditArea">
                                <div class="userEditLeft">
                                    <h5>General Information</h5>
                                    <div id="update_general_part">
                                        <p>Name<strong><?php echo $user_first_name . " " . $user_middle_name . " " . $user_last_name; ?></strong>

                                            <p>DOB<strong><?php echo $user_DOB; ?></strong></p>
                                            <p>Gender<strong><?php echo $user_gender; ?></strong></p>
                                            <?php
                                            if ($user_aboutme != '') {
                                                ?>
                                                <p>About Me<strong><?php echo $user_aboutme; ?></strong></p>
                                                <?php
                                            }
                                            ?>
                                            <button class="userEditBtn" style="float:right; " type="submit" id="general_information_update" name="general_information_update">EDIT INFORMATION</button>
                                    </div>
                                </div><!--userEditLeft-->

                                <div class="userEditRight">
                                    <h5>Account Information</h5>
                                    <div id="update_account_part">
                                        <p>Email <strong><?php echo $user_email; ?></strong>

                                            <p>Password <strong>**********</strong></p>
                                            <p>Phone <strong><?php echo $user_phone; ?></strong></p>                                   
                                            <button class="userEditBtn" style="float:right; " type="submit" id="account_information_update" name="account_information_update">EDIT INFORMATION</button><br/><br/><br/>
                                    </div>
                                    <p>Last Login <strong><?php echo $user_last_login; ?></strong></p>
                                    <p>Email Verification <strong><?php echo $user_verification ?></strong></p>
                                </div><!--userEditRight-->

                            </div><!--userEditArea-->

                            <div class="userEditArea userEditAreaBig">
                                <div class="userEditAreaBtm"><h5>My Addresses</h5>
                                </div><!--userEditAreaBtm-->
                                <div class="userEditLeft userEditSmal">
                                    <h5>User address</h5>
                                    <div id="update_address_part">
                                        <p>Registered Address <strong><?php echo $user_address; ?></strong></p>                                    
                                        <button class="userEditBtn" style="float:right;" type="submit" id="address_information_update" name="address_information_update">Edit Information</button>
                                    </div>
                                </div><!--userEditLeft-->

                                <div class="userEditRight userEditSmal">
                                    <h5>Newsletter</h5>
                                    <?php ?>
                                    <form action="<?php echo baseUrl("account.php") ?>" method="post">
                                        <p>Please Select You Status <select name="newsletter" class="input_edit" style="width: 30%">
                                                <option value="active" <?php if ($newsletterCondition == 'yes' && $newsletterStatus == 'active') echo 'selected' ?>>Active</option>
                                                <option value="inactive" <?php if ($newsletterCondition == 'no' || $newsletterStatus == 'inactive') echo 'selected'; ?>>Inactive</option></select>
                                        </p>
                                        <button class="userEditBtn" style="float:right;" type="submit" name="newsletter_update">Update</button>
                                    </form>
                                </div><!--userEditRight-->
                                <div class="clear"></div>
                            </div><!--userEditArea-->
                        </div><!--userAccountRight-->

                    </div><!--cartContainnerLft--></div>



                <?php include('user_side_menu.php'); ?>




            </div><!--cartContainner-->
        </div><!--innerContainer-->
        </div>

        <div class="footer">
            <div class="footerBar bgcolor11">


                <div class="inner">
                    <p class="show_hide icon-plus-sign"> about tuizo </p>

                </div>

            </div>

            <div class="fcontainer footerContainer" style="display:none">
                <div class="inner">

                    <div class="footer-section">


                        <h1 class="h3">Shop Secure</h1>
                        <ul>
                            <li><a href="">Order Online</a></li>
                            <li><a href="">FAQ</a></li>
                            <li><a href="">Payments</a></li>
                            <li><a href="">Delivery</a></li>
                            <li><a href="">Returns</a></li>
                            <li><a href="">General Conditions</a></li>
                            <li><a href="">Privacy Policy</a></li>
                            <li><a href="">Sitemap</a></li>
                            <li><a href="">Glossary</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h1 class="h3">Customer Care</h1>
                        <ul>
                            <li>+31 20 2150073 </li>
                            <li>Mon - Fri between 9am - 9pm CET</li>
                            <li>Saturday between 9am - 5pm CET</li>
                            <li><a class="arrow-right" href="">Country specific phone numbers</a></li>
                            <li>&nbsp;</li>
                            <li><a href="mailto:service@tuizo.com" class="arrow-right">Email Customer Care</a></li>
                        </ul>
                    </div>



                    <div class="footer-section">
                        <h1 class="h3">Follow Us</h1>
                        <ul>

                            <li><a class="arrow-right" href="">Country specific phone numbers</a></li>
                            <li>&nbsp;</li>
                            <li><a href="mailto:service@tuizo.com" class="arrow-right">Email Customer Care</a></li>
                        </ul>
                    </div>














                    <section>
                        <h1 class="h3">Newsletter</h1>

                        <form class="newsletter" action="/on/demandware.store/Sites-US-Site/en/Newsletter-Register" methode="get">
                            <fieldset>
                                <input name="email" placeholder="Your email" type="text">
                                    <input value="BD" class="newsLetterLang" name="lang" type="hidden">
                                        <input name="subscribe" value="true" type="hidden">
                                            <input value="" type="submit">
                                                </fieldset>
                                                </form>

                                                </section>

                                                </div>
                                                </div>


                                                </div>

                                                <script>
                                                    $("#account_information_update").on("click", function() {
                                                        var usrid = <?php echo $user_id; ?>;
                                                        $.post("ajax/account_information_update.php", {userid: usrid}, function(result) {
                                                            $("#update_account_part").html(result);
                                                        })
                                                    });
                                                    $("#general_information_update").on("click", function() {
                                                        var usrid = <?php echo $user_id; ?>;
                                                        $.post("ajax/general_information_update.php", {userid: usrid}, function(result) {
                                                            $("#update_general_part").html(result);
                                                        })
                                                    });
                                                    $("#address_information_update").on("click", function() {
                                                        var usrid = <?php echo $user_id; ?>;
                                                        $.post("ajax/address_information_update.php", {userid: usrid}, function(result) {
                                                            $("#update_address_part").html(result);
                                                        })
                                                    });
                                                </script>


                                                </body>
                                                </html>