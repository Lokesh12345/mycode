<?php
include"tdb.php";
$users=array();
$result = $conn->query("SELECT name,id from users");

while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$users[$rs['id']]=$rs['name'];
}

$quantity=array();
$result = $conn->query("SELECT sno,QUANTITY from daily_transaction");
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$quantity[$rs['sno']]=$rs['QUANTITY'];
}
$orginalquantity=current($quantity);
$result = $conn->query("SELECT d.CONS_ID as cons,d.SUPP_ID as sid,d.TO_DATE as TO_DATE,d.trans_type as trans_type,d.FROM_DATE as FROM_DATE,p.IMAGE as IMAGE,d.PROD_TYPE as ptype,d.sno as sno,d.DAY as days,d.DEL_TIME as deltime,d.CRE_DATE as credate,d.QUANTITY as quan,p.PROD_NAME as pname,p.COMP_NAME as COMP_NAME,u.name as uname,u.MOBILE as MOBILE FROM daily_transaction  d,users  u,products  p where d.PROD_ID=p.id and d.SUPP_ID=u.id and d.DEL_STATUS='NEW' and d.SUPP_ID='".$_GET['id']."'");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$fdate = date("d-M-Y",strtotime($rs["FROM_DATE"]));
$tdate = date("d-M-Y",strtotime($rs["TO_DATE"]));
if($rs["trans_type"]=='Orginal')
{
$quantity=$rs["quan"];
$type="New Order";
$orginal="";
}
else if($rs["trans_type"]=='Added')
{
$quantity=$rs["quan"];
$type="Additional";
$orginal="(".$orginalquantity.")";
}
else if($rs["trans_type"]=='Cancel')
{
$quantity=$orginalquantity-$rs["quan"];
$type="Cancel";
$orginal="(".$orginalquantity.")";
}
else if($rs["trans_type"]=='new')
{
$quantity=$orginalquantity;
$type="New Order";
$orginal="(".$orginalquantity.")";
}



    if ($outp != "") {$outp .= ",";}
    $outp .= '{"sid":"'. $rs["sid"].'",';
    $outp .= '"ptype":"'.$rs["ptype"].'",';
	 $outp .= '"sno":"'.$rs["sno"].'",';
	 $outp .= '"days":"'.rtrim($rs["days"],",").'",';
	  $outp .= '"deltime":"'.$rs["deltime"].'",';
	   $outp .= '"credate":"'.$rs["credate"].'",';
	    $outp .= '"quan":"'.$rs["quan"].'",';
		  $outp .= '"trans_type":"'.$rs["trans_type"].'",';
		   $outp .= '"MOBILE":"'.$rs["MOBILE"].'",';
		  $outp .= '"quantity":"'.$quantity.'",';
		 $outp .= '"pname":"'.$rs["pname"].'",';
		  $outp .= '"uname":"'.$users[$rs["cons"]].'",';
		   $outp .= '"FROM_DATE":"'.$fdate.'",';
		   $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
		     $outp .= '"TO_DATE":"'.$tdate.'",';
			 $outp .= '"Orginalquan":"'.$orginal.'",';
			 $outp .= '"type":"'.$type.'",';
    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>