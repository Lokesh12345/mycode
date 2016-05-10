<?php
include"tdb.php";
$result = $conn->query("SELECT t.sno as sno,t.PROD_TYPE as ptype,t.SUPP_ID,t.QUANTITY as QUANTITY,t.DEL_TIME as DEL_TIME,t.DEL_STATUS as status,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p where t.PROD_ID=p.id and t.CONS_ID='".$_GET['id']."' and t.trans_type='Orginal' and t.DEL_STATUS='Confirm' group by t.PROD_ID");
$outp = "";

while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

    if ($outp != "") {$outp .= ",";}
    $outp .= '{"ptype":"'. $rs["ptype"].'",';
    $outp .= '"sno":"'.$rs["SUPP_ID"].'",';
	$outp .= '"fdate":"'.$rs["fdate"].'",';
	$outp .= '"pname":"'.$rs["pname"].'",';
	$outp .= '"tdate":"'.$rs["tdate"].'",';
	$outp .= '"QUANTITY":"'.$rs["QUANTITY"].'",';
	$outp .= '"pid":"'.$rs["pid"].'",';
	$outp .= '"s":"'.$rs["sno"].'",';
    $outp .= '"image":"'. $rs["image"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>