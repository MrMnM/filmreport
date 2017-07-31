<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_sessionhandler_default.php';
include './includes/inc_dbconnect.php';
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <!-- DataTables Responsive CSS -->
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css"/>-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.css"/>


    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
</head>

<body>
<div id="wrapper">
<? include('./includes/inc_top.php');?>
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
                    <div class="dataTables_wrapper dt-bootstrap">
                        <table class="table table-striped dt-responsive nowrap" id="projectTable" cellspacing="0" style="width:98% !important; table-layout:fixed">
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
                    </div><!--table-responsive-->
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
                            <form role="form" action="h_project.php" method="post" id="newProject">
                                <input type="hidden" name="action" value="new">
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
                                    <input type="date" name="date" class="form-control" placeholder="500" required>
                                </div>
                                <datalist id="jobs">
                                <?include('./includes/joblist.html');?>
                                </datalist>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Arbeit als:</span>
                                    <input type="text" list="jobs" class="form-control" name="work" required>
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
                            <form role="form" action="h_new_prodcomp.php" method="post" id="newProdcomp">
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
                            <h4 class="modal-title" id="myModalLabel">Projekt wirklich L&ouml;schen ?</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form" action="h_project.php" method="post" id="deleteProject">
                                <input type="hidden" name="action" value="delete">
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Projekt ID:</span>
                                    <input type="text" class="form-control" name="id" id="toDelID">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Projekt Name:</span>
                                    <input type="text" class="form-control" id="toDelName" disabled>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                                <button type="submit" class="btn btn-danger">L&ouml;schen</button>
                            </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div><!--col-lg-12-->
    </div><!--row-->
</div> <!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js" integrity="sha256-OrCnS705nv33ycm/+2ifCnVfxxMdWvBMg5PUX1Fjpps=" crossorigin="anonymous"></script>
<!-- DataTables JavaScript -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.js"></script>
<!-- JqueryForms -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<!-- Custom Theme JavaScript -->
<script src="./js/sb-admin-2.js"></script>
<script src="./js/project_overview.js"></script>
<!-- onload -->
<script>
var table=null;
$(document).ready(function() {
    table = $('#projectTable').DataTable({
        "ajax": 'h_listprojects.php',
        "pagingType": "numbers",
        "lengthChange": false,
        "columnDefs": [ {
            "targets": 5,
            "data": 5,
            "searchable": false,
            "sortable":false,
            "render": function ( data, type, row) {
                    return '<button type="button" class="btn btn-default btn-circle"  onclick="window.open(\'view.php?id='+data+'\')"><i class="fa fa-eye"></i></button>\
                    <button type="button" class="btn btn-default btn-circle" onclick="window.location.href=\'project.php?id='+data+'\'"><i class="fa fa-pencil"></i></button>\
                    <button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProjectModal" onclick="setDelete(\''+data+'\',\''+row[1]+'\')"><i class="fa fa-times"></i></button>';
                    }
            } ]
    });

    $('#newProject').ajaxForm({
            dataType:  'json',
            success:  newCreated
        });
    $('#deleteProject').ajaxForm({
            dataType:  'json',
            success:   projDeleted
        });
    $('#newProdcomp').ajaxForm({
        dataType:  'json',
        success:   companyCreated
    });
    //TODO cache etc
    $('#companylist').html('').load("./h_load_companies.php");
});
</script>
</body>
</html>
