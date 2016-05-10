<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "meraMilk");

$result = $conn->query("SELECT date,version FROM expire");

$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"date":"'  . $rs["date"] . '",';
    
    $outp .= '"version":"'. $rs["version"]     . '"}'; 
}
$outp .="]";

$conn->close();

echo($outp);
?>
