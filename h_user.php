<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL);
//ignore_user_abort();
include './includes/inc_encrypt.php';
include './includes/inc_dbconnect.php';
$action = (isset($_POST['action']) and $_POST['action']!="") ? $_POST['action'] : null;

if ($action!='new') {
    include './includes/inc_sessionhandler_ajax.php';
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('{ "message": "ERROR: '.$conn->connect_error.'"}');
}

if ($action) {
    switch ($_POST["action"]) {
        case 'new':
            NewUser($conn);
        break;
        case 'update':
        if (!empty($u_id) && !empty($_POST["us_id"])) {
            UpdateInfo($u_id, $conn);
        } else {
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
        case 'get':
        if (!empty($u_id) && !empty($_POST["us_id"])) {
            GetUser($u_id, $conn);
        } else {
            die('{ "message": "ERROR: Fehlerhafte Daten"}');
        }
        break;
    }
}

$conn->close();

function GetUser($u_id, $conn)
{
    $sql = "SELECT mail, tel, name, address_1, address_2, ahv, dateob, konto, bvg, type, affiliation FROM `users` WHERE u_id='$u_id';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $u_name = $row["name"];
            $u_tel = $row["tel"];
            $u_mail = $row["mail"];
            $u_ahv = encrypt($row["ahv"], 'd');
            $u_dob = $row["dateob"];
            $u_konto = encrypt($row["konto"], 'd');
            $u_bvg = $row["bvg"];
            $u_address1= $row["address_1"];
            $u_address2= $row["address_2"];
            $type=$row["type"];
        }
    }
    //TODO more elegantly
    $arr = array('name' => $u_name, 'tel' => $u_tel, 'mail' => $u_mail, 'ahv' => $u_ahv, 'dob' => $u_dob, 'konto' => $u_konto,'bvg'=> $u_bvg,'address1'=>$u_address1 ,'address2'=>$u_address2);
    echo json_encode($arr);
}

function NewUser($conn)
{
    if (!empty($_POST["name"]) && !empty($_POST["mail"]) && !empty($_POST["pw"]) && !empty($_POST["pw2"])) {
        if ($_POST["pw"]!=$_POST["pw2"]) {
            die('{ "message": "Passwort stimmt nicht &uuml;eberein" }');
        }
        $mail = mysqli_real_escape_string($conn, $_POST["mail"]);
        $sql = "SELECT mail FROM `users` WHERE mail='$mail';";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            die('{ "message": "Zu dieser Email gibt es bereits einen Account" }');
        } else {
            $uid = md5($mail);
            $name = $_POST["name"];
            $active = substr(md5(microtime()), 1, 6); //TODO Timeout
            $pw= $_POST["pw"];
            $pwhash = mysqli_real_escape_string($conn, password_hash($pw, PASSWORD_DEFAULT));
            $date = date("Y-m-d");

            // $sql = "UPDATE users SET tel = '$tel', name='$name',address_1='$address1', address_2='$address2', ahv='$ahv', dateob='$dateob' , bvg='$bvg' , konto='$konto' WHERE u_id = '$u_id'";
            $sql = "INSERT INTO users (name,u_id,active,mail,pw,tel,address_1,address_2,ahv,dateob,bvg,konto) VALUES ('$name','$uid','$active','$mail','$pwhash','','','','','$date','','')";
            if ($conn->query($sql) === true) {
                $subject = 'Filmabrechnungsgenerator';
                $message = 'Hallo, bitte bestaetige deine Email Addresse mit folgendem Link: http://www.xibrix.ch/filmreport/validate/'.$active;
                $message = wordwrap($message, 76, "\r\n");
                $encoding = "utf-8";
                // Preferences for Subject field
                $subject_preferences = array(
                    "input-charset" => $encoding,
                    "output-charset" => $encoding,
                    "line-length" => 76,
                    "line-break-chars" => "\r\n"
                );
                $from_mail = "info@xibrix.ch";
                $from_name = "Filmstunden";
                // Mail header
                $header = "Content-type: text/html; charset=".$encoding." \r\n";
                $header .= "From: ".$from_name." <".$from_mail."> \r\n";
                $header .= "MIME-Version: 1.0 \r\n";
                $header .= "Content-Transfer-Encoding: 8bit \r\n";
                $header .= "Date: ".date("r (T)")." \r\n";
                $header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

                if (mail($mail, $subject, $message)) {
                    echo '{ "message": "SUCCESS" }';
                } else {
                    echo '{ "message": "MAIL ERROR" }';
                }
            } else {
                die('{ "message": "ERROR: '.$conn->connect_error.'"}');
            }
        }
    } else {
        die('{ "message": "Falsch aufgerufen" }');
    }
}



function UpdateInfo($u_id, $conn)
{
    $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
    if ($u_id != $us_id) {
        die('{ "message": "ERROR: NOT LOGGED IN"}');
    }

    if (!empty($_POST["name"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["name"]);
        $sql = "UPDATE users SET  name='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["tel"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["tel"]);
        $sql = "UPDATE users SET  tel='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["address1"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["address1"]);
        $sql = "UPDATE users SET  address_1='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["address2"])) {
        $cur= mysqli_real_escape_string($conn, $_POST["address2"]);
        $sql = "UPDATE users SET  address_2='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["ahv"])) {
        $cur= encrypt($_POST["ahv"], 'e');
        $sql = "UPDATE users SET  ahv='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["dob"])) {
        $cur=mysqli_real_escape_string($conn, $_POST["dob"]);
        $sql = "UPDATE users SET  dateob='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["konto"])) {
        $cur= encrypt($_POST["konto"], 'e');
        $sql = "UPDATE users SET  konto='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    if (!empty($_POST["bvg"])) {
        $cur=mysqli_real_escape_string($conn, $_POST["bvg"]);
        $sql = "UPDATE users SET  bvg='$cur' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
        } else {
            die('{ "message":"'. $sql .' ' . $conn->error.'"}');
        }
    }
    die('{ "message":"SUCCESS"}');
}
