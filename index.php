<?
include './includes/inc_sessionhandler_default.php';
include './includes/inc_variables.php';
if ($u_type=='producer') {header( 'Location: ./p_index.php') ;}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <!-- MetisMenu CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css"/>
    <!-- Morris Charts CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/morris.js/0.5.1/morris.css"/>
    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
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
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Einnahmen
                    <div class="pull-right">
                        <div class="btn-group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="#">Action</a>
                                </li>
                                <li><a href="#">Another action</a>
                                </li>
                                <li><a href="#">Something else here</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="#">eop</a>
                                </li>
                                <li><a href="#">Aewf</a>
                                </li>
                                <li><a href="#">Something else here</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                </div><!-- /.panel-heading -->
                <div class="panel-body">
                    <div id="stats"></div>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.col-lg-8 -->



        <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="activeProjects"></div>
                                    <div>Aktive Projekte</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--col lg4-->
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="huge">&oslash; Monat:</div>
                        </div>
                        <div class="col-xs-4 text-right">
                            <div class="huge" id="monthlyMean"></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.col-lg-4 -->

    </div><!-- /.row -->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<!-- Morris Charts JavaScript -->
<script src="https://cdn.jsdelivr.net/morris.js/0.5.1/morris.min.js"></script>
<!-- Raphael JavaScript -->
<script src="https://cdn.jsdelivr.net/raphael/2.2.7/raphael.js"></script>

<script src="./js/sidemenu.js"></script>
<script src="./js/index.js"></script>

</body>
</html>
