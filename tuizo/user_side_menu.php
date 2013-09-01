<div class="cartContainnerRight">
    <div class="clear"></div>
    <ul>
        <?php
        if(checkUserLogin()) {
            ?>
        <li><a style="border-top:1px solid #333;" href="account.php">MY ACCOUNT</a></li>
        <li><a style="border-top:1px solid #333;" href="addresses.php">MY ADDRESSES</a></li>
        <li><a style="border-top:1px solid #333;" href="order_history.php">MY ORDERS</a></li>
        <?php
        }
        ?>
        <li><a style="border-top:1px solid #333;" href="returns.php">RETURNS</a></li>
        <li><a href="#">SHIPPING</a></li>
        <li><a href="faq.php">FAQS</a></li>
        <li><a href="conditions.php">TERMS + CONDITIONS</a></li>
    </ul>
    <div class="customerHelp"><a href="#"><img src="images/help.png" width="160" height="100" alt="help"></a></div><!--customerHelp-->
</div><!--cartContainnerRight-->