<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

if (!empty($_GET["y"])&&!empty($_GET["m"])) {
    $s_year=$_GET["y"];
    $s_month=$_GET["m"];
    if (!empty($_GET["m2"])) {
            $e_month=$_GET["m2"];
    }else {
        $e_month=1;
    }
// TODO Secify End and Start Month aso to Move in Graph
}else{
    die('{ "message": "ERROR: KEINE DATEN ANGEGEBEN}');
}

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT tot_money, p_end FROM `projects` WHERE user_id='$u_id';";
$result = $conn->query($sql);

// Count Money
$totalMoney = array_pad(array(0), 12, 0);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $money = $row["tot_money"];
        $endmonth = $row["p_end"];
        $date = DateTime::createFromFormat('Y-m-d', $endmonth);
        $month = $date->format('n');
        $year = $date->format('Y');
        if ($year==$s_year) {
                for ($mcount=0; $mcount<count($totalMoney) ; $mcount++) {
                    if(($month-1)==$mcount){
                        $totalMoney[$mcount]=$totalMoney[$mcount]+$money;
                    }
                }
        }
    }
    $total = 0;
    foreach ($totalMoney as $value) {
        $total = $total+$value;
    }
    echo '{ "mean_month":"'.round($total/$s_month).'" }';

}
