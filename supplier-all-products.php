<?php
include"tdb.php";
$result = $conn->query("SELECT PROD_ID FROM `supplier_products` where `SUPP_ID`='".$_GET['id']."'");
$proid=array();
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$proid[]=$rs["PROD_ID"];
}
$pe='';
foreach($proid as $ee)
{
$pe .="'".$ee."',";
}
$pe=rtrim($pe,",'");
$pe=ltrim($pe,"'");
$result = $conn->query("SELECT * FROM `products` where id not in('".$pe."') and status='1' order by id desc");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"id":"'. $rs["id"].'",';
    $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
	 $outp .= '"PROD_NAME":"'.$rs["PROD_NAME"].'",';
	 $outp .= '"PROD_TYPE":"'.$rs["PROD_TYPE"].'",';
	  $outp .= '"status":"'.$rs["status"].'",';

    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>