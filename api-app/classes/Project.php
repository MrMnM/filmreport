<?php
use \Medoo\Medoo;

class Project
{
public function __construct($container)
{
    $this->db = $container->get('database');
    $this->auth = $container->get('auth');
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
    $indata = $this->db->select('projects', [
      "[>]companies" => ["p_company" => "company_id"]
    ], [
      'projects.project_id',
      'projects.p_start',
      'projects.p_name',
      'companies.name',
      'projects.tot_hours',
      'projects.tot_money',
      'projects.p_finished'
    ], [
      "AND" => [
        "user_id" => $_SESSION['user'],
        "p_finished" => $fin
      ]
    ]);
    $o=[];
    foreach ($indata as $cur) {
        $c=[
        $cur["p_start"],
        $cur["p_name"],
        $cur["name"],
        $cur["tot_hours"],
        $cur["tot_money"],
        $cur["project_id"],
        $cur["p_finished"]
        ];
      array_push($o, $c);
    }
    $out=['data' => $o];
    $response = $response->withJson($out);
    return $response;
}

public function new($request, $response, $args)
{
  $this->auth->check();
  $req = $request->getParsedBody();
  $now = date(DATE_ATOM, time());
  $project_id = md5($req['name'].$now);
  $this->db->insert('projects', [
    'c_date' => $now,
    'project_id' => $project_id,
    'user_id' => $_SESSION["user"],
    'p_name' => $req['name'],
    'p_start' => $req['date'],
    'p_job' => $req['work'],
    'p_gage' => $req['pay'],
    'p_company' => $req['company'],
  ]);
  $out= array('status'=>'SUCCESS','project_id'=>$project_id);
  return $response->withJson($out);
}

public function load($request, $response, $args)
{
  $this->auth->check();
  $p_id=$args['p_id'];
  $in = $this->db->select('projects', [
    "[>]companies" => ["p_company" => "company_id"]
  ], [
    'projects.p_start',
    'projects.p_name',
    'projects.p_company',
    'projects.p_job',
    'projects.p_gage',
    'projects.p_json',
    'projects.p_comment',
    'companies.name',
    'companies.address_1',
    'companies.address_2'
  ], [
    'project_id' => $p_id
  ]);
  if (sizeof($in)>1) {throw new Exception('Multiple Projects with same ID');};
  $in=$in[0];
  $company = $in['name']."</br>".$in['address_1']."</br>".$in['address_2'];
  $o = [
    "startdate"=>$in['p_start'],
    "name"=>$in['p_name'],
    "job"=>$in['p_job'],
    "pay"=>$in['p_gage'],
    "company"=>$company,
    "companyId"=>$in['p_company'],
    "data"=>$in['p_json'],
    "comment"=>$in['p_comment']
  ];
  return $response->withJson($o);
}

public function save($request, $response, $args)
{
  $this->auth->check();
  $p_id=$args['p_id'];
  $req = $request->getParsedBody();
  $add=json_decode($req['add'],true);
  $calcBase='SSFV_DAY';
  $baseHours=9;
  $otRates = [25,25,50,50,100,100,150];
  $settings = json_encode(array (
  'ver' => 1,
  'calc' => 'SSFV_DAY',
  'hoursDay' => 9,
  'lunch' => 32,
  'car' => 0.7,
  'ferien' => 0.0833,
  'ahv' => 0.0605,
  'alv' => 0.011,
  'bvg' => 0.06,
  'uvg' => 0.0162,
  'rate' => 
  array (
    0 => 1.25,
    1 => 1.5,
    2 => 2,
    3 => 2.5,
    4 => 0.25,
  ),
));

  $comment = "";
  if (!empty($req['comment'])) {
    $comment = $req['comment'];
  }

  $query = $this->db->update('projects', [
    'p_json' => $req['data'],
    'tot_hours' => $add['tothour'],
    'tot_money' => $add['totmoney'],
    'p_end' => $add['enddate'],
    'p_comment' => $comment,
    'settings' => $settings,
  ], [
    'project_id' => $p_id
  ]);

  if($query->rowCount()==1){
    return $response->withStatus(204);
  }else{
    return $response->withStatus(500);
  }
}

public function delete($request, $response, $args)
{
  $this->auth->check();
  $p_id=$args['p_id'];
  $resp = $this->db->delete('projects', ['project_id' => $p_id]);
$mask = '../upload/'.$p_id.'_*.*';
array_map('unlink', glob($mask));

  if($resp==1){
    return $response->withStatus(204);
  }else{
    return $response->withStatus(404);
  }
}

public function saveInfo($request, $response, $args)
{
  $this->auth->check();
  $p_id=$args['p_id'];
  $req = $request->getParsedBody();
  $query = $this->db->update('projects', [
    "p_name" => $req['name'],
    "p_job" => $req['work'],
    "p_gage" => $req['pay'],
    "p_company" => $req['company']
  ], [
    "project_id" => $p_id
  ]);
if($query>0){
  $out= array('status'=>'SUCCESS','project_id'=>$p_id);
}else{
  $out = array('status'=>'ERROR','project_id'=>$p_id);
}
return $response->withJson($out);
}

public function finish($request, $response, $args)
{
  $this->auth->check();
  $p_id=$args['p_id'];
  $query = $this->db->update('projects', [
    "p_finished" => 1
  ], [
    "project_id" => $p_id
  ]);

  if($query>0){
    $out= array('status'=>'SUCCESS','project_id'=>$p_id);
  }else{
    $out = array('status'=>'ERROR','project_id'=>$p_id);
  }
  return $response->withJson($out);
}

public function download($request, $response, $args)
{
  $p_id=$args['p_id'];
}

}
