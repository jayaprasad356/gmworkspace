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
$staff_id = $db->escapeString($_POST['staff_id']);
$sql = "SELECT *,timesheets.id AS id FROM timesheets,projects WHERE timesheets.project_id = projects.id AND timesheets.staff_id = $staff_id ORDER BY timesheets.date DESC LIMIT 20";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = true;
    $response['message'] = "Timesheet listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));

}else{
    $response['success'] = false;
    $response['message'] = "No Timesheet Found";
    print_r(json_encode($response));

}

?>