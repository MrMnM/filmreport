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
                Nicht gespeicherte &Auml;nderungen.
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

    //TODO ESCAPES!

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
    $company = $company."&#13;&#10;".$c_address1."&#13;&#10;".$c_address2;

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
    $u_address = $u_address1."&#13;&#10;".$u_address2;
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
            <h4><? echo $name;?></h4>
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
                    <p></p><!--TODO needed?-->
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-briefcase fa-fw"></i> Projektinformationen
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs">
                                                <span class="glyphicon glyphicon-pencil"></span>Bearbeiten
                                            </button>
                                        </div><!--btn-group-->
                                    </div><!--pull-right-->
                                </div><!--panel-heading-->
                                <div class="panel-body">
                                    <form role="form">
                                        <input type="hidden" id="projectId" value="<? echo $p_id;?>">
                                        <input type="hidden" id="startDate" value="<? echo $date;?>">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Projektname</span>
                                            <input type="text" class="form-control" value="<? echo $name;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Arbeit als:</span>
                                            <input type="text" class="form-control" value="<? echo $job;?>"disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Tagesgage</span>
                                            <input type="number" class="form-control" id="basePay" name="basePay" value="<? echo $pay;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Produktionsfirma</span>
                                            <textarea class="form-control" rows="3" disabled><? echo $company;?></textarea>
                                        </div>
                                    </form>
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
                                    <form role="form">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Name</span>
                                            <input type="text" class="form-control" value="<? echo $u_name;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Addresse</span>
                                            <textarea class="form-control" rows="2" disabled><? echo $u_address;?></textarea>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Telefon</span>
                                            <input type="tel" class="form-control" value="<? echo $u_tel;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Email</span>
                                            <input type="mail" class="form-control" value="<? echo $u_mail;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">AHV</span>
                                            <input type="text" class="form-control" value="<? echo $u_ahv;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Geburtsdatum</span>
                                            <input type="date" class="form-control" value="<? echo $u_dob;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Konto</span>
                                            <input type="text" class="form-control" value="<? echo $u_konto;?>" disabled>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">BVG</span>
                                            <input type="text" class="form-control" value="<? echo $u_bvg;?>" disabled>
                                        </div>
                                    </form>
                                </div><!--panel-body-->
                            </div><!--panel-->
                        </div><!--col.lg-6-->

                    </div><!--row-->
                </div><!--tab-pane-->

                <div class="tab-pane fade in" id="hours">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- TODO if needed: panel-heading -->
                            <!--<table width="100%" class="table table-striped table-bordered table-hover" id="projectinfo">
                                <table> TODO needed?-->
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

</div><!--pagewrapper-->
</div><!-- /#wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha256-U5ZEeKfGNOja007MMD3YBI0A3OSZOQbeG6z2f2Y0hu8=" crossorigin="anonymous"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js" integrity="sha256-OrCnS705nv33ycm/+2ifCnVfxxMdWvBMg5PUX1Fjpps=" crossorigin="anonymous"></script>

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
    });
</script>
</body>
</html>
