<?php
//calculate previous month calculation daily transactions
include"db.php";
$date=date("Y-m-d");
$ftday=new DateTime('first day of this month'); 
$firstdate=$ftday->format('Y-m-j');
$result = $conn->query("SELECT count(d.QUANTITY) as count,d.QUANTITY as quan,s.PRICE as price,d.DATE_DELIVER as month FROM daily_delivery d,supplier_products s where s.SUPP_ID='".$_GET['sid']."' and CONS_ID='".$_GET['id']."' and s.PROD_ID=d.PROD_ID and d.DATE_DELIVER>='".$firstdate."' group by  MONTH(d.DATE_DELIVER) order by d.DATE_DELIVER desc");
$outp = "";
$all=0;
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$total=$rs['count']*$rs['price'];
$date ="".$rs["month"];
$mon=$month = date("F",strtotime($date));
$d=date_parse_from_format("Y-m-d",$date);
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"month":"'.$mon.'",';
    $outp .= '"price":"'.$rs["price"].'",';
   
	$all=$all+$total;
	 $outp .= '"total":"'.$all.'"}'; 
   
}
$outp ='{"records":['.$outp.']}';
$deltotal=$all;
//get expected payments
include"customer-expected-payments.php";
?>
<?php
//get sum of
$resu= $conn->query("SELECT sum(curr_month_payment) as payed from `payments` where cid='".$_GET['id']."' and sid='".$_GET['sid']."'");
while ($r= $resu->fetch_array(MYSQLI_ASSOC)) {
//total payment the consumer payed
$totalpayed=$r['payed'];
}
$res= $conn->query("SELECT sum(curr_month_payment) as thismonth ,(select Prev_balance from payments where Year(payed_date) = Year(CURRENT_TIMESTAMP)  AND Month(payed_date) = Month(CURRENT_TIMESTAMP) and cid='".$_GET['id']."' and sid='".$_GET['sid']."' order by sno desc limit 1)as prevc FROM `payments` WHERE Year(payed_date) = Year(CURRENT_TIMESTAMP)  AND Month(payed_date) = Month(CURRENT_TIMESTAMP) and cid='".$_GET['id']."' and sid='".$_GET['sid']."'");

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
	  $sqlb = "SELECT sum(total) as total,sum(total_paid) as paid FROM consumption_per_month where MONTH(for_month)<=MONTH('".$yr."') and cid='".$_GET['id']."' and sid='".$_GET['sid']."'";

$resultb = $conn->query($sqlb);

if ($resultb->num_rows > 0) {
    // output data of each row
    while($rowb = $resultb->fetch_assoc()) {
       $previous_balance=$rowb['paid']-$rowb['total'];
    }
} else {
   $previous_balance=0;
}
?>


<div class="card">
  <div class="item item-divider">
    <?php echo date("F Y");?> 
  </div>
  <div class="item item-text-wrap">
   
   Previous Balance <span style="float:right"><?php echo $previous_balance;?></span>
   <br><br>
  Current Month Paid Amount <span style="float:right"><?php echo $thismonth;?></span><br>
   Expected Consuption <span style="float:right"><?php echo $expected;?></span><br>
   This month Balance <span style="float:right"><?php echo $thismonth-$expected;?></span>
   
  </div>
  <div class="item item-divider">
   Total out Standing <span style="float:right"><?php echo($thismonth-$expected)+$previous_balance;?></span>
  </div>
</div>


<?php
$sqlte = "SELECT sum(total) as total,for_month,total_paid,monthly_balance,previous_balance FROM consumption_per_month where cid='".$_GET['id']."' and sid='".$_GET['sid']."' group by MONTH(for_month) order by MONTH(for_month) desc";
$resultte = $conn->query($sqlte);

if ($resultte->num_rows > 0) {
    // output data of each row
    while($rowte = $resultte->fetch_assoc()) {
	$previous_balance=$rowte['previous_balance'];
      ?>
	  <div class="card">
  <div class="item item-divider">
    <?php echo date("F Y",strtotime($rowte['for_month']));?> 
  </div>
  <div class="item item-text-wrap">
   
   Paid amount <span style="float:right"><?php echo $rowte['total_paid'];?></span><br>
   Total Consuption <span style="float:right"><?php echo $rowte['total'];?></span><br>
      Monthly Total  <span style="float:right"><?php echo $rowte['monthly_balance'];?></span><br><br>
  Previous Balance <span style="float:right"><?php echo $previous_balance;?></span>
  </div>
  <div class="item item-divider">
   Total <span style="float:right"><?php echo $previous_balance+$rowte['monthly_balance'];?></span>
  </div>
</div><br><br>
	  <?php
    }
} else {
    
}
?>






















<?php
?>