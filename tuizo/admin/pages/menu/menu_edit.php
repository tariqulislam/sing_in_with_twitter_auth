<?php

include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$edit_menu_id = 0;

if(isset($_REQUEST['id'])) {
 $edit_menu_id = base64_decode($_REQUEST['id']);
}else{
    $link= 'index.php?msg='.base64_encode('ID missing.');
    redirect($link);
}
$menu_title = '';
$menuSql = "SELECT * FROM menu WHERE menu_id=" . intval($edit_menu_id);
$menuSqlResult = mysqli_query($con, $menuSql);
if($menuSqlResult) {
    $menuSqlResultRowObj = mysqli_fetch_object($menuSqlResult);
    if(isset($menuSqlResultRowObj->menu_id)){
        $menu_title = $menuSqlResultRowObj->menu_title;                 
    }
} else {
    if(DEBUG) {
      echo "menuSqlResult error : " . mysqli_error($con);  
    } else {
        $link = baseUrl('admin/pages/menu/index.php?err=' . base64_encode('Edit sql fail.'));
        redirect($link);
    }
}
if (isset($_POST['menu_edit']) AND $_POST['menu_edit'] == 'Submit') {

    extract($_POST);
        if ($menu_title == '') {
        $err = 'Menu Title field is required!!';
    } else {
        $menuCheckSql = "SELECT menu_title FROM menu WHERE menu_title='" . mysqli_real_escape_string($con, $menu_title) . "'  AND menu_id!='".$edit_menu_id."'";
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
            $menuInfoInsSql = "UPDATE menu SET $menuInfoFiled where menu_id=".intval($edit_menu_id);
            $menuInfoFiledResult = mysqli_query($con, $menuInfoInsSql);
            if ($menuInfoFiledResult) {
                $msg = "Menu Information Update successfully";
            } else {
                if (DEBUG) {
                    echo 'menuInfoUpdateSqlResult Error: ' . mysqli_error($con);
                }
                $err = "Insert Query failed.";
            }
        }
    }
    
        $responses = array();
$getset = mysqli_query($con, "SELECT * FROM config_settings");
if (mysqli_num_rows($getset) > 0) {

    while ($row = mysqli_fetch_object($getset)) {
        $responses[$row->CS_option] = $row->CS_value;
    }
}

$titlelimit = $responses['MENU_TITLE_CHARACTER_LIMIT'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Menu Update</title>   
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
                            <form action="<?php echo baseUrl('admin/pages/menu/menu_edit.php?id=').  base64_encode($edit_menu_id); ?>" method="post" enctype="multipart/form-data" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Menu</h5></div>
                                        <div class="rowElem noborder"><label> Menu Title:</label><div class="formRight"><input type="text" name="menu_title" maxlength="<?php echo $titlelimit;?>" placeholder="maximum<?php echo $titlelimit;?> character is allowed" value="<?php echo $menu_title; ?>"  /></div><div class="fix"></div></div>    
                                        <input type="submit" name="menu_edit" value="Submit" class="greyishBtn submitForm" />
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
