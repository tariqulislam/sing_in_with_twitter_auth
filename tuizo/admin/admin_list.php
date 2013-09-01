<?php
include ('../config/config.php');
if(!checkAdminLogin()){
    $link=  baseUrl('admin/index.php?err='.  base64_encode('Please login to access admin panel'));
    redirect($link);
}
$adminArray = array();
$adminSql = "SELECT * FROM admins";
$adminSqlResult = mysqli_query($con, $adminSql);
if ($adminSqlResult) {
    while ($adminSqlResultRowObj = mysqli_fetch_object($adminSqlResult)) {
        $adminArray[] = $adminSqlResultRowObj;
    }
    mysqli_free_result($adminSqlResult);
} else {
    if (DEBUG) {
        echo 'adminSqlResult Error : ' . mysqli_errno($con);
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico')?>" />
        <title>Admin panel: Admin list</title>

        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />
        <script src="<?php echo baseUrl('admin/js/jquery-1.4.4.js');?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/spinner/ui.spinner.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery-ui.min.js');?>"></script>  
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/fileManager/elfinder.min.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/jquery.wysiwyg.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/dataTables/jquery.dataTables.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/dataTables/colResizable.min.js');?>"></script>
        <!--Effect on left error menu, top message menu,-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/forms.js');?>"></script>
        <!--Effect on left error menu, top message menu,-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/autogrowtextarea.js');?>"></script>
        <!--Effect on left error menu, top message menu, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/autotab.js');?>"></script>
        <!--Effect on left error menu, top message menu,-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/jquery.validationEngine.js');?>"></script>
        <!--Effect on left error menu, top message menu,-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/colorPicker/colorpicker.js');?>"></script>
        <!--Effect on left error menu, top message menu-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/jquery.plupload.queue.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/ui/jquery.tipsy.js');?>"></script>
        <!--Effect on left error menu, top message menu-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jBreadCrumb.1.1.js');?>"></script>
        <!--Effect on left error menu, top message menu-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/cal.min.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.collapsible.min.js');?>"></script>   
        <!--Effect on left error menu-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.ToTop.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.listnav.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.sourcerer.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/custom.js');?>"></script>
        <!--Effect on left error menu, top message menu, pagination, search of body, and grid of body-->

    </head>

    <body>

        <?php include 'top_navigation.php'; ?>

        <?php include 'module_link.php'; ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include 'left_navigation.php'; ?>

            <!-- Content Start -->
            <div class="content">




                <div class="title"><h5> Admin Module </h5></div>

                <!-- Notification messages -->
                <?php include 'message.php'; ?>
                <!-- Charts -->
                <div class="table">
                    <div class="head"><h5 class="iFrames">Admin List</h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $adminArrayCounter = count($adminArray);
                            if ($adminArrayCounter > 0):
                                ?>
                                <?php for ($i = 0; $i < $adminArrayCounter; $i++): ?>
                                    <tr class="gradeA">
                                        <td><?php echo $adminArray[$i]->admin_email; ?></td>
                                        <td><?php echo $adminArray[$i]->admin_full_name; ?></td>
                                        <td><?php echo $adminArray[$i]->admin_type; ?></td>
                                        <td class="center"><?php echo $adminArray[$i]->admin_status; ?></td>
                                        <td class="center">
                                            <a href="admin_view.php?id=<?php echo base64_encode($adminArray[$i]->admin_id); ?>">View</a>
                                            |
                                            <a href="admin_edit.php?id=<?php echo base64_encode($adminArray[$i]->admin_id); ?>">Eidt</a>
                                      
                                        </td>
                                    </tr>
                                <?php endfor; /* $i=0; i<$adminArrayCounter; $++  */ ?>
                            <?php else: /* count($adminArray) > 0 */ ?>
                                <tr class="gradeC">
                                    <td colspan="5" class="center">Data Not Found</td>
                                </tr>
                            <?php endif; /* count($adminArray) > 0 */ ?>


                        </tbody>
                    </table>
                </div>

            </div>


            <!-- Content End -->

            <div class="fix"></div>
        </div>

        <?php include 'footer.php'; ?>
