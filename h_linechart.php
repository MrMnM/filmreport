<?
header("content-type: application/x-javascript");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_sessionhandler_ajax.php';

if (!empty($_GET["y"])&&!empty($_GET["m"])) {
    $s_year=$_GET["y"];
    $s_month=$_GET["m"];
}else{
    die('{ "message": "ERROR: KEINE DATEN ANGEGEBEN}');
}

include './includes/inc_dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT tot_money, p_end FROM `projects` WHERE user_id='$u_id';";
$result = $conn->query($sql);
?>
$(function() {
    Morris.Area({
        element: 'morris-area-chart',
        data: [{
<?
if ($result->num_rows > 0) {
    $totalMoney = array(0,0,0,0,0,0,0,0,0,0,0,0);
    while($row = $result->fetch_assoc()) {
        $money = $row["tot_money"];
        $endmonth = $row["p_end"];
        $date = DateTime::createFromFormat('Y-m-d', $endmonth);
        $month = $date->format('n');
        $year = $date->format('Y');
        if ($year==$s_year) {
                for ($mcount=0; $mcount<13 ; $mcount++) {
                    if(($month-1)==$mcount){
                        $totalMoney[$mcount]=$totalMoney[$mcount]+$money;
                }
            }
        }
    }
}
for ($i=0; $i < $s_month; $i++) {
    $m = sprintf("%02d", ($i+1));
    echo 'period: \''.$s_year.'-'.$m.'\','.PHP_EOL;
    echo 'Pay:'. $totalMoney[$i].PHP_EOL;
    if ($i<($s_month-1)) {
        echo '}, {'.PHP_EOL;
        }else{
            echo '}],'.PHP_EOL;
        }
}

?>
        xkey: 'period',
        ykeys: ['Pay'],
        labels: ['Einnahmen'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });
});
