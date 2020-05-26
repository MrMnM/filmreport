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

    private function replaceVariablesInTemplate($template, array $variables){
        return preg_replace_callback('#{(.*?)}#',
            function($match) use ($variables){
                $match[1] = trim($match[1], '$');
                return $variables[$match[1]];
            },
            ' ' . $template . ' ');
    }

    public function sendBill($request,$response,$args){
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
            ob_start();
            include('template_abrechnung.php');
            $body = ob_get_clean();
            ///--------------------------------------------------------------------------
          
            $url = 'https://filmstunden.ch/api/v01/view/download/'.$p_id.'?format=pdf';
            $binary_content = file_get_contents($url);

            $mail = new PHPMailer;
            $mail->CharSet = 'utf-8';  
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

    private function countDays($in){
      if ($in == ""){
        return 0;
      }else{
        return count(explode("<br>", $din)); 
      }
    }
}
