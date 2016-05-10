<?php
include"tdb.php";
$result = $conn->query("SELECT detail,status,sno from notifications where sid='".$_GET['id']."' order by sno desc");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$b = html_entity_decode($rs["detail"]);
    if ($outp != "") {$outp .= ",";}
   $outp .= '{"status":"'. $rs["status"].'",';
   	$outp .= '"sno":"'.$rs["sno"].'",';

       $outp .= '"detail":"'.$b.'"}'; 
	
}
$outp ='{"records":['.$outp.']}';



$conn->close();

echo($outp);
?>