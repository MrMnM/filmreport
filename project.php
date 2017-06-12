<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_sessionhandler_default.php';
include './includes/inc_dbconnect.php';
include './includes/inc_encrypt.php';
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
    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
    <!--Fontawsome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />

</head>

<body>
<div id="wrapper">
<? include('./includes/inc_top.php');?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p></p>
            <div class="alert alert-warning" id="saveWarning" style="display:none">
                <div class="spinner"></div>
            <span class="savetext">Nicht gespeicherte &Auml;nderungen.</span>
                <button type="button" class="btn btn-warning saveButton" id="saveButton"><span class="glyphicon glyphicon-save"> Speichern</span></button>
                <button type="button" class="btn btn-warning disabled" id="saveButtonDisabled" style="display:none"><span class="glyphicon glyphicon-save"> Speichern</span</button>
            </div>
            <div class="alert alert-success" id="saveInfo" style="display:none">
                Gespeichert.
            </div>

            <?if (!empty($_GET["id"])) { ?>
                    <div class="alert alert-info" id="saveNone">
                            &nbsp;
                    </div>
            <?}else{?>
                    <div class="alert alert-danger" id="saveNone">
                        <b>KEIN PROJEKT GELADEN</b>
                    </div>
            <? die();}?>
        </div><!-- /col-lg-12 -->
    </div><!-- /row -->
<?
if (!empty($_GET["id"])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $p_id = $conn->real_escape_string($_GET["id"]);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //TODO ESCAPES! Get all of THis Data Dynamically!

    //Get Projects
    $sql = "SELECT p_name, p_company, p_job, p_gage, p_start, p_json, p_comment FROM `projects` WHERE project_id='$p_id';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $name = $row["p_name"];
            $company_id = $conn->real_escape_string($row["p_company"]);
            $job = $row["p_job"];
            $pay = $row["p_gage"];
            $date = $row["p_start"];
            $json = $row["p_json"];
            $comment = $row["p_comment"];
        }
    }

    //Get companies
    $sql = "SELECT name, address_1, address_2 FROM `companies` WHERE company_id='$company_id';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $company = $row["name"];
            $c_address1 = $row["address_1"];
            $c_address2= $row["address_2"];
        }
    }
    $company = $company."\n</br>".$c_address1."\n</br>".$c_address2;

    $sql = "SELECT mail, tel, name, address_1, address_2, ahv, dateob, konto, bvg FROM `users` WHERE u_id='$u_id';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $u_name = $row["name"];
            $u_tel = $row["tel"];
            $u_mail = $row["mail"];
            $u_ahv = encrypt($row["ahv"],'d');
            $u_dob = $row["dateob"];
            $u_konto = encrypt($row["konto"],'d');
            $u_bvg = $row["bvg"];
            $u_address1= $row["address_1"];
            $u_address2= $row["address_2"];
        }
    }
    $u_address = $u_address1."\n</br>".$u_address2;
}

if (!empty($json)){
    echo '<script>';
    echo 'loadElement = JSON.parse(\''.$json.'\');';
    echo 'console.log(loadElement);';
    echo '</script>';
}else{
    echo '<script>';
    echo 'var loadElement = new Array()';
    echo '</script>';
}
?>

<!-- MAINCONTENT -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 id="title"></h4>
            <div style="float:right; top:-10px;">
                <button type="button" class="btn btn-default refreshButton"><i class="fa fa-refresh"></i></button>
                <button type="button" class="btn btn-default" onclick="window.open('view.php?id=<?echo $p_id;?>')"><i class="fa fa-eye"></i></button>
            </div>
        </div>

        <div class="panel-body">
        <!-- Nav tabs-->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#project" data-toggle="tab" aria-expanded="true">Projektinfos</a>
                </li>
                <li class=""><a href="#hours" data-toggle="tab" aria-expanded="false">Stunden</a>
                </li>
                <li class=""><a href="#notes" data-toggle="tab" aria-expanded="false">Bemerkungen</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="project">
                    <p></p>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-briefcase fa-fw"></i> Projektinformationen
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#updateProjectModal">
                                                <span class="glyphicon glyphicon-pencil"></span>Bearbeiten
                                            </button>
                                        </div><!--btn-group-->
                                    </div><!--pull-right-->
                                </div><!--panel-heading-->
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td width=150px><strong>Projektname:</strong></td>
                                                    <td id="projectName"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Arbeit als:</strong></td>
                                                    <td id="projectJob"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tagesgage:</strong></td>
                                                    <td id=projectPay></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Produktionsfirma:</strong></td>
                                                    <td><div id=projectCompany></div></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" id="projectId" value="<? echo $p_id;?>">
                                    <input type="hidden" id="startDate" value="<? echo $date;?>">
                                    <input type="hidden" id="basePay"value="<? echo $pay;?>">

                                </div><!--panel-body-->
                            </div><!--panel-->
                        </div><!--col-lg-6-->

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-user fa-fw"></i> Persönliche Informationen
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs">
                                                <span class="glyphicon glyphicon-pencil"></span>Bearbeiten
                                            </button>
                                        </div>
                                    </div>
                                </div><!--panel-heading-->
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td width=150px><strong>Name:</strong></td>
                                                    <td><? echo $u_name;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Addresse:</strong></td>
                                                    <td><? echo $u_address;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Telefon:</strong></td>
                                                    <td><? echo $u_tel;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>E-Mail:</strong></td>
                                                    <td><? echo $u_mail;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>AHV#:</strong></td>
                                                    <td><? echo $u_ahv;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Geburtsdatum:</strong></td>
                                                    <td><? echo $u_dob;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Konto:</strong></td>
                                                    <td><? echo $u_konto;?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>BVG:</strong></td>
                                                    <td><? echo $u_bvg;?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!--panel-body-->
                            </div><!--panel-->
                        </div><!--col.lg-6-->

                    </div><!--row-->
                </div><!--tab-pane-->

                <div class="tab-pane fade in" id="hours">
                    <p></p>
                    <div class="row">
                        <div class="col-lg-12">
                                <form action="#">
                                    <datalist id="work">
                                        <option value="Dreh">Dreh</option>
                                        <option value="Laden">Laden</option>
                                        <option value="Vorbereitung">Vorbereitung</option>
                                        <option value="Reisetag">Reisetag</option>
                                    </datalist>
                                    <table width="100%" class="table table-striped table-bordered table-hover" id="workhours" name="wh">
                                        <tr>
                                            <th width="150px">Datum</th>
                                            <th width="150px">Was</th>
                                            <th colspan="4" width="350px">Arbeitszeit</th>
                                            <th width="80px">Basis</th>
                                            <th colspan="8">Zuschlaege</th>
                                            <th colspan="2" width="10%">Spesen</th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colspan="4"></td>
                                            <td><font size=-2>bis 9h Tag</font></td>
                                            <td colspan="7">Uebersstunden</td>
                                            <td>Nacht</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Beginn</td>
                                            <td>Ende</td>
                                            <td>Pausen</td>
                                            <td width="60px">in h</td>
                                            <td></td>
                                            <td><font size=-2>10</font></td>
                                            <td><font size=-2>11</font></td>
                                            <td><font size=-2>12</font></td>
                                            <td><font size=-2>13</font></td>
                                            <td><font size=-2>14</font></td>
                                            <td><font size=-2>15</font></td>
                                            <td><font size=-2>16+</font></td>
                                            <td><font size=-2></font></td>
                                            <td>Essen</td>
                                            <td>Auto</td>
                                        </tr>
                                    </table>
                                </form>

                                <div id="editButtons">
                                    <button type="button" class="add-row btn btn-success btn-circle"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="remove-row btn btn-danger btn-circle"><i class="fa fa-minus"></i></button>
                                </div>

                        </div><!-- /.col-lg-12 -->
                    </div><!--row-->
                    <div class="row">
                        <div class="col-lg-12">
                            <p></p>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="calcualtions">
                                <tr>
                                    <td colspan ="5" align="right" width="550px">Arbeitszeit in Stunden:</td>
                                    <td width="100px" id="totalWorkHours"></td>
                                    <td width="80px"></td>
                                    <td colspan="2"><font size=-2>125%</font></td>
                                    <td colspan="2"><font size=-2>150%</font></td>
                                    <td colspan="2"><font size=-2>200%</font></td>
                                    <td colspan="1"><font size=-2>250%</font></td>
                                    <td><font size=-2>25%</font></td>
                                    <td colspan="2" width=10%></td>
                                </tr>
                                <tr>
                                    <td colspan ="6" align="right"></td>
                                    <td id="hoursDay">0</td>
                                    <td colspan="2" id="hours125">0</td>
                                    <td colspan="2" id="hours150">0</td>
                                    <td colspan="2" id="hours200">0</td>
                                    <td id="hours250">0</td>
                                    <td id="hours25">0</td>
                                    <td id="lunches">0</td>
                                    <td id="totalKilometers">0</td>
                                </tr>
                                <tr>
                                    <td colspan ="6" align="right"> a CHF:</td>
                                    <td id="payRateDay">0</td>
                                    <td colspan="2" id="payRate125">0</td>
                                    <td colspan="2" id="payRate150">0</td>
                                    <td colspan="2" id="payRate200">0</td>
                                    <td id="payRate250">0</td>
                                    <td id="payRate25">0</td>
                                    <td>32</td>
                                    <td>0.7</td>
                                </tr>
                                <tr>
                                    <td colspan ="6" align="right">CHF:</td>
                                    <td id="totalDay">0</td>
                                    <td colspan="2" id="total125">0</td>
                                    <td colspan="2" id="total150">0</td>
                                    <td colspan="2" id="total200">0</td>
                                    <td id="total250">0</td>
                                    <td id="total25">0</td>
                                    <td id="totalLunch">0</td>
                                    <td id="totalCar">0</td>
                                </tr>
                                <tr>
                                    <td colspan ="6" align="right">Total: </td>
                                    <td align="center">Grundlohn</td>
                                    <td colspan="8" align="center">Zuschlaege</td>
                                    <td colspan="2" align="center">Spesen</td>
                                </tr>
                                <tr>
                                    <td colspan ="6" align="right">CHF: </td>
                                    <td align="center" id="salaryBase"></td>
                                    <td colspan="8" align="center" id="salaryOvertime"></td>
                                    <td colspan="2" align="center" id="salaryAdditional"></td>
                                </tr>
                            </table>
                        </div><!--col-lg-12-->
                    </div><!--row-->
                </div><!--tab-pane-->

                <div class="tab-pane fade in" id="notes">
                    <p></p>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-briefcase fa-fw"></i> Bemerkungen
                                </div>
                                <div class="panel-body">
                                    <textarea class="form-control" rows="3" width=100% id="comment"><? echo $comment;?></textarea>
                                </div>
                            </div>
                        </div><!--col-lg-12-->
                    </div><!--row-->
                </div><!--tab-pane-->
            </div><!--tab-content-->
        </div><!--panel-body-->
    </div><!--panel-->


    <div class="modal fade" id="updateProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Projektinformationen anpassen</h4>
                </div>
                <div class="modal-body">
                    <form role="form" action="h_project.php" method="post" id="updateProject">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="us_id" value="<? echo $u_id;?>">
                        <input type="hidden" name="p_id" value="<? echo $p_id;?>">
                    <div class="form-group input-group">
                        <span class="input-group-addon">Projektname</span>
                        <input type="text" name="name" class="form-control" value="<? echo $name;?>" required="">
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
                        <input type="text" list="jobs" class="form-control" name="work" value="<? echo $job;?>" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Tagesgage</span>
                        <input type="number" name="pay" class="form-control" value="<? echo $pay;?>" required>
                    </div>

                        <div class="form-group input-group">
                            <span class="input-group-addon">Produktionsfirma</span>
                            <select class="form-control"  name="company" id="companylist" value="<? echo $company;?>">
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#newCompany"><i class="fa fa-plus"></i>
                                </button>
                            </span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                        <button type="submit" class="btn btn-primary" onclick="">Projektinformationen anpassen</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->





</div><!--pagewrapper-->
</div><!-- /#wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js" integrity="sha256-OrCnS705nv33ycm/+2ifCnVfxxMdWvBMg5PUX1Fjpps=" crossorigin="anonymous"></script>
<!-- JqueryForms -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js" integrity="sha384-tIwI8+qJdZBtYYCKwRkjxBGQVZS3gGozr3CtI+5JF/oL1JmPEHzCEnIKbDbLTCer" crossorigin="anonymous"></script>
<!-- Custom Theme JavaScript -->
<script src="./js/sb-admin-2.js"></script>
<!-- Custom Functions JavaScript -->
<script src="./js/project.js"></script>
<!--on ready-->
<script>
$(document).ready(function() {
    if (loadElement.length == 0){
        var rowElement = new Array();
    }else{
        loadJSON(loadElement);
        updateAll();
    }

    us_id = "<? echo $u_id;?>";
    p_id = "<?echo $p_id;?>";

    updateProjectInfo();

    setInterval(function() {
        if(!saved){
    Save();
}
}, 15000);

    $('#updateProject').ajaxForm({
            dataType:  'json',
            success: updateSuccess
        });



});


</script>
</body>
</html>
