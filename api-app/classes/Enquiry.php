<?php
use \Medoo\Medoo;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../../vendor/PHPMailer/Exception.php');
require_once('../../vendor/PHPMailer/PHPMailer.php');
require_once('../../vendor/PHPMailer/SMTP.php');
require_once('../../vendor/iCalcreator.class.php');


class Enquiry
{
public function __construct($container)
{
    $this->db = $container->get('database');
    $this->auth = $container->get('auth');
}

public function downloadICS($request, $response, $args){
  $p_id=$args['p_id'];
  $data = self::loadEnquiry($p_id);
  $data = $data[0];
  $org = $data['c_mail'];
  //var_dump($data);
  $v = new vcalendar();                              // initiate new CALENDAR
    $v->setConfig( 'unique_id', 'filmstunden.ch' );  // config with site domain
    $v->setProperty( "calscale", "GREGORIAN" );
    $v->setProperty( "method", "PUBLISH" );
    $v->setProperty( 'X-WR-CALNAME', 'Drehanfrage '.$data['p_name']);          // set some X-properties, name, content.. .
    $v->setProperty( 'X-WR-CALDESC', 'Automatisch generierter Drehanfragekalender' );
    $v=self::createCalEvents($v, $data['d_prep_date'], 'prep: '.$data['p_name'], '',$org);
    $v=self::createCalEvents($v, $data['d_shoot_date'], 'shoot: '.$data['p_name'], '',$org);
    $v=self::createCalEvents($v, $data['d_uload_date'], 'ret: '.$data['p_name'], '',$org);
    $v=self::createCalEvents($v, $data['d_misc_date'], 'misc: '.$data['p_name'], '',$org);
  //$v->setProperty( 'X-WR-TIMEZONE', 'Europe/Zurich' );
  return $response->withHeader('Content-Type', 'Content-type:text/calendar')
                  ->withHeader('Content-Disposition: attachment; filename="calendar.ics"')
                  ->withHeader('Content-Length: '.strlen($v->returnCalendar()))
                  ->write($v->returnCalendar());
}

private function createCalEvents($calendar, $dates, $name, $desc, $org){
  if($dates==""){return $calendar;}
  $dates = explode("<br>", $dates);
  foreach ($dates as $cur) {
    $date = DateTime::createFromFormat('d/m/Y', $cur);
    $dtstart = $date->format('Ymd'); 
    //$dtend = $date->modify('+1 day')->format('Ymd');
    //$vevent->setProperty( 'dtstart', '20070401', array('VALUE' => 'DATE'));// alt. date format, now for an all-day event
    $e = new vevent(); 
    $e->setProperty( 'summary', $name );
    $e->setProperty( 'categories', 'WORK' );               
   // $e->setProperty( 'dtstart', $dtstart );
   // $e->setProperty( 'dtend', $dtend );
    $e->setProperty( "sequence", 0 ); 
    $e->setProperty( 'dtstart', $dtstart, array('VALUE' => 'DATE'));
    $e->setProperty( 'description', $desc ); 
    $e->setProperty( 'organizer',   $org ); 
    $e->setProperty( 'location' , 'On Set' );     
    $calendar->addComponent( $e );                
  }
  return $calendar;
}

public function list($request, $response, $args)
{
    $this->auth->check();
    $mode = $request->getQueryParam('m');
    if ($mode == 0) {
      $fin= [0,1]; // ALLE ANZEIGEN
    } elseif ($mode == 1) {
      $fin= 0;  // ACTIVE
    } else {
      $fin= 1; // BEENDET
    }
    $indata= $this->db->select('enquiries', [
          'p_id',
          'p_name',
          'c_date',
          'c_name',
          'c_address'
    ], [
        "u_id" => $_SESSION['user']
    ]);
    $o=[];
    foreach ($indata as $cur) {
        $auftrag = $cur["c_name"];
        $firma = strip_tags($cur["c_address"], '<br>');
        $firma = explode("<br>", $firma);
        $auftrag = $auftrag." - ".$firma[0];
        $c=[
          $cur["c_date"],
          $cur["p_name"],
          $auftrag,
          $cur["p_id"]
        ];
      array_push($o, $c);
    }
    $out=['data' => $o];
    $response = $response->withJson($out);
    return $response;
}

private function loadEnquiry($p_id){
  $data = $this->db->select('enquiries', [
    "p_name",
    "p_texts",
    "d_prep_nr",
    "d_prep_date",
    "d_shoot_nr",
    "d_shoot_date",
    "d_uload_nr",
    "d_uload_date",
    "d_misc_nr",
    "d_misc_date",
    "c_name",
    "c_mail",
    "c_address",
    "u_name",
    "u_mail",
    "u_address",
    "employment"
  ], [
    "p_id" => $p_id
  ]);
  return $data;
}

public function view($request,$response,$args){
  $p_id=$args['p_id'];
  $indata = self::loadEnquiry($p_id);
  $indata[0]["employment"] = json_decode($indata[0]["employment"]);
  $indata[0]["text"] = json_decode($indata[0]["p_texts"]);
  unset($indata[0]["p_texts"]);
  $response = $response->withJson($indata);
  return $response;
}

public function new($input)
{
  if (!isset($_SESSION["running"]) || ($_SESSION["running"] != 1)) {
    $u_id="guest";
  } else {
    $u_id= $_SESSION['user'];
  }

  $now = date(DATE_ATOM, time());
  $p_id = $rest = substr(md5($req['name'].$now),0, 7);

  $p_texts_in->intro= $input['intro'];
  $p_texts_in->comments = $input['comment'];
  $p_texts_in->outro = $input['outro'];
  $p_texts = json_encode($p_texts_in);
  
  $employment->job = $input['e_job'];
  $employment->type = $input['e_type'];
  $employment->pay = $input['e_pay'];
  $employment->cond = $input['e_cond'];
  $emp = json_encode($employment);

  $this->db->insert('enquiries', [
    'p_id' => $p_id,
    'u_id' => $u_id,
    'u_name' => $input['u_name'],
    'u_mail' => $input['u_mail'],
    'u_address' => $input['u_address'],
    'c_name' => $input['c_name'],
    'c_mail' => $input['c_mail'],
    'c_address' => $input['c_addr'],
    'p_name' => $input['p_name'],
    'p_texts' => $p_texts,
    'd_prep_nr' => $input['nr_load'],
    'd_prep_date' => $input['d_load'],
    'd_shoot_nr' => $input['nr_shoot'],
    'd_shoot_date' => $input['d_shoot'],
    'd_uload_nr' => $input['nr_uload'],
    'd_uload_date' => $input['d_uload'],
    'd_misc_nr'=> $input['nr_misc'],
    'd_misc_date'=> $input['d_misc'],
    'employment' => $emp
  ]);

  //if($this->db->rowCount()>0){
    return $p_id;
  // }
  //var_dump($this->db->error());
  //return false;
}

public function sendEnquiry($request,$response,$args){
    $req = $request->getParsedBody();
     $p_id = self::new($req);
      $date = date('ymd');
      if (isset($req['p_id'])) {$p_id = $req['p_id'];}
      $p_title = $req['p_name']; 
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
      $d_prep_date = $req['d_load']; 
      $d_prep_nr =  $req['nr_load']; 
      $d_shoot_date = $req['d_shoot'];
      $d_shoot_nr = $req['nr_shoot'];  
      $d_load_date = $req['d_uload']; 
      $d_load_nr = $req['nr_uload'];
      $d_misc_date = $req['d_misc'];
      $d_misc_nr = $req['nr_misc']; 
      $introtext = $req['intro'];  
      $comments = $req['comment']; 
      $outrotext = $req['outro']; 
      $scripts ="";
      
      ///----------------------------------------------------------------------------------------------------------------------------
      ob_start();
      include('template_anfrage.php');
      $body = ob_get_clean();
      ///--------------------------------------------------------------------------
      if(isset($p_id)){
        $url = 'https://filmstunden.ch/api/v01/enquiries/'.$p_id.'/ics';
        $binary_content = file_get_contents($url);
      }

      $mail = new PHPMailer;
      $mail->CharSet = 'utf-8';  
      $mail->SetFrom('noreply@filmstunden.ch', $u_name); 
      $mail->AddReplyTo($u_mail, $u_name);
      $mail->AddCC($u_mail, $u_name);
      $mail->addAddress($req['c_mail']);
      $mail->Subject = 'Drehanfrage: '.$p_title;
      $mail->Body    = $body;
      $mail->IsHTML(true);

      if(isset($p_id)){
        $filename = str_replace(' ', '_', $p_title);
        $mail->AddStringAttachment($binary_content, $filename.".ics", $encoding = 'base64', $type = 'application/ics');
        /* mime_type of application/ics then gmail shows all events in a grey 'Events in this message' box .
           mime_type of text/calendar it shows the slicker gmail event box.
        */
      }
    
      if(!$mail->send()) {
        return $response ->withJson(array('status'=>'ERROR','msg'=>'mail error'));
      } else {
        return $response ->withJson(array('status'=>'SUCCESS', 'msg'=>'Sent Sucesfully'));
      }
 //   }else{
  //    return $response ->withJson(array('status'=>'ERROR', 'msg'=>'database error'));
   // }
}

}
