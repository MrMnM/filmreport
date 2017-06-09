<?
include './includes/inc_encrypt.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_dbconnect.php';


if (   !empty($_POST["name"])
    && !empty($_POST["address1"])
    && !empty($_POST["address2"])
    && !empty($_POST["ahv"])
    && !empty($_POST["dateob"])
    && !empty($_POST["konto"])
    && !empty($_POST["bvg"])
    && !empty($_POST["id"])
    && !empty($_POST["tel"])
    ) {
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$u_id = mysqli_real_escape_string($conn, $_POST["id"]);
$tel= mysqli_real_escape_string($conn, $_POST["tel"]);
$name= mysqli_real_escape_string($conn, $_POST["name"]);
$address1=mysqli_real_escape_string($conn,$_POST["address1"]);
$address2=mysqli_real_escape_string($conn,$_POST["address2"]);
$ahv= encrypt($_POST["ahv"],'e');
$dateob=mysqli_real_escape_string($conn,$_POST["dateob"]);
$konto= encrypt($_POST["konto"],'e');
$bvg=mysqli_real_escape_string($conn,$_POST["bvg"]);

$sql = "UPDATE users SET tel = '$tel', name='$name',address_1='$address1', address_2='$address2', ahv='$ahv', dateob='$dateob' , bvg='$bvg' , konto='$konto' WHERE u_id = '$u_id'";

if ($conn->query($sql) === TRUE) {
    session_start();
    if (session_status() === PHP_SESSION_ACTIVE){
        $_SESSION['running'] = 1;
        $_SESSION['user'] = $u_id;
        $_SESSION['name'] = $name;
        //echo session_status();
        echo ' <script type="text/javascript" language="JavaScript">window.location.href="./index.php";</script>';
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

}elseif(!empty($_GET["id"])){
    $u_id = $_GET["id"];
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Projektabrechnung</title>

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
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Abrechnungsgenerator</a>
            </div>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">
            <p></br></p>
            <div id="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Willkommen beim Filmabrechnungsgenerator
                        </div>
                        <form method="post" action="./firstlaunch.php" >
                            <div class="panel-body">
                                <p>Damit Sie sinnvoll Abrehcnungen erstellen k&ouml;nnen müssen Sie Ihre persönlichen Daten eingeben, die dann jeweils auf der Abrechnung erscheinen.</p>
                                    <input type="hidden" name="id" value="<?echo $u_id;?>">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Name</span>
                                        <input type="text" name="name" class="form-control" placeholder="Vorname Nachname" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Addresse</span>
                                        <input type="text" name="address1" class="form-control" placeholder="Rosengartenstrasse 5" required>
                                        <input type="text" name="address2" class="form-control" placeholder="8000 Zürich" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Telefon</span>
                                        <input type="text" name="tel" class="form-control" placeholder="+41(0)410000000" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">AHV#</span>
                                        <input type="text" name="ahv" class="form-control" placeholder="756.12334.5678.97" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Geburtsdatum</span>
                                        <input type="date" name="dateob" class="form-control" placeholder="1980-05-08" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">Konto#</span>
                                        <input type="text" name="konto" class="form-control" placeholder="CH93 0076 2011 6238 5295 7" required>
                                    </div>
                                    <div class="form-group input-group">
                                        <span class="input-group-addon">BVG</span>
                                        <input type="text" name="bvg" class="form-control" placeholder="VFA" required>
                                    </div>
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-success">Speichern</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div id="row">
                </div>
                <!-- /#page-wrapper -->
            </div>
        </div>
        <!-- /#wrapper -->
    </body>

        <!-- jQuery -->
        <script src="./vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="./vendor/metisMenu/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="./js/sb-admin-2.js"></script>

    </body>
    </html>
    <?
}else{
    die('FEHLER');
}?>
