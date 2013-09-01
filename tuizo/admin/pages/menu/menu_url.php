<?php

include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$menu_id = 0;
$del_menu_id = 0;

$menu_title = '';
$menu_url = '';
$priority ='';
    
        $responses = array();
$getset = mysqli_query($con, "SELECT * FROM config_settings");
if (mysqli_num_rows($getset) > 0) {

    while ($row = mysqli_fetch_object($getset)) {
        $responses[$row->CS_option] = $row->CS_value;
    }
}

$titlelimit = $responses['MENU_TITLE_CHARACTER_LIMIT'];
if(isset($_REQUEST['id']) || isset($_REQUEST['del_id'])) {
 $menu_id = base64_decode($_REQUEST['id']);
 $menu_title_request = base64_decode($_REQUEST['title']);
 if(isset($_REQUEST['del_id'])) {
 $del_menu_id = base64_decode($_REQUEST['del_id']);
 $menu_title_request = base64_decode($_REQUEST['title']);
 }
} 
 else {
    $link= 'index.php?msg='.base64_encode('ID missing.');
    redirect($link);
}

if (isset($_POST['menu_url_submit']) AND $_POST['menu_url_submit'] == 'Submit') {

    extract($_POST);
    
        if ($menu_title == '' OR $menu_title =="maximum$titlelimit character is allowed") {
        $err = 'Menu Title field is required!!';
    } elseif ($menu_url == '') {
        $err = 'Menu Url field is required!!';
    }  else if (!filter_var($menu_url, FILTER_VALIDATE_URL)) {
        $err = 'Valid URL is required!!';
    }   elseif ($priority == '') {
        $err = 'Priority field is required!!';
    }   else if(!is_numeric($priority)) {
        $err = 'Priority field is must integer!!';
    } else {
        $urlCheckSql="select MU_url_title from menu_url where MU_url_title='" . mysqli_real_escape_string($con, $menu_title) . "' && MU_menu_id='". $menu_id ."'";
        $urlCheckSqlResult=  mysqli_query($con,$urlCheckSql);
        if($urlCheckSqlResult)
        {
            $urlCheckSqlResultRowObj=mysqli_fetch_object($urlCheckSqlResult);
            if(isset($urlCheckSqlResultRowObj->MU_url_title) && $urlCheckSqlResultRowObj->MU_url_title==$menu_title)
            {
                $err = 'This Url name (<b>' . $menu_title . '</b>) already exist in our databse ';
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
             
            $menuUrl = clickableUrl($menu_url);
            
            $menuInfoFiled = '';
            $menuInfoFiled .=' MU_menu_id = "' . mysqli_real_escape_string($con, $menu_id) . '"';
            $menuInfoFiled .=', MU_url_title = "' . mysqli_real_escape_string($con, $menu_title) . '"';
            $menuInfoFiled .=', MU_url = "' . mysqli_real_escape_string($con, $menuUrl) . '"';
            $menuInfoFiled .=', MU_priority = "' . mysqli_real_escape_string($con, $priority) . '"';
            $menuInfoInsSql = "INSERT INTO menu_url SET $menuInfoFiled";
            $menuInfoFiledResult = mysqli_query($con, $menuInfoInsSql);
            if ($menuInfoFiledResult) {
                $msg = "Menu Information Insert successfully";
            } else {
                if (DEBUG) {
                    echo 'menuInfoUpdateSqlResult Error: ' . mysqli_error($con);
                }
                $err = "Insert Query failed.";
            }
        }
    }




if (isset($_REQUEST['del']) && isset($_REQUEST['del_id'])) {
    $del_menu_id = base64_decode($_REQUEST['del_id']);
    $menuDeleleteSql = "DELETE FROM menu_url WHERE MU_id=" . intval($del_menu_id);
    $menuDeleleteSqlResult = mysqli_query($con, $menuDeleleteSql);
    if ($menuDeleleteSqlResult) {
        $msg = "Menu Acount Successfully Deleted";
    } else {
        if (DEBUG) {
            $err = "menuDeleleteSqlResult ERROR : " . mysqli_error($con);
        } else {
            $err = "Menu Information Not Deleted";
        }
    }
}
$menuArray = array();
$menuSql = "select * from menu_url where MU_menu_id=$menu_id";
$menuSqlResult = mysqli_query($con, $menuSql);
if ($menuSqlResult) {
    while ($menuSqlResultRowObj = mysqli_fetch_object($menuSqlResult)) {
        $menuArray[] = $menuSqlResultRowObj;
    }
    mysqli_free_result($menuSqlResult);
} else {
    if (DEBUG) {
        echo 'menuSqlResult Error : ' . mysqli_error($con);
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Menu Url List</title>   
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
                        <h5 class="iGraph">Create Url For <?php echo $menu_title_request;?></h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/pages/menu/menu_url.php?id=').  base64_encode($menu_id).'&& title='.base64_encode($menu_title_request); ?>" method="post" enctype="multipart/form-data" class="mainForm">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Url</h5></div>
                                        <div class="rowElem noborder"><label> Url Title:</label><div class="formRight"><input type="text" name="menu_title" maxlength="<?php echo $titlelimit;?>" placeholder="maximum<?php echo $titlelimit;?> character is allowed" value="<?php echo $menu_title; ?>"  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> URL Link:</label><div class="formRight"><input type="text" name="menu_url" value="<?php echo $menu_url; ?>"  /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder"><label> Priority:</label><div class="formRight"><input type="text" name="priority" value="<?php echo $priority; ?>"  /></div><div class="fix"></div></div>
                                        <input type="submit" name="menu_url_submit" value="Submit" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                    </div>
                                </fieldset>
                            </form>


                        </div>
                    </div>
                </div>

                
                                <div class="table">
                    <div class="head"><h5 class="iFrames">Url List For <?php echo $menu_title_request;?></h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr>
                                <th>Url Title</th>
                                <th>Url Link</th>
                                <th>Priority</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$menuArrayCounter = count($menuArray);
if ($menuArrayCounter > 0):
    for ($i = 0; $i < $menuArrayCounter; $i++):
        ?><tr class="gradeA"> 
                                        <td><?php echo $menuArray[$i]->MU_url_title; ?></td>
                                        <td><?php echo $menuArray[$i]->MU_url; ?></td>
                                        <td><?php echo $menuArray[$i]->MU_priority; ?></td>
                                        <td class="center">
                                            <a href="menu_url_edit.php?menu_id=<?php echo base64_encode($menuArray[$i]->MU_menu_id); ?>&id=<?php echo base64_encode($menuArray[$i]->MU_id); ?>  && title=<?php echo base64_encode($menu_title_request)?>"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png'); ?>" height="14" width="14" alt="Edit" /></a>&nbsp;
                                            <a href="menu_url.php?del=yes && del_id=<?php echo base64_encode($menuArray[$i]->MU_id); ?> && title=<?php echo base64_encode($menu_title_request)?> && id=<?php echo base64_encode($menu_id)?>" onclick="return confirm('Are you sure want to delete?');"><img src="<?php echo baseUrl('admin/images/deleteFile.png'); ?>" height="14" width="14" alt="delete" /></a></td></tr>
    <?php
    endfor;
endif;
?>

                        </tbody>
                    </table>
                </div>
                
                
            </div>
            <!-- Content End -->

            <div class="fix"></div>
        </div>
<?php include basePath('admin/footer.php'); ?>
