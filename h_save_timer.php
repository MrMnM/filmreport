 <?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting( E_ALL | E_STRICT );

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

//TODO ESCAPES!!

if (!empty($u_id) && !empty($_POST["data"])&& !empty($_POST["id"])&& !empty($_POST["add"])) {
 $data = $_POST['data'];
 $add = json_decode($_POST['add'],true);
 $tothours=$add['tothour'];
 $totmoney=$add['totmoney'];
 $enddate=$add['enddate'];
 $p_id = $_POST['id'];


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die('{ "message": "ERROR: '.$conn->connect_error.'"}');
}
$sql = "UPDATE projects SET p_json = '$data', tot_hours='$tothours',tot_money='$totmoney', p_end='$enddate', p_comment='$comment' WHERE project_id = '$p_id'";

if ($conn->query($sql) === TRUE) {
    echo '{ "message": "SUCCESS:",  "project_id":"'.$p_id.'"}';
} else {
    die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
}

$conn->close();
}else{
    die('{ "message": "ERROR: Fehlerhafte Daten"}');
}
?>
