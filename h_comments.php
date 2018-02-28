<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include './includes/inc_dbconnect.php';
//include './includes/inc_sessionhandler_ajax.php';

session_name('SESSID');
session_start();
if (!isset($_SESSION["running"]) || ($_SESSION["running"] != 1)) {
$u_id="guest";
} else {
$u_id= $_SESSION['user'];
}

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
      if (!empty($u_id) && !empty($_POST["p_id"])&& !empty($_POST["text"])){
           NewComment($conn, $u_id);
       } else {die('{ "message": "ERROR: PLEASE SUPPLY CORRECT DATA" }');}
      break;
  }
}

function LoadComments($conn, $u_id)
{
  $id = mysqli_real_escape_string($conn, $_POST["p_id"]);
  $sql = "SELECT comments.id,
                 comments.time,
                 users.name,
                 comments.project,
                 comments.seen,
                 comments.text
          FROM `comments`
          INNER JOIN `users` ON comments.from = users.u_id
        -- TODO here  INNER JOIN `users` ON comments.to = users.u_id
          WHERE `project`='$id'";
  $result = $conn->query($sql);
    $full=[];
    if ($result->num_rows > 0) {
      $rowCount = mysqli_num_rows($result);
      while ($row = $result->fetch_assoc()) {
        $c=['id'=>$row["id"],
            'date'=>$row["time"],
            'from'=>$row["name"],
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
  $text = mysqli_real_escape_string($conn, $_POST["text"]);
  $sql = "INSERT INTO `comments` (`from`,`project`,`seen`,`text`)
  VALUES ('$u_id','$p_id',0,'$text')";

  if ($conn->query($sql) === true) {
      die('{ "message": "SUCCESS"}');
  } else {
      die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
  }
}

?>
