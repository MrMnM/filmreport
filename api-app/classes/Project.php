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
      'projects.p_finished',
      'projects.view_id'
    ], [
      "AND" => [
        "user_id" => $_SESSION['user'],
        "p_finished" => $fin
      ]
    ]);
    $o=[];
    foreach ($indata as $cur) {
      $c=[$cur["p_start"],
      $cur["p_name"],
      $cur["name"],
      $cur["tot_hours"],
      $cur["tot_money"],
      $cur["project_id"],
      $cur["p_finished"],
      $cur["view_id"]
    ];
    array_push($o, $c);
  }
  $out=['data' => $o];
  $response = $response->withJson($out);
  return $response;
}

public function new($request, $response, $args)
{
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

public function load($request, $response, $args){
  $p_id=$args['p_id'];

  $in = $this->db->select('projects', [
    "[>]companies" => ["p_company" => "company_id"]
  ], [
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
$company = $company.$in['name']."</br>".$in['address_1']."</br>".$in['address_2'];
$o = [
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

public function save($request, $response, $args){
  $this->auth->check();
  $p_id=$args['p_id'];
  $req = $request->getParsedBody();
  $add=json_decode($req['add'], true);
  $calcBase=0;
  $baseHours=0;
  $settings = json_encode(array('calcBase' => $calcBase, 'baseHours' => $baseHours));

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
  if($resp==1){
    return $response->withStatus(204);
  }else{
    return $response->withStatus(404);
  }
}

public function saveInfo($request, $response, $args)
{
  $p_id=$args['p_id'];
  /*    $us_id = mysqli_real_escape_string($conn, $_POST["us_id"]);
  if ($u_id != $us_id) {
  die('{ "message": "ERROR: NOT LOGGED IN"}');
}
$p_id = mysqli_real_escape_string($conn, $_POST["p_id"]);

if (!empty($_POST["name"])) {
$cur= mysqli_real_escape_string($conn, $_POST["name"]);
$sql = "UPDATE projects SET  p_name='$cur' WHERE project_id = '$p_id'";
if ($conn->query($sql) === true) {
} else {
die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
}
}
if (!empty($_POST["work"])) {
$cur= mysqli_real_escape_string($conn, $_POST["work"]);
$sql = "UPDATE projects SET  p_job='$cur' WHERE project_id = '$p_id'";
if ($conn->query($sql) === true) {
} else {
die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
}
}
if (!empty($_POST["pay"])) {
$cur= mysqli_real_escape_string($conn, $_POST["pay"]);
$sql = "UPDATE projects SET  p_gage='$cur' WHERE project_id = '$p_id'";
if ($conn->query($sql) === true) {
} else {
die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
}
}
if (!empty($_POST["company"])) {
$cur= mysqli_real_escape_string($conn, $_POST["company"]);
$sql = "UPDATE projects SET  p_company='$cur' WHERE project_id = '$p_id'";
if ($conn->query($sql) === true) {
} else {
die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
}
}
echo '{ "message": "SUCCESS",  "project_id":"'.$p_id.'"}';
*/
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

public function getInfo($request, $response, $args)
{
  $p_id=$args['p_id'];
}
}
