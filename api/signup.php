<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/functions.php');
$function = new functions;
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['role'])) {
    $response['success'] = false;
    $response['message'] = "Role is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['email'])) {
    $response['success'] = false;
    $response['message'] = "Email is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['password'])) {
    $response['success'] = false;
    $response['message'] = "Password is Empty";
    print_r(json_encode($response));
    return false;
}
$name = $db->escapeString($_POST['name']);
$email = $db->escapeString($_POST['email']);
$mobile = $db->escapeString($_POST['mobile']);
$role = $db->escapeString($_POST['role']);
$password = $db->escapeString($_POST['password']);


$sql = "SELECT * FROM staffs WHERE mobile = '$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $response['success'] = false;
    $response['message'] ="Mobile Number Already Exists";
    print_r(json_encode($response));
    return false;
}
else{
    if (isset($_FILES['image']) && !empty($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
        if (!is_dir('../upload/staffs/')) {
            mkdir('../upload/staffs/', 0777, true);
        }
        $image = $db->escapeString($fn->xss_clean($_FILES['image']['name']));
        $extension = pathinfo($_FILES["image"]["name"])['extension'];
        $result = $fn->validate_image($_FILES["image"]);
        if (!$result) {
            $response["success"]   = false;
            $response["message"] = "Image type must jpg, jpeg, gif, or png!";
            print_r(json_encode($response));
            return false;
        }
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = '../upload/staffs/' . "" . $filename;
        $upload_image = 'upload/staffs/' . "" . $filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            $response["success"]   = false;
            $response["message"] = "Invalid directory to load image!";
            print_r(json_encode($response));
            return false;
        }
    
        $sql = "INSERT INTO staffs (`name`,`email`,`mobile`,`role`,`password`,`image`)VALUES('$name','$email','$mobile','$role','$password','$upload_image')";
        $db->sql($sql);
        $sql = "SELECT * FROM staffs WHERE mobile = '$mobile'";
        $db->sql($sql);
        $res = $db->getResult();
        $response['success'] = true;
        $response['message'] = "Successfully Registered";
        $response['data'] = $res;
}
else{
    $response["success"]   = false;
    $response["message"] = "image parameter is missing.";
}
print_r(json_encode($response));
}
?>