<?php
include("config/config.php");
$pageName = 'about';
$pageTitle = 'Lyric Group | Raech Us';
$pageDescription = 'Lyric Group Description';
$pageKeywords = 'Lyric, bangladesh, Garments';
$aboutSql = "SELECT * FROM pages WHERE page_title='$pageName'";
$aboutSqlResult = mysqli_query($con, $aboutSql);
if ($aboutSqlResult) {
    $aboutSqlResultRowObj = mysqli_fetch_object($aboutSqlResult);
    if (isset($aboutSqlResultRowObj->page_body)) {
        $aboutPageBody = html_entity_decode($aboutSqlResultRowObj->page_body);
        $pageTitle = $aboutSqlResultRowObj->page_title;
        $pageDescription = $aboutSqlResultRowObj->page_meta_description;
        $pageKeywords = $aboutSqlResultRowObj->page_meta_keywords;
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
            <title> <?php echo $pageTitle;?></title>
            <meta name="description" content="<?php echo $pageDescription;?>">
                <meta name="keywords" content="<?php echo $pageKeywords?>">
                    <meta name="author" content="Ståle Refsnes">
                        <?php include(basePath('header.php')); ?>
                        <link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" />
                        <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
                        </head>

                        <body>

                            <div id="wrapper">

                                <?php include("menu.php"); ?>

                                <div style="clear:both"></div>

                                <?php
                                echo$aboutPageBody;
                                ?>
                            </div>

                            <?php include(basePath('footer.php')); ?>



                        </body>
                        </html>