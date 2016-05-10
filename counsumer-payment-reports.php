<?php
include"db.php";
$fdate=$_GET['fdate'];
$tdate=$_GET['tdate'];

$sql = "select * from payments where cid='".$_GET['cid']."' and sid='".$_GET['sid']."' and payed_date between '".$fdate."' and '".$tdate."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
?>
<div class="item item-input-inset">
    <label class="item-input-wrapper">
      <input type="text" placeholder="Search" ng-model="test">
    </label>
   
  </div>
 <div class="row">
  <div class="col col-25" style="background-color:#f2f2f2"><center>S.No</center></div>
    <div class="col col-25" style="background-color:#f2f2f2"><center>Date</center></div>
  <div class="col col-25" style="background-color:#f2f2f2"><center>Payed Amount</center></div>
  <div class="col col-25" style="background-color:#f2f2f2"><center>Previous bl</center></div>
</div>
<?php
$i=1;
    // output data of each row
    while($rowte = $result->fetch_assoc()) {
  ?>
 <div class="list">

  <div class="row" ng-repeat="x in names | filter : test">
  <div class="col col-25" style="background-color: #0099cc;color:white"><center><?php echo $i;?></center></div>
    <div class="col col-25" style="background-color:#00ccff;color:white"><center><?php echo $rowte['payed_date'];?></center></div>
  <div class="col col-25" style="background-color: #0099cc;color:white"><center><?php echo $rowte['curr_month_payment'];?></center></div>
  <div class="col col-25" style="background-color: #00ccff;color:white"><center><?php 
  if($rowte['Prev_balance']=='undefined')
  {
  echo"0";
  }else{
  
  echo $rowte['Prev_balance'];
  }?></center></div>
</div>
 </div>
<?php
  $i++;
    }
} else {
    echo "Sorry No Results found for the selection";
}

?>