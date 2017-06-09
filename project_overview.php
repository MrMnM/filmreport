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
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="./vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="./vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="./vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="./vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
                                    <span class="glyphicon glyphicon-plus"></span>
                                    Neues Projekt
                                </button>
                            </div>
                        </div>
                    </div>

                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class=" table responsive no-wrap" id="projectTable"> <!--//TODO MAKE AJAX as well-->
                                <thead>
                                    <tr>
                                        <th data-priority="2">Datum</th>
                                        <th data-priority="1">Projektname</th>
                                        <th data-priority="3">Produktionsfirma</th>
                                        <th data-priority="6">Stunden</th>
                                        <th data-priority="5">Einnahmen</th>
                                        <th data-priority="6"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    // Check connection
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    $sql = "SELECT company_id, name FROM `companies`";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        $cnt = 0;
                                        while($cmp = $result->fetch_assoc()) {
                                            $companies[$cnt][0] = $cmp["company_id"];
                                            $companies[$cnt][1] = $cmp["name"];
                                            $cnt=$cnt+1;
                                        }
                                    }

                                    // Check connection
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    $sql = "SELECT project_id, p_start, p_name, p_company, tot_hours, tot_money FROM `projects` WHERE user_id='$u_id';";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>'.$row["p_start"].'</td>';
                                            echo '<td>'.$row["p_name"].'</td>';
                                            foreach($companies as $arr){
                                                if ($arr[0] == $row["p_company"]) {
                                                    echo '<td>'.$arr[1].'</td>';
                                                }
                                            }
                                            echo '<td>'.$row["tot_hours"].'</td>';
                                            echo '<td>'.$row["tot_money"].'</td>';
                                            echo '<td>';
                                            echo '<button type="button" class="btn btn-default btn-circle"  onclick="window.open(\'view.php?id='.$row["project_id"].'\')"><i class="fa fa-eye"></i></button>';
                                            echo '<button type="button" class="btn btn-default btn-circle" onclick="window.location.href=\'project.php?id='.$row["project_id"].'\'"><i class="fa fa-pencil"></i></button>';
                                            echo '<button type="button" class="btn btn-default btn-circle"><i class="fa fa-share-alt"></i></button>';
                                            echo '<button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProjectModal" onclick="setDelete(\''.$row["project_id"].'\')"><i class="fa fa-times"></i></button>';
                                            echo '</td></tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- Modal -->
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
                                                <!-- TODO -->
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
                                            <option value="ProduktionsleiterIn">ProduktionsleiterIn</option>
                                            <option value="Produktions-AssistentIn">Produktions-AssistentIn</option>
                                            <option value="Produktions-SekretärIn">Produktions-SekretärIn</option>
                                            <option value="AufnahmeleiterIn">AufnahmeleiterIn</option>
                                            <option value="Set-AufnahmeleiterIn">Set-AufnahmeleiterIn</option>
                                            <option value="Aufnahmeleiter-AssistentIn">Aufnahmeleiter-AssistentIn</option>
                                            <option value="Regie-AssistentIn">Regie-AssistentIn</option>
                                            <option value="2. RegieassistentIn">2. RegieassistentIn</option>
                                            <option value="Continuity">Continuity</option>
                                            <option value="Chef-Kameramann">Chef-Kameramann</option>
                                            <option value="SchwenkerIn">SchwenkerIn</option>
                                            <option value="Kamera-Assistent">Kamera-Assistent</option>
                                            <option value="2. Kamera-AssistentIn">2. Kamera-AssistentIn</option>
                                            <option value="DIT">DIT</option>
                                            <option value="Video-TechnikerIn">Video-TechnikerIn</option>
                                            <option value="Chef-BeleuchterIn">Chef-BeleuchterIn</option>
                                            <option value="BeleuchterIn">BeleuchterIn</option>
                                            <option value="Key Grip">Key Grip</option>
                                            <option value="Grip">Grip</option>
                                            <option value="TonmeisterIn">TonmeisterIn</option>
                                            <option value="TonoperateurIn">TonoperateurIn</option>
                                            <option value="Perche">Perche</option>
                                            <option value="Ausstattungsleitung">Ausstattungsleitung</option>
                                            <option value="AusstatterIn">AusstatterIn</option>
                                            <option value="AusstattungsassistentIn">AusstattungsassistentIn</option>
                                            <option value="RequisiteurIn">RequisiteurIn</option>
                                            <option value="Decorbau">Decorbau</option>
                                            <option value="KostümbildnerIn">KostümbildnerIn</option>
                                            <option value="Kostüm AssistentIn">Kostüm AssistentIn</option>
                                            <option value="Garderobe">Garderobe</option>
                                            <option value="Chef-MaskenbildnerIn">Chef-MaskenbildnerIn</option>
                                            <option value="MaskenbildnerIn">MaskenbildnerIn</option>
                                            <option value="Maskenbildner-Assistentin">Maskenbildner-Assistentin</option>
                                            <option value="Hair-StylistIn">Hair-StylistIn</option>
                                            <option value="Chef-Editor">Chef-Editor</option>
                                            <option value="Editor">Editor</option>
                                            <option value="Ton-Editor">Ton-Editor</option>
                                            <option value="Editor-AssistentIn">Editor-AssistentIn</option>
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
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- Modal -->
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
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->


                    <!-- Modal -->
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
    </div>
    <!-- /#wrapper -->
</div>
<!-- jQuery -->
<script src="./vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="./vendor/metisMenu/metisMenu.min.js"></script>
<!-- DataTables JavaScript -->
<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="./vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="./vendor/datatables-responsive/dataTables.responsive.js"></script>

<!-- Custom Theme JavaScript -->
<script src="./js/sb-admin-2.js"></script>
<!-- Custom Theme JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script src="./js/project_overview.js"></script>
<script>
$(document).ready(function() {
    $('#projectTable').DataTable({
        "pagingType": "numbers",
        "lengthChange": false,
        "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            {
                "bSearchable": false ,
                "bSortable": false,
            }
        ]
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
    //TODO Ajax setup cache etc
    $('#companylist').html('').load("./h_load_companies.php");
});
</script>
</body>
</html>
