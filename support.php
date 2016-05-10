<?php
include"tdb.php";
$bys="";
$outp = "";
$result = $conn->query("select bys from users where id='".$_GET['id']."'");
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    $bys .=$rs["bys"].",";
}


   $bys=rtrim($bys,",");

$result = $conn->query("select NAME,MOBILE from users where id IN(".$bys.")");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

    if ($outp != "") { $bys .=$rs["bys"].",";$outp .= ",";}
    $outp .= '{"NAME":"'. $rs["NAME"].'",';
    $outp .= '"MOBILE":"'. $rs["MOBILE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>