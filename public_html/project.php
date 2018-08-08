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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.basictable/1.0.8/basictable.min.css" />
    <link href="./css/main.css" rel="stylesheet">
</head>

<body>
<div id="wrapper">

  <?//----------------------------------
  include_once('./includes/inc_top.php');
  //------------------------------------?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p></p>
            <!--error messages-->
            <div class="alert alert-danger" id="saveError" style="display:none">
                <span class="savetext">Fehler beim Speichern</span>
                <button type="button" id="resaveButton" class="btn btn-danger"><span class="fa fa-cloud-upload"> Erneut versuchen</span></button>
            </div>
            <div class="alert alert-warning" id="saveWarning" style="display:none">
                <div class="project-spinner"></div>
            <span class="savetext">Nicht gespeicherte &Auml;nderungen.</span>
                <button type="button" id="saveButton" class="btn btn-warning saveButton"><span class="fa fa-cloud-upload"> Speichern</span></button>
                <button type="button" class="btn btn-warning disabled" id="saveButtonDisabled" style="display:none"><span class="fa fa-cloud-upload"> Speichern</span</button>
            </div>
            <div class="alert alert-success" id="saveInfo" style="display:none">
                Gespeichert.
            </div>
            <div class="alert alert-info" id="saveNone">
                &nbsp;
            </div>
        </div><!-- /col-lg-12 -->
    </div><!-- /row -->
<!-- MAINCONTENT -->
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <h4 id="title"><div class="loading-spinner-left"></div>&nbsp;</h4>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#project" data-toggle="tab" aria-expanded="true">Infos</a></li>
                <li><a href="#hours" data-toggle="tab" aria-expanded="false" id="hoursTab">Stunden</a></li>
                <li><a href="#spesen" data-toggle="tab" aria-expanded="false">Spesen</a></li>
                <li><a href="#notes" data-toggle="tab" aria-expanded="false">Kommentare</a></li>
            </ul>
            <div style="float:right; margin-top:-50px;">
                <button type="button" id="refresh" class="btn btn-default refreshButton"><i class="fa fa-refresh"></i></button>
                <button type="button" id="view" class="btn btn-default"><i class="fa fa-eye"></i></button>
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
                                    <table width="100%" class="table table-striped table-bordered table-condensed" id="workhours" name="wh">
                                             <tr>
                                                <th width="135px">Datum</th>
                                                <th width="120px">Was</th>
                                                <th colspan="3" width="250px">Arbeitszeit</th>
                                                <th class="hidden-xs hidden-sm hidden-md"></th>
                                                <th></th>
                                                <th colspan="8" class="hidden-xs hidden-sm hidden-md">Zuschlaege</th>
                                                <th colspan="2" width="110px">Spesen</th>
                                              </tr>

                                              <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="hidden-xs">Beginn</td>
                                                <td class="hidden-xs">Ende</td>
                                                <td class="hidden-xs">Pausen</td>
                                                <td width="60px" class="hidden-xs hidden-sm hidden-md">in h</td>
                                                <td></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>10</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>11</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>12</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>13</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>14</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>15</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>16+</font></td>
                                                <td class="hidden-xs hidden-sm hidden-md"><font size=-2>Nacht</font></td>
                                                <td width=20px class="hidden-xs">Essen</td>
                                                <td class="hidden-xs">Auto</td>
                                              </tr>
                                    </table>
                                </form>

                                <div id="editButtons">
                                    <button type="button" id="addRow" class="btn btn-success btn-circle"><i class="fa fa-plus"></i></button>
                                    <button type="button" id="removeRow" class="btn btn-danger btn-circle"><i class="fa fa-minus"></i></button>
                                </div>
                        </div><!-- /.col-lg-12 -->
                    </div><!--row-->
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-md-offset-5">
                            <p></p>
                            <table class="table table-bordered table-hover" cellspacing="0" cellpadding="0">
                              <tr>
                                <th width="35px"></th>
                                <th colspan="3">Totalarbeitszeit in Stunden:</th>
                                <th id="totalWorkHours"></th>
                              </tr>
                              <tr class="cat">
                                <th class="collapseHeader"><i class="fa fa-chevron-right"></i></th>
                                <th colspan="3" class="collapseHeader">Grundlohn</th>
                                <th class="collapseHeader" id="salaryBase"></th>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>Grundlohn (9h/Tag)</td>
                                <td id="hoursDay"></td>
                                <td id="payRateDay"></td>
                                <td id="totalDay"></td>
                              </tr>
                              <tr class="cat">
                                <th class="collapseHeader"><i class="fa fa-chevron-right"></i></th>
                                <th colspan="3" class="collapseHeader">Uberstundenzuschläge Total</th>
                                <th class="collapseHeader" id="salaryOvertime"></th>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>125 (10&11)</td>
                                <td id="hours125"></td>
                                <td id="payRate125"></td>
                                <td id="total125"></td>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>150 (12&13)</td>
                                <td id="hours150"></td>
                                <td id="payRate150"></td>
                                <td id="total150"></td>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>200 (14&15)</td>
                                <td id="hours200"></td>
                                <td id="payRate200"></td>
                                <td id="total200"></td>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>250 (16+)</td>
                                <td id="hours250"></td>
                                <td id="payRate250"></td>
                                <td id="total250"></td>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>Nacht</td>
                                <td id="hours25"></td>
                                <td id="payRate25"></td>
                                <td id="total25"></td>
                              </tr>
                              <tr class="cat">
                                <th class="collapseHeader"><i class="fa fa-chevron-right"></i></th>
                                <th colspan="3" class="collapseHeader">Spesen Total</th>
                                <th class="collapseHeader" id="salaryAdditional"></th>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>Essen</td>
                                <td id="lunches"></td>
                                <td>à 32 CHF</td>
                                <td id="totalLunch"></td>
                              </tr>
                              <tr class="BottomSub">
                                <td></td>
                                <td>Auto</td>
                                <td id="totalKilometers"></td>
                                <td>à 0.7 CHF</td>
                                <td id="totalCar"></td>
                              </tr>
                              <tr class="cat">
                                <th></th>
                                <th colspan="3">Total</th>
                                <th id="totalOverall"></th>
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
                                    <textarea class="form-control" rows="3" width=100% id="comment">
<!-- COMMENT LOADS IN HERE ****************************************************************-->
                                    </textarea>
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
<!-- CHATS LOAD IN HERE ****************************************************************-->
                              </div>
                              <div class="send-wrap hideSend">
                                                              <br>
                                <textarea class="form-control send-message" rows="3" placeholder="Antworten..." id="commentText"></textarea>
                              </div>
                              <div class="btn-panel hideSend">
                                <a href="#" class=" col-lg-4 text-right btn send-message-btn pull-right" role="button" id="submitComment"><i class="fa fa-plus"></i> Antworten</a>
                              </div><!--btn-->
                            </div><!--panelBody-->
                          </div><!--panel-->
                        </div><!--col-lg-6-->
                      </div><!--col-lg-12-->
                    </div><!--row-->

                    <div class="tab-pane fade in" id="spesen">
                        <p></p>
                        <div class="row">
                            <div class="col-lg-12">
                                  <div class="table-responsive">
                                     <table class="table table-striped table-bordered table-condensed">
                                       <thead>
                                         <tr>
                                           <th width="30px">#</th>
                                           <th width="100px">Datum</th>
                                           <th width="20%">Name</th>
                                           <th class="hidden-xs hidden-sm">Beschreibung</th>
                                           <th width="100px"></th>
                                         </tr>
                                       </thead>
                                       <tbody id="expenseTable">
                                       </tbody>
                                     </table>
                                   </div>
                                  <a href="#" id="addExpenseBtn" class="btn btn-default btn-block"  data-toggle="modal" data-target="#addExpenseModal">Ausgaben hinzuf&uuml;gen</a>
                          			</div>
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
                    <form role="form" action="" method="post" id="updateProject">
                    <div class="form-group input-group">
                        <span class="input-group-addon">Projektname</span>
                        <input type="text" id="p_name" name="name" class="form-control" required>
                    </div>
                    <datalist id="joblist"></datalist>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Arbeit als:</span>
                        <input type="text" id="p_job" list="joblist" class="form-control" name="work" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Tagesgage</span>
                        <input type="number" id="p_pay" name="pay" class="form-control" required>
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

    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Spesen Hinzufügen</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group input-group" id="exp_date_g">
                      <span class="input-group-addon">Datum</span>
                      <input type="date" id="exp_date" name="name" class="form-control" required>
                  </div>
                    <div class="form-group input-group" id="exp_name_g">
                        <span class="input-group-addon">Name</span>
                        <input type="text" id="exp_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group input-group" id="exp_value_g">
                        <span class="input-group-addon">Betrag</span>
                        <input type="number" step="0.01" min="0" id="exp_value" name="pay" class="form-control" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Beschreibung</span>
                        <textarea class="form-control" rows="2" id="exp_comment"></textarea>
                    </div>
                    <div id="imageupload">
                    <form action="#" class="dropzone" id="fileUploadZone">
                    <div class="fallback">
                      <input name="file" type="file" />
                    </div>
                  </div>
                  <div id="imagefinder" style="display:none">
                  </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
                        <button type="button" class="btn btn-primary" id="saveExpenseBtn">Speichern</button>
                    </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div><!--pagewrapper-->
</div><!-- /#wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/twix.js/1.1.5/twix.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.basictable/1.0.8/jquery.basictable.min.js"></script>
<script type="module" src="./js/project_main.js"></script>
</body>
</html>
