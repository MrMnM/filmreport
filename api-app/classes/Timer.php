<?
use \Medoo\Medoo;

class Timer
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function new($request,$response,$args){
      /*
      $name= mysqli_real_escape_string($conn, $_POST["name"]);
      $now = date(DATE_ATOM, time());
      $timer_id = substr(md5($name.$now), 0, 5);
      if ($project_id = NewProject($u_id, $name, $now, $conn)) {
          $sql = "INSERT INTO active_timers (timer_id,project_id,user_id,name,creation)
          VALUES ('$timer_id','$project_id', '$u_id','$name','$now')";
          if ($conn->query($sql) === true) {
              echo '{ "message": "SUCCESS",  "name":"'.$name.'", "id":"'.$timer_id.'"}';
          } else {
              die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
          }
      } else {
          die('{ "message": "ERROR: PROJECT COULDNT BE CREATED"}');
      }


  function NewProject($u_id, $name, $date, $conn)
  {
      $project_id = md5($name.$date);
      $sql = "INSERT INTO projects (project_id,user_id,p_name,p_start,p_company)
      VALUES ('$project_id', '$u_id','$name','$date','timer')";

      if ($conn->query($sql) === true) {
          return $project_id;
      } else {
          die('{ "message": "ERROR: CONN FAILED: '.$conn->connect_error.'"}');
      }
  }
  */
    }

    public function list($request,$response,$args){
      /*
      $sql = "SELECT timer_id, name, creation FROM `active_timers` WHERE user_id='$u_id';";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          $counter = 1;
          $rowCount = mysqli_num_rows($result);
          while ($row = $result->fetch_assoc()) {
              echo '<a href="#" onclick="SetActive(\''.$row["timer_id"].'\',\''.$row["name"].'\')" class="list-group-item success">'.PHP_EOL;
              echo '<i class="fa fa-hourglass-half fa-fw"></i> '.$row["name"].PHP_EOL;
              echo '<span class="pull-right small"><em class="text-muted">'.$row["creation"].'</em>&nbsp;&nbsp;<button class="btn btn-outline btn-danger btn-xs" onClick="deleteTimer(\''.$row["timer_id"].'\')"><i class="fa fa-fw fa-times" id="deleteTimer"></i></button>'.PHP_EOL;
              echo '</span></a>';
          }
      }
      */
    }

    public function load($request,$response,$args){
      /*
      $timer =array();
      $timer_id= mysqli_real_escape_string($conn, $_POST["id"]);
      $sql = "SELECT action, t_time  FROM `e_timers` WHERE timer_id='$timer_id' ORDER BY id ASC;";
      $result = $conn->query($sql);
      if (!$result) {
          die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
      } else {
          $rowCount = mysqli_num_rows($result);
          while ($row = $result->fetch_assoc()) {
              $timer[] = $row;
          }
      }
      echo json_encode($timer);
      */
    }

    public function update($request,$response,$args){
/*
      $success=0;
      $t_id= mysqli_real_escape_string($conn, $_POST["id"]);
      $act= mysqli_real_escape_string($conn, $_POST["a"]);
      $time= mysqli_real_escape_string($conn, $_POST["t"]);
      $sql = "INSERT INTO e_timers (timer_id,action,t_time)
      VALUES ('$t_id', '$act', '$time')";
      if ($conn->query($sql) === true) {
          $success = $success+1;
      } else {
          die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
      }
      $sql = "UPDATE `active_timers` SET nr_entries = nr_entries + 1 WHERE timer_id ='$t_id'";
      if ($conn->query($sql) === true) {
          $success = $success+1;
      } else {
          die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
      }
      if ($success==2) {
          die('{ "message": "SUCCESS"}');
      } else {
          die('{ "message": "ERROR:"}');
      }
      */
    }

    public function delete($request,$response,$args){
      /*
      $id = mysqli_real_escape_string($conn, $_POST["id"]);
      $sql = "DELETE FROM active_timers WHERE timer_id='$id' AND user_id='$u_id'";
      if ($conn->query($sql) === true) {
          die('{ "message": "SUCCESS"}');
      } else {
          die('{ "message": "ERROR: ' . $sql . $conn->error.'}');
      }
      */
    }

}
