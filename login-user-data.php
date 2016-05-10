<?php
include"tdb.php";
$result = $conn->query("SELECT * FROM users  where MOBILE='".$_GET['id']."' and status='1'");
$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"Name":"'  . $rs["NAME"] . '",';
    $outp .= '"id":"'   . $rs["id"]        . '",';
	 $outp .= '"bys":"'   . $rs["bys"]        . '",';
    $outp .= '"type":"'. $rs["REG_TYPE"]     . '"}'; 
}
$outp .="]";

$conn->close();

echo($outp);
?>