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
    case 'load':
       if (!empty($u_id) && !empty($_POST["p_id"])){
           LoadComments($conn, $u_id);
       } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
      break;
    case 'add':
      if (!empty($u_id) && !empty($_POST["p_id"])&& !empty($_POST["to_id"])&& !empty($_POST["text"])){
           NewComment($conn, $u_id);
       } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
      break;
  }
}

function LoadComments($conn, $u_id)
{ //TODO Add right fields here
  $id = mysqli_real_escape_string($conn, $_POST["p_id"]);
  $sql = "SELECT * FROM `comments` WHERE `project`='$id'";
  $result = $conn->query($sql);
    $full=[];
    if ($result->num_rows > 0) {
      $rowCount = mysqli_num_rows($result);
      while ($row = $result->fetch_assoc()) {
        $c=['id'=>$row["id"],
            'date'=>$row["time"],
            'from'=>$row["from"],
            'to'=>$row["to"],
            'text'=>$row["text"]
        ];
      array_push($full,$c);
      }
    }
    echo json_encode($full);
  }


function NewComment($conn, $u_id)
{ //TODO Continue Here
  $p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);
  $to = mysqli_real_escape_string($conn, $_POST["to_id"]);
  $text = mysqli_real_escape_string($conn, $_POST["text"]);
  $sql = "INSERT INTO `comments` (`to`,`from`,`project`,`seen`,`text`)
  VALUES ('$to','$u_id','$p_id',0,'$text')";

  if ($conn->query($sql) === true) {
      die('{ "message": "SUCCESS"}');
  } else {
      die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
  }

}

?>