<?php
include"tdb.php";
$result = $conn->query("select d.CONS_ID as sid,u.id,u.NAME from users u,daily_delivery d where d.CONS_ID=u.id and d.SUPP_ID='".$_GET['id']."' group by d.CONS_ID");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"sid":"'. $rs["sid"].'",';
    $outp .= '"id":"'.$rs["id"].'",';
    $outp .= '"NAME":"'. $rs["NAME"].'"}'; 

   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>