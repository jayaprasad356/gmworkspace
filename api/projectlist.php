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
if (empty($_POST['client_id'])) {
    $response['success'] = false;
    $response['message'] = "Client Id is Empty";
    print_r(json_encode($response));
    return false;
}
$client_id = $db->escapeString($_POST['client_id']);

$sql = "SELECT *,projects.name AS project_name FROM projects,clients WHERE clients.id=projects.client_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $data = array();
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['name'] = $row['name'];
        $temp['project_name'] = $row['project_name'];
        $temp['description'] = $row['description'];
        $rows[] = $temp;
        
    }

    $response['success'] = true;
    $response['message'] = "Projects listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));

}else{
    $response['success'] = false;
    $response['message'] = "No Projects Found";
    print_r(json_encode($response));

}

?>