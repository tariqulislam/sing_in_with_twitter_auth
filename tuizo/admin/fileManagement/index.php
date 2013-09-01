<?php
include ('../../config/config.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$folder = '';
$root = 'fileManager';


$path = $config['IMAGE_UPLOAD_PATH'] . '/' . $root . '/';
if (isset($_REQUEST['folder']) AND $_REQUEST['folder'] != '') {
    $folder = trim($_REQUEST['folder'], '/') . '/';
}
if (!is_dir($path)) {
     mkdir($path, 0777, TRUE);
}

$path .= $folder;

$folder_name = '';
/* Start : Upload file */
if (isset($_REQUEST['fileUpload']) AND $_REQUEST['fileUpload'] == 'Upload') {
    /* Start : create folder */
    if ($_FILES["file"]["error"] == 0) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $path . '/' . basename($_FILES['file']['name']))) {
            $msg = "<b>{$_FILES['file']['name']}</b> uploaded successfully";
        } else {
            $err = "File could not upload";
        }
    }


    /* End : create folder */
}
/* End : Upload file */
/* Start : create folder file */
if (isset($_REQUEST['folder_create']) AND $_REQUEST['folder_create'] == 'Create Folder') {
    /* Start : create folder */

    if (isset($_POST['folder_name']) AND $_POST['folder_name'] != '') {
        $folder_name = trim($_POST['folder_name']);
        if (is_dir($path . '/' . $folder_name)) {
            $err = "<b>$folder_name</b> already exist!!";
        } else {
            if (!is_dir($path . '/' . $folder_name)) {
                if (mkdir($path . '/' . $folder_name, 0777, TRUE)) {
                    $msg = "<b>$folder_name</b> created successfully ";
                } else {
                    $err = "<b>$folder_name</b> could not create";
                }
            }
        }
    }

    /* End : create folder */
}
/* End : create folder */
/* Start: search foler or file to this directory  */
$folderArray = array();
$fileArray = array();
if (is_dir($path)) {
    /* checking user path correct or not */
    $sub_dir_array = array();
    if ($handle = opendir($path)) {
        $images_array['src'] = array();
        while (false !== ($entry = readdir($handle))) {
            if ($entry === '.' || $entry === '..') {
                continue;
            } else {
                if (is_dir($path . "/" . $entry)) {
                    /* if folder */
                    $folderArray[] = $entry;
                } else {
                    /* if file */
                    $fileArray[] = $entry;
                }
            }
        }
        closedir($handle);
    } else {
        $err = 'No file found';
    }
} else {
    $err = "wrong path you are accessing !!";
}
/* End: search foler or file to this directory  */
//printDie($folderArray);
//printDie($fileArray);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>   
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
        <title>Admin Panel | File Management</title>   
        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" /> 
        <script src="<?php echo baseUrl('admin/js/jquery.min.js'); ?>" type="text/javascript"></script>  
        <!--tree view -->  
        <script src="<?php echo baseUrl('admin/js/treeViewJquery.min.js'); ?>"></script> 
        <script src ="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type = "text / javascript" ></script>   
        <!--tree view --> 
        <!--Start admin panel js/css --> 
<?php include basePath('admin/header.php'); ?>   
        <!--End admin panel js/css -->               
        <script type="text/javascript">
            function copyLink(link) {
                var fname = prompt("Please copy this link:", link);
            }
        </script>
    </head>

    <body>

        <?php include basePath('admin/top_navigation.php'); ?>

<?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
<?php include 'fileManagement_left_navigation.php'; ?>

            <!-- Content Start -->
            <div class="content">




                <div class="title"><h5> File Management Module11 </h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>
                <?php
                if (isset($_REQUEST['folder']) AND $_REQUEST['folder'] != '') {
                    $prefixFolder = '';
                    $breadcrumbArray = explode('/', trim($_REQUEST['folder'], '/'));
                    //  printDie($breadcrumbArray);
                    $n = count($breadcrumbArray);
                    echo '<div style="font-size:17px;">';
                    echo '<span>';
                    echo '<a href="index.php?folder=' . $prefixFolder . '">Root&nbsp;&raquo;&nbsp;</a>';
                    echo '</span>';
                    $i = 0;
                    foreach ($breadcrumbArray AS $breadcrumb) {
                        $prefixFolder .=$breadcrumb . '/';
                        echo '<span>';
                        if (($i + 1) == $n) {
                            echo '<a style="color:#000;" href="index.php?folder=' . $prefixFolder . '">' . ucfirst($breadcrumb) . '</a>';
                        } else {
                            echo '<a href="index.php?folder=' . $prefixFolder . '">' . ucfirst($breadcrumb) . '&nbsp;&raquo;&nbsp;</a>';
                        }

                        echo '</span>';
                        $i++;
                    }
                    echo '</div>';
                }
                ?>

                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Create FAQ </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="<?php echo baseUrl('admin/fileManagement/index.php?folder=' . $folder); ?>" method="post" class="mainForm" enctype="multipart/form-data" >

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Upload file </h5></div>
                                        <div class="rowElem noborder"><label> Select file:</label><div class="formRight"><input type="file" name="file"  /></div><div class="fix"></div></div>

                                        <input type="submit" name="fileUpload" value="Upload" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                    </div>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Create Folder</h5></div>
                                        <div class="rowElem noborder"><label> Folder Name:</label><div class="formRight"><input type="text" name="folder_name" value="<?php echo $folder_name; ?>"  /></div><div class="fix"></div></div>

                                        <input type="submit" name="folder_create" value="Create Folder" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                    </div>
                                </fieldset>
                            </form>


                        </div>
                    </div>
                </div>



                <!-- Charts -->
                <div class="table">
                    <div class="head"><h5 class="iFrames">File Management List</h5></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>



                            <?php
                            $folderArrayCounter = count($folderArray);
                            if ($folderArrayCounter > 0):
                                ?>
    <?php for ($i = 0; $i < $folderArrayCounter; $i++): ?>
                                    <tr class="gradeA">
                                        <td>Folder</td>
                                        <td><a href="index.php?folder=<?php echo $folder . $folderArray[$i]; ?>" title="<?php echo $folderArray[$i]; ?>"><?php echo $folderArray[$i]; ?></a></td>
                                        <td class="center">
                                            <a href="index.php?folder=<?php echo $folder . $folderArray[$i]; ?>" title="<?php echo $folderArray[$i]; ?>">Files</a>

                                        </td>
                                    </tr>
                                <?php endfor; /* $i=0; i<$folderArrayCounter; $++  */ ?>
<?php endif; /* count($folderArray) > 0 */ ?>



                            <?php
                            $fileArrayCounter = count($fileArray);
                            if ($fileArrayCounter > 0):
                                ?>
                                <?php for ($i = 0; $i < $fileArrayCounter; $i++): ?>
        <?php $link = baseUrl('upload/' . $root . '/' . trim($folder, '/') . '/' . $fileArray[$i]); ?>
                                    <tr class="gradeA">
                                        <td>File</td>
                                        <td><a target="_blanck" href="<?php echo $link; ?>"><?php echo $fileArray[$i]; ?></a></td>
                                        <td class="center">
                                            <a href="javascript:copyLink('<?php echo $link; ?>');">Link</a>

                                        </td>
                                    </tr>
                                <?php endfor; /* $i=0; i<$fileArrayCounter; $++  */ ?>
<?php endif; /* count($fileArray) > 0 */ ?>
                        </tbody>
                    </table>
                </div>

            </div>


            <!-- Content End -->

            <div class="fix"></div>
        </div>

        <?php include basePath('admin/footer.php'); ?>
