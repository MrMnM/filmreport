<?php
session_name('SESSID');
session_set_cookie_params(0, '/', '.filmstunden.ch',TRUE,TRUE);
session_start();
if (!isset($_SESSION["running"]) || ($_SESSION["running"] != 1)) {
    session_destroy();
    die('{ "message": "ERROR: NICHT EINGELOGGT"}');
} else {
    $u_id= $_SESSION['user'];
    $u_name= $_SESSION['name'];
    $u_type=$_SESSION['type'];
}
?>
