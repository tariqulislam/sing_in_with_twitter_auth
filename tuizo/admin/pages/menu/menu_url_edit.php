<?php

include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$edit_url_id = 0;
if(isset($_REQUEST['id'])) {
 $edit_url_id = base64_decode($_REQUEST['id']);
 $menu_title_request = base64_decode($_REQUEST['title']);
}else{
    $link= 'index.php?msg='.base64_encode('ID missing.');
    redirect($link);
}
$menu_title = '';
$menu_url = '';
$priority = '';
$menuSql = "SELECT * FROM menu_url WHERE MU_id=" . intval($edit_url_id);
$menuSqlResult = mysqli_query($con, $menuSql);
if($menuSqlResult) {
    $menuSqlResultRowObj = mysqli_fetch_object($menuSqlResult);
    if(isset($menuSqlResultRowObj->MU_id)){
        $MU_id = $menuSqlResultRowObj->MU_id;
        $main_menu_id = $menuSqlResultRowObj->MU_menu_id;
        $menu_title = $menuSqlResultRowObj->MU_url_title;
        $menu_url = $menuSqlResultRowObj->MU_url;
        $priority = $menuSqlResultRowObj->MU_priority;
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
    }   elseif ($menu_url == '') {
        $err = 'Menu Url field is required!!';
    }   else if (!filter_var($menu_url, FILTER_VALIDATE_URL)) {
        $err = 'Valid URL is required!!';
    }   elseif ($priority == '') {
        $err = 'Priority field is required!!';
    }   else if(!is_numeric($priority)) {
        $err = 'Priority field is must integer!!';
    }  else {
        $urlCheckSql="SELECT MU_url_title FROM menu_url WHERE MU_url_title='" . mysqli_real_escape_string($con, $menu_title) . "' AND MU_id !='". $MU_id ."' AND MU_menu_id='".$main_menu_id."'";
        $urlCheckSqlResult=  mysqli_query($con,$urlCheckSql);
        if($urlCheckSqlResult)
        {
            $urlCheckSqlResultRowObj=mysqli_fetch_object($urlCheckSqlResult);
            if(isset($urlCheckSqlResultRowObj->MU_url_title) && $urlCheckSqlResultRowObj->MU_url_title==$menu_title)
            {
                $err = 'Menu name (<b>' . $menu_title . '</b>) already exist in our databse ';
            }
            mysqli_free_result($urlCheckSqlResult);
        } else {
            if (DEBUG) {
                echo 'urlCheckSqlResult Error: ' . mysqli_error($con);
				
            }
			$err = "Query failed.";
        }

    }

    if ($err == '') {
                
            $menuInfoFiled = '';
            $menuInfoFiled .=' MU_url_title = "' . mysqli_real_escape_string($con, $menu_title) . '"';
            $menuInfoFiled .=', MU_url = "' . mysqli_real_escape_string($con, $menu_url) . '"';
            $menuInfoFiled .=', MU_priority = "' . mysqli_real_escape_string($con, $priority) . '"';
            $menuInfoInsSql = "UPDATE menu_url SET $menuInfoFiled where MU_id=".intval($edit_url_id);
            $menuInfoFiledResult = mysqli_query($con, $menuInfoInsSql);
            if ($menuInfoFiledResult) {  
                
                 $link = 'menu_url.php?id='.$_REQUEST['menu_id'].'&msg='.  base64_encode('Update successfully').'&title='. base64_encode($menu_title_request);
                redirect($link);             
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
        <title>Admin Panel | Product</title>   
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
                <div class="title"><h5>Menu Url Update</h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Edit Url For <?php echo $menu_title_request;?></h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/pages/menu/menu_url_edit.php?id=').  base64_encode($edit_url_id); ?>&menu_id=<?php echo $_REQUEST['menu_id'];?>&& title=<?php echo base64_encode($menu_title_request);?>" method="post" enctype="multipart/form-data" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Url</h5></div>
                                        <div class="rowElem noborder"><label> Url Title:</label><div class="formRight"><input type="text" name="menu_title" maxlength="<?php echo $titlelimit;?>" placeholder="maximum<?php echo $titlelimit;?> character is allowed" value="<?php echo $menu_title; ?>"  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> Url Link:</label><div class="formRight"><input type="text" name="menu_url" value="<?php echo $menu_url; ?>"  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> Url Priority:</label><div class="formRight"><input type="text" name="priority" value="<?php echo $priority; ?>"  /></div><div class="fix"></div></div>
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
