<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );
include './includes/inc_sessionhandler_default.php';
include './includes/inc_encrypt.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css" integrity="sha256-4NxXT7KyZtupE4YdYLDGnR5B8P0JWjNBpF8mQBzYtrM=" crossorigin="anonymous">
    <link href="./css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
</head>

<body>
<div id="wrapper">
<?
    include('./includes/inc_top.php');
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p></p>
            <div class="alert alert-warning" id="saveWarning" style="display:none">
                <div class="project-spinner"></div>
            <span class="savetext">Nicht gespeicherte &Auml;nderungen.</span>
                <button type="button" id="saveButton" class="btn btn-warning saveButton"><span class="fa fa-cloud-upload"> Speichern</span></button>
                <button type="button" class="btn btn-warning disabled" id="saveButtonDisabled" style="display:none"><span class="fa fa-save"> Speichern</span</button>
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
}

//TODO Get Rid of this Shit
// JSON HERE ************************************************************************************************************************
if (!empty($json)){?>
    <script>
    var loadElement = JSON.parse('<?echo $json?>')
    </script>
<?}else{?>
    <script>
    var loadElement = new Array()
    </script>
<?}?>

<!-- MAINCONTENT -->
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <h4 id="title"><div class="loading-spinner-left"></div>&nbsp;</h4>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#project" data-toggle="tab" aria-expanded="true">Infos</a></li>
                <li><a href="#hours" data-toggle="tab" aria-expanded="false">Stunden</a></li>
                <li><a href="#notes" data-toggle="tab" aria-expanded="false">Kommentare</a></li>
            </ul>

            <div style="float:right; margin-top:-50px;">
                <button type="button" id="refresh" class="btn btn-default refreshButton"><i class="fa fa-refresh"></i></button>
                <button type="button" class="btn btn-default" onclick="window.open('view.php?id=<?echo $p_id;?>')"><i class="fa fa-eye"></i></button>
            </div>

        </div>

        <div class="panel-body">
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
                                            <button type="button" id="openProjectModal" class="btn btn-default btn-xs" data-toggle="modal" data-target="#updateProjectModal">
                                                <span class="fa fa-pencil"></span>
                                            </button>
                                        </div><!--btn-group-->
                                    </div><!--pull-right-->
                                </div><!--panel-heading-->
                                <div class="panel-body">
                                    <div class="table">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td width=110px><strong>Projektname:</strong></td>
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
                                                    <td><strong>Produktion:</strong></td>
                                                    <td id=projectCompany></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <input type="hidden" id="projectId" value="<? echo $p_id;?>">
                                    <input type="hidden" id="startDate" value="<?
/* DATE HERE *******************************************************************************************************/
                                    echo $date;?>">
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
                                            <button type="button" class="btn btn-default btn-xs" onclick="window.location.href='./user.php'">
                                                <span class="fa fa-pencil"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div><!--panel-heading-->
                                <div class="panel-body">
                                    <div class="table">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td width=110px><strong>Name:</strong></td>
                                                    <td id="userName"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Addresse:</strong></td>
                                                    <td id="userAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Telefon:</strong></td>
                                                    <td id="userTel"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>E-Mail:</strong></td>
                                                    <td id="userMail"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>AHV#:</strong></td>
                                                    <td id="userAHV"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Geburtsdatum:</strong></td>
                                                    <td id="userDob"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Konto:</strong></td>
                                                    <td id="userKonto"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>BVG:</strong></td>
                                                    <td id="userBVG"></td>
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
                                <form action="#" width="100%">
                                    <datalist id="work">
                                        <option value="Dreh">Dreh</option>
                                        <option value="Laden">Laden</option>
                                        <option value="Vorbereitung">Vorbereitung</option>
                                        <option value="Reisetag">Reisetag</option>
                                    </datalist>
                                    <table width="100%" class="table table-striped table-bordered table-hover" id="workhours" name="wh">
                                        <tr>
                                            <th width="135px">Datum</th>
                                            <th width="120px">Was</th>
                                            <th colspan="3" width="250px">Arbeitszeit</th>
                                            <th class="largescreen"></th>
                                            <th></th>
                                            <th colspan="8" class="hidden-xs hidden-sm hidden-md">Zuschlaege</th>
                                            <th colspan="2" width="110px">Spesen</th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Beginn</td>
                                            <td>Ende</td>
                                            <td>Pausen</td>
                                            <td width="60px" class="largescreen">in h</td>
                                            <td></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>10</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>11</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>12</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>13</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>14</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>15</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>16+</font></td>
                                            <td class="hidden-xs hidden-sm hidden-md"><font size=-2>Nacht</font></td>
                                            <td width=20px>Essen</td>
                                            <td>Auto</td>
                                        </tr>
                                    </table>
                                </form>

                                <div id="editButtons">
                                    <button type="button" id="addRow" class="btn btn-success btn-circle"><i class="fa fa-plus"></i></button>
                                    <button type="button" id="removeRow" class="btn btn-danger btn-circle"><i class="fa fa-minus"></i></button>
                                </div>

                        </div><!-- /.col-lg-12 -->
                    </div><!--row-->
                    <div class="row hidden-xs">
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
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-comment-o fa-fw"></i> Bemerkungen
                                </div>
                                <div class="panel-body">
                                    <textarea class="form-control" rows="3" width=100% id="comment"><?
/*COMMENT HERE **************************************************************************************************************/
                                    echo $comment;?></textarea>
                                </div>
                            </div>
                        </div><!--col-lg-12-->
                        <div class="col-lg-6">
                          <div class="panel panel-default">
                            <div class="panel-heading">
                              <i class="fa fa-comments fa-fw"></i> Chat
                            </div>
                            <div class="panel-body">
                              <div class="message-wrap col-lg-12" id="chats">
<!-- COMMENTS LOAD IN HERE ****************************************************************3-->
                              </div>
                              <div class="send-wrap hideSend">
                                                              <br>
                                <textarea class="form-control send-message" rows="3" placeholder="Antworten..." id="commentText"></textarea>
                              </div>
                              <div class="btn-panel hideSend">
                                <a href="" class=" col-lg-4 text-right btn send-message-btn pull-right" role="button" id="submitComment"><i class="fa fa-plus"></i> Antworten</a>
                              </div><!--btn-->
                            </div><!--panelBody-->
                          </div><!--panel-->
                        </div><!--col-lg-6-->
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
                        <input type="text" name="name" class="form-control" value="<?
                        /*NAME HERE *************************************************************************************************/
                        echo $name;?>" required="">
                    </div>
                    <datalist id="joblist"></datalist>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Arbeit als:</span>
                        <input type="text" list="joblist" class="form-control" name="work" value="<?
/* JOB HERE ******************************************************************************************************************************************/
                         echo $job;?>" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Tagesgage</span>
                        <input type="number" name="pay" class="form-control" value="<?
/* PAY HERE *************************************************************************************************************************************************/
                        echo $pay;?>" required>
                    </div>
                        <div class="form-group input-group companylist">
                            <span class="input-group-addon">Produktionsfirma</span>
                            <select class="form-control" name="company" id="companylist">
                              <option>Loading Companies...</option>
                            </select>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/twix.js/1.1.5/twix.min.js"></script>
<script type="module" src="./js/project_main.js"></script>
<script>
    moment().format();
    var company="<?
/* COMPANY HERE **********************************************************************************************************/
    echo $company_id?>"
    const us_id = "<? echo $u_id;?>"
    const p_id = "<?echo $p_id;?>"
</script>
</body>
</html>
