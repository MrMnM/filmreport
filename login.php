<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_dbconnect.php';
include './includes/inc_variables.php';

$unknownUser = FALSE;
$invalidPassword = FALSE;
$closedExisting = FALSE;
$activated =TRUE;

if (true){
    session_start();
    if (!empty($_SESSION['running']) && $_SESSION['running'] == 1){
        $closedExisting = TRUE;
    }
    $_SESSION['running'] = null;
    $_SESSION['user'] = null;
    //echo 'session destroyed';
}

if (!empty($_POST["pw"]) && !empty($_POST["mail"])) {
    $pw= $_POST["pw"];
    $mail = $_POST["mail"];
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT pw, u_id, name, active, type, affiliation FROM `users` WHERE mail='$mail';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $hash = $row["pw"];
            $uid = $row["u_id"];
            $name = $row["name"];
            $type =$row["type"];
            $affiliation = $row["affiliation"];
            $active = $row["active"];
        }
    }    else{
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
            if (session_status() === PHP_SESSION_ACTIVE){
                $_SESSION['running'] = 1;
                $_SESSION['user'] = $uid;
                $_SESSION['name'] = $name;
                $_SESSION['type'] = $type;
                //echo session_status();
                if ($type=='producer') {
                    header( 'Location: ./p_index.php') ;
                }else{
                header( 'Location: ./index.php') ;
            }
            }

        } elseif (!$activated) {
            $activated = FALSE;
        }else{
            $invalidPassword = TRUE;
        }
    }
}
?>

<html>
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
                            <a href=".\new_account.php" class="btn btn-outline btn-success btn-xs">
                                <i class="fa fa-plus fa-fw"></i> Neues Konto
                            </a>
                        </div>
                    </div>
                </div>
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
            <form role="form" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" placeholder="E-mail" name="mail" type="email" autofocus required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Password" name="pw" type="password" value="" required>
                    </div>
<!-- TODO implement remember me-->
                        <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                    </fieldset>
                </form>
            </div>
        </div>
        <p align="right"><font size="-1" color="#888888"><? echo $VERSION; ?></font></p>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
</body>
</html>
