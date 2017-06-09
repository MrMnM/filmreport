<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_dbconnect.php';
include './includes/inc_sessionhandler_ajax.php';

if (!empty($u_id) && !empty($_POST["name"]) && !empty($_POST["date"]) && !empty($_POST["work"]) && !empty($_POST["pay"]) && !empty($_POST["company"])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die('{ "message": "Error: '.$conn->connect_error.'"}');
    }

    $p_name= mysqli_real_escape_string($conn,$_POST["name"]);
    $p_startdate= mysqli_real_escape_string($conn,$_POST["date"]);
    $p_work= mysqli_real_escape_string($conn,$_POST["work"]);
    $p_pay= mysqli_real_escape_string($conn,$_POST["pay"]);
    $p_company= mysqli_real_escape_string($conn,$_POST["company"]);
    $now = date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
    $project_id = md5($p_name.$now);

    $sql = "INSERT INTO projects (project_id,user_id,p_name,p_start,p_job,p_gage,p_company)
    VALUES ('$project_id', '$u_id','$p_name','$p_startdate','$p_work','$p_pay','$p_company')";

    if ($conn->query($sql) === TRUE) {
        echo '{ "message": "SUCCESS",  "project_id":"'.$project_id.'"}';
    } else {
        die('{ "message": "Error: ' . $sql . $conn->error.'}');
    }
    $conn->close();
}else{
    die('{ "message": "ERROR, PLEASE SUPPLY WITH CORRECT DATA" }');
}
?>
