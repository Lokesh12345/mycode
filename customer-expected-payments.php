<?php
include"tdb.php";
$final=0;
$resultse = $conn->query("select PROD_ID,g_id from daily_transaction where CONS_ID='".$_GET['id']."' and DEL_STATUS='Confirm' and trans_type='Orginal'");
while ($rze = $resultse->fetch_array(MYSQLI_ASSOC)) {
$result = $conn->query("SELECT t.sno as sno,t.PROD_TYPE as ptype,t.trans_type as trans_type,t.DAY as day,t.g_id as gid,t.QUANTITY as QUANTITY,t.DEL_TIME as DEL_TIME,t.DEL_STATUS as status,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p where t.PROD_ID=p.id and t.CONS_ID='".$_GET['id']."' and t.PROD_ID='".$rze['PROD_ID']."' and t.g_id='".$rze['g_id']."' order by t.FROM_DATE asc");
$outp = "";
$old="";
$orginal=array();
$from_date=array();
$todate=array();
$orders=array();
$tdateplusone=1;
$no = 0;

$neworder=array();
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$results = $conn->query("select PRICE,DICOUNT_NO from supplier_products where  PROD_ID='".$rs['pid']."' order by sno desc limit 1");
while ($rss = $results->fetch_array(MYSQLI_ASSOC)) {
$cost=$rss['PRICE']-$rss['DICOUNT_NO'];
}



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
$orders['pname'][]=$rs["pname"];
$orders['day'][]=$rs["day"];

$orders['QUANTITY'][]=$rs["QUANTITY"];
$orders['image'][]=$rs["image"];
$orders['gid'][]=$rs["gid"];

$orders['trans_type'][]=$rs["trans_type"];
$orders['DEL_TIME'][]=$rs["DEL_TIME"];
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
$count=count($neworder['sno'])-1;
$outp = "";
for($a=0;$a<=$count;$a++)
{
//replace all , in all days at the end
$days=rtrim($neworder['day'][$a],",");

//get the last day of the month
$lday=new DateTime('last day of this month'); 
$ldday=$lday->format('Y-m-d');

//get this month first date
$ftday=new DateTime('first day of this month'); 
$firstdate=$ftday->format('Y-m-d');

//if tdate is greater than this month floor to this month end 
if($neworder['tdate'][$a]>$ldday)
{
$tdate=$ldday;
}
else
{
$tdate=$neworder['tdate'][$a];
}



//if fdate is less than this month floor to this month first 
if($neworder['fdate'][$a]<$firstdate)
{
$fdate=$firstdate;
}
else
{
$fdate=$neworder['fdate'][$a];
}
$quan=$neworder['QUANTITY'][$a];


//check if all days or not
if($days=="All Days")
{

//convert date to format to find the differnce between 2 dates and add+1
$date1=date_create($fdate);
$date2=date_create($tdate);
$diff=date_diff($date1,$date2);
$diff=$diff->format("%a")+1;


$final=$final+$diff*($cost*$quan);


$month=date("m");
$cfdate=array();
$ctdate=array();
$sqler = "SELECT fdate,tdate FROM cancel_orders where MONTH(fdate)>={$month}";
$resulter = $conn->query($sqler);

if ($result->num_rows > 0) {
    // output data of each row
    while($rowrr = $resulter->fetch_assoc()) {
$cfdate[]=$rowrr['fdate'];
$ctdate[]=$rowrr['tdate'];
    }
}



$begin = new DateTime('2016-05-06');
$end = new DateTime( '2016-05-31' );

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

foreach ( $period as $dt )
{
  $f=$dt->format( "l Y-m-d H:i:s\n" );
}



}
//if condition for checking if all days or not ends
//if days are not all days else loop starts 
else
{
$daysall=explode(",",$days);

foreach($daysall as $day)
{ 
$day=ltrim($day,"Only");
if(trim($day)=="Mondays")
{
$da=1;
}
if(trim($day)=="Tuesdays")
{
$da=2;
}
if(trim($day)=="Wednesdays")
{
$da=3;
}
if(trim($day)=="Thursdays")
{
$da=4;
}
if(trim($day)=="Fridays")
{
$da=5;
}
if(trim($day)=="Saturdays")
{
$da=6;
}
if(trim($day)=="Sundays")
{
$da=7;
}

if($fdate>$tdate)
{
$start = new DateTime(''.$fdate);
$end = new DateTime(''.$tdate);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($start, $interval, $end);
foreach ($period as $dt)
{
echo $dt->format('N');
    if ($dt->format('N') == $da)
    {
        $no++;
    }
}
}else
{
$no++;
}



}

$final=$final+$no*($cost*$quan);

}
//else loop condition ends if it is not all days

	
}
}
?>