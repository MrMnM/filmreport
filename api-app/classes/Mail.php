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

    public function sendEnquiry($request,$response,$args){
        $req = $request->getParsedBody();
        $date = date('ymd');
        //$p_id = $req['p_id'];
        $p_title = $req['p_name']; 
        $introtext = $req['intro'];  
        $c_name = $req['c_name']; 
        $c_mail = $req['c_mail']; 
        $c_address1 = $req['c_addr']; 
        $u_name = $req['u_name']; 
        $u_address1 = $req['u_address']; 
        $u_mail = $req['u_mail'];
        $job = $req['e_job']; 
        $emp_type = $req['e_type']; 
        $emp_pay = $req['e_pay']; 
        $emp_cond = $req['e_cond']; 
        $d_prep_nr = "0"; 
        $d_prep_date = $req['d_load']; 
        $d_shoot_nr = "0"; 
        $d_shoot_date = $req['d_shoot']; 
        $d_load_nr = "0"; 
        $d_load_date = $req['d_uload']; 
        $d_misc_nr = "0"; 
        $d_misc_date = $req['d_misc']; 
        $comments = ""; 
        $outrotext = $req['outro']; 

        ///----------------------------------------------------------------------------------------------------------------------------
        ob_start();
        include('template_anfrage.php');
        $body = ob_get_clean();
        ///--------------------------------------------------------------------------
      
        $mail = new PHPMailer;
        $mail->CharSet = 'utf-8';  
        $mail->SetFrom('noreply@filmstunden.ch', $u_name); 
        $mail->AddReplyTo($u_mail, $u_name);
        $mail->AddCC($u_mail, $u_name);
        $mail->addAddress($c_mail);
        $mail->Subject = 'Drehanfrage: '.$p_title;
        $mail->Body    = $body;
        $mail->IsHTML(true);
        if(!$mail->send()) {
          return $response ->withJson(array('status'=>'ERROR','msg'=>'something broke'));
        } else {
          return $response ->withJson(array('status'=>'SUCCESS', 'msg'=>'Sent Sucesfully'));
        }
}
}
