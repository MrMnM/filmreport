<?//----------------------------------
require_once('./includes/inc_sessionhandler_default.php');
require_once('../api-app/lib/Globals.php');
//------------------------------------?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Projektabrechnung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/morris.js/0.5.1/morris.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
<div id="wrapper">

<?//----------------------------------
include_once('./includes/inc_top.php');
//------------------------------------?>

<div id="page-wrapper">
<p></br></p>
  <div class="row freelance" style="display:none;">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-line-chart fa-fw"></i> Einnahmen
                <div class="pull-right">
                </div>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
                <div id="stats">
                </div>
                <div class="input-group input-daterange">
                    <input width=50px type="text" class="form-control input-s" id="fromDate" value="">
                    <div class="input-group-addon input-s">bis</div>
                    <input type="text" class="form-control input-s" id="toDate" value="">
                </div>
                <hr>
                <div id="yearView">
                </div>
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-8 -->

    <div class="col-lg-4 col-md-6">
    <!-- ACTIVE PROJECTS --->
      <div class="panel panel-green">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-tasks fa-5x"></i>
            </div><!--col-xs-3-->
            <div class="col-xs-9 text-right">
              <div class="huge" id="activeProjects"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
              <div>Aktive Projekte</div>
            </div><!--text-right-->
          </div><!--row-->
        </div><!--panel-heading-->
      </div><!--panel-->
    <!-- MONTHLY MEAN-->
      <div class="panel panel-primary">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-8">
              <div class="huge">&oslash; Monat:</div>
              <div id="dateRange"></div>
            </div><!--col-xs-8-->
            <div class="col-xs-4 text-right">
              <div class="huge" id="monthlyMean"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
            </div><!--col-xs-4-->
          </div><!--row-->
        </div><!--panel-heading-->
      </div><!--panel-->
    </div><!--collg4-->
    <div class="col-lg-4 col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-bar-chart-o fa-fw"></i> Einnahmen pro Firma
        </div>
        <div class="panel-body">
          <div id="donut"></div>
        </div><!-- /.panel-body -->
      </div><!-- /.panel -->
    </div><!-- /.col-lg-4 -->

  </div><!-- /.row freelance-->

  <div class="row producer" style="display:none;">
    <div class="col-lg-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-bar-chart-o fa-fw"></i> Aktuell Laufende Projekte
        </div><!-- /.panel-heading -->
        <div class="panel-body">
          <div class="list-group">
            <a href="#" class="list-group-item">
              <i class="fa-li fa fa-spinner fa-spin"></i> New Comment
              <span class="pull-right text-muted small"><em>4 minutes ago</em></span>
            </a>
          </div><!--listgroup-->
          <a href="#" class="btn btn-default btn-block">Neues Projekt erstellen</a>
        </div><!-- /.panel-body -->
      </div><!-- /.panel -->
    </div><!-- /.col-lg-8 -->
  </div><!-- /.row producer-->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdn.jsdelivr.net/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdn.jsdelivr.net/raphael/2.2.7/raphael.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<script type="module" src="./js/home.js"></script>

</body>
</html>
