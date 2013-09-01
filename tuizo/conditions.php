<?php
include("config/config.php");
$pageName = 'conditions';
$pageTitle = 'Tuizo | Conditions';
$pageDescription = 'Tuizo Description';
$pageKeywords = 'Tuizo, bangladesh, Garments';
$conditionsSql = "SELECT * FROM pages WHERE page_title='$pageName'";
$conditionsSqlResult = mysqli_query($con, $conditionsSql);
if ($conditionsSqlResult) {
    $conditionsSqlResultRowObj = mysqli_fetch_object($conditionsSqlResult);
    if (isset($conditionsSqlResultRowObj->page_body)) {
        $conditionsPageBody = html_entity_decode($conditionsSqlResultRowObj->page_body);
        $pageTitle = $conditionsSqlResultRowObj->page_title;
        $pageDescription = $conditionsSqlResultRowObj->page_meta_description;
        $pageKeywords = $conditionsSqlResultRowObj->page_meta_keywords;
    } else {
        echo "some thing wrong";
    }
} else {
    if (DEBUG) {
        echo 'aboutSqlResult Error: ' . mysqli_error($con);
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
            <title> <?php echo $pageTitle; ?> </title>
            <meta name="description" content="<?php echo $pageDescription; ?>">
                <meta name="keywords" content="<?php echo $pageKeywords ?>">
                    <meta name="author" content="Ståle Refsnes">

                        <?php include(basePath('header.php')); ?>
                        <link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" />
                        <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
                        </head>

                        <body>

                            <div id="wrapper">

                                <?php include("menu.php"); ?>	
                                <!-- header end -->

                                <div id="innerContainer" >
                                    <div class="cartContainner">
                                        <div class="cartContainnerLft returnContainer">


                                            <h1 class="x-large">terms + conditions</h1>

                                            <?php
                                            echo $conditionsPageBody;
                                            ?>
                                        </div>
                                        <?php include ('user_side_menu.php'); ?>

                                    </div><!--cartContainner-->
                                </div><!--innerContainer-->
                            </div>

                            <?php include(basePath('footer.php')); ?>




                        </body>
                        </html>