<?
use \Medoo\Medoo;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../../vendor/PHPMailer/Exception.php');
require_once('../../vendor/PHPMailer/PHPMailer.php');
require_once('../../vendor/PHPMailer/SMTP.php');

class Mail
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function send($request,$response,$args){

            $req = $request->getParsedBody();
            $date = date('ymd');
            $p_id = $req['p_id'];
            $p_name = $req['p_name'];
            $body = $req['mailText'];
            $sender = $req['u_mail'];
            $name = $req['u_name'];
            $nospName = str_replace(' ', '_', $name);
///----------------------------------------------------------------------------------------------------------------------------
$body = <<<TEMP
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Simple Transactional Email</title>
    <style>
@media only screen and (max-width:620px){table[class=body] h1{font-size:28px!important;margin-bottom:10px!important}table[class=body] a,table[class=body] ol,table[class=body] p,table[class=body] span,table[class=body] td,table[class=body] ul{font-size:16px!important}table[class=body] .article,table[class=body] .wrapper{padding:10px!important}table[class=body] .content{padding:0!important}table[class=body] .container{padding:0!important;width:100%!important}table[class=body] .main{border-left-width:0!important;border-radius:0!important;border-right-width:0!important}table[class=body] .btn table{width:100%!important}table[class=body] .btn a{width:100%!important}table[class=body] .img-responsive{height:auto!important;max-width:100%!important;width:auto!important}}@media all{.ExternalClass{width:100%}.ExternalClass,.ExternalClass div,.ExternalClass font,.ExternalClass p,.ExternalClass span,.ExternalClass td{line-height:100%}.apple-link a{color:inherit!important;font-family:inherit!important;font-size:inherit!important;font-weight:inherit!important;line-height:inherit!important;text-decoration:none!important}#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}.btn-primary table td:hover{background-color:#34495e!important}.btn-primary a:hover{background-color:#34495e!important;border-color:#34495e!important}}
    </style>
  </head>
  <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">
            <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">Abrechnung</span>
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">{$body}</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
              <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                <tr>
                  <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 5px; padding-top: 5px; font-size: 12px; color: #999999; text-align: center;">
                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Email versendet aus <a href="https://filmstunden.ch" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Filmstunden.ch</a></span>
                    <br><span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Um zu Antworten, direkt auf diese Mail antworten.</span>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </td>
        <td style="font-family: sans-serif; font-size: 10px; vertical-align: top;">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
TEMP;
///--------------------------------------------------------------------------





          //  $req['mailTo']

            $url = 'https://filmstunden.ch/api/v01/view/download/'.$p_id.'?format=pdf';
            $binary_content = file_get_contents($url);

            $mail = new PHPMailer;
            $mail->SetFrom('noreply@filmstunden.ch', $name);
            $mail->AddReplyTo($sender, $name);
            $mail->addAddress('marius.mahler@gmail.com');
            //$mail->AddCC('person1@domain.com', 'Person One');
            $mail->Subject  = $req['mailSubject'];
            $mail->Body     = $body;
            $mail->IsHTML(true);
            $mail->AddStringAttachment($binary_content, $date."_".$p_name."_".$nospName.".pdf", $encoding = 'base64', $type = 'application/pdf');
            if(!$mail->send()) {
              return $response ->withJson(array('status'=>'ERROR','msg'=>'something broke'));
            } else {
              return $response ->withJson(array('status'=>'SUCCESS', 'msg'=>'Sent Sucesfully'));
            }
    }
}
