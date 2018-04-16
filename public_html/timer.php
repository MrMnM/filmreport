<?

//TODO Macheen das es ohne login klappt
include './includes/inc_sessionhandler_default.php';
require_once('../api-app/lib/Globals.php');
$db=$GLOBALS['db'];
$servername = "localhost";
$dbname = $db['database_name'];
$username = $db['username'];
$password = $db['password'];
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
    include('./includes/inc_top.php');

    ?>
<div id="page-wrapper">
    <p></br></p>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default" id="activetimer" style="display:none;">
                    <div class="panel-heading" id="projectTitle">
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-play"></i>
                                            <!--<span class="caret"></span>-->
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" id="shoot"><i class="fa fa-video-camera"></i> Dreh</a>
                                            </li>
                                            <li><a href="#" id="load"><i class="fa fa-truck"></i> Laden</a>
                                            </li>
                                            <li><a href="#" id="drive"><i class="fa fa-car"></i> Reise</a>
                                            </li>
                                        </ul>
                                    </div>
                                        <button type="button" class="btn btn-warning btn-lg" id="pause"><i class="fa fa-pause"></i></button>
                                        <button type="button" class="btn btn-danger btn-lg" id="stop"><i class="fa fa-stop"></i></button>
                                    </div>

                                    <div class="col-sm-8 huge" id="timerCount">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                    <div class="panel panel-default" id="selector">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Timers
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group" id="timers">
                                <a href="#" class="list-group-item success"><div class="loading-spinner-center"></div>&nbsp;</a>
                            </div>
                            <button class="btn btn-default btn-block" data-toggle="modal" data-target="#newTimerModal"><i class="fa fa-plus fa-fw"></i> Neuen Timer hinzufügen</button>
                            <!-- /.list-group -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <!-- Modal -->
        <div class="modal fade" id="newTimerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Neuen Timer erstellen</h4>
                    </div>
                    <div class="modal-body modalContent" name="modalContent" id="modalContent">
                        <form role="form" action="h_timer.php" method="post" id="newTimerForm">
                            <input type="hidden" name="action" value="new">
                            <div class="form-group input-group">
                                <span class="input-group-addon">Name</span>
                                <input type="text" name="name" class="form-control" placeholder="Testtimer" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                            <button type="submit" class="btn btn-primary" onclick="" id="submitbutton" name="submitbutton">Erstellen</button>
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
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script type="module" src="./js/timer.js"></script>

    </body>
    </html>
