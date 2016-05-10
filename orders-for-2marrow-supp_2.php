<?php
include"db.php";
$sqlzz = "SELECT CONS_ID,PROD_ID FROM daily_transaction group by CONS_ID,PROD_ID";
$resultz = $conn->query($sqlzz);
if ($resultz->num_rows > 0) {

while($rsr = $resultz->fetch_array(MYSQLI_ASSOC)){
$creid=$rsr['CONS_ID'];
$preid=$rsr['PROD_ID'];
$result = $conn->query("SELECT t.sno as sno,t.PROD_TYPE as ptype,t.SUPP_ID as sid,t.trans_type as trans_type,t.QUANTITY as QUANTITY,t.DEL_TIME as DEL_TIME,t.DEL_STATUS as status,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p where t.PROD_ID=p.id and t.DEL_STATUS='Confirm' and t.CONS_ID='".$creid."' and t.PROD_ID='".$preid."' order by t.FROM_DATE asc");

$outp = "";
$old="";
$today=date("Y-m-d");
$today=date_create($today);
$today=date_add($today,date_interval_create_from_date_string("1 days"));
$today=date_format($today,"Y-m-d");

$today = date($today,strtotime($today. "+1 days"));
$orginal=array();
$from_date=array();
$todate=array();
$orders=array();
$tdateplusone=1;
$neworder=array();
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$date = ''.$rs["DEL_TIME"]; 
$date=date('h:i a', strtotime($date));
$old=$rs["QUANTITY"];
$orginal[]=$rs["QUANTITY"];
$from_date[]=$rs["fdate"];
$todate[]=$rs["tdate"];
$orders['sno'][]=$rs["sno"];
$orders['fdate'][]=$rs["fdate"];
$orders['tdate'][]=$rs["tdate"];
$orders['ptype'][]=$rs["ptype"];
$orders['pname'][]=$rs["sid"];//supplier id assigned to pname
$orders['QUANTITY'][]=$rs["QUANTITY"];
$orders['image'][]=$rs["image"];
$orders['trans_type'][]=$rs["trans_type"];
$orders['DEL_TIME'][]=$rs["DEL_TIME"];
}
$t=(isset($orders['sno']) ? $orders['sno'] : null);
$count=count($t);
$r=(isset($orders['tdate'][0]) ? $orders['tdate'][0] : null);

$orgtodate=$r;
for($i=0;$i<$count;$i++)
{
$j=$i;
if($orders['trans_type'][$i]=='Cancel')
{
$quan=$orginal[0]-$orders['QUANTITY'][$i];
}
else
{
$quan=$orders['QUANTITY'][$i];
}
if(($orders['tdate'][$i])>=($orders['fdate'][$j]))
{
$j=$j+1;
$k=$j-1;
if(!empty($orders['tdate'][$j]))
{
$min1=date_create($orders['fdate'][$j]);
$min1=date_sub($min1,date_interval_create_from_date_string("1 days"));
$bef=date_format($min1,"Y-m-d");
$neworder['sno'][]=$orders['sno'][$i];
$neworder['fdate'][]=$orders['fdate'][$i];
$neworder['tdate'][]=$bef;
$neworder['ptype'][]=$orders['ptype'][$i];
$neworder['pname'][]=$orders['pname'][$i];
$neworder['QUANTITY'][]=$quan;
$neworder['image'][]=$orders['image'][$i];
$neworder['DEL_TIME'][]=$orders['DEL_TIME'][$i];

if($i!=0)
{

if($orders['tdate'][$i]<$orders['fdate'][$j] )
{
$adde=$orders['fdate'][$j];
$datetime1 = new DateTime($orders['tdate'][$i]);
$datetime2 = new DateTime($adde);
$interval = $datetime1->diff($datetime2);
$inte=$interval->format('%R%a')-1;
$min1=date_create($orders['fdate'][$j]);
$min1=date_sub($min1,date_interval_create_from_date_string($inte." days"));
$bef=date_format($min1,"Y-m-d");
$neworder['sno'][]=$orders['sno'][$i];
$neworder['fdate'][]=$bef;
$neworder['tdate'][]=$bef;
$neworder['ptype'][]=$orders['ptype'][$i];
$neworder['pname'][]=$orders['pname'][$i];
$neworder['QUANTITY'][]=$orginal[0];
$neworder['image'][]=$orders['image'][$i];
$neworder['tdate'][$k]=$orders['tdate'][$i];
$neworder['DEL_TIME'][]=$orders['DEL_TIME'][$i];

}
}
}
else
{

$neworder['sno'][]=$orders['sno'][$i];
$neworder['fdate'][]=$orders['fdate'][$i];
$neworder['tdate'][]=$orders['tdate'][$k];
$neworder['ptype'][]=$orders['ptype'][$i];
$neworder['pname'][]=$orders['pname'][$i];
$neworder['QUANTITY'][]=$orders['QUANTITY'][$i];
$neworder['image'][]=$orders['image'][$i];
$neworder['DEL_TIME'][]=$orders['DEL_TIME'][$i];
$add=date_create($orders['tdate'][$k]);
$add=date_add($add,date_interval_create_from_date_string("1 days"));
$add=date_format($add,"Y-m-d");
//last row//
$co=count($neworder['DEL_TIME']);

if($co!=1)
{
if($neworder['tdate'][$k]!=$orgtodate)
{
$neworder['sno'][]=$orders['sno'][$i];
$neworder['fdate'][]=$add;
$neworder['tdate'][]=$orgtodate;
$neworder['ptype'][]=$orders['ptype'][$i];
$neworder['pname'][]=$orders['pname'][$i];
$neworder['QUANTITY'][]=$orginal[0];
$neworder['image'][]=$orders['image'][$i];
$neworder['DEL_TIME'][]=$orders['DEL_TIME'][$i];
}
}
}
}
}
$re=(isset($neworder['sno']) ? $neworder['sno'] : null);
$count=count($re)-1;
$outp = "";
for($a=0;$a<=$count;$a++)
{
$date = ''.$neworder['DEL_TIME'][$a]; 
$date=date('h:i a', strtotime($date));

if ($outp != "") {$outp .= ",";}
    $outp .= '{"ptype":"'.$neworder['ptype'][$a].'",';
    $outp .= '"sno":"'.$neworder['sno'][$a].'",';
	$outp .= '"fdate":"'.$neworder['fdate'][$a].'",';
	$outp .= '"pname":"'.$neworder['pname'][$a].'",';
	$outp .= '"tdate":"'.$neworder['tdate'][$a].'",';
	$outp .= '"QUANTITY":"'.$neworder['QUANTITY'][$a].'",';
	$outp .= '"time":"'.$neworder['DEL_TIME'][$a].'",';
    $outp .= '"image":"'.$neworder['image'][$a].'"}'; 
	//getting the price
	$sql = "SELECT PRICE,DICOUNT_NO FROM supplier_products where SUPP_ID='".$neworder['pname'][$a]."' and PROD_ID='".$preid."' ORDER BY sno desc limit 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
while($rs = $result->fetch_array(MYSQLI_ASSOC)){
$suppprice=$rs['PRICE'];
$DICOUNT_NO=$rs['DICOUNT_NO'];
}
}
$outpt = "";
//getting price ends
//find whether day is between the f and t dates
$sql = "SELECT DAY FROM daily_transaction where '".$today."' between '".$neworder['fdate'][$a]."' and '".$neworder['tdate'][$a]."' limit 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
while($rs = $result->fetch_array(MYSQLI_ASSOC)){
$days=rtrim($rs['DAY'],",");
if($days=="All Days")
{
//all days code here

//check if today date is in between any cancel order
$sqlrt = "SELECT fdate,tdate FROM cancel_orders where cid='".$creid."' and pid='".$preid."' and sid='".$neworder['pname'][$a]."' and '".$today."'>=fdate and '".$today."'<=tdate";
$resulttr = $conn->query($sqlrt);

if ($resulttr->num_rows > 0) {
echo"echo yes dude";
} else {

$sql = "SELECT u.NAME,u.COLONY,u.AREA,u.HOUSE_NAME,u.HOUSE_NO,u.MOBILE,u.NEXT_DOOR,p.PROD_NAME,p.IMAGE,p.COMP_NAME FROM users u,products p where u.id='".$creid."' and p.id='".$preid."' ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       $cname=$row['NAME'];
	    $COLONY=$row['COLONY'];
		 $AREA=$row['AREA'];
		
		  $HOUSE_NAME=$row['HOUSE_NAME'];
		   $PROD_NAME=$row['PROD_NAME'];
		    $NEXT_DOOR=$row['NEXT_DOOR'];
			$HOUSE_NO=$row['HOUSE_NO'];
			$MOBILE=$row['MOBILE'];
			$IMAGE=$row['IMAGE'];
			$COMP_NAME=$row['COMP_NAME'];
    }


   echo'<div class="list">
 <div class="item item-avatar item-button-right" ng-repeat="x in names | filter : test ">
 <img src="'.$IMAGE.'">
	 <h2>'.$cname.'</h2>
	  <p>'.$PROD_NAME.'</p>
	  <p>Quantity : '.$neworder['QUANTITY'][$a].'</p>
      <p> '.$COMP_NAME.' / '.$neworder['DEL_TIME'][$a].' <br></p>
	  <p>House Name : '.$HOUSE_NAME.' <br>House No:  '.$HOUSE_NO.' <br></p>
	   <p>Area : '.$AREA.' <br>Colony:  '.$COLONY.' <br></p>
    <a href="tel:'.$MOBILE.'"class="button button-positive">
      <i class="icon ion-ios-telephone"></i>
    </a>
  </div>
</div>';
} 
}
}
else
{
$sqlrt = "SELECT fdate,tdate FROM cancel_orders where cid='".$creid."' and pid='".$preid."' and sid='".$neworder['pname'][$a]."' and '".$today."'>=fdate and '".$today."'<=tdate";

$resulttr = $conn->query($sqlrt);

if ($resulttr->num_rows > 0) {

} else {

  
//if only days then this
$trime=rtrim($rs['DAY'],",");
$daysall=explode(",",$trime);
foreach($daysall as $day)
{ 
$day=ltrim($day,"Only");
if(trim($day)=="Mondays")
{
$da="Monday";
}
if(trim($day)=="Tuesdays")
{
$da="Tuesday";
}
if(trim($day)=="Wednesdays")
{
$da="Wednesday";
}
if(trim($day)=="Thursdays")
{
$da="Thursday";
}
if(trim($day)=="Fridays")
{
$da="Friday";
}
if(trim($day)=="Saturdays")
{
$da="Saturday";
}
if(trim($day)=="Sundays")
{
$da="Sunday";
}
}

$daterer=strtotime($today);
$todayday=date("l",$daterer);
if($da==$todayday)
{
$price=$suppprice-$DICOUNT_NO;



$sql = "SELECT u.NAME,u.COLONY,u.AREA,u.HOUSE_NAME,u.HOUSE_NO,u.MOBILE,u.NEXT_DOOR,p.PROD_NAME,p.IMAGE,p.COMP_NAME FROM users u,products p where u.id='".$creid."' and p.id='".$preid."' ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       $cname=$row['NAME'];
	    $COLONY=$row['COLONY'];
		 $AREA=$row['AREA'];
		
		  $HOUSE_NAME=$row['HOUSE_NAME'];
		   $PROD_NAME=$row['PROD_NAME'];
		    $NEXT_DOOR=$row['NEXT_DOOR'];
			$HOUSE_NO=$row['HOUSE_NO'];
			$MOBILE=$row['MOBILE'];
			$IMAGE=$row['IMAGE'];
			$COMP_NAME=$row['COMP_NAME'];
    }
} 
$price=$suppprice-$DICOUNT_NO;
   echo'
   	
   <div class="list">
 <div class="item item-avatar item-button-right" ng-repeat="x in names | filter : test ">
 <img src="'.$IMAGE.'">
	 <h2>'.$cname.'</h2>
	  <p>'.$PROD_NAME.'</p>
	  <p>Quantity : '.$neworder['QUANTITY'][$a].'</p>
      <p> '.$COMP_NAME.' / '.$neworder['DEL_TIME'][$a].' <br></p>
	  <p>House Name : '.$HOUSE_NAME.' <br>House No:  '.$HOUSE_NO.' <br></p>
	   <p>Area : '.$AREA.' <br>House No:  '.$COLONY.' <br></p>
    <a href="tel:'.$MOBILE.'"class="button button-positive">
      <i class="icon ion-ios-telephone"></i>
    </a>
  </div>
</div>';
}
}
}
}
        
    
} 
		
}
}
}
?><?php
?>