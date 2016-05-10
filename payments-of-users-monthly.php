<?php
include"db.php";
$date=date("Y-m-d");
$datestring=$date.'first day of last month';
$dt=date_create($datestring);
$premonth=$dt->format('m');
$sqlb = "SELECT sum(total) as total,sum(total_paid) as paid FROM consumption_per_month where MONTH(for_month)<MONTH('".$date."')";
	echo $sqlb;
$resultb = $conn->query($sqlb);

if ($resultb->num_rows > 0) {
    // output data of each row
    while($rowb = $resultb->fetch_assoc()) {
        $previous_balance=$rowb['paid']-$rowb['total'];
    }
} else {
   $previous_balance=0;
}

$sqls = "SELECT CONS_ID as id,SUPP_ID as sid,PROD_ID as pid from daily_delivery where MONTH(DATE_DELIVER)='".$premonth."' group by CONS_ID,SUPP_ID,PROD_ID";
$results = $conn->query($sqls);
$all=0;
$quant=0;
if ($results->num_rows > 0) {
    // output data of each row
    while($rows = $results->fetch_assoc()) {
	
$result = $conn->query("SELECT (select sum(curr_month_payment) as paid from payments where MONTH(payed_date)='".$premonth."')as paid,count(d.QUANTITY) as count,sum(d.price) as dprice,s.DICOUNT_NO as dis,(select sum(QUANTITY) from daily_delivery where CONS_ID='".$rows['id']."' and PROD_ID='".$rows['pid']."' and SUPP_ID='".$rows['sid']."' and MONTH(DATE_DELIVER)='".$premonth."') as quan,sum(d.price) as price,d.PROD_ID as PROD_ID,d.DATE_DELIVER as month FROM daily_delivery d,supplier_products s where d.SUPP_ID='".$rows['sid']."' and d.CONS_ID='".$rows['id']."' and d.PROD_ID='".$rows['pid']."' and s.PROD_ID=d.PROD_ID and d.SUPP_ID=s.SUPP_ID and MONTH(d.DATE_DELIVER)='".$premonth."' group by  MONTH(d.DATE_DELIVER) order by d.DATE_DELIVER desc");
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

echo"quanity:".$rs['count']." prid".$rs['PROD_ID']." price".$rs['dprice']." total quantity ".$rs['quan']."<br> ";

$total=$rs['quan']*$rs['price'];

$date ="".$rs["month"];
$paid ="".$rs["paid"];

$mon=$month = date("F",strtotime($date));
$d=date_parse_from_format("Y-m-d",$date);
	$all=$total;
	$balnce=$paid-$all;
   echo "all: ".$all."<br><hr>";
   
}
$datec=date("Y-m-d");
$sql = "INSERT INTO consumption_per_month (cid,sid,total,for_month,created_date,pid,total_paid,monthly_balance,previous_balance)VALUES ('".$rows['id']."','".$rows['sid']."', '".$all."','".$date."','".$datec."','".$rows['pid']."','".$paid."','".$balnce."','".$previous_balance."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
}
?>