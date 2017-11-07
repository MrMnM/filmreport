<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

if (!empty($_POST["name"]) && !empty($_POST["address1"]) && !empty($_POST["address2"]) && !empty($_POST["phone"]) && !empty($_POST["mail"])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die('{ "message": "Error: '.$conn->connect_error.'"}');
    }

    $name=mysqli_real_escape_string($conn, $_POST["name"]);
    $address1=mysqli_real_escape_string($conn, $_POST["address1"]);
    $address2=mysqli_real_escape_string($conn, $_POST["address2"]);
    $phone= mysqli_real_escape_string($conn, $_POST["phone"]);
    $companyid = substr(md5($name.$address1.$phone), 0, 5);
    $mail = mysqli_real_escape_string($conn, $_POST["mail"]);

    $sql = "INSERT INTO companies (company_id,name,address_1,address_2,telephone,mail)
    VALUES ('$companyid','$name', '$address1','$address2','$phone','$mail')";

    if ($conn->query($sql) === true) {
        echo '{ "message": "SUCCESS", "c_id":"'.$companyid.'"}';
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'"}');
    }
    $conn->close();
} else {
    die('{ "message": "ERROR, PLEASE SUPPLY WITH CORRECT DATA" }');
}
