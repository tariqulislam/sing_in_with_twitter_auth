<?php
include_once("config/config.php");
$customerCareArray = array();
$customerCareArraySql = "SELECT * FROM customer_care";
$customerCareArraySqlResult = mysqli_query($con, $customerCareArraySql);
if ($customerCareArraySqlResult) {
    $customerCareArraySqlResultRowObj = mysqli_fetch_object($customerCareArraySqlResult);
    $customerCareEmail = $customerCareArraySqlResultRowObj->customer_care_email;
    $customerCareAddress = $customerCareArraySqlResultRowObj->customer_care_address;
    $customerCareFacebookLink = $customerCareArraySqlResultRowObj->customer_care_facebook_link;
    $customerCareTwiterLink = $customerCareArraySqlResultRowObj->customer_care_twiter_link;
}

if(isset($_REQUEST['subscribe']) && $_REQUEST['subscribe'] == 'submit') {
        extract($_POST);

    if ($subscribe_email == '') {
        $err ='empty email'; 
        echo"<script>alert('Please Enter A Valid Email Address');</script>";
    } elseif (!isValidEmail($subscribe_email)) {
        $err = 'invalid email';
        echo"<script>alert('Please Enter A Valid Email Address');</script>";
    }
    if ($err == '') {
        $subscribeFiled = '';
        $subscribeFiled .=' subscribe_email = "' . mysqli_real_escape_string($con, $subscribe_email) . '"';
        $subscribeFiled .=', subscribe_date ="' .gmdate("Y-m-d H:i:s"). '"';
        $subscribeSql = "INSERT INTO subscribe_information SET $subscribeFiled";
        $subscribeSqlResult = mysqli_query($con, $subscribeSql);
        if ($subscribeSqlResult) {
            echo"<script>alert('Subscribe Information sent successfully');</script>";
        } else {
            if (DEBUG) {
                echo '$subscribeInsSqlResult Error: ' . mysqli_error($con);
            }
            $err = "Insert Query failed.";
        }
    }
}
?>
<div class="footer">
    <div class="footerBar bgcolor11">


        <div class="inner">
            <p class="show_hide icon-plus-sign"> about tuizo </p>

        </div>

    </div>

    <div class="fcontainer footerContainer" style="display:none">
        <div class="inner">

            <div class="footer-section mobileHide tabletShow">


                <h1 class="h3">Shop Secure</h1>
                <ul>
                    <li><a href="">Order Online</a></li>
                    <li><a href="<?php echo baseUrl("faq.php"); ?>">FAQ</a></li>
                    <li><a href="<?php echo baseUrl("about.php"); ?>">About</a></li>
                    <li><a href="<?php echo baseUrl("conditions.php"); ?>">Conditions</a></li>
<!--                    <li><a href="">Payments</a></li>
                    <li><a href="">Delivery</a></li>-->
                    <li><a href="<?php echo baseUrl("returns.php"); ?>">Returns</a></li>
<!--                    <li><a href="">General Conditions</a></li>
                    <li><a href="">Privacy Policy</a></li>
                    <li><a href="">Sitemap</a></li>
                    <li><a href="">Glossary</a></li>-->
                </ul>
            </div>

            <div class="footer-section">
                <h1 class="h3">Customer Care</h1>
                <?php
                echo html_entity_decode($customerCareAddress);
                ?>
            </div>

            <div class="footer-section">
                <h1 class="h3">Follow Us</h1>
                <ul class="sociallink">
                    
                    <li class="fb transitionall"><a target="_blank" href="<?php echo $customerCareFacebookLink;?>">Facebook</a></li>
                    <li class="tw transitionall"><a target="_blank" href="<?php echo $customerCareTwiterLink;?>">Twitter</a></li>

                </ul>
            </div>


            <section class="mobileHide tabletShow">
                <h1 class="h3">Newsletter</h1>

                <form class="newsletter" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <fieldset>
                        <input class="newsletterInput" name="subscribe_email" placeholder="Your email" type="text">
                        <input value="BD" class="newsLetterLang" name="lang" type="hidden">
                        <input name="subscribe" value="true" type="hidden">
                        <input class="submitDark subSmall" name="subscribe" value="submit" type="submit">
                    </fieldset>
                </form>

            </section>
            <div class="clear"></div>
            <div class="pcHide">
                <h1 class="h3">Newsletter</h1>

                <form class="newsletter" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <fieldset>
                        <input class="newsletterInput" name="subscribe_email" placeholder="Your email" type="text">
                        <input value="BD" class="newsLetterLang" name="lang" type="hidden">
                        <input name="subscribe" value="true" type="hidden">
                        <input class="submitDark subSmall" name="subscribe" value="submit" type="submit">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>


</div>