<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);
header('Content-Type: application/json');

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

function linechart($result,$start,$end){
$d1 = new DateTime($start);
$d2 = new DateTime($end);
$interval = $d2->diff($d1);
$monthsDifference = $interval->format('%m')+1;
$endmonth = $d2->format('m');

$totalMoney = array_pad(array(0), $monthsDifference, 0);
$o=[];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $money = $row["tot_money"];
        $current = $row["p_end"];
        $d3 = new DateTime($current);
        $endmonth = $d3->format('m');
            for ($mcount=0; $mcount<count($totalMoney) ; $mcount++) {
                if (($endmonth)==$mcount+1) {
                    $totalMoney[$mcount]=$totalMoney[$mcount]+$money;
                }
        }
    }
}
for ($mcount=0; $mcount<count($totalMoney) ; $mcount++) {
    $m = sprintf("%02d", ($mcount+1));
    $to = ['period'=>'2017-'.$m,'Pay'=>$totalMoney[$mcount]];
    array_push($o,$to);
}

return json_encode($o);
}

function donutchart($comp, $result){
    $o = [];
    $lastcompany ="";
    $totalmoney=0;
    $companies = [];
    if ($comp->num_rows > 0) {
        while ($row = $comp->fetch_assoc()) {
             $id=$row["company_id"];
             $name=$row["name"];
             $companies[$id]=$name;
        }
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $money = $row["tot_money"];
            $company = $row["p_company"];
            if ($lastcompany==$company) {
                $totalmoney=$totalmoney + $money;
                $lastcompany=$company;
            } else {
                if ($lastcompany!="") {
                    $to = ['label'=>$companies[$lastcompany],'value'=>$totalmoney];
                    array_push($o,$to);
                }
                $totalmoney=$money;
                $lastcompany=$company;
            }
        }
    }
    usort($o, function($a, $b) {return $a['value'] - $b['value'];});
    return json_encode($o);
}


//------------------------------------------------------------------------------
if (!empty($_GET["t"])&&!empty($_GET["t"])) {
    $type=$_GET["t"];
}else{
    die('{ "message": "ERROR: NO CHART TYPE SELECTED"}');
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');
}

if (!empty($_GET["s"])&&!empty($_GET["e"])&&$type=='l') {
    $start=mysqli_real_escape_string($conn, $_GET["s"]);
    $end=mysqli_real_escape_string($conn, $_GET["e"]);
    $sql = "SELECT tot_money, p_end FROM `projects` WHERE user_id='$u_id' AND p_start BETWEEN '$start' AND '$end' ORDER BY p_start;";
    //$sql = "SELECT tot_money, p_end FROM `projects` WHERE user_id='$u_id';";

        $result = $conn->query($sql);

    //echo json_encode($result);
    echo linechart($result,$start,$end);

} elseif ($type=='d'){
    $sql = "SELECT tot_money, p_company FROM `projects` WHERE user_id='$u_id' ORDER BY p_company;";
    $result = $conn->query($sql);
    $sql = "SELECT company_id, name FROM `companies`";
    $comp = $conn->query($sql);
    echo donutchart($comp,$result);
}else{
    die('{ "message": "ERROR: NO DATES SPECIFIED OR INVALID CHARTTYPE"}');
}
