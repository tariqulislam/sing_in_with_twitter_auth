<?php
include("config/config.php");
$pageTitle = 'Tuizo | FAQ';
$pageDescription = 'Tuizo Description';
$pageKeywords = 'Tuizo, bangladesh, online shopping';
$faqArray = array();
$faqSql = "SELECT * FROM faq ORDER BY faq_priority DESC";
$faqSqlResult = mysqli_query($con, $faqSql);
if ($faqSqlResult) {
    while ($faqSqlResultRowObj = mysqli_fetch_object($faqSqlResult)) {
        $faqArray[] = $faqSqlResultRowObj;
    }
} else {
    if (DEBUG) {
        echo 'faqCheckSqlResult Error: ' . mysqli_error($con);
    }
    $err = "Query failed.";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title> <?php echo $pageTitle; ?></title>
            <meta name="description" content="<?php echo $pageDescription; ?>">
                <meta name="keywords" content="<?php echo $pageKeywords; ?>">
                    <meta name="author" content="Ståle Refsnes">

                        <?php include(basePath('header.php')); ?>
                        <link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" />
                        <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>

                        </head>

                        <body>

                            <div id="wrapper">
                                <?php
                                include("menu.php");
                                ?>

                                <div id="innerContainer" >
                                    <div class="faqContainner">
                                        <div class="faqTop clearfix">
                                            <h1 class="x-large">Frequently Asked Questions</h1>
                                            <p>every thing you really need to know about tuizo</p>
                                        </div><!--faqTop-->

                                        <div class="innerScrollfaq">
                                            <?php
                                            $faqArrayCounter = count($faqArray);
                                            if ($faqArrayCounter > 0) {
                                                for ($i = 0; $i < $faqArrayCounter; $i++) {
                                                    echo'<div class = "faqQuestionAns">';
                                                    echo "<h6>" . $faqArray[$i]->faq_question . "</h6>";
                                                    echo "<p>" . html_entity_decode($faqArray[$i]->faq_answer) . "</p>";
                                                    echo "</div>";
                                                }
                                            }
                                            ?>
                                        </div>


                                    </div><!--faqContainner-->
                                </div><!--innerContainer-->
                            </div>
                            
                            <!-- extra -->
                             </div>                            

                            <?php
                            include("footer.php");
                            ?>



                        </body>
                        </html>