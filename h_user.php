<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//ignore_user_abort();

include './includes/inc_encrypt.php'
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
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
        case 'update':
        if (!empty($u_id) && !empty($_POST["us_id"])) {
            UpdateInfo($u_id, $conn);
        }else{
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
    }
}

$conn->close();


function UpdateInfo($u_id, $conn){
    $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
    if($u_id != $us_id){
        die('{ "message": "ERROR: NOT LOGGED IN"}');
    }

    if (!empty($_POST["name"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["name"]);
        $sql = "UPDATE users SET  name='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["tel"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["tel"]);
        $sql = "UPDATE users SET  tel='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["address1"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["address1"]);
        $sql = "UPDATE users SET  address_1='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["address2"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["address2"]);
        $sql = "UPDATE users SET  address_2='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["ahv"])) {
        $cur= encrypt($_POST["ahv"],'e');
        $sql = "UPDATE users SET  ahv='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["dateob"])) {
        $cur=mysqli_real_escape_string($conn,$_POST["dob"]);
        $sql = "UPDATE users SET  dateob='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["konto"])) {
        $cur= encrypt($_POST["konto"],'e');
        $sql = "UPDATE users SET  konto='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    if (!empty($_POST["bvg"])) {
        $cur=mysqli_real_escape_string($conn,$_POST["bvg"]);
        $sql = "UPDATE users SET  bvg='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}';
        }
    }
    $conn->close();
}
