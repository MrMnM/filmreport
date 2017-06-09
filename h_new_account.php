<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_dbconnect.php';

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die('{ "message": "ERROR, '. $conn->connect_error.'" }');
}

if (!empty($_POST["mail"]) && !empty($_POST["pw"]) && !empty($_POST["pw2"])) {

    if($_POST["pw"]!=$_POST["pw2"]){
    die('{ "message": "Passwort stimmt nicht &uuml;eberein" }');
    }

    $mail = mysqli_real_escape_string($conn, $_POST["mail"]);
    $uid = md5($mail);

    $sql = "SELECT mail FROM `users` WHERE mail='$mail';";

    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            die('{ "message": "Zu dieser Email gibt es bereits einen Account" }');
    }else{
    $active = substr(md5(microtime()),0,6);
    $pw= $_POST["pw"];
    $pwhash = mysqli_real_escape_string($conn, password_hash($pw, PASSWORD_DEFAULT));

    $sql = "INSERT INTO users (u_id,active,mail,pw) VALUES ('$uid','$active', '$mail','$pwhash')";
    if ($conn->query($sql) === TRUE) {
        $to      = $mail;
        $subject = 'Filmabrechnungsgenerator';
        $message = 'Hallo, bitte bestaetige deine Email Addresse mit folgendem Link: http://www.xibrix.ch/filmreport/new_account.php?v='.$active;
        $message = wordwrap($message, 70, "\r\n");
        $headers = 'From: webmaster@filmreport.com' . "\r\n" .
            'Reply-To: webmaster@filmreport.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);



        echo '{ "message": "SUCCESS" }';
    } else {
        echo '{ "message": "SQL Fehler" }';
    }
}

}elseif(!empty($_GET["v"])){
    $val = mysqli_real_escape_string($conn, $_GET["v"]);
    $sql = "SELECT active, u_id FROM `users` WHERE active='$val';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $u_id = $row["u_id"];
        }
        $sql = "UPDATE users SET active = 'yes' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
            die('<script type="text/javascript" language="JavaScript">window.location.href="./firstlaunch.php?id='.$u_id.'";</script>');
        } else {
            die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
        }

    }else{
        die('{ "message": "Ung&uuml;ltiger Validierungsschl&uuml;ssel" }');
    }

}else{
    die('{ "message": "Falsch aufgerufen" }');
}
