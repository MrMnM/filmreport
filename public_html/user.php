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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./css/main.css" rel="stylesheet">
</head>

<body>
<div id="wrapper">

  <?//----------------------------------
  include_once('./includes/inc_top.php');
  //------------------------------------?>

<div id="page-wrapper">
  <p></br></p>
  <div class="row">
    <div class="col-lg-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-user fa-fw"></i> Persönliche Informationen
        </div><!--panel heading-->
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <table class="table table-hover">
                <tbody>
                  <tr class="name">
                      <td width="150px"><strong>Name:</strong></td>
                      <td class="name"></td>
                  </tr>
                  <tr class="address">
                      <td><strong>Addresse:</strong></td>
                      <td class="address"></td>
                  </tr>
                  <tr class="tel">
                      <td><strong>Telefon:</strong></td>
                      <td class="tel"></td>
                  </tr>
                  <tr class="ahv">
                      <td><strong>AHV#:</strong></td>
                      <td class="ahv"></td>
                  </tr>
                  <tr class="dob">
                      <td><strong>Geburtsdatum:</strong></td>
                      <td class=dob></td>
                  </tr>
                  <tr class="konto">
                      <td><strong>Konto:</strong></td>
                      <td class="konto"></td>
                  </tr>
                  <tr class="bvg">
                      <td><strong>BVG:</strong></td>
                      <td class="bvg"></td>
                  </tr>
                </tbody>
              </table><!--tableresponsive-->
              <div class="pull-right">
                <div class="btn-group">
                  <button class="btn btn-default" id="saveInfo">Speichern</button>
                </div>
              </div>
            </div><!--col lg12-->
          </div><!--row-->
        </div><!--panelbody-->
      </div><!--panel-->
    </div><!--col6--->
    <div class="col-lg-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fa fa-lock fa-fw"></i> Passwort
        </div><!--panel heading-->
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <form role="form">
                <table class="table table-hover">
                  <tbody>
                    <tr class="mail">
                      <td width="150px"><strong>E-Mail:</strong></td>
                      <td class="mail"></td>
                    </tr>
                    <tr class="mail">
                      <td width="150px"><strong>Aktuelles Passwort:</strong></td>
                      <td class="curpw"></td>
                    </tr>
                    <tr class="mail">
                      <td width="150px"><strong>Neues Passwort:</strong></td>                          <td class="newpw"></td>
                    </tr>
                    <tr class="mail">
                      <td width="150px"><strong>Wiederholen:</strong></td>
                      <td class="newpw2"></td>
                    </tr>
                  </tbody>
                </table>
                <div class="pull-right">
                  <div class="btn-group">
                    <button type="submit" class="btn btn-default disabled">Speichern</button>
                  </div>
                </div><!--pull-right-->
              </form>
            </div><!--col12-->
          </div><!--row-->
        </div><!--panelbody-->
      </div><!--panel-->
    </div><!--col6-->
  </div><!--row-->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.metismenu/1.1.3/metisMenu.min.js"></script>
<script type="module" src="./js/user.js"></script>
</body>
</html>
