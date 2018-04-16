<?php
require_once('../api-app/lib/Globals.php');
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Filmabrechnungsgenerator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="./css/main.css" rel="stylesheet">
    <link href="./../css/main.css" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-user fa-fw"></i><b> Neues Konto erstellen</b>
            </div>
            <div class="panel-body">
      <div id="error" class="alert alert-danger alert-dismissable" style="display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <p id="errorcontent">Benutzername und/oder Passwort stimmen nicht &uuml;berein.</p>
      </div>
      <div id="success" class="alert alert-success" style="display: none;">
          <p>Account wurde erfolreich erstellt, eine E-Mail wurde an die angegebene Addresse versendet. Es kann einige Minuten dauern, bis das Mail tatsächlich ankommt, bitte etwas Geduld. Die Bestätigungs E-Mail ist 12 Stunden gültig und muss innerhalb dieser Zeit registriert werden.</p>
      </div>
      <form role="form" method="post" action="https://filmstunden.ch/api/v01/user/new" id="createAccount">
          <fieldset>
              <div class="form-group">
                  <label>Name</label>
                  <div class="pull-right">
                      <button type="button" class="btn btn-outline btn-link" data-container="body" data-toggle="popover" data-placement="left" data-content="Bitte hier den vollen Namen angeben, da das nachher auch der Name ist, auf den die Abrechnungen generiert werden." data-original-title="" title="" aria-describedby="popover1">
                          <i class="fa fa-question fa-fw"></i>
                      </button>
                  </div>
                  <input class="form-control" name="name" placeholder="Vorname Nachname"required>
              </div>
              <div class="form-group">
                  <label>E-Mail</label>
                  <div class="pull-right">
                      <button type="button" class="btn btn-outline btn-link" data-container="body" data-toggle="popover" data-placement="left" data-content="Es muss eine gültige E-Mail Addresse angegeben werden um das Konto erstellen zu können" data-original-title="" title="" aria-describedby="popover2">
                          <i class="fa fa-question fa-fw"></i>
                      </button>
                  </div>
                  <input class="form-control" name="mail" placeholder="info@email.ch" required>
              </div>
              <div class="form-group">
                  <label>Passwort</label>
                  <div class="pull-right">
                      <button type="button" class="btn btn-outline btn-link" data-container="body" data-toggle="popover" data-placement="left" data-content="Das Passwort muss mindestens 5 Zeichen umfassen" data-original-title="" title="" aria-describedby="popover2">
                          <i class="fa fa-question fa-fw"></i>
                      </button>
                  </div>
                  <input class="form-control" type="password" pattern=".{5,}" name="pw" placeholder="&#0149;&#0149;&#0149;&#0149;&#0149;&#0149&#0149;&#0149;&#0149;&#0149;&#0149;&#0149;" required>
              </div>
              <div class="form-group">
                  <label>Passwort wiederholen</label>
                  <input class="form-control" type="password" name="pw2" placeholder="&#0149;&#0149;&#0149;&#0149;&#0149;&#0149&#0149;&#0149;&#0149;&#0149;&#0149;&#0149;" required>
              </div>
              <button type="submit" class="btn btn-lg btn-success btn-block">Erstellen</button>
          </fieldset>
      </form>
  </div>
</div>
<p align="right"><font size="-1" color="#888888"><?php echo $GLOBALS['version']; ?></font></p>
</div>
</div>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
<script>
$(()=>{
$('#createAccount').ajaxForm({
        dataType:  'json',
        success: updateSuccess
    });
});
$("[data-toggle=popover]").popover()
function updateSuccess(data){
    if (data.status=="SUCCESS") {
        $('#success').show()
        $('#createAccount').hide()
        $('#error').hide()
    }else{
        $('#error').show()
        $('#success').hide()
        $('#error').find("p").html(data.msg)
    }
}
</script>
</html>
