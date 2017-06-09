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
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="./vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
                                <button type="button" class="btn btn-outline btn-success btn-xs dropdown-toggle" data-toggle="modal" data-target="#newAccount">
                                    <i class="fa fa-plus fa-fw"></i> Neues Konto
                                </button>
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
                                            <!--
                                            <div class="checkbox">
                                            <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                        </label>
                                    </div>
                                -->
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <p align="right"><font size="-1" color="#888888"><? echo $VERSION; ?></font></p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="newAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Neues Konto erstellen</h4>
                    </div>
                    <div class="modal-body modalContent" name="modalContent" id="modalContent">
                        <p>Um ein Konto zu erstellen, m&uuml;ssen Sie eine g&uuml;ltige E-Mail Addresse angeben, mit der Sie das Konto aktivieren können.</p>
                        <p>Danach m&uuml;ssen Sie Ihr Konto freischalten und weitere Pers&ouml;nliche Infos eingeben, um die Abrechnungen zu erstellen.</p>
                        <p></p>
                        <form role="form" action="h_new_account.php" method="post" id="newAccountForm">
                            <div class="form-group input-group">
                                <span class="input-group-addon">E-Mail</span>
                                <input type="email" name="mail" class="form-control" placeholder="mail@mail.com" required>
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">Passwort</span>
                                <input type="password" name="pw" class="form-control" placeholder="Passwort eingeben" required>
                                <input type="password" name="pw2" class="form-control" placeholder="Passwort wiederholen" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                            <button type="submit" class="btn btn-primary" onclick="" id="submitbutton" name="submitbutton">Neues Konto erstellen</button>
                            <button type="submit" class="btn btn-primary" data-dismiss="modal" id="closebutton" name="closebutton" style="display:none">OK</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>

    <!-- jQuery -->
    <script src="./vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="./vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="./dist/js/sb-admin-2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
    <script>

    function newCreated(data) {
        if (data.message=="SUCCESS") {
            $('#modalContent').html('<div class="alert alert-success">Account wurde erstellt. Bitte den Link im Best&auml;tigungsemail klicken um die Registrierung abzuschliessen.</div>');
            $('#submitbutton').hide();
            $('#closebutton').show();
        }else{
            alert(data.message);
        }
    }

    $(document).ready(function() {
        $('#newAccountForm').ajaxForm({
            dataType:  'json',
            success:  newCreated
        });
    });
    </script>

</body>
</html>
