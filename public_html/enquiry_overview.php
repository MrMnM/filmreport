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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.css"/>
    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar-plus-o fa-fw"></i> Drehanfragen
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" id="newEnquiry" class="btn btn-success btn-xs">
                                <span class="glyphicon glyphicon-plus"></span>Neue Anfrage
                            </button>
                        </div>
                    </div>
                </div><!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="container-fluid">
                        <table class="table table-striped table-condensed nowrap" id="projectTable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Projektname</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div><!-- /.panel-body -->
            </div><!-- Panel -->

    </div><!--col-lg-12-->
</div><!--row-->
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script type="module" src="./js/enquiry_overview.js"></script>

    </body>
    </html>
