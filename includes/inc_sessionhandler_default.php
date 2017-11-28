<?
session_name('sessionID');
session_start();
if ($_SESSION["running"] != 1) {
    session_destroy();
    header( 'Location: ./login.php') ;
}else{
    $u_id= $_SESSION['user'];
    $u_name= $_SESSION['name'];
    $u_type=$_SESSION['type'];
}
?>
