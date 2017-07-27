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
    $sql = "SELECT pw, u_id, name, active FROM `users` WHERE mail='$mail';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $hash = $row["pw"];
            $uid = $row["u_id"];
            $name = $row["name"];
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
                //echo session_status();
                echo ' <script type="text/javascript" language="JavaScript">window.location.href="./index.php";</script>';
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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Filmabrechnungsgenerator</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous" />
    <!-- MetisMenu CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css" integrity="sha256-4NxXT7KyZtupE4YdYLDGnR5B8P0JWjNBpF8mQBzYtrM=" crossorigin="anonymous">
    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--
</div>
-->
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <!--<i class="fa fa-briefcase fa-fw"></i>--><b> Login</b>
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
                                        <!-- TODO reimplement
                                        <div class="checkbox">
                                        <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                            -->
                            <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <p align="right"><font size="-1" color="#888888"><? echo $VERSION; ?></font></p>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
<!-- Metis Menu Plugin JavaScript -->
<!--<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js" integrity="sha256-OrCnS705nv33ycm/+2ifCnVfxxMdWvBMg5PUX1Fjpps=" crossorigin="anonymous"></script>-->
<!-- Custom Theme JavaScript -->
<!--<script src="./js/sb-admin-2.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
</body>
</html>
