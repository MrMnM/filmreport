<?
//TODO Companyhandler mit newm list, edit, delete
include './includes/inc_dbconnect.php';
$action = (isset($_POST['action']) and $_POST['action']!="") ? $_POST['action'] : null;
if ($action) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {die('{ "message": "ERROR: CONN FAILED:'. $conn->connect_error.'"}');}
    switch ($_POST["action"]) {
    case 'list':
        if(isset($_POST["fields"])){
          $fields = $_POST["fields"];
          echo ListFields($conn, $fields);
        }else{
          die('{ "message": "NO FIELDS SELECTED}');
        }
      break;
    case 'delete':
        break;
  }
}else{
          die('{ "message": "NO ACTION SELECTED}');
}

function str_lreplace($search, $subject)
{
    $pos = strrpos($subject, $search);
    if($pos !== false){
        $subject = substr_replace($subject,'', $pos, strlen($search));
    }
    return $subject;
}


function ListFields($conn, $fields)
{
  $query = '';
  foreach ($fields as $v) {
    switch ($v) {
      case 'id':
        $query = $query.'company_id, ';
      break;
      case 'name':
        $query = $query.'name, ';
      break;
      case 'address':
        $query = $query.'address_1, address_2, ';
      break;
      default:
      $query = 'company_id, ';
  }
}
$query = str_lreplace(', ',$query);
$sql = "SELECT $query FROM `companies`";
$full=[];
if ($result->num_rows > 0) {
    while ($cmp = $result->fetch_assoc()) {
      $c=[
          "id"=>$cmp["company_id"],
          "name"=>$cmp["name"]
        ];
      array_push($full,$c);
    }
}
return json_encode($full);
}
