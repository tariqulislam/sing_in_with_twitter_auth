<?php
include("config/config.php");
$profile_image = '';

//redirecting if user already logged in
if (checkUserLogin()) {
    $link = "account.php";
    redirect($link);
}


$email = '';
$first_name = '';
$middle_name = '';
$last_name = '';
$year = '';
$month = '';
$day = '';
$gender = '';
$phone = '';
$about_me = '';
$address = '';

if (isset($_REQUEST['next']) && $_REQUEST['next'] == 'next') {
    extract($_POST);
    $session_id = session_id();
    $givenEmail = $email;
    $userHashKey = $session_id;
    $width = 0;

    /* Start Rand key generator */
    $length = 8;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    /* End Rand key generator */


    if ($_FILES["profile_image"]["error"] == 0) {
        list($width) = getimagesize($_FILES["profile_image"]["tmp_name"]);
    }
    $max_width = 100;


    if ($email == '') {
        $err = "Email Address Field Must Not Empty";
    } elseif (!isValidEmail($email)) {
        $err = "Please Enter A Valid Email Address";
    } elseif ($first_name == '') {
        $err = "Please Enter Your FIRST NAME";
    } elseif ($last_name == '') {
        $err = 'Please Enter Your LAST NAME';
    } elseif ($year == '-1' or $month == '-1' or $day == '-1') {
        $err = 'Please Select A Valid DATE OF BIRTH';
    } elseif (!checkdate($month, $day, $year)) {
        $err = 'Please Select A Valid DATE OF BIRTH';
    } elseif ($gender == '') {
        $err = 'Please Select Your GENDER';
    } elseif ($address == '') {
        $err = 'Please Enter Your ADDRESS';
    } elseif ($phone == '') {
        $err = 'Please Enter Your PHONE Number';
    } elseif ($password == '') {
        $err = 'Please Set A PASSWORD';
    } elseif ($password != $confirm_password) {
        $err = 'CONFIRM PASSWORD Does Not Matches';
    } elseif (!isset($terms_conditions)) {
        $err = 'Check The TERMS AND CONDITIONS First';
    }
    $userNameExistingSql = "SELECT * FROM users WHERE user_email='$email'";
    $userNameExistingSqlResult = mysqli_query($con, $userNameExistingSql);
    if ($userNameExistingSqlResult) {
        if (mysqli_num_rows($userNameExistingSqlResult) > 0) {
            $err = 'Email Address Already Exist Please Select Another';
        }
    }
    if ($err == '') {

        //Start User photo Insertion
        $max_user_id = getMaxValue('users', 'user_id');
        $new_user_id = $max_user_id + 1;
        /* Srat: image upload */
        $profile_image = basename($_FILES['profile_image']['name']);
        $info = pathinfo($profile_image, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
        $profile_image_name = str_replace(' ', '_', $first_name) . '-' . $new_user_id . '.' . $info; /* create custom image name color id will add  */
        $profile_image_source = $_FILES["profile_image"]["tmp_name"];

        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/profile_image/')) {
            mkdir($config['IMAGE_UPLOAD_PATH'] . '/profile_image/', 0777, TRUE);
        }
        $profile_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/profile_image/' . $profile_image_name;

        if (!move_uploaded_file($profile_image_source, $profile_image_target_path)) {
            $profile_image_name = '';
        }


        //End User Photo Insertion


        $signUpFiled = '';
        $signUpFiled .=' user_email = "' . mysqli_real_escape_string($con, $email) . '"';
        $signUpFiled .=', user_password = "' . mysqli_real_escape_string($con, securedPass($password)) . '"';
        $signUpFiled .=', user_hash = "' . mysqli_real_escape_string($con, $email) . '"';
        $signUpFiled .=', user_first_name = "' . mysqli_real_escape_string($con, $first_name) . '"';
        $signUpFiled .=', user_middle_name = "' . mysqli_real_escape_string($con, $middle_name) . '"';
        $signUpFiled .=', user_last_name = "' . mysqli_real_escape_string($con, $last_name) . '"';
        $signUpFiled .=', user_DOB = "' . mysqli_real_escape_string($con, ($year . '-' . $month . '-' . $day)) . '"';
        $signUpFiled .=', user_gender = "' . mysqli_real_escape_string($con, $gender) . '"';
        $signUpFiled .=', user_aboutme = "' . mysqli_real_escape_string($con, $about_me) . '"';
        $signUpFiled .=', user_profile_image = "' . mysqli_real_escape_string($con, $profile_image_name) . '"';
        $signUpFiled .=', user_address = "' . mysqli_real_escape_string($con, $address) . '"';
        $signUpFiled .=', user_phone = "' . mysqli_real_escape_string($con, $phone) . '"';
        $signUpFiled .=', user_last_login = "' . mysqli_real_escape_string($con, $email) . '"';
        if (isset($news_letter_check)) {
            //$signUpFiled .=', user_news_letter_sent = "' . mysqli_real_escape_string($con, 'active') . '"';
        }
        $signUpSql = "INSERT INTO users SET $signUpFiled";
        $signUpSqlResult = mysqli_query($con, $signUpSql);
        if (isset($news_letter_check)) {
            $newsletterSql = "INSERT INTO subscribe_information (subscribe_email, subscribe_date, status) VALUES('$email','" . gmdate("Y-m-d H:i:s") . "','active')";
            $newsletterSqlResult = mysqli_query($con, $newsletterSql);
            if (!$newsletterSqlResult) {
                echo 'newsletter Information Not saved' . mysqli_error($con);
            }
        }
        if ($signUpSqlResult) {
            $msg='Users Information Saved successfully Please Check Your Email For Next Time Login';


            require(basePath("lib/class.phpmailer.php"));

            $mail = new PHPMailer();

            $mail->IsSMTP(); // send via SMTP

            $mail->SMTPDebug = 1;

            //IsSMTP(); // send via SMTP

            $mail->SMTPAuth = true; // turn on SMTP authentication

            $mail->Username = "bluetest"; // Enter your SMTP username

            $mail->Password = "bluepass2012"; // SMTP password

            $webmaster_email = "no-reply@lyric.com"; //Add reply-to email address

            $email = $givenEmail; // Add recipients email address

            $name = $email; // Add Your Recipient's name

            $mail->From = 'murad@bscheme.com';

            $mail->FromName = 'murad@bscheme.com';

            $mail->AddAddress($email, $name);

            $mail->AddReplyTo($webmaster_email, "Webmaster");

            //$mail->extension=php_openssl.dll;

            $mail->WordWrap = 50; // set word wrap

            /* $mail->AddAttachment("/var/tmp/file.tar.gz"); // attachment

              $mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment */

            $mail->IsHTML(true); // send as HTML



            $mail->Subject = "You have a message from murad@bscheme.com";



            $mail->Body = '

		<html>

		' . $email . ' has sent below message to you.<br /><br />
		
		<a href=' . baseUrl("account_activation_check.php?email=$givenEmail&hashKey=$randomString&email=$email") . '>Please Click This Link<br /><br />
		
		

		</html>';      //HTML Body



            $mail->AltBody = $mail->Body;     //Plain Text Body

            if (!$mail->Send()) {

                $err = "Internal error. Try again later.";
                echo'Internal error. Try again later.';
            } else {

                $msg = "Thank you. Please check your email.";


                $_SESSION["user_name"] = $first_name . " " . $last_name;
                $_SESSION["mail_address"] = $email;
                $userIdSqlResult = mysqli_query($con, "SELECT max(user_id) AS maxid FROM users");
                $userIdSqlResultRowObj = mysqli_fetch_object($userIdSqlResult);
                $user_id = $userIdSqlResultRowObj->maxid;
                $_SESSION["user_id"] = $user_id;

                //checking the action status and redirecting accordingly
                if (isset($_GET['action']) && $_GET['action'] == 'checkout') {
                    $link = "shipping_billing.php?msg=".base64_encode('Users Information Saved successfully Please Check Your Email For Next Time Login');
                    redirect($link);
                } else {
                    $link = "account.php?msg=".base64_encode('Users Information Saved successfully Please Check Your Email For Next Time Login');
                    redirect($link);
                }
            }
        } else {
            if (DEBUG) {
                echo '$subscribeInsSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Insert Query failed.";
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
            <title>Tuizo||Sign up</title>
            <?php
            include("header.php");
            ?>
    </head>

    <body>

        <div id="wrapper">

            <?php
            include("menu.php");
            ?>
            <!-- header end -->

            <div id="innerContainer" >
                <div class="cartContainner signUpContainner">
                    <div class="signUpContainerLft">
                        <h1 class="x-large">sign up</h1>
                        <div class="loginLink">
                            <p> DO YOU HAVE AN EXISTING UNIQLO ACCOUNT? </p>
                            <a href="sign_in.html">Login</a></div><!--loginLink-->
                        <?php if ($err != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage"><?php echo $err; ?></p></div><!--formError-->
                        <?php endif; /* ($err !='') */ ?>
                        <?php if ($msg != ''): ?>
                            <div class="formError" id="formError"><p id="formErrorMessage" style="color: green;"><?php echo $msg; ?></p></div><!--formError-->
<?php endif; /* ($err !='') */ ?>


                        <div class="formcondition">
                            <h3>ENTER YOUR INFORMATION BELOW</h3>
                            <p>Note: your name and billing address must be entered exactly as they appear on your credit card.</p>
                            <span><sup>*</sup> = required</span></div><!--formcondition-->

                        <div class="signUpForm">
                            <form name="signup" method="post" action="<?php echo baseUrl("sign_up.php"); ?>" enctype="multipart/form-data">
                                <table width="100%" border="0">
                                    <tr>
                                        <td width="30%"><label for="email">Email Address*</label> </td>
                                        <td width="70%"><input id="email" type="text" name="email" value="<?php echo$email; ?>">
                                                <p id="errormsg" style="color:green"></p></td>
                                        <input type='hidden' name='mail_checker' id='mail_checker'>
                                    </tr>
                                    <tr>
                                        <tr>
                                            <td width="30%"><label for="first_name">First Name*</label> </td>
                                            <td width="70%"><input id="first_name" type="text" name="first_name" value="<?php echo$first_name; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td width="30%"><label for="middle_name">Middle Name</label> </td>
                                            <td width="70%"><input id="middle_name" type="text" name="middle_name" value="<?php echo$middle_name; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td width="30%"><label for="last_name">Last Name*</label> </td>
                                            <td width="70%"><input id="last_name" type="text" name="last_name" value="<?php echo$last_name; ?>"></td>
                                        </tr>
                                        <tr>
                                            <td width="30%"><label for="Birth">Date of Birth*(day/month/year)</label></td>
                                            <td width="70%">
                                                <select id="Birth" style="width:15%;margin-right:2%;" placeholder="Day" type="text" name="day">
                                                    <option value="-1">day</option>
                                                    <?php
                                                    for ($i = 1; $i < 32; $i++) {
                                                        echo"<option value='$i'" . (($day == $i) ? 'selected' : '') . ">$i</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <select style="width:15%;margin-right:2%;" placeholder="Month" type="text" name="month">
                                                    <option value="-1"> Month</option>
                                                    <option value="01" <?php if ($month == '01') echo 'selected'; ?>>January</option> 
                                                    <option value="02" <?php if ($month == '02') echo 'selected'; ?>>February</option> 
                                                    <option value="03" <?php if ($month == '03') echo 'selected'; ?>>March</option> 
                                                    <option value="04" <?php if ($month == '04') echo 'selected'; ?>>April</option> 
                                                    <option value="05" <?php if ($month == '05') echo 'selected'; ?>>May</option> 
                                                    <option value="06" <?php if ($month == '06') echo 'selected'; ?>>June</option> 
                                                    <option value="07" <?php if ($month == '07') echo 'selected'; ?>>July</option> 
                                                    <option value="08" <?php if ($month == '08') echo 'selected'; ?>>August</option> 
                                                    <option value="09" <?php if ($month == '09') echo 'selected'; ?>>September</option> 
                                                    <option value="10" <?php if ($month == '10') echo 'selected'; ?>>October</option> 
                                                    <option value="11" <?php if ($month == '11') echo 'selected'; ?>>November</option> 
                                                    <option value="12" <?php if ($month == '12') echo 'selected'; ?>>December</option> 
                                                </select>
                                                <select style="width:15%;margin-left:2%;" type="text" placeholder="Year"  name="year">
                                                    <option value='-1'>Year</option>
                                                    <?php
                                                    for ($j = (date("Y") - 80); $j <= (date("Y")); $j++) {
                                                        echo"<option value = '$j'" . (($year == $j) ? 'selected' : '') . ">$j</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <td width="30%"><label for="Gender">Gender*</label> </td>
                                        <td valign="top" width="70%">
                                            <input name="gender" type="radio" id="male" style="width:4%;" <?php if ($gender == 'male') echo 'checked'; ?> value='male'>
                                                <label for="male"><strong>Male</strong></label>       

                                                <input name="gender" type="radio" id="female" style="width:4%;" <?php if ($gender == 'female') echo 'checked'; ?> value='female'>
                                                    <label for="female"><strong>Female</strong></label>                         




                                                    </td>
                                                    </tr>

                                                    <tr>
                                                        <td width="30%"><label for="about_me">About Me</label> </td>
                                                        <td width="70%"><textarea id="about_me" type="text" name="about_me"><?php echo $about_me; ?></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%"><label for="profile_image">Profile Image</label></td>
                                                        <td width="70%"><input id="profile_image" type="file" name="profile_image"></td>
                                                    </tr>

                                                    <tr>
                                                        <td width="30%"><label for="address">Address*</label></td>
                                                        <td width="70%"><input id="address" style="width:50%" type="text" value="<?php echo $address; ?>" name="address"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%"><label for="Phone">Phone*</label></td>
                                                        <td width="70%"><input id="phone" style="width:50%;" type="text" value="<?php echo $phone; ?>" name="phone"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%"><label for="Password">Password*</label></td>
                                                        <td width="70%"><input id="Password" type="password" name="password"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="30%"><label for="Confirm">Confirm Password*</label></td>
                                                        <td width="70%"><input id="Confirm" type="password" name="confirm_password"></td>
                                                    </tr>

                                                    </table>


                                                    </div><!--signUpForm-->

                                                    <div class="newsLetter">
                                                        <h6>NEWSLETTER</h6> 
                                                        <p><input id="news_letter_check" type="checkbox"  <?php if (isset($news_letter_check)) echo 'checked'; ?> name="news_letter_check" >


                                                                <label for="news_letter_check"> &nbsp;Brighten my inbox with Tuizo deals, events, and other updates.</label>
                                                        </p>
                                                    </div><!--newsLetter-->

                                                    <div class="newsLetter">
                                                        <h6>Terms + Conditions* </h6> 
                                                        <p><input id="TermsCnd" type="checkbox" <?php if (isset($terms_conditions)) echo 'checked'; ?> name="terms_conditions" >

                                                                <label for="TermsCnd">&nbsp;By joining, you agree to our TERMS AND CONDITIONS
                                                                    Yes, I agree to the Terms + Conditions </label> </p>
                                                    </div><!--newsLetter-->
                                                    <input type="submit" style="float:right;" class="signUpNext" name="next" value="next">

                                                        </div><!--signUpContainerLft-->
                                                        </form>
                                                        <?php
                                                        include("user_side_menu.php");
                                                        ?>

                                                        </div><!--cartContainner-->
                                                        </div><!--innerContainer-->
                                                        </div>

                                                        <?php
                                                        include("footer.php");
                                                        ?>


                                                        <!--
                                                        Start of checking Email Existence
                                                        Email Existing Check From users Table
                                                        -->           
                                                        <script>
                                                            $("#email").on('blur', function() {
                                                                txt = $(this).val();
                                                                if (txt != '') {
                                                                    $.post("ajax/sign_up.php", {emailcheck: txt}, function(result) {
                                                                        $("#errormsg").html(result);
                                                                        $("#mail_checker").val(result);
                                                                        if (result != 'You Can Use This Email Address') {
                                                                            $("#email").focus();
                                                                        }
                                                                    });
                                                                } else if (txt == '') {
                                                                    $("#errormsg").html('');
                                                                }
                                                            });
                                                        </script>
                                                        <!--
                                                        End of checking Email Existence
                                                        -->

                                                        </body>
                                                        </html>