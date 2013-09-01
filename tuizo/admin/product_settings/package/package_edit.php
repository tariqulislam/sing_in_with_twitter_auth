<?php
include ('../../../config/config.php');
require basePath('lib/Zebra_Image.php');
if (!checkAdminLogin()) {
    $link = baseUrl('admin/index.php?err=' . base64_encode('Please login to access admin panel'));
    redirect($link);
}
$package_id = '';
$package_updated_by = $_SESSION['admin_id'];
$package_name = '';
$package_description = '';
$package_price = '';
$package_tax_class_id = '';
$package_discount = '';
$package_catagory_id = '';
$package_logo = '';
$package_banner = '';
$package_order_by = '';
$package_status = '';
$package_expiery = '';
$package_sold_amount = '';
$package_user_type = '';
//==================== GET the package id And show is to edit Form=============//
if (isset($_GET['package_id'])) {
    $package_id = base64_decode($_GET['package_id']);
    $query = "SELECT * FROM packages WHERE package_id=" . intval($package_id);
    $result = mysqli_query($con, $query);
    while ($p_rows = mysqli_fetch_assoc($result)) {
        $package_name = $p_rows['package_name'];
        $package_description = $p_rows['package_description'];

        $package_price = intval($p_rows['package_price']);
        $package_tax_class_id = $p_rows['package_tax_class_id'];
        $package_discount = intval($p_rows['package_discount']);
        $package_catagory_id = $p_rows['package_catagory_id'];
        $package_order_by = $p_rows['package_order_by'];
        $package_status = $p_rows['package_status'];
        $package_expiery = $p_rows['package_expiery'];
        $package_updated_by = $p_rows['package_updated_by'];
        $package_sold_amount = $p_rows['package_sold_amount'];
        $package_user_type = $p_rows['package_user_type'];
    }
}
//==================== End GET the package id And show is to edit Form=============//
//==================== Edit package =============//
if (isset($_POST['package_edit']) AND $_POST['package_edit'] == 'Submit') {
    extract($_POST);

    if ($_FILES["package_logo"]["error"] == 0) {
        list($package_logo_width, $package_logo_height) = getimagesize($_FILES["package_logo"]["tmp_name"]);
        //echo"<script>alert($package_logo_width);</script>";
    }
    if ($_FILES["package_banner"]["error"] == 0) {
        list($package_banner_width, $package_banner_height) = getimagesize($_FILES["package_banner"]["tmp_name"]);
    }
//======================= if the package sold amount is 0 ========================//
    if ($package_sold_amount == 0) {
//======================== if post the package status active from form===========//
        if ($package_status == 'active') {
            $check_package_update_query = "SELECT * FROM packages WHERE package_name='" . mysqli_real_escape_string($con, $package_name) . "'";
            $check_package_update_result = mysqli_query($con, $check_package_update_query);

            $active_package_count = 0;
            while ($update_rows = mysqli_fetch_assoc($check_package_update_result)) {
                if ($update_rows['package_status'] == 'active' && $update_rows['package_id'] == $package_id) {
                    $active_package_count = 0;
                    break;
                } else if ($update_rows['package_status'] == 'active') {
                    $active_package_count+=1;
                }
            }
            if ($active_package_count >= 1) {

                if ($package_name == '') {
                    $err = 'Name field is Required';
                } else if ($package_description == '') {
                    $err = 'Description field is Required';
                } else if ($package_price == '') {
                    $err = "price field is Required";
                } else if (!is_numeric($package_price)) {
                    $err = 'Price must be decimal';
                } else if ($package_discount == '') {
                    $err = 'Discount field is Required';
                } else if ($package_order_by == '') {
                    $err = 'Order by field is required';
                } else if (!ctype_digit($package_order_by)) {
                    $err = 'order by must be whole number';
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_width < $config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']) {
                    $err = "Logo Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']}</b>";
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_height > ($package_logo_width * $config['IMAGE_RATIO'])) {
                    $err = "Logo Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_width < $config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']) {
                    $err = "Banner Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_height > ($package_banner_width * $config['IMAGE_RATIO'])) {
                    $err = "Banner Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                } else if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {
                                $err = zebraImageErrorHandaling($image->error);
// if there was an error, let's see what the error is about
// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;

                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }
                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';
                    $Updatepackage .= '  package_name = "' . mysqli_real_escape_string($con, $package_name) . '"';
                    $Updatepackage .= ', package_description  = "' . mysqli_real_escape_string($con, $package_description) . '"';
                    $Updatepackage .= ', package_price = "' . mysqli_real_escape_string($con, $package_price) . '"';
                    $Updatepackage .= ', package_tax_class_id = "' . mysqli_real_escape_string($con, $package_tax_class_id) . '"';
                    $Updatepackage .= ', package_discount = "' . mysqli_real_escape_string($con, $package_discount) . '"';
                    $Updatepackage .= ', package_catagory_id = "' . mysqli_real_escape_string($con, $package_catagory_id) . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_order_by = "' . mysqli_real_escape_string($con, $package_order_by) . '"';
                    $Updatepackage .= ', package_updated_by = "' . $package_updated_by . '"';
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $Updatepackage .=', package_user_type ="' . mysqli_real_escape_string($con, $package_user_type) . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.And Package Status is no Active,Because Duplicate Active Package is Not Add the package list,And Inactive All the Active package";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            } else if ($active_package_count == 0) {
                if ($package_name == '') {
                    $err = 'Name field is Required';
                } else if ($package_description == '') {
                    $err = 'Description field is Required';
                } else if ($package_price == '') {
                    $err = "price field is Required";
                } else if (!ctype_digit($package_price)) {
                    $err = 'Price must be decimal';
                } else if ($package_discount == '') {
                    $err = 'Discount field is Required';
                } else if ($package_order_by == '') {
                    $err = 'Order by field is required';
                } else if (!ctype_digit($package_order_by)) {
                    $err = 'order by must be whole number';
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_width < $config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']) {
                    $err = "Logo Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']}</b>";
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_height > ($package_logo_width * $config['IMAGE_RATIO'])) {
                    $err = "Logo Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_width < $config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']) {
                    $err = "Banner Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_height > ($package_banner_width * $config['IMAGE_RATIO'])) {
                    $err = "Banner Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                }else if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {
                                $err = zebraImageErrorHandaling($image->error);
// if there was an error, let's see what the error is about
// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;

                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';
                    $Updatepackage .= '  package_name = "' . mysqli_real_escape_string($con, $package_name) . '"';
                    $Updatepackage .= ', package_description  = "' . mysqli_real_escape_string($con, $package_description) . '"';
                    $Updatepackage .= ', package_price = "' . mysqli_real_escape_string($con, $package_price) . '"';
                    $Updatepackage .= ', package_tax_class_id = "' . mysqli_real_escape_string($con, $package_tax_class_id) . '"';
                    $Updatepackage .= ', package_discount = "' . mysqli_real_escape_string($con, $package_discount) . '"';
                    $Updatepackage .= ', package_catagory_id = "' . mysqli_real_escape_string($con, $package_catagory_id) . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_order_by = "' . mysqli_real_escape_string($con, $package_order_by) . '"';
                    $Updatepackage .= ', package_status = "' . mysqli_real_escape_string($con, $package_status) . '"';
                    $Updatepackage .= ', package_updated_by = "' . $package_updated_by . '"';
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $Updatepackage .=', package_user_type ="' . mysqli_real_escape_string($con, $package_user_type) . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.And This Package Status is Active Already";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            }
        }
//======================== End if post the package status active from form===========//
//======================== if post the package status inactive from form===========//
        else if ($package_status == 'inactive') {
            $check_package_update_query = "SELECT * FROM packages WHERE package_name='" . mysqli_real_escape_string($con, $package_name) . "'";
            $check_package_update_result = mysqli_query($con, $check_package_update_query);

            $active_package_count = 0;
            while ($update_rows = mysqli_fetch_assoc($check_package_update_result)) {
                if ($update_rows['package_status'] == 'active' && $update_rows['package_id'] == $package_id) {
                    $active_package_count = 0;
                    break;
                } else if ($update_rows['package_status'] == 'inactive') {
                    $active_package_count+=1;
                }
            }

            if ($active_package_count >= 1) {

                if ($package_name == '') {
                    $err = 'Name field is Required';
                } else if ($package_description == '') {
                    $err = 'Description field is Required';
                } else if ($package_price == '') {
                    $err = "price field is Required";
                } else if (!ctype_digit($package_price)) {
                    $err = 'Price must be decimal';
                } else if ($package_discount == '') {
                    $err = 'Discount field is Required';
                } else if ($package_order_by == '') {
                    $err = 'Order by field is required';
                } else if (!ctype_digit($package_order_by)) {
                    $err = 'order by must be whole number';
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_width < $config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']) {
                    $err = "Logo Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']}</b>";
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_height > ($package_logo_width * $config['IMAGE_RATIO'])) {
                    $err = "Logo Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_width < $config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']) {
                    $err = "Banner Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_height > ($package_banner_width * $config['IMAGE_RATIO'])) {
                    $err = "Banner Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                }else if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {
                                $err = zebraImageErrorHandaling($image->error);
// if there was an error, let's see what the error is about
// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;

                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';
                    $Updatepackage .= '  package_name = "' . mysqli_real_escape_string($con, $package_name) . '"';
                    $Updatepackage .= ', package_description  = "' . mysqli_real_escape_string($con, $package_description) . '"';
                    $Updatepackage .= ', package_price = "' . mysqli_real_escape_string($con, $package_price) . '"';
                    $Updatepackage .= ', package_tax_class_id = "' . mysqli_real_escape_string($con, $package_tax_class_id) . '"';
                    $Updatepackage .= ', package_discount = "' . mysqli_real_escape_string($con, $package_discount) . '"';
                    $Updatepackage .= ', package_catagory_id = "' . mysqli_real_escape_string($con, $package_catagory_id) . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_order_by = "' . mysqli_real_escape_string($con, $package_order_by) . '"';
                    $Updatepackage .= ', package_updated_by = "' . $package_updated_by . '"';
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $Updatepackage .=', package_user_type ="' . mysqli_real_escape_string($con, $package_user_type) . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            } else if ($active_package_count == 0) {
                if ($package_name == '') {
                    $err = 'Name field is Required';
                } else if ($package_description == '') {
                    $err = 'Description field is Required';
                } else if ($package_price == '') {
                    $err = "price field is Required";
                } else if (!ctype_digit($package_price)) {
                    $err = 'Price must be decimal';
                } else if ($package_discount == '') {
                    $err = 'Discount field is Required';
                } else if ($package_order_by == '') {
                    $err = 'Order by field is required';
                } else if (!ctype_digit($package_order_by)) {
                    $err = 'order by must be whole number';
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_width < $config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']) {
                    $err = "Logo Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']}</b>";
                } else if ($_FILES["package_logo"]["error"] == 0 && $package_logo_height > ($package_logo_width * $config['IMAGE_RATIO'])) {
                    $err = "Logo Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_width < $config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']) {
                    $err = "Banner Image width must not less than <b>{$config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']}</b>";
                } else if ($_FILES["package_banner"]["error"] == 0 && $package_banner_height > ($package_banner_width * $config['IMAGE_RATIO'])) {
                    $err = "Banner Image height, width ratio must not greater than <b>{$config['IMAGE_RATIO']}</b>";
                }else if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {
                                $err = zebraImageErrorHandaling($image->error);
// if there was an error, let's see what the error is about
// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;
                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';
                    $Updatepackage .= '  package_name = "' . mysqli_real_escape_string($con, $package_name) . '"';
                    $Updatepackage .= ', package_description  = "' . mysqli_real_escape_string($con, $package_description) . '"';
                    $Updatepackage .= ', package_price = "' . mysqli_real_escape_string($con, $package_price) . '"';
                    $Updatepackage .= ', package_tax_class_id = "' . mysqli_real_escape_string($con, $package_tax_class_id) . '"';
                    $Updatepackage .= ', package_discount = "' . mysqli_real_escape_string($con, $package_discount) . '"';
                    $Updatepackage .= ', package_catagory_id = "' . mysqli_real_escape_string($con, $package_catagory_id) . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_status = "' . mysqli_real_escape_string($con, $package_status) . '"';
                    $Updatepackage .= ', package_order_by = "' . mysqli_real_escape_string($con, $package_order_by) . '"';
                    $Updatepackage .= ', package_updated_by = "' . $package_updated_by . '"';
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $Updatepackage .=', package_user_type ="' . mysqli_real_escape_string($con, $package_user_type) . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            }
        }
//======================== End if post the package status inactive from form===========//
    }
//======================= End if the package sold amount is 0 ========================//
//====================== if the package sold amount is greater than 0 ====================//
    else if ($package_sold_amount >= 1) {
//======================== if post the package status active from form===========//
        if ($package_status == 'active') {
            $check_package_update_query = "SELECT * FROM packages WHERE package_name='" . mysqli_real_escape_string($con, $package_name) . "'";
            $check_package_update_result = mysqli_query($con, $check_package_update_query);

            $active_package_count = 0;
            while ($update_rows = mysqli_fetch_assoc($check_package_update_result)) {
                if ($update_rows['package_status'] == 'active' && $update_rows['package_id'] == $package_id) {
                    $active_package_count = 0;
                    break;
                } else if ($update_rows['package_status'] == 'active') {
                    $active_package_count+=1;
                }
            }
            if ($active_package_count >= 1) {

                if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {

// if there was an error, let's see what the error is about
                                $err = zebraImageErrorHandaling($image->error);

// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;

                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';

                    $Updatepackage .= ' package_updated_by = "' . $package_updated_by . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';

                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.Package Status is not Active,Because duplicate same named active package is not Add to package list";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            } else if ($active_package_count == 0) {

                if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {
                                $err = zebraImageErrorHandaling($image->error);
// if there was an error, let's see what the error is about
// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;

                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';
                    $Updatepackage .= ' package_status = "' . mysqli_real_escape_string($con, $package_status) . '"';
                    $Updatepackage .= ', package_updated_by = "' . $package_updated_by . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            }
        }
//======================== End if post the package status active from form===========//
//======================== if post the package status inactive from form===========//
        else if ($package_status == 'inactive') {
            $check_package_update_query = "SELECT * FROM packages WHERE package_name='" . mysqli_real_escape_string($con, $package_name) . "'";
            $check_package_update_result = mysqli_query($con, $check_package_update_query);

            $active_package_count = 0;
            while ($update_rows = mysqli_fetch_assoc($check_package_update_result)) {
                if ($update_rows['package_status'] == 'active' && $update_rows['package_id'] == $package_id) {
                    $active_package_count = 0;
                    break;
                } else if ($update_rows['package_status'] == 'inactive') {
                    $active_package_count+=1;
                }
            }

            if ($active_package_count >= 1) {


                if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {

// if there was an error, let's see what the error is about
                                $err = zebraImageErrorHandaling($image->error);


// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;

                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {

// if there was an error, let's see what the error is about
                                $err = zebraImageErrorHandaling($image1->error);
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');

                    $Updatepackage = '';
                    $Updatepackage .= ' package_updated_by = "' . $package_updated_by . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            } else if ($active_package_count == 0) {

                if ($package_expiery == '') {
                    $err = 'Expire date is Required';
                } else {

                    if ($_FILES['package_logo']['tmp_name'] != '') {

                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_logo_image_name = basename($_FILES['package_logo']['name']);
                        $info = pathinfo($package_logo_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_logo = str_replace(' ', '_', $package_name) . '-' . 'logo' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_logo_image_source_path = $_FILES["package_logo"]["tmp_name"];
                        $package_logo_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_logo;


                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_logo_image_source_path, $package_logo_images_target_path)) {
                            $package_logo = '';
                        } else {
                            /* Start: uploaded image resize */


                            $logo_image_source_path = $package_logo_images_target_path;
                            $logo_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_logo;
                            $image = new Zebra_Image();
                            $image->target_path = $logo_image_target_path;
                            $image->source_path = $logo_image_source_path;

                            $image->preserve_aspect_ratio = true;

                            if (!$image->resize(100)) {

// if there was an error, let's see what the error is about
                                $err = zebraImageErrorHandaling($image->error);


// if no errors       
                            }
                            /* End: uploaded image resize */
                        }
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
//$max_image_id = getMaxValue('packages', 'package_id');
                        $new_image_id = $package_id;

                        /* Srat: image upload */
                        $package_banner_image_name = basename($_FILES['package_banner']['name']);
                        $info = pathinfo($package_banner_image_name, PATHINFO_EXTENSION); /* it will return me like jpeg, gif, pdf, png */
                        $package_banner = str_replace(' ', '_', $package_name) . '-' . 'banner' . '-' . $new_image_id . '.' . $info; /* create custom image name color id will add  */

                        $package_banner_image_source_path = $_FILES["package_banner"]["tmp_name"];
                        $package_banner_images_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/' . $package_banner;

                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id)) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id, 0777, TRUE);
                        }
                        if (!is_dir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/')) {
                            mkdir($config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/', 0777, TRUE);
                        }
                        if (!move_uploaded_file($package_banner_image_source_path, $package_banner_images_target_path)) {
                            $package_banner = '';
                        } else {
                            /* Start: uploaded image resize */


                            $banner_image_source_path = $package_banner_images_target_path;
                            $banner_image_target_path = $config['IMAGE_UPLOAD_PATH'] . '/packages/' . $new_image_id . '/small/' . $package_banner;
                            $image1 = new Zebra_Image();
                            $image1->target_path = $banner_image_target_path;
                            $image1->source_path = $banner_image_source_path;
                            $image1->preserve_aspect_ratio = true;

                            if (!$image1->resize(100)) {
                                $err = zebraImageErrorHandaling($image1->error);
// if there was an error, let's see what the error is about
                            }
// if no errors
                        }
                    }

                    $dateTime = new DateTime($package_expiery);
                    $package_expire_date = date_format($dateTime, 'Y-m-d');
                    $Updatepackage = '';

                    $Updatepackage .= ' package_status = "' . mysqli_real_escape_string($con, $package_status) . '"';
                    if ($_FILES['package_logo']['tmp_name'] != '') {
                        $Updatepackage .= ', package_logo = "' . mysqli_real_escape_string($con, $package_logo) . '"';
                    }
                    if ($_FILES['package_banner']['tmp_name'] != '') {
                        $Updatepackage .= ', package_banner = "' . mysqli_real_escape_string($con, $package_banner) . '"';
                    }
                    $Updatepackage .= ', package_updated_by = "' . $package_updated_by . '"';
                    $Updatepackage .= ', package_expiery = "' . $package_expire_date . '"';
                    $SqlUpdatePackage = "UPDATE `packages` SET $Updatepackage WHERE package_id='$package_id'";
                    $ExecuteUpdatePackage = mysqli_query($con, $SqlUpdatePackage);

                    if ($ExecuteUpdatePackage) {
                        $msg = "Package info update successfully.";
                        $link = 'index.php?msg=' . base64_encode($msg);
                        redirect($link);
                    } else {
                        if (DEBUG) {
                            echo "ExecuteUpdateBanner mysqli_error: " . mysqli_error($con);
                        }
                        $err = "Package could not update successfully.";
                    }
                }
            }
        }
//======================== End if post the package status inactive from form===========//
    }
//====================== End if the package sold amount is greater than 0 ====================//
}
//==================== End Edit Package ==========//
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo baseUrl('admin/images/favicon.ico') ?>" />
        <title>Package panel: Package Edit </title>

        <!-- Script and style for Light box 2 -->
        <script src="<?php echo baseUrl('js/lightbox/js/jquery-1.10.2.min.js'); ?>"></script>
        <script src="<?php echo baseUrl('js/lightbox/js/lightbox-2.6.min.js'); ?>"></script>
        <link href="<?php echo baseUrl('js/lightbox/css/lightbox.css'); ?>" rel="stylesheet" />
        <!-- End Script and Style for Light box 2 -->

        <link href="<?php echo baseUrl('admin/css/main.css'); ?>" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />
        <script src="<?php echo baseUrl('admin/js/jquery-1.4.4.js'); ?>" type="text/javascript"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload, editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/spinner/ui.spinner.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery-ui.min.js'); ?>"></script>  
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/fileManager/elfinder.min.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/jquery.wysiwyg.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/wysiwyg.image.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/wysiwyg.link.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/wysiwyg/wysiwyg.table.js'); ?>"></script>
        <!--Effect on wysiwyg editor -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/dataTables/jquery.dataTables.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/dataTables/colResizable.min.js'); ?>"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/forms.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/autogrowtextarea.js'); ?>"></script>
        <!--Effect on left error menu, top message menu, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/autotab.js'); ?>"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/forms/jquery.validationEngine.js'); ?>"></script>
        <!--Effect on left error menu, top message menu-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/colorPicker/colorpicker.js'); ?>"></script>
        <!--Effect on left error menu, top message menu -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/plupload.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/plupload.html5.js'); ?>"></script>
        <!--Effect on file upload-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/plupload.html4.js'); ?>"></script>
        <!--No effect-->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/uploader/jquery.plupload.queue.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/ui/jquery.tipsy.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,  -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jBreadCrumb.1.1.js'); ?>"></script>
        <!--Effect on left error menu, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/cal.min.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.collapsible.min.js'); ?>"></script>
        <!--Effect on left error menu, File upload -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.ToTop.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio, -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.listnav.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/jquery.sourcerer.js'); ?>"></script>
        <!--Effect on left error menu, top message menu,Drowpdowns and selects, Checkbox and radio -->
        <script type="text/javascript" src="<?php echo baseUrl('admin/js/custom.js'); ?>"></script>
        <!--Effect on left error menu, top message menu, body-->
        <style type="text/css">
            .noselect{ pointer-events: none; }
        </style>
    </head>

    <body>

        <?php include basePath('admin/top_navigation.php'); ?>

        <?php include basePath('admin/module_link.php'); ?>


        <!-- Content wrapper -->
        <div class="wrapper">

            <!-- Left navigation -->
            <?php include basePath('admin/product_settings/product_settings_left_navigation.php'); ?>

            <!-- Content Start -->
            <div class="content">
                <div class="title"><h5>Package Module</h5></div>

                <!-- Notification messages -->
                <?php include basePath('admin/message.php'); ?>
                <!-- Charts -->
                <div class="widget first">
                    <div class="head">
                        <h5 class="iGraph">Create package </h5></div>
                    <div class="body">
                        <div class="charts" style="width: 700px; height: auto;">
                            <form action="package_edit.php" method="post" class="mainForm" enctype="multipart/form-data">

                                <!-- Input text fields -->
                                <fieldset>
                                    <div class="widget first">
                                        <div class="head"><h5 class="iList">Package Information</h5></div>
                                        <!-- form Design -->
                                        <div class="rowElem noborder"><input type="hidden" name="package_sold_amount" value="<?php echo $package_sold_amount; ?>"/><input type="hidden" name="package_id" value="<?php echo base64_decode($_GET['package_id']); ?>"/><label>Package Name:</label><div class="formRight">
                                                <input <?php if ($package_sold_amount != 0) echo 'readonly=readonly'; ?> type="text" name="package_name" value="<?php echo $package_name; ?>"  /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Package Description:</label><div class="formRight">
                                                <input type="text" <?php if ($package_sold_amount != 0) echo 'readonly=readonly'; ?> name="package_description"  value="<?php echo $package_description; ?>"/></div><div class="fix"></div></div>

                                        <div class="rowElem"><label>Package price:</label><div class="formRight"><input type="text" <?php if ($package_sold_amount != 0) echo 'readonly=readonly'; ?> name="package_price" value="<?php echo $package_price; ?>" /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Package Tax:</label>
                                            <div class="formRight">   
                                                <div  <?php if ($package_sold_amount != 0) echo 'class="noselect"'; ?> >
                                                    <select name="package_tax_class_id" <?php if ($package_sold_amount != 0) echo 'onclick="javascript: alert(1);"'; ?> >
                                                        <?php
                                                        $package_tax_query = "SELECT TC_id,TC_title FROM tax_classes";
                                                        $package_tax_result = mysqli_query($con, $package_tax_query);
                                                        if (mysqli_num_rows($package_tax_result) >= 1) {
                                                            while ($rows = mysqli_fetch_assoc($package_tax_result)) {
                                                                ?>
                                                                <option value="<?php echo $rows['TC_id']; ?>" 
                                                                <?php
                                                                if ($rows['TC_id'] == $package_tax_class_id) {
                                                                    echo 'selected=selected';
                                                                }
                                                                ?>
                                                                        ><?php echo $rows['TC_title']; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="fix"></div></div>
                                        <div class="rowElem"><label>Package discount:</label><div class="formRight"><input type="text" <?php if ($package_sold_amount != 0) echo 'readonly=readonly'; ?> name="package_discount" value="<?php echo $package_discount; ?>" /></div><div class="fix"></div></div>

                                        <div class="rowElem noborder"><label>Package Category:</label>
                                            <div class="formRight">   
                                                <div  <?php if ($package_sold_amount != 0) echo 'class="noselect"'; ?> >
                                                    <select name="package_catagory_id" <?php if ($package_sold_amount != 0) echo 'onclick="javascript: alert(1);"'; ?>>
                                                        <?php
                                                        $package_category_query = "SELECT category_id,category_name FROM categories WHERE category_parent_id='2'";
                                                        $package_category_result = mysqli_query($con, $package_category_query);
                                                        if (mysqli_num_rows($package_category_result) >= 1) {
                                                            while ($rows = mysqli_fetch_assoc($package_category_result)) {
                                                                ?>
                                                                <option value="<?php echo $rows['category_id']; ?>" 
                                                                <?php
                                                                if ($rows['category_id'] == $package_catagory_id) {
                                                                    echo 'selected=selected';
                                                                }
                                                                ?>
                                                                        ><?php echo $rows['category_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="fix"></div></div>
                                        <div class="rowElem noborder"><label>Package User Type:</label>
                                            <div class="formRight">
                                                <div <?php if ($package_sold_amount != 0) echo 'class="noselect"'; ?>>
                                                    <select name="package_user_type">
                                                        <?php
                                                        $user_types = array('all', 'man', 'woman', 'kids');

                                                        foreach ($user_types as $u) {
                                                            ?>
                                                            <option value="<?php echo $u; ?>" 
                                                            <?php
                                                            if ($package_user_type == $u) {
                                                                echo 'selected="selected"';
                                                            }
                                                            ?>

                                                                    ><?php echo $u; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="fix"></div></div>
                                        <div class="rowElem">
                                            <label>Select Logo Image:</label>
                                            <div class="formRight">
                                                <input type="file" name="package_logo"/>&nbsp;Minimum width:<?php echo $config['CONFIG_SETTINGS']['PACKAGE_LOGO_WIDTH']; ?>
                                            </div>
                                            <div class="fix"></div></div>
                                        <div class="rowElem">
                                            <label>Select banner Image:</label>
                                            <div class="formRight">
                                                <input type="file" name="package_banner"/>&nbsp;Minimum width:<?php echo $config['CONFIG_SETTINGS']['PACKAGE_BANNER_WIDTH']; ?>
                                            </div>
                                            <div class="fix"></div></div>
                                        <div class="rowElem"><label>Package Priority:</label><div class="formRight"><input <?php if ($package_sold_amount != 0) echo 'readonly=readonly'; ?> type="text" name="package_order_by" value="<?php echo $package_order_by; ?>" /></div><div class="fix"></div></div>
                                        <div class="rowElem noborder">
                                            <label>Package Status :</label>
                                            <div class="formRight">
                                                <div <?php if ($package_sold_amount != 0) echo 'class="noselect"'; ?> >
                                                    <select name="package_status" <?php if ($package_sold_amount != 0) echo 'onclick="javascript: alert(1);"'; ?> >
                                                        <option value="active" <?php
                                                        if ($package_status == 'active') {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>Active</option>
                                                        <option value="inactive" <?php
                                                        if ($package_status == 'inactive') {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>> Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                        <div class="rowElem noborder">
                                            <label>Expire Date:</label>
                                            <div class="formRight">
                                                <input type="text" name="package_expiery" value="<?php
                                                $dateTime = new DateTime($package_expiery);
                                                echo date_format($dateTime, 'Y-m-d');
                                                ?>" class="datepicker" />
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                        <input type="submit" name="package_edit" value="Submit" class="greyishBtn submitForm" />
                                        <div class="fix"></div>
                                        <!-- End Form Design -->

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

