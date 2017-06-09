<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_encrypt.php';
include './includes/inc_dbconnect.php';
include './includes/inc_sessionhandler_ajax.php';

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
