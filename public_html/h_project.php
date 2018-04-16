<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 'on');


include './includes/inc_sessionhandler_ajax.php';
require_once('../api-app/lib/Globals.php');
$db=$GLOBALS['db'];
$servername = "localhost";
$dbname = $db['database_name'];
$username = $db['username'];
$password = $db['password'];

$action = (isset($_POST['action']) and $_POST['action']!="") ? $_POST['action'] : null;
if ($action) {

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}

    switch ($_POST["action"]) {
    case 'load':
        if (!empty($u_id) && !empty($_POST["p_id"])) {
            LoadProject($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
    case 'update':
        if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
            UpdateProject($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
    case 'getinfo':
        if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
            GetProjectInfo($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
  }
}

function UpdateProject($u_id, $conn)
{
    $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
    if ($u_id != $us_id) {
        die('{ "message": "ERROR: NOT LOGGED IN"}');
    }
    $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);

    if (!empty($_POST["name"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["name"]);
        $sql = "UPDATE projects SET  p_name='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
        }
    }
    if (!empty($_POST["work"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["work"]);
        $sql = "UPDATE projects SET  p_job='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
        }
    }
    if (!empty($_POST["pay"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["pay"]);
        $sql = "UPDATE projects SET  p_gage='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
        }
    }
    if (!empty($_POST["company"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["company"]);
        $sql = "UPDATE projects SET  p_company='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
        }
    }
    echo '{ "message": "SUCCESS",  "project_id":"'.$p_id.'"}';
}

function GetProjectInfo($u_id, $conn)
{
    if (!empty($_POST["p_id"])) {
        $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);
        //Get Projects
        $sql = "SELECT p_name, p_company, p_job, p_gage FROM `projects` WHERE project_id='$p_id';";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = mysqli_real_escape_string($conn, $row["p_name"]);
                $company_id = mysqli_real_escape_string($conn, $row["p_company"]);
                $job = mysqli_real_escape_string($conn, $row["p_job"]);
                $pay = mysqli_real_escape_string($conn, $row["p_gage"]);
            }
        }
        //Get companies
        $sql = "SELECT name,
                       address_1,
                       address_2
                FROM `companies`
                WHERE company_id='$company_id';";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $company = $row["name"];
                $c_address1 = $row["address_1"];
                $c_address2= $row["address_2"];
            }
        }
        $company = $company."</br>".$c_address1."</br>".$c_address2;
        $o = ["name"=>$name,
              "job"=>$job,
              "pay"=>$pay,
              "company"=>$company,
              "companyId"=>$company_id];
        echo json_encode($o);
    } else {
        die('{ "message":"ERROR GETTING PROJECT INFO"}');
    }
}

function LoadProject($u_id, $conn)
{
    if (!empty($_POST["p_id"])) {
          $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);
          $sql = "SELECT p_json, p_comment FROM `projects` WHERE project_id='$p_id';";
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $json = $row["p_json"];
                  $comment = $row["p_comment"];
              }
          }
          $o = ["data"=>$json,
                "comment"=>$comment];
        echo json_encode($o);
    } else {
        die('{ "message":"ERROR GETTING PROJECT INFO"}');
    }
}
