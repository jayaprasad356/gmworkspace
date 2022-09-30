<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');
$db = new Database();
$db->connect();

if (empty($_POST['staff_id'])) {
    $response['success'] = false;
    $response['message'] = "Staff Id is Empty";
    print_r(json_encode($response));
    return false;
}

$staff_id = $db->escapeString($_POST['staff_id']);
$date = date('Y-m-d');
$month = date('m');
$year = date('Y');
$sql = "SELECT SUM(hours) AS today_hours FROM `timesheets` WHERE date = '$date' AND status = 1";
$db->sql($sql);
$res = $db->getResult();
$today_hours = ($res[0]['today_hours'] == null) ? $res[0]['today_hours'] : "0";
$sql = "SELECT SUM(hours) AS month_hours FROM `timesheets` WHERE MONTH(date) = '$month' AND YEAR(date) = '$year' AND status = 1";
$db->sql($sql);
$res = $db->getResult();
$month_hours = ($res[0]['month_hours'] == null) ? $res[0]['month_hours'] : "0";
$response['success'] = true;
$response['message'] = "Dashboard Successfully Retrived";
$response['today_hours'] = $today_hours;
$response['month_hours'] = $month_hours;
print_r(json_encode($response));

?>