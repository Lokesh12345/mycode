<?php
include"tdb.php";
$result = $conn->query("select NAME,id,bys from users where REG_TYPE='M' and bys='".$_GET['id']."'");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"name":"'. $rs["NAME"].'",';
	   $outp .= '"id":"'.$rs["id"].'",';
    $outp .= '"bys":"'. $rs["bys"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>