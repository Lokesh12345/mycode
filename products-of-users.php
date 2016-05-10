<?php
include"tdb.php";
$result = $conn->query("SELECT PROD_ID,STATUS FROM `supplier_products` where `SUPP_ID`='".$_GET['id']."'");
$proid=array();
$status=array();

while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$proid[]=$rs["PROD_ID"];
$status[$rs["PROD_ID"]]=$rs["STATUS"];
}
$pe='';
foreach($proid as $ee)
{
$pe .="'".$ee."',";
}
$pe=rtrim($pe,",'");
$pe=ltrim($pe,"'");
$result = $conn->query("SELECT * FROM `products` where id  in('".$pe."') order by id desc");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

    if ($outp != "") {$outp .= ",";}
    $outp .= '{"id":"'. $rs["id"].'",';
    $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
	 $outp .= '"PROD_NAME":"'.$rs["PROD_NAME"].'",';
	 $outp .= '"PROD_TYPE":"'.$rs["PROD_TYPE"].'",';
	  $outp .= '"status":"'.$status[$rs["id"]].'",';

    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 

}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>