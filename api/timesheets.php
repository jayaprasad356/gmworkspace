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

if (empty($_POST['project_name'])) {
    $response['success'] = false;
    $response['message'] = "Project Name is Empty";
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

$project_name = $db->escapeString($_POST['project_name']);
$hours = $db->escapeString($_POST['hours']);
$date = $db->escapeString($_POST['date']);
$description = $db->escapeString($_POST['description']);


$sql = "INSERT INTO timesheets (`project_name`,`date`,`hours`,`description`)VALUES('$project_name','$date','$hours','$description')";
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['message'] = "Successfully Timesheet Entry added";
$response['data'] = $res;
print_r(json_encode($response));

?>