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
                <i class="fa fa-user fa-fw"></i>
                <b> Benutzeraccount validieren</b>
            </div>
            <div class="panel-body">
              <p>Account mit Validierungsschl&uuml;ssel <b><?= urlencode($_GET['v']);?></b> validieren?</p>
      <div id="error" class="alert alert-danger alert-dismissable" style="display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <p id="errorcontent"></p>
      </div>
      <div id="success" class="alert alert-success" style="display: none;">
          <p>Account wurde erfolgreich validiert</p>
      </div>
      <form role="form" method="get" action="https://filmstunden.ch/api/v01/user/validate?v=<?= urlencode($_GET['v']);?>" id="validateAccount">
          <fieldset>
              <button type="submit" id="validate-btn" class="btn btn-lg btn-success btn-block">Benutzeraccount validieren</button>
          </fieldset>
      </form>
      <button type="submit" id="login-btn" class="btn btn-lg btn-success btn-block" style="display: none;">Zum Login</button>
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
$(() => { // JQUERY STARTFUNCTION
$('#validateAccount').ajaxForm({
        dataType:  'json',
        success: updateSuccess
    });
});

$('#target-email').html('Account:')

$('#login-btn').click(function(){
   window.location.href='https://filmstunden.ch/login.php';
})

$("[data-toggle=popover]").popover()
function updateSuccess(data){
    if (data.status=="SUCCESS") {
        $('#success').show()
        $('#login-btn').show()
        $('#validate-btn').hide()
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
