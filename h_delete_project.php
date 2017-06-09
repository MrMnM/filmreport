<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

session_start();
if ($_SESSION["running"] != 1) {
    session_destroy();
    die('<script type="text/javascript" language="JavaScript">window.location.href="./login.php";</script>');
}else{
    $u_id = $_SESSION['user'];
}

if (!empty($_POST["id"]) && !empty($u_id)) {
include './includes/inc_dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die('{ "message": "Error: '.$conn->connect_error.'"}');
    }

    $id = $_POST["id"];

    $sql = "DELETE FROM projects WHERE project_id='$id' AND user_id='$u_id'";

    if ($conn->query($sql) === TRUE) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$id.'"}';
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
    $conn->close();
}else{
    die('{ "message": "ERROR, PLEASE SUPPLY WITH CORRECT DATA" }');
}
?>
