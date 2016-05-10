<?php
include"tdb.php";

$result = $conn->query("SELECT * FROM users where id='".$_GET['id']."' and `REG_TYPE`='D'");
$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"EFFECTIVE_FROM":"'. $rs["EFFECTIVE_FROM"].'",';
	 $outp .= '"EFFECTIVE_TO":"'. $rs["EFFECTIVE_TO"].'",';
    $outp .= '"ACTIVE":"'. $rs["status"].'"}'; 
}
$outp .="]";

$conn->close();

echo($outp);
?>