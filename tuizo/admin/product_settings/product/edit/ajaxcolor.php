<?php

include ('../../../../config/config.php');
$color_id = '';
if (isset($_POST['color_id'])) {

    extract($_POST);
    if ($color_id != 0) {
        $sql = mysqli_query($con, "SELECT * FROM colors WHERE color_id='$color_id'");
        if ($sql) {
            $row = mysqli_fetch_object($sql);

            if ($row->color_image_name != "") {
                if (file_exists($config['IMAGE_UPLOAD_PATH'] . '/color_img/' . $row->color_image_name)) {
                    echo '<img src="' . $config['IMAGE_UPLOAD_URL'] . '/color_img/' . $row->color_image_name . '" style="height:25px; width:25px; float:left;" />';
                } else {
                    echo '<span style="background:#' . $row->color_code . ';float: left;height: 25px;margin: 0 5px 0 0;width: 25px;"></span>';
                }
            }
        }
    }
}
?>
  



