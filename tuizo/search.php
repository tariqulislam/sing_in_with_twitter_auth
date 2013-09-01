<?php
include('./config/config.php');
$pageTitle = 'Tuizo | Package';
$pageDescription = 'Tuizo Search';
$pageKeywords = 'Tuizo, bangladesh, Garments';
$SearchString = '';
$totalSearch = '';
$per_page = 10;
$number_of_page = '';
$searchArray = array();
$package_arrays = array();
$category_arrays = array();
$product_arrays = array();
$searchArray['package_arrays'] = '';
$searchArray['category_arrays'] = '';
$searchArray['product_arrays'] = '';
if (isset($_GET['SearchString'])) {
    extract($_GET);
    /** Start: Query for package Array * */
    $query_of_package_array = "SELECT 
      p.package_id,p.package_name,p.package_description
        FROM
               packages p
        WHERE p.package_name LIKE '%" . mysqli_real_escape_string($con, trim($SearchString)) . "%' OR p.package_description LIKE '%" . mysqli_real_escape_string($con, trim($SearchString)) . "%'";
    $result_of_package_array = mysqli_query($con, $query_of_package_array);
    if ($result_of_package_array) {
        while ($row = mysqli_fetch_object($result_of_package_array)) {
            $package_arrays[] = $row;
        }
    }
    $searchArray['package_arrays'] = $package_arrays;
    /** End: Query for package Array * */
    /** Start: Query of category Array * */
//    $query_of_category_array = "SELECT 
//                        c.category_id,c.category_name
//                  FROM
//                        
//                        categories c
//                  WHERE 
//                  c.category_name LIKE '%" . mysqli_real_escape_string($con, trim($SearchString)) . "%'";
//    $result_of_category_array = mysqli_query($con, $query_of_category_array);
//    if ($result_of_category_array) {
//        while ($row1 = mysqli_fetch_object($result_of_category_array)) {
//            $category_arrays[] = $row1;
//        }
//    }
//    $searchArray['category_arrays'] = $category_arrays;
    /** END: query of category array * */
    /** Start: Query of Product array * */
    $query_of_product_array = "SELECT 
    p.product_id, p.product_title,p.product_long_description
        FROM

            products p
        WHERE 
        p.product_title LIKE '%" . mysqli_real_escape_string($con, trim($SearchString)) . "%' OR p.product_long_description LIKE '%" . mysqli_real_escape_string($con, trim($SearchString)) . "%'";
    $result_of_product_array = mysqli_query($con, $query_of_product_array);
    if ($result_of_product_array) {
        while ($row2 = mysqli_fetch_object($result_of_product_array)) {
            $product_arrays[] = $row2;
        }
    }
    $searchArray['product_arrays'] = $product_arrays;
    /** End: Query of product array * */
    $searchCount = count($package_arrays) + count($category_arrays) + count($product_arrays);
    $number_of_page = $searchCount / $per_page;
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>

        <title><?php echo $pageTitle; ?> </title>
        <meta name="description" content="<?php echo $pageDescription; ?>">
            <meta name="keywords" content="<?php echo $pageKeywords ?>">
                <meta name="author" content="Ståle Refsnes">
                    <?php include(basePath('header.php')); ?>
                    </head>

                    <body>

                        <div id="wrapper">
                            <?php include("menu.php"); ?>

                            <div style="clear:both"></div>

                            <div id="innerContainer" >
                                <div class="searchContainner">
                                    <div class="searchTitle"><h1>Search results for Package</h1></div>
                                    
                                     <?php
                                    foreach ($package_arrays as $p) {
                                        ?>
                                        <div class="searchProduct">
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td><div class="search_des">
                                                            <a href="<?php $url="package.php?package_id=$p->package_id&package_name=$p->package_name"; echo baseUrl($url);?>"><?php echo $p->package_name; ?></a>
                                                            <p><?php echo $p->package_description; ?>.</p></div></td>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!--searchProduct-->

                                        <?php
                                    }
                                    ?>
                                    <div class="searchTitle"><h1>Search results for Product</h1></div>
                                    <?php
                                    $i=0;
                                    foreach ($product_arrays as $po) {
                                        ?>
                                        <div class="searchProduct">
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td><div class="search_des">
                                                            <a href="#"><?php echo $po->product_title; ?></a>
                                                            <p><?php echo $po->product_long_description; ?>.</p></div></td>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div><!--searchProduct-->

                                        <?php
                                    }
                                    ?>
                                   
                                     
                                </div>




                                <div style="clear:both;"></div>
                                <div class="CategoryPagination">
<!--                                    <ul> 
                                        <?php
//                                        if ($number_of_page > 1) {
//                                            ?>
                                            <li><a href="#">Next</a></li>
                                            //<?php
//                                            $x = 1;
//                                            for ($i = 0; $i < $number_of_page; $i++) {
//                                                ?>
                                                <li><a href="#">//<?php echo $x; ?></a></li>
                                                //<?php
//                                                $x++;
//                                            }
//                                            ?>
                                            <li><a href="#">Previous</a></li>
                                            //<?php
//                                        }
                                        ?>  
                                    </ul>
                                    <p>Showing 1 to 9 of 100 (10 Pages)</p>-->
                                </div><!--CategoryPagination-->
                            </div><!--searchContent-->



                        </div><!--searchContainner-->
                        </div><!--innerContainer-->
                        </div>

                        <?php include(basePath('footer.php')); ?>




                    </body>
                    </html>