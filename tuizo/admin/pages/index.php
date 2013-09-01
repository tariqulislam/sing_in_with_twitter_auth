<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}

//delete query here
if (isset($_REQUEST['del']) && isset($_REQUEST['id'])) {
    $del_page_id = base64_decode($_REQUEST['id']);
    $pageDeleleteSql = "delete from pages where page_id=" . intval($del_page_id);
    $pageDeleleteSqlResult = mysqli_query($con, $pageDeleleteSql);
    if ($pageDeleleteSqlResult) {
        $msg = "Page Acount Successfully Deleted";
    } else {
        if (DEBUG) {
            $err = "pageDeleleteSqlResult ERROR : " . mysqli_error($con);
        } else {
            $err = "Page Information Not Deleted";
        }
    }
}
$pageArray = array();
$pageSql = "select * from pages";
$pageSqlResult = mysqli_query($con, $pageSql);
if ($pageSqlResult) {
    while ($pageSqlResultRowObj = mysqli_fetch_object($pageSqlResult)) {
        $pageArray[] = $pageSqlResultRowObj;
    }
    mysqli_free_result($pageSqlResult);
} else {
    if (DEBUG) {
        echo 'pageSqlResult Error : ' . mysqli_error($con);
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | Pages List</title>   
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
            <?php include 'pages_left_navigation.php'; ?>

            <!-- Content Start -->
            <div class="content">




                <div class="title"><h5>Page Module </h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="table">
                    <div class="head"><h5 class="iFrames">Page List</h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr>
                                <th>Page Title</th>
                                <th>Page Url</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pageArrayCounter = count($pageArray);
                            if ($pageArrayCounter > 0):
                                for ($i = 0; $i < $pageArrayCounter; $i++):
                                    ?><tr class="gradeA"> 
                                        <td><?php echo $pageArray[$i]->page_title; ?></td>
                                        <td><?php echo "<a href=" . $pageArray[$i]->page_url . " target='_blank'>" . $pageArray[$i]->page_url . "</a>"; ?></td>
                                        <td class="center">
                                            <a href="pages_edit.php?id=<?php echo base64_encode($pageArray[$i]->page_id); ?>"><img src="<?php echo baseUrl('admin/images/pencil-grey-icon.png'); ?>" height="14" width="14" alt="Edit" /></a>&nbsp;
                                            <a href="<?php if ($pageArray[$i]->page_type == 'user-created') {
                                echo 'index.php?del=yes&id=' . base64_encode($pageArray[$i]->page_id);
                            } else {
                                echo 'javascript:void(0);';
                            } ?>" title="Edit" href="index.php?del=yes && id=<?php echo base64_encode($pageArray[$i]->page_id); ?>"><img src="<?php echo baseUrl('admin/images/deleteFile.png'); ?>" height="14" width="14" alt="delete" onclick="return confirm('Are you sure want to delete?');" /></a></td></tr>
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
