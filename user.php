<?
include './includes/inc_sessionhandler_default.php';
include './includes/inc_variables.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Projektabrechnung</title>
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
</head>

<body>
<div id="wrapper">
<?
if ($u_type == 'producer') {
include('./includes/inc_top_producer.php');
}else{
include('./includes/inc_top_freelancer.php');
}
?>
<div id="page-wrapper">
    <p></br></p>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-user fa-fw"></i> Persönliche Informationen
                </div><!--panel heading-->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr class="name">
                                                <td width="150px"><strong>Name:</strong></td>
                                                <td class="name"></td>
                                            </tr>
                                            <tr class="address">
                                                <td><strong>Addresse:</strong></td>
                                                <td class="address"></td>
                                            </tr>
                                            <tr class="tel">
                                                <td><strong>Telefon:</strong></td>
                                                <td class="tel"></td>
                                            </tr>
                                            <tr class="ahv">
                                                <td><strong>AHV#:</strong></td>
                                                <td class="ahv"></td>
                                            </tr>
                                            <tr class="dob">
                                                <td><strong>Geburtsdatum:</strong></td>
                                                <td class=dob></td>
                                            </tr>
                                            <tr class="konto">
                                                <td><strong>Konto:</strong></td>
                                                <td class="konto"></td>
                                            </tr>
                                            <tr class="bvg">
                                                <td><strong>BVG:</strong></td>
                                                <td class="bvg"></td>
                                            </tr>
                                        </tbody>
                                    </table><!--tableresponsive-->
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button class="btn btn-default" id="saveInfo">Speichern</button>
                                    </div>
                                </div>
                        </div><!--col lg12-->
                    </div><!--row-->
                </div><!--panelbody-->
            </div><!--panel-->
        </div><!--col6--->
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-lock fa-fw"></i> Passwort
                </div><!--panel heading-->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr class="mail">
                                                <td width="150px"><strong>E-Mail:</strong></td>
                                                <td class="mail"></td>
                                            </tr>
                                            <tr class="mail">
                                                <td width="150px"><strong>Aktuelles Passwort:</strong></td>
                                                <td class="curpw"></td>
                                            </tr>
                                            <tr class="mail">
                                                <td width="150px"><strong>Neues Passwort:</strong></td>
                                                <td class="newpw"></td>
                                            </tr>
                                            <tr class="mail">
                                                <td width="150px"><strong>Wiederholen:</strong></td>
                                                <td class="newpw2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-default disabled">Speichern</button>
                                    </div>
                                </div>
                            </form>
                        </div><!--col12-->
                    </div><!--row-->
                </div><!--panelbody-->
            </div><!--panel-->
        </div><!--col6-->
    </div><!--row-->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js" integrity="sha256-OrCnS705nv33ycm/+2ifCnVfxxMdWvBMg5PUX1Fjpps=" crossorigin="anonymous"></script>
<!-- Custom Theme JavaScript -->
<script src="./js/sidemenu.js"></script>
<!-- Custom Functions JavaScript -->
<script>var us_id = "<? echo $u_id;?>";</script>
<script src="./js/user.js"></script>
</body>
</html>
