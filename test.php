<?php

$final=0;
$resultse = $conn->query("select PROD_ID from daily_transaction where CONS_ID='".$cuter."' and DEL_STATUS='Confirm' and trans_type='Orginal'");
while ($rze = $resultse->fetch_array(MYSQLI_ASSOC)) {
$result = $conn->query("SELECT t.sno as sno,t.SUPP_ID as sid,t.DAY as ptype,t.trans_type as trans_type,t.QUANTITY as QUANTITY,t.DEL_TIME as DEL_TIME,t.DEL_STATUS as status,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p where t.PROD_ID=p.id and t.CONS_ID='".$cuter."' and t.DEL_STATUS='Confirm' and t.PROD_ID='".$rze['PROD_ID']."' order by t.FROM_DATE asc");
$old="";
$orginal=array();
$from_date=array();
$todate=array();
$orders=array();
$tdateplusone=1;
$neworder=array();
$cost=0;
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$results = $conn->query("select PRICE,DICOUNT_NO from supplier_products where SUPP_ID='".$rs['sid']."' and PROD_ID='".$rs['pid']."' order by sno desc limit 1");
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
$orders['QUANTITY'][]=$rs["QUANTITY"];
$orders['image'][]=$rs["image"];
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
$count=count($neworder['sno'])-1;
$all=0;
$dday=0;
for($a=0;$a<=$count;$a++)
{
$days=rtrim($neworder['ptype'][$a],",");
if($days=="All Days")
{
$day=new DateTime('last day of this month'); 

$dday=$day->format('Y-m-j');
$date = ''.$neworder['DEL_TIME'][$a]; 
$date=date('h:i a', strtotime($date));
$date1=date_create("".$neworder['fdate'][$a]);
$date2=date_create("".$neworder['tdate'][$a]);
if($neworder['tdate'][$a]>$dday)
{

$date2=date_create("".$dday);
}
$diff=date_diff($date1,$date2);
$subcost=$neworder['QUANTITY'][$a]*$cost;
$modcost=$subcost*($diff->format("%a")+1);
$all=$all+$modcost;
}
else
{

$day=new DateTime('last day of this month'); 

$dday=$day->format('Y-m-j');
$ftday=new DateTime('first day of this month'); 

$firstdate=$ftday->format('Y-m-j');
$trime=rtrim($days,",");
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
$no = 0;
$date = date('Y-m-d', strtotime('+1 month'));

// One month from a specific date
$ndate = date('Y-m-d', strtotime('+1 month', strtotime($firstdate)));
if($neworder['tdate'][$a]>=$ndate)
{
$ttdate=$dday;

}
else
{
$ttdate=$neworder['tdate'][$a];
}
if($neworder['fdate'][$a]<=$firstdate)
{
$ftdate=date("Y-m-d");
}
else
{
$ftdate=$neworder['fdate'][$a];
}

$start = new DateTime($ftdate);
$end   = new DateTime($ttdate);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($start, $interval, $end);
foreach ($period as $dt)
{
    if ($dt->format('N') == $da)
    {
        $no++;
    }
}
$date = ''.$neworder['DEL_TIME'][$a]; 
$date=date('h:i a', strtotime($date));
$subcost=$neworder['QUANTITY'][$a]*$cost;
$modcost=$subcost*$no;
$all=$all+$modcost;
}
//foreach ends
}
}
$outpe = "";
if ($outpe != "") {$outpe .= ",";}
$outpe.= '{"Total":"'.$all.'"}';
$outpe ='{"records":['.$outp.']}';
$final=$all+$final;
}
?>