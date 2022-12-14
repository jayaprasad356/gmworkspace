<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$db->connect();

if (isset($config['system_timezone']) && isset($config['system_timezone_gmt'])) {
    date_default_timezone_set($config['system_timezone']);
    $db->sql("SET `time_zone` = '" . $config['system_timezone_gmt'] . "'");
} else {
    date_default_timezone_set('Asia/Kolkata');
    $db->sql("SET `time_zone` = '+05:30'");
}
if (isset($_GET['table']) && $_GET['table'] == 'clients') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "WHERE name like '%" . $search . "%' OR mobile like '%" . $search . "%' OR address like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $sql = "SELECT COUNT(`id`) as total FROM `clients`" . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM clients ". $where ." ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . "," . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-client.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['email'] = $row['email'];
        $tempRow['address'] = $row['address'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'staffs') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= "WHERE name like '%" . $search . "%' OR id like '%" . $search . "%'OR mobile like '%" . $search . "%'OR role like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `staffs` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM staffs " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-staff.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['role'] = $row['role'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['email'] = $row['email'];
        $tempRow['password'] = $row['password'];
        $tempRow['github'] = $row['github'];
        $tempRow['upi'] = $row['upi'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'projects') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND name like '%" . $search . "%' OR client_name like '%" . $search . "%' OR description like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }
    $join = "WHERE projects.client_id=clients.id";        
    $sql = "SELECT COUNT(*) as total FROM `projects`,`clients`" . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT *,clients.name AS client_name,projects.name AS name FROM projects,clients $join ";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-project.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $id = $row['id'];
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['client_name'] = $row['client_name'];
        $tempRow['description'] = $row['description'];
        $tempRow['price'] = $row['price'];
        $price = $row['price'];
        $sql = "SELECT SUM(amount) - $price AS balance FROM project_bill WHERE project_id = $id";
        $db->sql($sql);
        $res = $db->getResult();
        $tempRow['balance'] = (isset($res[0]['balance'])) ? $res[0]['balance'] : "0";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'timesheets') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if ((isset($_GET['status'])  && $_GET['status'] != '')) {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "AND t.status='$status' ";
    }
    if ((isset($_GET['date']) && $_GET['date'] != '')) {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $where .= "AND t.date = '$date'";
    }
    if ((isset($_GET['staff_id'])  && $_GET['staff_id'] != '')) {
        $staff_id = $db->escapeString($fn->xss_clean($_GET['staff_id']));
        $where .= "AND t.staff_id='$staff_id' ";
    }
    if ((isset($_GET['project_id'])  && $_GET['project_id'] != '')) {
        $project_id = $db->escapeString($fn->xss_clean($_GET['project_id']));
        $where .= "AND t.project_id='$project_id' ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "t.id like '%" . $search . "%' OR p.name like '%" . $search . "%' OR t.date like '%" . $search . "%' OR t.description like '%" . $search . "%' OR s.name like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }     
    
    $join = "WHERE t.staff_id = s.id AND t.project_id = p.id";

    $sql = "SELECT COUNT(*) as `total` FROM `timesheets` t,`staffs` s,`projects` p $join " . $where . " ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT t.id AS id,t.*,p.name AS project_name,t.status AS status,s.name AS staff_name  FROM `timesheets` t,`staffs` s,`projects` p $join 
    $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-timesheet.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        //$operate .= ' <a  class="text text-danger" href="delete-timesheet.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $tempRow['id'] = $row['id'];
        $tempRow['date'] = $row['date'];
        $tempRow['staff_name'] = $row['staff_name'];
        $tempRow['project_name'] = $row['project_name'];
        $tempRow['description'] = $row['description'];
        $tempRow['hours'] = $row['hours'];
        $tempRow['column'] = $checkbox;
        if ($row['status'] == 1)
            $tempRow['status'] = "<p class='text text-success'> Verified</p>";
        else
            $tempRow['status'] = "<p class='text text-danger'>Not-verified</p>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'payout') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    // if ((isset($_GET['staff_id'])  && $_GET['staff_id'] != '')) {
    //     $staff_id = $db->escapeString($fn->xss_clean($_GET['staff_id']));
    //     $where .= "AND t.staff_id='$staff_id' ";
    // }
    if ((isset($_GET['month'])  && $_GET['month'] != '')) {
        $month = $db->escapeString($fn->xss_clean($_GET['month']));
        $where .= "AND MONTH(t.date)='$month' ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    // if (isset($_GET['search']) && !empty($_GET['search'])) {
    //     $search = $db->escapeString($_GET['search']);
    //     $where .= "WHERE t.staff_id like '%" . $search . "%' OR s.name like '%" . $search . "%'";
    // }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }

    $sql = "SELECT COUNT(t.staff_id) as `total` FROM `staffs`s,`timesheets`t WHERE t.staff_id = s.id ".$where." GROUP BY t.staff_id";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT *,s.name AS staff_name,SUM(t.hours) AS total_hours,SUM(t.hours)*s.cost_per_hour AS total_amount FROM `staffs`s,`timesheets`t WHERE t.staff_id=s.id ".$where." GROUP BY t.staff_id";
    $db->sql($sql);
    $res = $db->getResult();
    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {
        $tempRow['id'] = $row['id'];
        $tempRow['staff_name'] = $row['staff_name'];
        $tempRow['total_hours'] = $row['total_hours'];
        $tempRow['cost_per_hour'] = $row['cost_per_hour'];
        $tempRow['total_amount'] = $row['total_amount'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'tasks') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'tasks.id';
    $order = 'DESC';
    if ((isset($_GET['status'])  && $_GET['status'] != '')) {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "AND tasks.status='$status' ";
    }
    if ((isset($_GET['staff_id'])  && $_GET['staff_id'] != '')) {
        $staff_id = $db->escapeString($fn->xss_clean($_GET['staff_id']));
        $where .= "AND tasks.assign_id='$staff_id' ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        //$where .= "WHERE name like '%" . $search . "%' OR mobile like '%" . $search . "%' OR address like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $sql = "SELECT COUNT(*) as total FROM `tasks`,`projects` WHERE tasks.project_id = projects.id " . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT *,tasks.id AS id,projects.name AS project_name,tasks.description AS description FROM tasks,projects WHERE tasks.project_id = projects.id ". $where ." ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . "," . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-task.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['project_name'] = $row['project_name'];
        $tempRow['description'] = $row['description'];
        if ($row['status'] == 1){
            $tempRow['status'] = "<p class='text text-success'>Tested</p>";

        }elseif($row['status'] == 2){
            $tempRow['status'] = "<p class='text text-primary'>Fixed</p>";

        }
            
        else{
            $tempRow['status'] = "<p class='text text-danger'>Bug</p>";

        }
            
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'project_bill') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "WHERE p.name like '%" . $search . "%' OR pb.date like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $join = "LEFT JOIN `projects` p ON pb.project_id=p.id";

    $sql = "SELECT COUNT(pb.id) as total FROM `project_bill` pb $join ". $where ."";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT pb.id AS id,pb.*,p.name AS name  FROM `project_bill` pb $join
        $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-project-bill.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['amount'] = $row['amount'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['date'] = $row['date'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
$db->disconnect();
