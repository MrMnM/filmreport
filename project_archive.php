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
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/datatables.min.css"/>


    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
                    <i class="fa fa-archive fa-fw"></i> Projektarchiv

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

            <div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="delModalTitle">Projekt <strong id="toDelName"></strong> wirklich L&ouml;schen ?</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">Achtung, dadurch wird das Projekt <strong id="toDelName"></strong> endg&uuml;ltig gel&ouml;scht</div>
                            <form role="form" action="h_project.php" method="post" id="deleteProject">
                                <input type="hidden" name="action" value="delete">
                                    <input type="hidden" class="form-control" name="p_id" id="toDelID">
                                    <input type="hidden" class="form-control" id="toDelName" disabled>
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
<!-- JqueryForms -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<!-- Custom Theme JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js" integrity="sha256-vIL0pZJsOKSz76KKVCyLxzkOT00vXs+Qz4fYRVMoDhw=" crossorigin="anonymous"></script>
<script src="./js/sb-admin-2.js"></script>
<script src="./js/project_overview.js"></script>
<!-- onload -->
<script>
var table=null;
$(document).ready(function() {
    table = $('#projectTable').DataTable({
        "ajax": 'h_listprojects.php?fin=1',
        "pagingType": "numbers",
        "order": [[ 0, "desc" ]],
        "autoWidth": false,
"columns" : [
    { width : '5em' },
    { width : '12em' },
    { width : '50px' },
    { width : '20px' },
    { width : '20px' },
    { width : '30px' }
],
        "columnDefs": [ {
            "targets": 1,
            "render": function ( data, type, row) {
                    return '<a href="view.php?id='+row[5]+'" target="_blank"><b>'+row[1]+'</b></a>';
                    }
        },{
            "targets": 5,
            "data": 5,
            "searchable": false,
            "sortable":false,
            "render": function ( data, type, row) {
                    return '<button type="button" class="btn btn-default btn-circle"  onclick="window.open(\'view.php?id='+data+'\')"><i class="fa fa-eye"></i></button>\
                    <div class="btn-group"><button type="button" class="btn btn-default btn-circle dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-download">\
                    </i></button><ul class="dropdown-menu pull-right" role="menu"><li><a href="h_download.php?t=xlsx&id='+data+'"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a></li><li><a href="h_download.php?t=pdf&id='+data+'"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a></li></ul></div>\
                    <button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProjectModal" onclick="setDelete(\''+data+'\',\''+row[1]+'\')"><i class="fa fa-times"></i></button>';
                    }
            } ],
            responsive: {
    details: {
        display: $.fn.dataTable.Responsive.display.childRowImmediate,
        type: ''
    }
}
    });

    new $.fn.dataTable.Responsive( table );

    $('#newProject').ajaxForm({
            dataType:  'json',
            success:  newCreated
        });
    $('#finishProject').ajaxForm({
            dataType:  'json',
            success:  projFinished
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
