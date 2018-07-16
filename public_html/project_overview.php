<?//----------------------------------
require_once('./includes/inc_sessionhandler_default.php');
require_once('../api-app/lib/Globals.php');
//------------------------------------?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Projektabrechnung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.css"/>
    <link href="./css/main.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div id="wrapper">

  <?//----------------------------------
  include_once('./includes/inc_top.php');
  //------------------------------------?>

<div id="page-wrapper">
    <p></br></p>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-table fa-fw"></i> Projekte
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btn-xs"  data-toggle="modal" data-target="#newProjectModal">
                                <span class="glyphicon glyphicon-plus"></span>Neues Projekt
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
                                    <th>Produktionsfirma</th>
                                    <th>Stunden</th>
                                    <th>Einnahmen</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div><!-- /.panel-body -->
            </div><!-- Panel -->

            <div class="modal fade" id="newProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Neues Projekt</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" action="https://filmstunden.ch/api/v01/project/new" method="post" id="newProject">
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Produktionsfirma</span>
                                    <select class="form-control"  name="company" id="companylist" required>
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#newCompany"><i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Projektname</span>
                                    <input type="text" name="name" class="form-control" placeholder="Unbekanntes Projekt" required="">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Startdatum</span>
                                    <input type="date" id="newDate" name="date" class="form-control" required>
                                </div>
                                <datalist id="joblist"></datalist>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Arbeit als:</span>
                                    <input type="text" list="joblist" class="form-control" name="work" id="work" required>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Tagesgage</span>
                                    <input type="number" name="pay" class="form-control" placeholder="500" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                                <button type="submit" class="btn btn-primary" onclick="">Neues Projekt erstellen</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" id="newCompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Neue Produktionfirma hinzufügen</h4>
                        </div>
                        <div class="modal-body" id="newCompanyCreated">
                            <form role="form" action="https://filmstunden.ch/api/v01/company/new" method="post" id="newProdcomp">
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Firmenname</span>
                                    <input type="text" name="name" class="form-control" placeholder="Prodfirma" required="">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Addresse</span>
                                    <input type="text" name="address1" class="form-control" placeholder="Rosengartenstrasse 21" required>
                                    <input type="text" name="address2" class="form-control" placeholder="8050 Zürich" required>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Telefon</span>
                                    <input type="text" name="phone" class="form-control" placeholder="500" required>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">E-Mail</span>
                                    <input type="text" name="mail" class="form-control" placeholder="500" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                                <button type="submit" class="btn btn-primary" onclick="">Produktionsfirma hinzufügen</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="delModalTitle">Projekt <strong id="toDelName"></strong> wirklich L&ouml;schen ?</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">Achtung, dadurch wird das Projekt <strong id="toDelName"></strong> endg&uuml;ltig gel&ouml;scht</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                                <button type="button" class="btn btn-danger" id="delete-btn">L&ouml;schen</button>
                            </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" id="finishProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="finModalTitle"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">Achtung, dadurch wird das Projekt <strong id="toFinName"></strong> abgeschlossen und kann danach nicht mehr bearbeitet werden</div>
                            <form role="form" action="" method="post" id="finishProject">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                                <button type="submit" class="btn btn-success">Abschliessen</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!--col-lg-12-->
    </div><!--row-->
</div> <!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>-->
<script type="module" src="./js/project_overview.js"></script>
</body>
</html>
