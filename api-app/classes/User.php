<?php
use \Medoo\Medoo;

class User
{
  public function __construct($container)
  {
    $this->db = $container->get('database');
    $this->auth = $container->get('auth');
    $this->enc = $container->get('encrypt');
  }

  public function get($request, $response, $args)
  {
    $this->auth->check();
    $data = $this->db->select('users', [
      'mail',
      'name',
      'tel',
      'address_1',
      'address_2',
      'ahv',
      'dateob',
      'konto',
      'bvg',
      'type',
      'affiliation',
    ], [
      "u_id" => $_SESSION['user']
    ]);
    if (sizeof($data)>1) {
      throw new Exception('Multiple Users with same ID');
    };
    $data = $data['0'];
    $data['ahv'] = $this->enc->encrypt($data['ahv'], 'd');
    $data['konto'] = $this->enc->encrypt($data['konto'], 'd');
    array_walk_recursive($data, function(&$item) {
      $item = htmlspecialchars($item, ENT_QUOTES);
    });
    return $response->withJson($data);
  }

  public function getSpecific($request, $response, $args){
    //$u_id = $this->enc->encrypt($args['u_id'],'d');
    $u_id = $args['u_id']; //TODO IMplement Encryption uf uid
    $data = $this->db->select('users', [
      'mail',
      'name',
      'tel',
      'address_1',
      'address_2',
      'ahv',
      'dateob',
      'konto',
      'bvg',
      'type',
      'affiliation',
    ], [
      "u_id" => $u_id
    ]);
    if (sizeof($data)>1) {throw new Exception('Multiple Users with same ID');};
    $data = $data['0'];
    $data['ahv'] = $this->enc->encrypt($data['ahv'], 'd');
    $data['konto'] = $this->enc->encrypt($data['konto'], 'd');
    array_walk_recursive($data, function(&$item) {
      $item = htmlspecialchars($item, ENT_QUOTES);
    });
    return $response->withJson($data);
  }

  public function update($request, $response, $args)
  {
    $this->auth->check();
    $body = $request->getParsedBody();
    try {
      $this->db->update("users", [
        "name" => $body['name'],
        "tel" => $body['tel'],
        "address_1" => $body['address_2'],
        "address_2" => $body['address_1'],
        "ahv" => $this->enc->encrypt($body['ahv'], 'e'),
        "dateob" => $body['dateob'],
        "konto" => $this->enc->encrypt($body['konto'], 'e'),
        "bvg" => $body['bvg'],
      ], [
        "u_id" => $_SESSION['user']
      ]);
      $data =  array('status'=>'200','msg'=>'SUCCESS');
    } catch (Exception $e) {
      return $response->withStatus(500);
    }
    return $response->withJson($data);
  }

  public function new($request, $response, $args)
  {
    $req = $request->getParsedBody();
    if (empty($req["name"]) || empty($req["mail"]) || empty($req["pw"]) || empty($req["pw2"])) {
      return $response->withJson(array('status'=>'ERROR','msg'=>'Es wurden nicht alle Daten übermittelt'));
    }
    if ($req["pw"]!=$req["pw2"]) {
      return $response->withJson(array('status'=>'ERROR','msg'=>'Passwörter stimmen nicht überein'));
    }

    $count =  $this->db->count('users', ['mail'=>$req['mail']]);
    if ($count>0) {
      return $response->withJson(array('status'=>'ERROR','msg'=>'Diese Mailaddresse ist bereits registriert'));
    }

    $mail = $req['mail'];
    $uid = md5($req['mail']);
    $name = $req["name"];
    $active = bin2hex(random_bytes(3));
    $pwhash = password_hash($req['pw'], PASSWORD_DEFAULT);
    $this->db->insert('users', [
      'name'=>$name,
      'u_id'=>$uid,
      'active'=>'no',
      'mail'=>$mail,
      'pw'=>$pwhash
    ]);
    $this->db->insert('tokens', [
      'type'=>'val',
      'user_id'=>$uid,
      'token'=>$active,
      'timeout'=>Medoo::raw('NOW() + INTERVAL 12 HOUR')
    ]);
    $this->db->query("DELETE FROM tokens WHERE timeout < NOW()");
    if ($this->sendRegistrationMail($mail, $active)) {
      $out=array('status'=>'SUCCESS','msg'=>'Bestätigungs E-Mail wurde versandt');
    } else {
      $out= array('status'=>'ERROR','msg'=>'Fehler beim Mailversand');
    }
    return $response->withJson($out);
  }

  private function sendRegistrationMail($mail, $active)
  {
    $subject = 'Filmabrechnungsgenerator';
    $message = 'Hallo, bitte bestaetige deine Email Addresse mit folgendem Link: https://filmstunden.ch/validate/'.$active;
    $message = wordwrap($message, 76, "\r\n");
    $encoding = "utf-8";
    // Preferences for Subject field
    $subject_preferences = array(
      "input-charset" => $encoding,
      "output-charset" => $encoding,
      "line-length" => 76,
      "line-break-chars" => "\r\n"
    );
    $from_mail = "info@filmstunden.ch";
    $from_name = "Filmstunden";
    // Mail header
    $header = "Content-type: text/html; charset=".$encoding." \r\n";
    $header .= "From: ".$from_name." <".$from_mail."> \r\n";
    $header .= "MIME-Version: 1.0 \r\n";
    $header .= "Content-Transfer-Encoding: 8bit \r\n";
    $header .= "Date: ".date("r (T)")." \r\n";
    $header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

    if (mail($mail, $subject, $message, $header)) {
      return true;
    } else {
      return false;
    }
  }

  public function validate($request, $response, $args)
  {
    $this->db->query("DELETE FROM tokens WHERE timeout < NOW()");
    $token = $request->getQueryParam('v');
    $validated = false;
    $u_id = $this->db->get("tokens", "user_id", [
      "AND" => [
        "type" => 'val',
        'token' => $token
        ]]);
        if (empty($u_id)) {
          return $response->withJson(array('status'=>'ERROR','msg'=>'Ungültiger Validationsschlüssel'));
        } else {
          $this->db->update("users", [
            "active" => 'yes',
          ], [
            "u_id" => $u_id
          ]);

          $this->db->delete("tokens", [
            "AND" => [
              "type" => 'val',
              'token' => $token
              ]]);
              return $response->withJson(array('status'=>'SUCCESS','msg'=>'Benutzer erfolgreich validiert'));
            }
          }
        }
