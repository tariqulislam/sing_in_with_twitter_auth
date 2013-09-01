<?php
include("config/config.php");
$session_id = session_id();

if (checkUserLogin()) {
    $link = "account.php";
    redirect($link);
}
if (isset($_REQUEST["err"])) {
    $err = base64_decode($_REQUEST["err"]);
}


if (isset($_POST["login"])) {
    if ($_POST["email"] == '') {
        $err = 'Email Field Must Not Blank';
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $err = 'Email Must Not Invalid';
    } elseif ($_POST["password"] == '') {
        $err = 'Password Field Must Not Blank';
    }
    if ($err == '') {
        $checkNameSql = "SELECT * FROM users WHERE user_email='" . $_POST['email'] . "' AND user_status='active' AND user_verification='yes'";
        $checkNameSqlResult = mysqli_query($con, $checkNameSql);
        if ($checkNameSqlResult) {
            $checkNameSqlResultRowObj = mysqli_fetch_object($checkNameSqlResult);
            if (isset($checkNameSqlResultRowObj->user_password)) {
                $password = $checkNameSqlResultRowObj->user_password;

                if (securedPass($_POST["password"]) == $password) {
                    $_SESSION["user_name"] = $checkNameSqlResultRowObj->user_last_name;
                    $_SESSION["mail_address"] = $checkNameSqlResultRowObj->user_email;
                    $_SESSION["user_id"] = $checkNameSqlResultRowObj->user_id;
                    $hashKeyUpdateSql = "UPDATE users SET user_hash = '$session_id',user_last_login='" . date('y-m-d h:i:s') . "' WHERE user_email='" . $_POST['email'] . "' AND user_status='active'";
                    if (mysqli_query($con, $hashKeyUpdateSql)) {

                        //checking the action status and redirecting accordingly
                        if (isset($_GET['action']) && $_GET['action'] == 'checkout') {
                            $link = "shipping_billing.php";
                            redirect($link);
                        } else {
                            $link = "account.php";
                            redirect($link);
                        }
                    }
                } else {
                    $err = "Your Given Password Is Incorrect";
                }
            } else {
                $checkNameSql = "SELECT * FROM users WHERE user_email='" . $_POST['email'] . "' AND user_status='active' AND user_verification='no'";
                $checkNameSqlResult = mysqli_query($con, $checkNameSql);
                if ($checkNameSqlResult) {
                    $checkNameSqlResultRowObj = mysqli_fetch_object($checkNameSqlResult);
                    if (isset($checkNameSqlResultRowObj->user_password)) {
                        $err = 'Email Address Not Verified';
                    } else {
                        $err = 'Email Address Not Found';
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Tuizo||Sign in</title>
            <?php
            include("header.php");
            ?>
    </head>

    <body>

        <div id="wrapper">

            <?php
            include("menu.php");
            ?>

            <div id="innerContainer" >
                <div class="signInContainner">
                    <div class="signInTop clearfix">
                        <h1 class="x-large">sign in</h1>
                        <?php if ($err != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage"><?php echo $err; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                        <?php if ($msg != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: green;"><?php echo $msg; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                        <p>ENJOY A SITE THAT'S MADE FASTER, EASIER, AND SMARTER FOR YOU - EVERY TIME YOU SIGN IN.</p>
                    </div><!--signInTop-->
                    <div class="signInArea">
                        <div class="signInLeft">
                            <h3>Login With Facebook</h3>
                            <a class="btn_facebook" href="#"><span>LOGIN</span></a>
                        </div><!--signInLeft-->
                        <div class="signInright">
                            <h3>Login With email</h3>
                            <?php
                            $query = '';
                            if (isset($_GET['action'])) {
                                $query = '?' . $_SERVER['QUERY_STRING'];
                            }
                            ?>
                            <form name="login" method="post" action="<?php echo baseUrl("sign_in.php" . $query); ?>">
                                <table width="100%" border="0">
                                    <tr>
                                        <td width="50%"><span>Email Address</span></td>
                                        <td width="50%"><input type="text" name="email" id="mail"></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><span>Password</span></td>
                                        <td width="50%"><input type="password" name="password" id="password"></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><a href="<?php echo baseUrl("forgot_password.php") ?>">Forgot it?</a></td>
                                        <td width="50%"><button class="button" type="submit" name="login">login</button></td>
                                    </tr>
                                </table>
                            </form>

                        </div><!--signInright-->
                    </div><!--signInArea-->
                    <p class="signUpLink" align="right">New to TUIZO? <?php if (isset($_GET['action']) && $_GET['action'] == 'checkout') { ?> <a style="color:#F33;" href="sign_up.php?action=<?php echo $_GET['action']; ?>"><?php } else { ?> <a style="color:#F33;" href="sign_up.php">  <?php } ?>Sign up</a></p>

                </div><!--signInContainner-->
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

                        <form class="newsletter" action="/on/demandware.store/Sites-US-Site/en/Newsletter-Register" methode="post">
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
                                                </body>
                                                </html>