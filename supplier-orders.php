<?php
include"tdb.php";
$users=array();
$result = $conn->query("SELECT name,id from users");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$users[$rs['id']]=$rs['name'];
}
$result = $conn->query("SELECT  count(distinct d.PROD_ID) as prds, d.CONS_ID as cons,d.SUPP_ID as sid,d.TO_DATE as TO_DATE,d.FROM_DATE as FROM_DATE,p.IMAGE as IMAGE,d.PROD_TYPE as ptype,d.sno as sno,d.DAY as days,d.DEL_TIME as deltime,d.CRE_DATE as credate,d.QUANTITY as quan,p.PROD_NAME as pname,p.COMP_NAME as COMP_NAME,u.name as uname FROM daily_transaction  d,users  u,products  p where d.PROD_ID=p.id and d.SUPP_ID=u.id and d.DEL_STATUS='Confirm' and d.SUPP_ID='".$_GET['id']."' group by d.CONS_ID ");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$fdate = date("d-M-Y",strtotime($rs["FROM_DATE"]));
$tdate = date("d-M-Y",strtotime($rs["TO_DATE"]));
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"sid":"'. $rs["sid"].'",';
    $outp .= '"ptype":"'.$rs["ptype"].'",';
	 $outp .= '"sno":"'.$rs["sno"].'",';
	 $outp .= '"days":"'.$rs["days"].'",';
	  $outp .= '"deltime":"'.$rs["deltime"].'",';
	   $outp .= '"credate":"'.$rs["credate"].'",';
	    $outp .= '"quan":"'.$rs["quan"].'",';
		 $outp .= '"pname":"'.$rs["pname"].'",';
		 $outp .= '"cid":"'.$rs["cons"].'",';
		  $outp .= '"uname":"'.$users[$rs["cons"]].'",';
		   $outp .= '"FROM_DATE":"'.$fdate.'",';
		   $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
		   $outp .= '"prds":"'.$rs["prds"].'",';
		     $outp .= '"TO_DATE":"'.$tdate.'",';
    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>