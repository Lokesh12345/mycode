<?php
//calculate previous month calculation daily transactions
include"tdb.php";

error_reporting(0);
$users=array();
 $yr=date("Y-m-d");
$result = $conn->query("SELECT name,id from users");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$users[$rs['id']]=$rs['name'];
}
$resultt = $conn->query("SELECT  count(distinct d.PROD_ID) as prds, d.CONS_ID as cons,d.SUPP_ID as sid,d.TO_DATE as TO_DATE,d.FROM_DATE as FROM_DATE,p.IMAGE as IMAGE,d.PROD_TYPE as ptype,d.sno as sno,d.DAY as days,d.DEL_TIME as deltime,d.CRE_DATE as credate,d.QUANTITY as quan,p.PROD_NAME as pname,p.COMP_NAME as COMP_NAME,u.name as uname FROM daily_transaction  d,users  u,products  p where d.DEL_STATUS='Confirm' and d.PROD_ID=p.id and d.SUPP_ID=u.id and d.SUPP_ID='".$_GET['id']."' group by d.CONS_ID");
$outp = "";
while ($rst = $resultt->fetch_array(MYSQLI_ASSOC)) {
$cuter=$rst["cons"];
$date=date("Y-m-d");
$ftday=new DateTime('first day of this month'); 
$firstdate=$ftday->format('Y-m-j');
$result = $conn->query("SELECT count(d.QUANTITY) as count,d.QUANTITY as quan,s.PRICE as price,d.DATE_DELIVER as month FROM daily_delivery d,supplier_products s where s.SUPP_ID='".$_GET['id']."' and CONS_ID='".$cuter."' and s.PROD_ID=d.PROD_ID and d.DATE_DELIVER>='".$firstdate."' group by  MONTH(d.DATE_DELIVER) order by d.DATE_DELIVER desc");
$outpe = "";
$all=0;
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$total=$rs['count']*$rs['price'];
$date ="".$rs["month"];
$mon=$month = date("F",strtotime($date));
$d=date_parse_from_format("Y-m-d",$date);
    if ($outp != "") {$outp .= ",";}
    $outpe .= '{"month":"'.$mon.'",';
    $outpe .= '"price":"'.$rs["price"].'",';
   
	$all=$all+$total;
	 $outpe .= '"total":"'.$all.'"}'; 
   
}

$deltotal=$all;
//get expected payments
include"customer-expected-paymentss.php";

//get sum of
$resu= $conn->query("SELECT sum(curr_month_payment) as payed from `payments` where cid='".$cuter."' and sid='".$_GET['id']."'");
while ($r= $resu->fetch_array(MYSQLI_ASSOC)) {
//total payment the consumer payed
$totalpayed=$r['payed'];
}
$res= $conn->query("SELECT sum(curr_month_payment) as thismonth ,(select Prev_balance from payments where Year(payed_date) = Year(CURRENT_TIMESTAMP)  AND Month(payed_date) = Month(CURRENT_TIMESTAMP) and cid='".$cuter."' and sid='".$_GET['id']."' order by sno desc limit 1)as prevc FROM `payments` WHERE Year(payed_date) = Year(CURRENT_TIMESTAMP)  AND Month(payed_date) = Month(CURRENT_TIMESTAMP) and cid='".$cuter."' and sid='".$_GET['id']."'");
while ($rt= $res->fetch_array(MYSQLI_ASSOC)) {
//total payment the consumer payed for the month
$thismonth=$rt['thismonth'];
$prevc=$rt['prevc'];
}
//calculation of this month
$paid=$thismonth;
//epected calculation
$expected=$final;
//expected balance for the current month total paid - expected 
$balance=$expected-$paid;
//total balance
if($thismonth=='')
{
$thismonth=0;
}
      $preb=$prevc;
	  
	  $yr=date("Y-m-d");
	  $sqlb = "SELECT sum(total) as total,sum(total_paid) as paid FROM consumption_per_month where MONTH(for_month)<=MONTH('".$yr."') and cid='".$cuter."' and sid='".$_GET['id']."'";

$resultb = $conn->query($sqlb);

if ($resultb->num_rows > 0) {
    // output data of each row
    while($rowb = $resultb->fetch_assoc()) {
       $previous_balance=$rowb['paid']-$rowb['total'];
    }
} else {
   $previous_balance=0;
}

 $aller=($thismonth-$expected)+$previous_balance;
 

//old code here test it
$sqlter = "SELECT sum(total) as total,sum(total_paid) as paid FROM consumption_per_month where sid='".$_GET['id']."' and cid='".$rs["cons"]."'";
$resulter = $conn->query($sqlter);

if ($resulter->num_rows > 0) {
    // output data of each row
    while($row = $resulter->fetch_assoc()) {
       $payments=$row['paid']-$row['total'];
    }
}
else
{

$payments=0;
} 
$fdate = date("d-M-Y",strtotime($rs["FROM_DATE"]));
$tdate = date("d-M-Y",strtotime($rs["TO_DATE"]));
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"sid":"'. $rs["sid"].'",';
    $outp .= '"ptype":"'.$rs["ptype"].'",';
	 $outp .= '"sno":"'.$rs["sno"].'",';
	 $outp .= '"days":"'.$rs["days"].'",';
	  $outp .= '"deltime":"'.$rs["deltime"].'",';
	   $outp .= '"credate":"'.$rs["credate"].'",';
	    $outp .= '"payments":"'.$payments.'",';
	    $outp .= '"quan":"'.$rs["quan"].'",';
		 $outp .= '"pname":"'.$rs["pname"].'",';
		 $outp .= '"cid":"'.$cuter.'",';
		  $outp .= '"uname":"'.$users[$cuter].'",';
		   $outp .= '"FROM_DATE":"'.$cuter.'",';
		   $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
		   $outp .= '"prds":"'.$rs["prds"].'",';
		     $outp .= '"TO_DATE":"'.$tdate.'",';
			 $outp .= '"all":"'.$aller.'",';
    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();
$outp=str_replace(",,",",","".$outp);
echo($outp);
?>