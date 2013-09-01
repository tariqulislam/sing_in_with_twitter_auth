<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

$menu_title = '';
    $responses = array();
$getset = mysqli_query($con, "SELECT * FROM config_settings");
if (mysqli_num_rows($getset) > 0) {

    while ($row = mysqli_fetch_object($getset)) {
        $responses[$row->CS_option] = $row->CS_value;
    }
}

$titlelimit = $responses['MENU_TITLE_CHARACTER_LIMIT'];
 
if (isset($_POST['menu_create']) AND $_POST['menu_create'] == 'Submit') {

    extract($_POST);
    if ($menu_title == '' || $menu_title=="maximum$titlelimit character is allowed") {
        $err = 'Menu Title field is required!!';
    } else {
        $menuCheckSql="SELECT menu_title FROM menu WHERE menu_title='" . mysqli_real_escape_string($con, $menu_title) . "'";
        $menuCheckSqlResult=  mysqli_query($con,$menuCheckSql);
        if($menuCheckSqlResult)
        {
            $menuCheckSqlResultRowObj=mysqli_fetch_object($menuCheckSqlResult);
            if(isset($menuCheckSqlResultRowObj->menu_title) && $menuCheckSqlResultRowObj->menu_title==$menu_title)
            {
                $err = 'Menu name (<b>' . $menu_title . '</b>) already exist in our databse ';
            }
            mysqli_free_result($menuCheckSqlResult);
        } else {
            if (DEBUG) {
                echo 'menuCheckSqlResult Error: ' . mysqli_error($con);
				
            }
			$err = "Query failed.";
        }

    }


    if ($err == '') {
            $menuInfoFiled = '';
            $menuInfoFiled .=' menu_title = "' . mysqli_real_escape_string($con, $menu_title) . '"';
            $menuInfoInsSql = "INSERT INTO menu SET $menuInfoFiled";
            $menuInfoFiledResult = mysqli_query($con, $menuInfoInsSql);
            if ($menuInfoFiledResult) {
                //$msg = "Menu information insert successfully";
                 $link = 'index.php?msg='.  base64_encode('Menu Information Successfully added');
                redirect($link);
            } else {
                if (DEBUG) {
                    echo 'menuInfoInsSqlResult Error: ' . mysqli_error($con);
                }
                $err = "Insert Query failed.";
            }
        }
    }
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Menu Create</title>   
        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" /> 
        <script src="<?php echo baseUrl('admin/js/jquery.min.js'); ?>" type="text/javascript"></script>  
        <!--tree view -->  
        <script src="<?php echo baseUrl('admin/js/treeViewJquery.min.js'); ?>"></script> 
        <script src ="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type = "text / javascript" ></script>   
        <!--tree view --> 
        <!--Start admin panel js/css --> 
<?php include basePath('admin/header.php'); ?>   
        <!--End admin panel js/css -->               

    </head>

    <body>

<?php include basePath('admin/top_navigation.php'); ?>

        <?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
<?php include ('../pages_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Menu Module</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Create Menu </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/pages/menu/menu_create.php'); ?>" method="post" enctype="multipart/form-data" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Menu</h5></div>
                                        <div class="rowElem noborder"><label> Menu Title:</label><div class="formRight"><input type="text" name="menu_title"  value="<?php echo $menu_title; ?>" maxlength="<?php echo $titlelimit;?>" placeholder="maximum<?php echo $titlelimit;?> character is allowed"  /></div><div class="fix"></div></div>    
                                        <input type="submit" name="menu_create" value="Submit" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                    </div>
                                </fieldset>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>
<?php include basePath('admin/footer.php'); ?>
