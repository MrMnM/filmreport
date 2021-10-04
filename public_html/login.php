<?
require_once('../api-app/lib/Globals.php');
$db=$GLOBALS['db'];
$servername = "localhost";
$dbname = $db['database_name'];
$username = $db['username'];
$password = $db['password'];
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("DELETE FROM tokens WHERE timeout < NOW()");

$unknownUser = FALSE;
$invalidPassword = FALSE;
$closedExisting = FALSE;
$activated =TRUE;

if (isset($_GET['action']) && strtolower($_GET['action']) == 'logout'){
  setcookie("REMID", '0', time()-3600, '/', '.filmstunden.ch',TRUE,TRUE);
  setcookie("SESSID", '0', time()-3600, '/', '.filmstunden.ch',TRUE,TRUE);
  $closedExisting = TRUE;
}elseif(isset($_COOKIE['REMID'])){
  $cookie= htmlspecialchars($_COOKIE["REMID"]);
  $t_id = explode(":", $cookie);
  $sql = "SELECT tokens.token_id,
                 tokens.user_id,
                 tokens.token,
                 users.name
          FROM `tokens`
          LEFT JOIN users ON tokens.user_id = users.u_id
          WHERE tokens.type='rem' AND tokens.token_id='$t_id[0]';";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $dbt_id = $row["token_id"];
          $uid = $row["user_id"];
          $db_token = $row["token"];
          $name = $row["name"];
      }
  }
    
  if($db_token===$t_id[1]){
      login($conn,$uid,$name,'0',$rememberme);
  }
}


if (isset($_POST['remember']) && strtolower($_POST['remember']) == 'remember'){
    $rememberme = 1;
}


if (!empty($_POST["pw"]) && !empty($_POST["mail"])) {
    $pw= $_POST["pw"];
    $mail = $_POST["mail"];
    //$sql = "SELECT pw, u_id, name, active, type, affiliation FROM `users` WHERE mail='$mail';";
    $stmt = $conn->prepare("SELECT pw, u_id, name, active, type, affiliation FROM `users` WHERE mail=?;");
    $stmt->bind_param("s", $mail);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hash = $row["pw"];
            $uid = $row["u_id"];
            $name = $row["name"];
            $type =$row["type"];
            $affiliation = $row["affiliation"];
            $active = $row["active"];
        }
    } else{
        $unknownUser = TRUE;
        $active ="no";
    }

    if($active=='yes'){
        $activated = TRUE;
    }else{
        $activated = FALSE;
    }

    if(!$unknownUser && $activated){
        if (password_verify($pw, $hash)) {
            login($conn,$uid,$name,$type,$rememberme);
        } elseif (!$activated) {
            $activated = FALSE;
        }else{
            $invalidPassword = TRUE;
        }
    }
}

function login($conn,$uid,$name,$type,$remember){
  session_name('SESSID');
  session_set_cookie_params(0, '/', '.filmstunden.ch',TRUE,TRUE);
  session_start();
  if($remember){
    $sql = "DELETE FROM tokens WHERE user_id='$uid'";
    $conn->query($sql);
    $rand1 = bin2hex(random_bytes(4));
    $rand2 = bin2hex(random_bytes(30));
    $value=$rand1.':'.$rand2;
    setcookie("REMID", $value, time()+60*60*24*7*2, '/', '.filmstunden.ch',TRUE,TRUE);
    //TODO delete token on Logout
    $sql = "INSERT INTO tokens (type, token_id, user_id, token, timeout)
                       VALUES ('rem', '$rand1', '$uid', '$rand2', NOW() + INTERVAL 2 WEEK);";
    $result = $conn->query($sql);
  }
  if (session_status() === PHP_SESSION_ACTIVE){
      $_SESSION['running'] = 1;
      $_SESSION['user'] = $uid;
      $_SESSION['name'] = $name;
      $_SESSION['type'] = $type;
      header( 'Location: ./home.php') ;
  }else{
    die("Session not running");
  }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filmabrechnungsgenerator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="./css/main.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
            <b> Login</b>
            <div class="pull-right">
              <div class="btn-group">
                <a href="./new_account.php" class="btn btn-outline btn-success btn-xs">
                <i class="fa fa-plus fa-fw"></i> Neues Konto</a>
              </div>
            </div>
          </div><!--heading-->
          <div class="panel-body">
          
            <? if ($unknownUser || $invalidPassword) {?>
              <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Benutzername und/oder Passwort stimmen nicht &uuml;berein.
              </div>
              
            <?}elseif (!$activated) {?>
              <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Die E-Mail Addresse wurde noch nicht best&auml;tigt.
              </div>
            <?}?>
            
            <? if ($closedExisting) {?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Erfolgreich ausgeloggt
              </div>
            <?}?>
              <div class="alert alert-danger alert-dismissable" id="ES6" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                Browser unterst&uuml;tzt ES6 nur eingeschr&auml;nkt!</br>Filmstunden.ch wird nicht einwandfrei funktionieren.
              </div>
            
            <form role="form" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" name="mail" type="email" autofocus required>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" name="pw" type="password" value="" required>
                </div>
<div class="checkbox">
  <label>
   <input name="remember" type="checkbox" value="remember">
   <span class="cr"><i class="cr-icon fa fa-check"></i></span>
   Remember me
   </label>
</div>
                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
              </fieldset>
            </form>
          </div><!--body-->
        </div>
        <p align="right"><font size="-1" color="#888888"><? echo $GLOBALS['version']; ?></font></p>
    </div><!--md-4-->
  </div><!--row-->
</div><!--container-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script nomodule src="./js/checkES6.js"></script>
</body>
</html>
