<?php
include ('../../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

//delete query here
if (isset($_REQUEST['del']) && isset($_REQUEST['id'])) {
    $del_menu_id = base64_decode($_REQUEST['id']);
    $menuDeleleteSql = "delete from menu where menu_id=" . intval($del_menu_id);
    $urlDeleteSql = "DELETE FROM menu_url where MU_menu_id =" . intval($del_menu_id);
    $menuDeleleteSqlResult = mysqli_query($con, $menuDeleleteSql);
    $urlDeleleteSqlResult = mysqli_query($con, $urlDeleteSql);
    if ($menuDeleleteSqlResult && $urlDeleleteSqlResult) {
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
$menuSql = "select * from menu";
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
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Menu List</title>   
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




                <div class="title"><h5>Menu Module </h5></div>

                <!-- Notification messages -->
<?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="table">
                    <div class="head"><h5 class="iFrames">Menu List</h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr>
                                <th>Menu Title</th>
                                <th>Menu links</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
$menuArrayCounter = count($menuArray);
if ($menuArrayCounter > 0):
    for ($i = 0; $i < $menuArrayCounter; $i++):
        ?><tr class="gradeA"> 
                                        <td><?php echo $menuArray[$i]->menu_title; ?></td>
                                        <td><a href="menu_url.php?id=<?php echo base64_encode($menuArray[$i]->menu_id); ?>&title=<?php echo base64_encode($menuArray[$i]->menu_title); ?>">Urls</a></td>
                                        <td class="center">
                                            <a href="menu_edit.php?id=<?php echo base64_encode($menuArray[$i]->menu_id); ?>"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png'); ?>" height="14" width="14" alt="Edit" /></a>&nbsp;
                                            <a href="index.php?del=yes && id=<?php echo base64_encode($menuArray[$i]->menu_id);  ?>" onclick="return confirm('Are you sure want to delete?');"><img src="<?php echo baseUrl('admin/images/deleteFile.png'); ?>" height="14" width="14" alt="delete" /></a>&nbsp;
                                           
                                        </td></tr>
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
