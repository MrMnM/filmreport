<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );
//ignore_user_abort();

include './includes/inc_dbconnect.php';
include './includes/inc_sessionhandler_ajax.php';

$action = (isset($_POST['action']) AND $_POST['action']!="") ? $_POST['action'] : null;

if($action){
  switch ($_POST["action"]){
    case 'new':
       if (!empty($u_id) && !empty($_POST["name"]) && !empty($_POST["date"]) && !empty($_POST["work"]) && !empty($_POST["pay"]) && !empty($_POST["company"])) {
            NewProject($u_id, $servername, $username, $password, $dbname);
        }else{
            die('{ "message": "Error: Please supply correct data" }');
        }
      break;
    case 'delete':
        if (!empty($_POST["id"]) && !empty($u_id)) {
            DeleteProject($u_id, $servername, $username, $password, $dbname);
        }else{
            die('{ "message": "ERROR, PLEASE SUPPLY WITH CORRECT DATA" }');
        }
    break;
    case 'save':
    if (!empty($u_id) && !empty($_POST["data"])&& !empty($_POST["id"])&& !empty($_POST["add"])) {
        SaveProject($u_id, $servername, $username, $password, $dbname);
    }else{
        die('{ "message": "ERROR: Fehlerhafte Daten"}');
    }
    break;
  }
}



function NewProject($u_id, $servername, $username, $password, $dbname){
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die('{ "message": "Error: '.$conn->connect_error.'"}');
    }
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
    $conn->close();
}

function DeleteProject($u_id, $servername, $username, $password, $dbname){
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die('{ "message": "Error: '.$conn->connect_error.'"}');
    }

    $id = mysqli_real_escape_string($conn,$_POST["id"]);
    $sql = "DELETE FROM projects WHERE project_id='$id' AND user_id='$u_id'";

    if ($conn->query($sql) === TRUE) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$id.'"}';
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
    $conn->close();
}

function SaveProject($u_id, $servername, $username, $password, $dbname){
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die('{ "message": "ERROR: '.$conn->connect_error.'"}');
    }
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
       echo '{ "message": "SUCCESS:",  "project_id":"'.$p_id.'"}';
   } else {
       die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
   }
   $conn->close();
}


/*
if (!empty($_POST["us_id"]) && !empty($_POST["p_id"])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die('{ "message":"'. $conn->connect_error.'"}');
    }
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
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["work"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["work"]);
        $sql = "UPDATE projects SET  p_job='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["pay"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["pay"]);
        $sql = "UPDATE projects SET  p_gage='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["company"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["company"]);
        $sql = "UPDATE projects SET  p_company='$cur' WHERE project_id = '$p_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    $conn->close();
}
*/
?>
