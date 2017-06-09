<?
session_start();
if ($_SESSION["running"] != 1) {
    session_destroy();
    die('<script type="text/javascript" language="JavaScript">window.location.href="./login.php";</script>');
}else{
    $u_id= $_SESSION['user'];
    $u_name= $_SESSION['name'];
}
?>
