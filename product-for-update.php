<?php
include"tdb.php";

$result = $conn->query("SELECT * FROM products where id='".$_GET['id']."'");
$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"id":"'. $rs["id"].'",';
    $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
	 $outp .= '"PROD_NAME":"'.$rs["PROD_NAME"].'",';
	 $outp .= '"PROD_TYPE":"'.$rs["PROD_TYPE"].'",';
	 $outp .= '"status":"'.$rs["status"].'",';

    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 
}
$outp .="]";

$conn->close();

echo($outp);
?>