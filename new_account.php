<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_dbconnect.php';
include './includes/inc_variables.php';

if(!empty($_GET["v"])){
    $val = mysqli_real_escape_string($conn, $_GET["v"]);
    $sql = "SELECT active, u_id FROM `users` WHERE active='$val';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $u_id = $row["u_id"];
        }
        $sql = "UPDATE users SET active = 'yes' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === TRUE) {
            die('<script type="text/javascript" language="JavaScript">window.location.href="./login.php";</script>');
        } else {
            die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
        }
    }else{
        die('{ "message": "Ung&uuml;ltiger Validierungsschl&uuml;ssel" }');
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
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css" integrity="sha256-4NxXT7KyZtupE4YdYLDGnR5B8P0JWjNBpF8mQBzYtrM=" crossorigin="anonymous">-->
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
                <i class="fa fa-user fa-fw"></i><b> Neues Konto erstellen</b>
            </div>
            <div class="panel-body">
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Benutzername und/oder Passwort stimmen nicht &uuml;berein.
                </div>
                <form role="form" method="post" action="h_user.php" id="createAccount">
                    <input type="hidden" name="action" value="new">
                    <fieldset>
                        <div class="form-group">
                            <label>Name</label>
                            <div class="pull-right">
                                <button type="button" class="btn btn-outline btn-link" data-container="body" data-toggle="popover" data-placement="left" data-content="Bitte hier den vollen Namen angeben, da das nachher auch der Name ist, auf den die Abrechnungen generiert werden." data-original-title="" title="" aria-describedby="popover1">
                                    <i class="fa fa-question fa-fw"></i>
                                </button>
                            </div>
                            <input class="form-control" name="name" placeholder="Vorname Nachname"required>
                        </div>
                        <div class="form-group">
                            <label>E-Mail</label>
                            <div class="pull-right">
                                <button type="button" class="btn btn-outline btn-link" data-container="body" data-toggle="popover" data-placement="left" data-content="Es muss eine gültige E-Mail Addresse angegeben werden um das Konto erstellen zu können" data-original-title="" title="" aria-describedby="popover2">
                                    <i class="fa fa-question fa-fw"></i>
                                </button>
                            </div>
                            <input class="form-control" name="mail" placeholder="info@email.ch" required>
                        </div>
                        <div class="form-group">
                            <label>Passwort</label>
                            <input class="form-control" type="password" name="pw" placeholder="&#0149&#0149&#0149&#0149&#0149&#0149" required>
                        </div>
                        <div class="form-group">
                            <label>Passwort wiederholen</label>
                            <input class="form-control" type="password" name="pw2" placeholder="&#0149&#0149&#0149&#0149&#0149&#0149" required>
                        </div>
                        <button type="submit" class="btn btn-lg btn-success btn-block">Erstellen</button>
                    </fieldset>
                </form>
            </div>
        </div>
        <p align="right"><font size="-1" color="#888888"><? echo $VERSION; ?></font></p>
    </div>
</div>
</div>
</body>
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
<!-- Metis Menu Plugin JavaScript -->
<!--<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js" integrity="sha256-OrCnS705nv33ycm/+2ifCnVfxxMdWvBMg5PUX1Fjpps=" crossorigin="anonymous"></script>-->
<!-- Custom Theme JavaScript -->
<!--<script src="./js/sb-admin-2.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
$('#createAccount').ajaxForm({
        dataType:  'json',
        success: updateSuccess
    });
});

function updateSuccess(data){
    if (data.message=="SUCCESS:") {
        //TODO HIDE INFO AND SHOW CREATED
    }else{
        //WORK ON ERROR
    }
}
</script>

</html>
