<?
header("content-type: application/x-javascript");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_sessionhandler_ajax.php';
include './includes/inc_dbconnect.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT company_id, name FROM `companies`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cnt = 0;
    while($cmp = $result->fetch_assoc()) {
        $companies[$cnt][0] = $cmp["company_id"];
        $companies[$cnt][1] = $cmp["name"];
        $cnt=$cnt+1;
    }
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo '{'.PHP_EOL;
echo '"data": ['.PHP_EOL;


$sql = "SELECT project_id, p_start, p_name, p_company, tot_hours, tot_money FROM `projects` WHERE user_id='$u_id';";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $rowCount = mysqli_num_rows($result);
    $counter = 1;
    while($row = $result->fetch_assoc()) {
        echo '['.PHP_EOL;
        echo '"'.$row["p_start"].'",'.PHP_EOL;
        echo '"'.$row["p_name"].'",'.PHP_EOL;
        foreach($companies as $arr){
            if ($arr[0] == $row["p_company"]) {
                echo '"'.$arr[1].'",'.PHP_EOL;
            }
        }
        echo '"'.$row["tot_hours"].'",'.PHP_EOL;
        echo '"'.$row["tot_money"].'",'.PHP_EOL;
        echo '"'.$row["project_id"].'"'.PHP_EOL;
        if ($counter == $rowCount) {
            echo ']'.PHP_EOL; // TODO check if works
        }else{
            echo '],'.PHP_EOL;
        }
        $counter++;

    }
}

echo ']'.PHP_EOL;
echo '}'.PHP_EOL;

/*
    echo 'period: \''.$s_year.'-'.$m.'\','.PHP_EOL;

    [
        "Tiger Nixon",
        "System Architect",
        "Edinburgh",
        "5421",
        "2011\/04\/25",
        "$320,800"
    ],


    $row_count = mysqli_num_rows($result)

*/


?>
