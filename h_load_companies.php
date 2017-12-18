<?php
header('Content-Type: application/json');
include './includes/inc_dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}

$sql = "SELECT company_id, name FROM `companies` ORDER BY `name` ASC"; //TODO fix sorting by id, possibly in js?
$result = $conn->query($sql);
$full=[];
if ($result->num_rows > 0) {
    while ($cmp = $result->fetch_assoc()) {
      $c=["id"=>$cmp["company_id"],"name"=>$cmp["name"]];
      array_push($full,$c);
    }
}
echo json_encode($full);
?>
