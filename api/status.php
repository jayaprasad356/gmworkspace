<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
if (empty($_POST['staff_id'])) {
    $response['success'] = false;
    $response['message'] = "Staff Id is Empty";
    print_r(json_encode($response));
    return false;
}
$status = (isset($_POST['status']) && !empty($_POST['status'])) ? $db->escapeString($_POST['status']) : 0;
$staff_id = $db->escapeString($_POST['staff_id']);

$sql = "UPDATE `staffs` SET `status` = $status WHERE id = $staff_id";
$db->sql($sql);
$res = $db->getResult();
$sql = "SELECT * FROM staffs WHERE id = $staff_id";
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['message'] = "Status Updated";
$response['data'] = $res;
print_r(json_encode($response));


?>