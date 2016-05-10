<?php
include"tdb.php";
$result = $conn->query("select d.SUPP_ID as sid,u.id,u.NAME from users u,daily_transaction d where d.SUPP_ID=u.id and d.CONS_ID='".$_GET['id']."' group by d.SUPP_ID");
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