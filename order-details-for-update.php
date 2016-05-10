<?php
include"tdb.php";
$result = $conn->query("SELECT u.NAME as sname,p.COMP_NAME as cmpname,t.DEL_TIME as dtime,t.CONS_ID as cid ,t.QUANTITY as quanity,t.sno as sno,t.day as days,t.PROD_TYPE as ptype,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p,users u where t.PROD_ID=p.id and t.SUPP_ID=u.id and t.sno='".$_GET['id']."'");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$days=rtrim($rs["days"], ",");
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"ptype":"'. $rs["ptype"].'",';
    $outp .= '"sno":"'.$rs["sno"].'",';
	$outp .= '"fdate":"'.$rs["fdate"].'",';
	$outp .= '"pname":"'.$rs["pname"].'",';
	$outp .= '"cid":"'.$rs["cid"].'",';
	$outp .= '"days":"'.$days.'",';
	$outp .= '"tdate":"'.$rs["tdate"].'",';
	$outp .= '"dtime":"'.$rs["dtime"].'",';
	$outp .= '"pid":"'.$rs["pid"].'",';
	$outp .= '"sname":"'.$rs["sname"].'",';
	$outp .= '"quanity":"'.$rs["quanity"].'",';
	$outp .= '"cmpname":"'.$rs["cmpname"].'",';
    $outp .= '"image":"'. $rs["image"].'"}'; 
	
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>