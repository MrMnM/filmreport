<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include './includes/inc_dbconnect.php';
include './includes/inc_sessionhandler_ajax.php';

$action = (isset($_POST['action']) and $_POST['action']!="") ? $_POST['action'] : null;
if ($action) {

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}

    switch ($_POST["action"]) {
    case 'new':
       if (!empty($u_id) && !empty($_POST["name"]) && !empty($_POST["date"]) && !empty($_POST["work"]) && !empty($_POST["pay"]) && !empty($_POST["company"])) {
           NewProject($u_id, $conn);
       } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
      break;
    case 'delete':
        if (!empty($_POST["p_id"]) && !empty($u_id)) {
            DeleteProject($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
    case 'save':
        if (!empty($u_id) && !empty($_POST["data"])&& !empty($_POST["p_id"])&& !empty($_POST["add"])) {
            SaveProject($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
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
    case 'finish':
        if (!empty($u_id)  && !empty($_POST["p_id"])) {
            FinishProject($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
    case 'getinfo':
        if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
            GetProjectInfo($u_id, $conn);
        } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
        break;
  }
}


function NewProject($u_id, $conn)
{
    $p_name= mysqli_real_escape_string($conn, $_POST["name"]);
    $p_startdate= mysqli_real_escape_string($conn, $_POST["date"]);
    $p_work= mysqli_real_escape_string($conn, $_POST["work"]);
    $p_pay= mysqli_real_escape_string($conn, $_POST["pay"]);
    $p_company= mysqli_real_escape_string($conn, $_POST["company"]);
    $now = date(DATE_ATOM, time());
    $project_id = md5($p_name.$now);
    $url='http://www.filmstunden.ch/shorten.php?longurl=http://www.xibrix.ch/filmreport/view.php?id='.$project_id;
    $short = file_get_contents($url);

    $sql = "INSERT INTO projects (c_date,project_id,user_id,p_name,p_start,p_job,p_gage,p_company,view_id)
    VALUES ('$now', '$project_id', '$u_id','$p_name','$p_startdate','$p_work','$p_pay','$p_company','$short')";

    if ($conn->query($sql) === true) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$project_id.'"}';
    } else {
        die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
    }
}

function DeleteProject($u_id, $conn)
{
    $id = mysqli_real_escape_string($conn, $_POST["p_id"]);
    $sql = "DELETE FROM projects WHERE project_id='$id' AND user_id='$u_id'";
    if ($conn->query($sql) === true) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$id.'"}';
    } else {
        die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
    }
}

function SaveProject($u_id, $conn)
{
    $data = mysqli_real_escape_string($conn, $_POST['data']);
    $add = json_decode($_POST['add'], true);
    $tothours=mysqli_real_escape_string($conn, $add['tothour']);
    $totmoney=mysqli_real_escape_string($conn, $add['totmoney']);
    $enddate=mysqli_real_escape_string($conn, $add['enddate']);
    //$calcBase=mysqli_real_escape_string($conn, $add['calcBase']);
    $calcBase=0;
    $baseHours=0;
    //$baseHours =mysqli_real_escape_string($conn, $add['baseHours']);
    $settings = json_encode(array('calcBase' => $calcBase, 'baseHours' => $baseHours));
    $p_id = mysqli_real_escape_string($conn, $_POST['p_id']);
    if (!empty($_POST["comment"])) {
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    } else {
        $comment = "";
    }

    $sql = "UPDATE projects
            SET p_json = '$data',
                tot_hours='$tothours',
                tot_money='$totmoney',
                p_end='$enddate',
                p_comment='$comment',
                settings='$settings'
            WHERE project_id = '$p_id'";

    if ($conn->query($sql) === true) {
        die('{ "message": "SUCCESS"}');
    } else {
        die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
    }
}

function FinishProject($u_id, $conn)
{
    /*  TODO  $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
        if($u_id != $us_id){
            die('{ "message": "ERROR: NOT LOGGED IN"}');
        }
    */
    $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);
    $sql = "UPDATE projects SET  p_finished=1 WHERE project_id = '$p_id'";
    if ($conn->query($sql) === true) {
    } else {
        die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
    }
    echo '{ "message": "SUCCESS",  "project_id":"'.$p_id.'"}';
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
