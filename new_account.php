<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

include './includes/inc_dbconnect.php';
include './includes/inc_variables.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die('{ "message": "ERROR: '.$conn->connect_error.'"}');
}

$validated = false;

if (!empty($_GET["v"])) {
    $val = mysqli_real_escape_string($conn, $_GET["v"]);
    $sql = "SELECT active, u_id FROM `users` WHERE active='$val';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $u_id = $row["u_id"];
        }
        $sql = "UPDATE users SET active = 'yes' WHERE u_id = '$u_id'";
        if ($conn->query($sql) === true) {
            $validated = true;
        } else {
            die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
        }
    } else {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
    <link href="./../css/main.css" rel="stylesheet">
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

<?php if ($validated==true) {?>
    <div id="success" class="alert alert-success">
        <p>Account wurde erfolreich Validiert</p>
        <a href=".\..\login.php">ZUM LOGIN</a>
    </div>
<?} else {?>
                <div id="error" class="alert alert-danger alert-dismissable" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p id="errorcontent">Benutzername und/oder Passwort stimmen nicht &uuml;berein.</p>
                </div>
                <div id="success" class="alert alert-success" style="display: none;">
                    <p>Account wurde erfolreich erstellt, eine E-Mail wurde an die angegebene Addresse versendet. Es kann einige Minuten dauern, bis das Mail tatsächlich ankommt, bitte etwas Geduld</p>
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
                            <input class="form-control" type="password" name="pw" placeholder="&#0149;&#0149;&#0149;&#0149;&#0149;&#0149;" required>
                        </div>
                        <div class="form-group">
                            <label>Passwort wiederholen</label>
                            <input class="form-control" type="password" name="pw2" placeholder="&#0149;&#0149;&#0149;&#0149;&#0149;&#0149;" required>
                        </div>
                        <button type="submit" class="btn btn-lg btn-success btn-block">Erstellen</button>
                    </fieldset>
                </form>
                <?}?>
            </div>
        </div>
        <p align="right"><font size="-1" color="#888888"><?php echo $VERSION; ?></font></p>
    </div>
</div>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script>
$(document).ready(function() {
$('#createAccount').ajaxForm({
        dataType:  'json',
        success: updateSuccess
    });
});

$("[data-toggle=popover]").popover()

function updateSuccess(data){
    if (data.message=="SUCCESS") {
        $('#success').show()
        $('#createAccount').hide()
        $('#error').hide()
    }else{
        $('#error').show()
        $('#success').hide()
        $('#error').find("p").html(data.message)
    }
}
</script>
</html>
