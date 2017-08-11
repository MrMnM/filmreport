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
       if (!empty($u_id) && !empty($_POST["name"]) && !empty($_POST["date"]) && !empty($_POST["work"]) && !empty($_POST["pay"]) && !empty($_POST["company"])) {
            NewProject($u_id, $conn);
        }else{
            die('{ "message": "Error: Please supply correct data" }');
        }
      break;
    case 'delete':
        if (!empty($_POST["id"]) && !empty($u_id)) {
            DeleteProject($u_id, $conn);
        }else{
            die('{ "message": "ERROR, PLEASE SUPPLY WITH CORRECT DATA" }');
        }
    break;
    case 'save':
    if (!empty($u_id) && !empty($_POST["data"])&& !empty($_POST["id"])&& !empty($_POST["add"])) {
        SaveProject($u_id, $conn);
    }else{
        die('{ "message": "ERROR: Fehlerhafte Daten"}');
    }
    break;
    case 'update':
    if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
        UpdateProject($u_id, $conn);
    }else{
        die('{ "message": "ERROR: Fehlerhafte Daten"}');
    }
    break;

    case 'finish':
    if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
        FinishProject($u_id, $conn);
    }else{
        die('{ "message": "ERROR: Fehlerhafte Daten"}');
    }
    break;

    case 'getinfo':
    if (!empty($u_id) && !empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
        GetProjectInfo($u_id, $conn);
    }else{
        die('{ "message": "ERROR: Fehlerhafte Daten"}');
    }
    break;
  }
}

$conn->close();



function NewProject($u_id, $conn){
    $p_name= mysqli_real_escape_string($conn,$_POST["name"]);
    $p_startdate= mysqli_real_escape_string($conn,$_POST["date"]);
    $p_work= mysqli_real_escape_string($conn,$_POST["work"]);
    $p_pay= mysqli_real_escape_string($conn,$_POST["pay"]);
    $p_company= mysqli_real_escape_string($conn,$_POST["company"]);
    $now = date(DATE_ATOM, time());
    $project_id = md5($p_name.$now);

    $sql = "INSERT INTO projects (project_id,user_id,p_name,p_start,p_job,p_gage,p_company)
    VALUES ('$project_id', '$u_id','$p_name','$p_startdate','$p_work','$p_pay','$p_company')";

    if ($conn->query($sql) === TRUE) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$project_id.'"}';
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
}

function DeleteProject($u_id, $conn){
    $id = mysqli_real_escape_string($conn,$_POST["id"]);
    $sql = "DELETE FROM projects WHERE project_id='$id' AND user_id='$u_id'";

    if ($conn->query($sql) === TRUE) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$id.'"}';
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
}

function SaveProject($u_id, $conn){
    $data = mysqli_real_escape_string($conn,$_POST['data']);
    $add = json_decode($_POST['add'],true);
    $tothours=mysqli_real_escape_string($conn,$add['tothour']);
    $totmoney=mysqli_real_escape_string($conn,$add['totmoney']);
    $enddate=mysqli_real_escape_string($conn,$add['enddate']);
    $p_id = mysqli_real_escape_string($conn,$_POST['id']);
    if (!empty($_POST["comment"])) {
         $comment = mysqli_real_escape_string($conn,$_POST['comment']);
    } else{
        $comment = "";
    }

   $sql = "UPDATE projects SET p_json = '$data', tot_hours='$tothours',tot_money='$totmoney', p_end='$enddate', p_comment='$comment' WHERE project_id = '$p_id'";

   if ($conn->query($sql) === TRUE) {
       die('{ "message": "SUCCESS:"}');
   } else {
       die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
   }
}

function FinishProject($u_id, $conn){
//TODO
}

function UpdateProject($u_id, $conn){
    $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
    if($u_id != $us_id){
        die('{ "message": "ERROR: NOT LOGGED IN"}');
    }
    $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);

    if (!empty($_POST["name"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["name"]);
        $sql = "UPDATE projects SET  p_name='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["work"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["work"]);
        $sql = "UPDATE projects SET  p_job='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["pay"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["pay"]);
        $sql = "UPDATE projects SET  p_gage='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["company"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["company"]);
        $sql = "UPDATE projects SET  p_company='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    echo '{ "message": "SUCCESS:",  "project_id":"'.$p_id.'"}';
}

function GetProjectInfo($u_id, $conn){
    if (!empty($_POST["p_id"])) {
    $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);

    //Get Projects
    $sql = "SELECT p_name, p_company, p_job, p_gage FROM `projects` WHERE project_id='$p_id';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $name = mysqli_real_escape_string($conn,$row["p_name"]);
            $company_id = mysqli_real_escape_string($conn,$row["p_company"]);
            $job = mysqli_real_escape_string($conn,$row["p_job"]);
            $pay = mysqli_real_escape_string($conn,$row["p_gage"]);
        }
    }

    //Get companies
    $sql = "SELECT name, address_1, address_2 FROM `companies` WHERE company_id='$company_id';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $company = $row["name"];
            $c_address1 = $row["address_1"];
            $c_address2= $row["address_2"];
        }
    }
    $company = $company."</br>".$c_address1."</br>".$c_address2;

    echo '{';
    echo '"name": "'.$name.'",';
    echo '"job": "'.$job.'",';
    echo '"pay": "'.$pay.'",';
    echo '"company": "'.$company.'"';
    echo '}';


} else {
    die('{ "message":"ERROR"}');
}
}
?>
