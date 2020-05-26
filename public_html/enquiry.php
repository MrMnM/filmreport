<?

//TODO Macheen das es ohne login klappt
include './includes/inc_sessionhandler_default.php';
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

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha256-916EbMg70RQy9LHiGkXzG8hSg9EdNy97GazNG/aiY1w=" crossorigin="anonymous" />
    <!-- MetisMenu CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css" integrity="sha256-4NxXT7KyZtupE4YdYLDGnR5B8P0JWjNBpF8mQBzYtrM=" crossorigin="anonymous">
    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/ui/trumbowyg.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css"/>


    <!-- Custom CSS -->
    <link href="./css/main.css" rel="stylesheet">
    <link href="./css/wizard.css" rel="stylesheet">

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
    include('./includes/inc_top.php');

    ?>
<div id="page-wrapper">
<p></br></p>
<section id="enquiry">
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="active">
                        <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                            <span class="round-tab">
                                 <i class="fa fa-handshake-o" aria-hidden="true"></i>
                            </span>
                        </a>

                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                            <span class="round-tab">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                            <span class="round-tab">
                                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                            <span class="round-tab">
                                <i class="fa fa-commenting-o" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            <form role="form">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">
                            <div class="form-group">
                                <label>Projekt</label>
                                <input id="projectName" class="form-control" placeholder="Unbenanntes Projekt" required>
                            </div>
                            <div class="form-group">
                                <label>Drehanfrage von</label>
                                <input id="c_name" class="form-control" placeholder="Hans Muster"><br>
                                <input id="c_mail" class="form-control" placeholder="hans.muster@mail.com">
                            </div>
                            <div class="form-group">
                                <label>Produktionsfirma</label>
                                <select class="form-control"  name="company" id="companylist" required><option value="" disabled selected>Firma auswählen oder Addresse unten eingeben</option></select>
                                <textarea id="companyAddress" rows="3"></textarea>
                            </div>
                        <ul class="list-inline pull-right">
                            <li><button id="firstStep" type="button" class="btn btn-primary next-step">Speichern & Weiter</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step2">
                        <datalist id="joblist"></datalist>
                        <div class="form-group">
                            <label>Arbeit als</label>
                            <input id="job" type="text" list="joblist" class="form-control" name="work" id="work" required>
                        </div>
                        <div class="form-group">
                            <label>Abrechnungsart</label>
                            <select class="form-control"  name="emptype" id="emptype" required>
                                <option value="Anstellung" selected>Anstellung</option>
                                <option value="Selbstständig">Selbstständig</option>
                            </select>
                        </div>
                        <div class="form-group">
                                <label>Lohn & Anstellungskonditionen</label>
                                <input id="pay" class="form-control" placeholder="510 CHF" required><br>
                                <input id="pay2" class="form-control" value="9h/Tag SSFV Tagesbasis" required>

                            </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Zurück</button></li>
                            <li><button type="button" class="btn btn-primary next-step">Speichern & Weiter</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="step3">
                        <div class="form-group">
                            <label>Ladetage</label>
                            <div class="row">
                                <div class="col-sm-2">
                                    <input id="loadnr" type="text" value="0" class="form-control"/>
                                </div>
                                <div class="col-sm-10">
                                    <input id="loaddate" type="text" class="form-control date"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Drehtage</label>
                            <div class="row">
                                <div class="col-sm-2">
                                    <input id="shootnr" type="text" value="0" class="form-control"/>
                                </div>
                                <div class="col-sm-10">
                                    <input id="shootdate" type="text" class="form-control date"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ausladetage</label>
                            <div class="row">
                                <div class="col-sm-2">
                                    <input id="unloadnr" type="text" value="0" class="form-control"/>
                                </div>
                                <div class="col-sm-10">
                                    <input id="unloaddate" type="text" class="form-control date"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Diverse</label>
                            <div class="row">
                                <div class="col-sm-2">
                                    <input id="miscnr" type="text" value="0" class="form-control"/>
                                </div>
                                <div class="col-sm-10">
                                    <input id="miscdate" type="text" class="form-control date"/>
                                </div>
                            </div>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Zurück</button></li>
                            <li><button type="button" class="btn btn-primary btn-info-full next-step">Speichern & Weiter</button></li>
                        </ul>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="complete">
                        <div class="form-group">
                                <label>Einleitungstext</label>
                                <textarea id="introtext" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                                <label>Abschlusstext</label>
                                <textarea id="outrotext" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                                <label>Bemerkungen</label>
                                <textarea id="comment" rows="3"></textarea>
                        </div>
                        <ul class="list-inline pull-right">
                            <li><button type="button" class="btn btn-default prev-step">Zurück</button></li>
                            <li><button type="button" id="sendMail" class="btn btn-primary btn-info-full next-step">Mail Absenden</button></li>
                        </ul>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </section>
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/trumbowyg.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script type="module" src="./js/enquiry.js"></script>

    </body>
    </html>
