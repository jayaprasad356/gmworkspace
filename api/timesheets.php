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

if (empty($_POST['project_id'])) {
    $response['success'] = false;
    $response['message'] = "Project Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['staff_id'])) {
    $response['success'] = false;
    $response['message'] = "Staff Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['date'])) {
    $response['success'] = false;
    $response['message'] = "Date is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['hours'])) {
    $response['success'] = false;
    $response['message'] = "Hours is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['description'])) {
    $response['success'] = false;
    $response['message'] = "Description id Empty";
    print_r(json_encode($response));
    return false;
}

$project_id = $db->escapeString($_POST['project_id']);
$hours = $db->escapeString($_POST['hours']);
$date = $db->escapeString($_POST['date']);
$description = $db->escapeString($_POST['description']);
$staff_id = $db->escapeString($_POST['staff_id']);

$sql = "INSERT INTO timesheets (`project_id`,`staff_id`,`date`,`hours`,`description`)VALUES('$project_id','$staff_id','$date','$hours','$description')";
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['message'] = "Successfully Timesheet Entry Added";
$response['data'] = $res;
print_r(json_encode($response));


?>