<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}


if (!empty($_GET["s"])&&!empty($_GET["e"])) {
    $start=mysqli_real_escape_string($conn, $_GET["s"]);
    $end=mysqli_real_escape_string($conn, $_GET["e"]);
    $sql = "SELECT tot_money, p_end FROM `projects` WHERE user_id='$u_id' AND p_start BETWEEN '$start' AND '$end' ORDER BY p_start;";
    $result = $conn->query($sql);
} else {
    die('{ "message": "ERROR: KEINE DATEN ANGEGEBEN}');
}

$start = new DateTime($start);
$end = new DateTime($end);
$monthsDifference =($end->diff($start)->m + ($end->diff($start)->y*12))+1;

$total =0;
$active=0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total = $total + $row["tot_money"];
    }
}

$active=0;
$sql = "SELECT COUNT(*) AS active FROM projects WHERE user_id='$u_id' AND p_finished=0";
$result = $conn->query($sql);
$data=$result->fetch_assoc();
$active= $data['active'];

$o = ['mean_month'=>round($total/$monthsDifference),
      'active_projects'=>$active];

echo json_encode($o);
