<?
include './includes/inc_dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT company_id, name FROM `companies` ORDER BY `name`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cnt = 0;
    while($cmp = $result->fetch_assoc()) {
        echo '<option value="'.$cmp["company_id"].'">'.$cmp["name"].'</option>';
        $companies[$cnt][0] = $cmp["company_id"];
        $companies[$cnt][1] = $cmp["name"];
        $cnt=$cnt+1;
    }
}
?>
