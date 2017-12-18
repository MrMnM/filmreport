<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}


$sql = "SELECT company_id, name FROM `companies`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($cmp = $result->fetch_assoc()) {
      $comp[$cmp["company_id"]] = $cmp["name"];
    }
}

if ($_GET["fin"]==1) {
    $sql = "SELECT project_id, p_start, p_name, p_company, tot_hours, tot_money, p_finished, view_id  FROM `projects` WHERE user_id='$u_id' AND p_finished=1;";
} elseif ($_GET["fin"]==0)  {
    $sql = "SELECT project_id, p_start, p_name, p_company, tot_hours, tot_money, p_finished, view_id  FROM `projects` WHERE user_id='$u_id' AND p_finished=0;";
} elseif ($_GET["fin"]==2) {
    $sql = "SELECT project_id, p_start, p_name, p_company, tot_hours, tot_money, p_finished, view_id  FROM `projects` WHERE user_id='$u_id';";
}

$result = $conn->query($sql);
$full=[];
if ($result->num_rows > 0) {
    $rowCount = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc()) {
      $c=[$row["p_start"],
          $row["p_name"],
          $comp[$row["p_company"]],
          $row["tot_hours"],
          $row["tot_money"],
          $row["project_id"],
          $row["p_finished"],
          $row["view_id"]
        ];
      array_push($full,$c);
    }
  }
  $out=['data' => $full];
  echo json_encode($out);
