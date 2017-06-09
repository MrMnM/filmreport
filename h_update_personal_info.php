<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_encrypt.php';
include './includes/inc_dbconnect.php';
include './includes/inc_sessionhandler_ajax.php';


if (!empty($_POST["u_id"])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $u_id = mysqli_real_escape_string($conn, $_POST["u_id"]);
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
