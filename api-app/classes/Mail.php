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
			$subject = $req['mailSubject'];
            $nospName = str_replace(' ', '_', $name);
///----------------------------------------------------------------------------------------------------------------------------
$body = <<<TEMP
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <title>Abrechnung</title>
    <!--[if !mso]><!-- -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
		#outlook a{padding:0}.ReadMsgBody{width:100%}.ExternalClass{width:100%}.ExternalClass *{line-height:100%}body{margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}table,td{border-collapse:collapse;mso-table-lspace:0;mso-table-rspace:0}img{border:0;height:auto;line-height:100%;outline:0;text-decoration:none;-ms-interpolation-mode:bicubic}p{display:block;margin:13px 0}
    </style>
    <!--[if !mso]><!-->
    <style type="text/css">
		@media only screen and (max-width:480px){@-ms-viewport{width:320px}@viewport{width:320px}}
    </style>
    <!--<![endif]-->
    <!--[if mso]>
        <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
    <!--[if lte mso 11]>
        <style type="text/css">
          .outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
    <style type="text/css">
		@media only screen and (min-width:480px){.mj-column-per-100{width:100%!important}}
    </style>
    <style type="text/css">
    </style>
</head>
<body style="background-color:#f9f9f9;">
	<div style="background-color:#f9f9f9;">
      <!--[if mso | IE]>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
        <div style="background:#f9f9f9;background-color:#f9f9f9;Margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f9f9f9;background-color:#f9f9f9;width:100%;">
                <tbody>
                    <tr>
                      <td style="border-bottom:#333957 solid 5px;direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                        <!--[if mso | IE]>
							<table role="presentation" border="0" cellpadding="0" cellspacing="0">     
								<tr>
								</tr>
							</table>
						<![endif]-->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
        <div style="background:#fff;background-color:#fff;Margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;background-color:#fff;width:100%;">
                <tbody>
                    <tr>
                        <td style="border:#dddddd solid 1px;border-top:0px;direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                            <!--[if mso | IE]>
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0"> 
        <tr>
            <td
               style="vertical-align:bottom;width:600px;"
            >
          <![endif]-->
                            <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:bottom;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:bottom;" width="100%">
                                    <tr>
                                        <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                <tbody>
                                                    <tr>
                                                        <td style="width:64px;"><!--
                                                            <img height="auto" src="https://i.imgur.com/KO1vcE9.png" style="border:0;display:block;outline:none;text-decoration:none;width:100%;" width="64" />
                                                        --></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                            <div style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:24px;font-weight:bold;line-height:22px;text-align:center;color:#525252;">
                                                {$subject}
                                            </div>
                                        </td>
                                    </tr>
									<tr>
                                        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                            <div style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:14px;line-height:22px;text-align:left;color:#525252;">
                                            {$body}
											</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!--[if mso | IE]>
            </td>
        </tr>
                  </table>
                <![endif]-->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--[if mso | IE]>
          </td>
        </tr>
      </table>
      
      <table
         align="center" border="0" cellpadding="0" cellspacing="0" style="width:600px;" width="600"
      >
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->
        <div style="Margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                    <tr>
                        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                         <!--[if mso | IE]>
						 <table role="presentation" border="0" cellpadding="0" cellspacing="0">
						<tr>
						<td style="vertical-align:bottom;width:600px;">
						<![endif]-->
                            <div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:bottom;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align:bottom;padding:0;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                    <tr>
                                                        <td align="center" style="font-size:0px;padding:0;word-break:break-word;">
                                                            <div style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:12px;font-weight:300;line-height:1;text-align:center;color:#575757;">
                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Email versendet aus <a href="https://filmstunden.ch" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Filmstunden.ch</a></span>
                    <br><span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Um zu Antworten, direkt auf diese Mail antworten.</span>                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]>
							</td>
							</tr>
							</table>
							<![endif]-->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--[if mso | IE]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </div>
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
            $mail->AddCC($sender, $name);
            $mail->addAddress($req['mailTo']);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->IsHTML(true);
            $mail->AddStringAttachment($binary_content, $date."_".$p_name."_".$nospName.".pdf", $encoding = 'base64', $type = 'application/pdf');
            if(!$mail->send()) {
              return $response ->withJson(array('status'=>'ERROR','msg'=>'something broke'));
            } else {
              return $response ->withJson(array('status'=>'SUCCESS', 'msg'=>'Sent Sucesfully'));
            }
    }
}
