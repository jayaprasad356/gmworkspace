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
$month = date('m');
$year = date('Y');
$staff_id = $db->escapeString($_POST['staff_id']);
$sql = "SELECT DAY(date) AS day,SUM(hours) AS hours FROM timesheets WHERE staff_id = $staff_id AND YEAR(date) = '$year' AND MONTH(date) = '$month' GROUP BY date ORDER BY date ";
$db->sql($sql);
$res = $db->getResult();
$list=array();
$hours=array();
$num = $db->numRows($res);
for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month){
        $list[]=date('j', $time);
        $result = 0;
 
        for($i=0; $i<$num; $i++)
        {
            $day=$res[$i]['day'];
  
            if (date('j', $time)==$day){
                $hours[]=$res[$i]['hours'];
                $result = 1;
               

            }
            else{
                //$hours[]='0';
            }       
                
        }
        if($result == 0){
            $hours[] = '0';
        }

    }       
        
}
// echo "<pre>";
// print_r($list);
// echo "</pre>";
$response['days'] = $list;
$response['hours'] = $hours;
print_r(json_encode($response));
// $response['success'] = true;
// $response['message'] = "Successfully Timesheet Entry Added";
// $response['data'] = $res;
// print_r(json_encode($response));


?>