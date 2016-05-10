<?php
include"tdb.php";
$sqlzz = "SELECT CONS_ID,PROD_ID,g_id FROM daily_transaction where DEL_STATUS='Confirm'";
$resultz = $conn->query($sqlzz);
if ($resultz->num_rows > 0) {

while($rsr = $resultz->fetch_array(MYSQLI_ASSOC)){
$creid=$rsr['CONS_ID'];
$preid=$rsr['PROD_ID'];
$result = $conn->query("SELECT t.sno as sno,t.PROD_TYPE as ptype,t.DAY as day,t.g_id as gid,t.SUPP_ID as sid,t.trans_type as trans_type,t.QUANTITY as QUANTITY,t.DEL_TIME as DEL_TIME,t.DEL_STATUS as status,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p where t.PROD_ID=p.id and t.CONS_ID='".$creid."' and t.PROD_ID='".$preid."' and t.g_id='".$rsr['g_id']."' order by t.FROM_DATE asc");
$outp = "";
$old="";
$today=date("Y-m-d");


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
$orders['gid'][]=$rs["gid"];
$orders['day'][]=$rs["day"];

}

$count=count($orders['sno']);
$orgtodate=$orders['tdate'][0];
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
$neworder['day'][]=$orders['day'][$i];

$neworder['QUANTITY'][]=$quan;
$neworder['image'][]=$orders['image'][$i];
$neworder['gid'][]=$orders['gid'][$i];

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
$neworder['day'][]=$orders['day'][$i];

$neworder['QUANTITY'][]=$orginal[0];
$neworder['image'][]=$orders['image'][$i];
$neworder['gid'][]=$orders['gid'][$i];

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
$neworder['day'][]=$orders['day'][$i];

$neworder['gid'][]=$orders['gid'][$i];

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
$neworder['gid'][]=$orders['gid'][$i];

$neworder['pname'][]=$orders['pname'][$i];
$neworder['day'][]=$orders['day'][$i];

$neworder['QUANTITY'][]=$orginal[0];
$neworder['image'][]=$orders['image'][$i];
$neworder['DEL_TIME'][]=$orders['DEL_TIME'][$i];
}
}
}
}
}
print_r($neworder);
$count=count($neworder['sno'])-1;
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
	$sql = "SELECT PRICE,DICOUNT_NO,COMP_NAME FROM supplier_products where SUPP_ID='".$neworder['pname'][$a]."' and PROD_ID='".$preid."' ORDER BY sno desc limit 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
while($rs = $result->fetch_array(MYSQLI_ASSOC)){
$cmpname=$rs['COMP_NAME'];
$suppprice=$rs['PRICE'];
$DICOUNT_NO=$rs['DICOUNT_NO'];
}
}
//getting price ends
//find whether day is between the f and t dates
$sql = "SELECT DAY FROM daily_transaction where '".$today."' between '".$neworder['fdate'][$a]."' and '".$neworder['tdate'][$a]."' and SUPP_ID='".$neworder['pname'][$a]."' and CONS_ID='".$creid."' and g_id='".$neworder['gid'][$a]."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
while($rse = $result->fetch_array(MYSQLI_ASSOC)){
$days=rtrim($rse['DAY'],",");

echo $days."<br>";
if($days=="All Days")
{
//all days code here

//check if today date is in between any cancel order
$sqlrt = "SELECT fdate,tdate FROM cancel_orders where cid='".$creid."' and pid='".$preid."' and sid='".$neworder['pname'][$a]."' and '".$today."'>=fdate and '".$today."'<=tdate";
echo $sqlrt;
$resulttr = $conn->query($sqlrt);

if ($resulttr->num_rows > 0) {
echo"echo yes dude";
} else {

  




$price=$suppprice-$DICOUNT_NO;
$sql = "INSERT INTO daily_delivery (CONS_ID,SUPP_ID,PROD_ID,DATE_DELIVER,TIME_TO_DELIVER,QUANTITY,price,DEL_STATUS,PROD_TYPE,COMP_NAME)VALUES ('".$creid."','".$neworder['pname'][$a]."', '".$preid."','".$today."','".$neworder['DEL_TIME'][$a]."','".$neworder['QUANTITY'][$a]."','".$price."','D','".$orders['ptype'][$a]."','".$cmpname."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


echo"quantity: ".$neworder['QUANTITY'][$a]."<br>";
echo"cid: ".$creid."<br>";
echo"SUPP_ID: ".$neworder['pname'][$a]."<br>";
echo"PROD_ID: ".$preid."<br>";
echo"DATE_DELIVER: ".$today."<br>";
echo"TIME_TO_DELIVER: ".$neworder['DEL_TIME'][$a]."<br>";
echo"QUANTITY: ".$neworder['QUANTITY'][$a]."<br>";
echo"price: ".$suppprice."<br>";
echo"DISCOUNT: ".$DICOUNT_NO."<br>";
echo"this is the day<br>";
//all days code ends here 
echo"echo no dude";
}
}
else
{
$sqlrt = "SELECT fdate,tdate FROM cancel_orders where cid='".$creid."' and pid='".$preid."' and sid='".$neworder['pname'][$a]."' and '".$today."'>=fdate and '".$today."'<=tdate";
echo $sqlrt." hte sec";
$resulttr = $conn->query($sqlrt);

if ($resulttr->num_rows > 0) {
echo"echo yes dude";
} else {

  
//if only days then this
$trime=rtrim($rse['DAY'],",");
echo "days-->".$trime;
$daysall=explode(",",$trime);
echo "today is".$rse['DAY'];
foreach($daysall as $day)
{

$day=ltrim($day,"Only");

if(trim($day)=="Mondays")
{
$da="Monday";
echo "today is monday";
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
echo $da;
$daterer=strtotime($today);
$todayday=date("l",$daterer);
if($da==$todayday)
{
$price=$suppprice-$DICOUNT_NO;
$sql = "INSERT INTO daily_delivery (CONS_ID,SUPP_ID,PROD_ID,DATE_DELIVER,TIME_TO_DELIVER,QUANTITY,price,DEL_STATUS,PROD_TYPE,COMP_NAME)VALUES ('".$creid."','".$neworder['pname'][$a]."', '".$preid."','".$today."','".$neworder['DEL_TIME'][$a]."','".$neworder['QUANTITY'][$a]."','".$price."','D','".$orders['ptype'][$a]."','".$cmpname."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


echo"quantity: ".$neworder['QUANTITY'][$a]."<br>";
echo"cid: ".$creid."<br>";
echo"SUPP_ID: ".$neworder['pname'][$a]."<br>";
echo"PROD_ID: ".$preid."<br>";
echo"DATE_DELIVER: ".$today."<br>";
echo"TIME_TO_DELIVER: ".$neworder['DEL_TIME'][$a]."<br>";
echo"QUANTITY: ".$neworder['QUANTITY'][$a]."<br>";
echo"price: ".$suppprice."<br>";
echo"DISCOUNT: ".$DICOUNT_NO."<br>";
echo"this is the day<br>";
}

}
}
}
}
        
    
} else {
   echo "no";
}
		
	echo"<hr>";
}
}
}
?>