<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//ignore_user_abort();

include './includes/inc_dbconnect.php';
include './includes/inc_sessionhandler_ajax.php';

$action = (isset($_POST['action']) AND $_POST['action']!="") ? $_POST['action'] : null;

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('{ "message": "ERROR: '.$conn->connect_error.'"}');
}

if($action){
    switch ($_POST["action"]){
        case 'new':
        if (!empty($u_id) && !empty($_POST["name"])) {
            NewTimer($u_id, $conn);
        }else{
            die('{ "message": "Error: Please supply correct data" }');
        }
        break;
        case 'delete':
        if (!empty($_POST["id"]) && !empty($u_id)) {
            DeleteTimer($u_id, $conn);
        }else{
            die('{ "message": "ERROR, PLEASE SUPPLY WITH CORRECT DATA" }');
        }
        break;
        case 'update':
        if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
            UpdateTimer($u_id, $conn);
        }else{
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
        case 'gettimers':
        if (!empty($u_id)) {
            GetTimers($u_id, $conn);
        }else{
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
        case 'save':
        if (!empty($u_id) && !empty($_POST["timer"]) && !empty($_POST["data"])) {
            SaveTimer($u_id, $conn);
        }else{
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
    }
}
$conn->close();


function NewTimer($u_id, $conn){
    $name= mysqli_real_escape_string($conn,$_POST["name"]);
    $now = date(DATE_ATOM, time());
    $timer_id = substr(md5($name.$now),0,5);
    if ($project_id = NewProject($u_id,$name,$now,$conn)){
        $sql = "INSERT INTO active_timers (timer_id,project_id,user_id,name,creation)
        VALUES ('$timer_id','$project_id', '$u_id','$name','$now')";
        if ($conn->query($sql) === TRUE) {
            echo '{ "message": "SUCCESS",  "name":"'.$name.'", "id":"'.$timer_id.'"}';
        } else {
            die('{ "message": "Error: ' . $sql . $conn->error.'}');
        }
    }else{
        die('{ "message": "Error: Projekt nicht erstellt"}');
    }
}


function NewProject($u_id, $name, $date, $conn){
    $project_id = md5($name.$date);
    $sql = "INSERT INTO projects (project_id,user_id,p_name,p_start,p_company)
    VALUES ('$project_id', '$u_id','$name','$date','timer')";

    if ($conn->query($sql) === TRUE) {
        return $project_id;
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
}

function GetTimers($u_id, $conn){
    $sql = "SELECT timer_id, name, creation FROM `active_timers` WHERE user_id='$u_id';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $counter = 1;
        $rowCount = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()) {
            echo '<a href="#" onclick="SetActive(\''.$row["timer_id"].'\',\''.$row["name"].'\')" class="list-group-item success">'.PHP_EOL;
            echo '<i class="fa fa-hourglass-half fa-fw"></i> '.$row["name"].PHP_EOL;
            echo '<span class="pull-right small"><em class="text-muted">'.$row["creation"].'</em>&nbsp;&nbsp;<button class="btn btn-outline btn-danger btn-xs" onClick="deleteTimer(\''.$row["timer_id"].'\')"><i class="fa fa-fw fa-times" id="deleteTimer"></i></button>'.PHP_EOL;
            echo '</span></a>';
        }
    }
}

function SaveTimer($u_id, $conn){
}

function DeleteTimer($u_id, $conn){
    $id = mysqli_real_escape_string($conn,$_POST["id"]);
    $sql = "DELETE FROM active_timers WHERE timer_id='$id' AND user_id='$u_id'";
    if ($conn->query($sql) === TRUE) {
        die('{ "message": "SUCCESS",  "timer_id":"'.$id.'"}');
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
}


?>
