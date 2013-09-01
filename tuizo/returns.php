<?php
include("config/config.php");

$pageName = 'returns';
$pageTitle = 'Tuizo | Returns';
$pageDescription = 'Tuizo Description';
$pageKeywords = 'Tuizo, bangladesh, Garments';
$returnsSql = "SELECT * FROM pages WHERE page_title='$pageName'";
$returnsSqlResult = mysqli_query($con, $returnsSql);
if ($returnsSqlResult) {
    $returnsSqlResultRowObj = mysqli_fetch_object($returnsSqlResult);
    if (isset($returnsSqlResultRowObj->page_body)) {
        $returnsPageBody = html_entity_decode($returnsSqlResultRowObj->page_body);
        $pageTitle = $returnsSqlResultRowObj->page_title;
        $pageDescription = $returnsSqlResultRowObj->page_meta_description;
        $pageKeywords = $returnsSqlResultRowObj->page_meta_keywords;
        
    } else {
        echo "some thing wrong";
    }
} else {
    if (DEBUG) {
        echo 'returnsSqlResult Error: ' . mysqli_error($con);
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
            <title><?php echo $pageTitle;?> </title>
            <meta name="description" content="<?php echo $pageDescription; ?>">
                <meta name="keywords" content="<?php echo $pageKeywords ?>">
                    <meta name="author" content="Ståle Refsnes">
                        <?php include(basePath('header.php')); ?>
                        <link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" />



                        </head>

                        <body>

                            <div id="wrapper">

                                <?php include("menu.php"); ?>

                                <div id="innerContainer" >
                                    <div class="cartContainner">
                                        <?php
                                        echo $returnsPageBody;
                                        ?>
                                        <?php include(basePath('user_side_menu.php')); ?>

                                    </div><!--cartContainner-->
                                </div><!--innerContainer-->
                            </div>

                            <?php include(basePath('footer.php')); ?>



<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script>
		(function($){
			$(window).load(function(){
				$(".scrollContent").mCustomScrollbar(
				{
				//theme:"dark".
				theme:"dark"	
				}
				
				);
				/*demo fn*/
				
			});
		})(jQuery);
	</script>
                        </body>
                        </html>