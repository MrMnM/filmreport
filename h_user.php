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
if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}

if ($action) {
    switch ($_POST["action"]) {
        case 'new':
            NewUser($conn);
        break;
        case 'update':
        if (!empty($u_id) && !empty($_POST["us_id"])) {
            UpdateUser($u_id, $conn);
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

function GetUser($u_id, $conn)
{
  $sql = "SELECT mail, tel, name, address_1, address_2, ahv, dateob, konto, bvg, type, affiliation FROM `users` WHERE u_id='$u_id';";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $arr = [
            'name' => $row["name"],
            'tel' => $row["tel"],
            'mail' => $row["mail"],
            'ahv' => encrypt($row["ahv"], 'd'),
            'dob' => $row["dateob"],
            'konto' => encrypt($row["konto"], 'd'),
            'bvg'=> $row["bvg"],
            'address1'=>$row["address_1"],
            'address2'=>$row["address_2"]];
      }
  }
  echo json_encode($arr);
}

function update($conn, $post,$db,$encrypt)
{
  if (!empty($_POST[$post])) {
    if($encrypt){
      $cur= encrypt($_POST[$post], 'e');
    }else{
      $cur= mysqli_real_escape_string($conn, $_POST[$post]);
    }
      $sql = "UPDATE users SET '$db'='$cur' WHERE u_id = '$u_id'";
      if ($conn->query($sql) !== true) {
          die('{ "message":"ERROR:'. $sql .' ' . $conn->error.'"}');
      }
  }
}

function UpdateUser($u_id, $conn)
{
    $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
    if ($u_id != $us_id) {die('{ "message": "ERROR: NOT LOGGED IN"}');}
    update($conn, "name", "name", FALSE);
    update($conn, "tel", "tel", FALSE);
    update($conn, "address1", "address_1", FALSE);
    update($conn, "address2", "address_2", FALSE);
    update($conn, "ahv", "ahv", TRUE);
    update($conn, "dob", "dateob", FALSE);
    update($conn, "konto", "konto", TRUE);
    update($conn, "bvg", "bvg", FALSE);
    die('{ "message":"SUCCESS"}');
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
            $active = substr(md5(microtime()), 1, 6); // TODO Timeout
            /*
            Next to your resetkey column place a DATETIME column called, maybe, expires.
            Then, whenever you insert a new reset key, also insert a value into expires:
            INSERT INTO forgot (resetkey, expires) VALUES (whatever, NOW() + INTERVAL 48 HOUR)
            Right before you read any reset key from the table, do this:
            DELETE FROM forgot WHERE expires < NOW()
            */
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
