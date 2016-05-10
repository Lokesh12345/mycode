<?php
include"tdb.php";
$result = $conn->query("SELECT t.sno as sno,t.PROD_TYPE as ptype,t.trans_type as trans_type,t.g_id as gid,t.QUANTITY as QUANTITY,t.DEL_TIME as DEL_TIME,t.DEL_STATUS as status,t.FROM_DATE as fdate,t.TO_DATE as tdate,t.PROD_ID as pid,p.PROD_NAME as pname,p.IMAGE as image FROM daily_transaction t,products p where t.PROD_ID=p.id and t.CONS_ID='".$_GET['id']."' and t.PROD_ID='".$_GET['pid']."' and t.g_id='".$_GET['gid']."' order by t.FROM_DATE asc");
$outp = "";
$old="";
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
$orders['pname'][]=$rs["pname"];
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
$date = ''.$neworder['DEL_TIME'][$a]; 
$date=date('h:i a', strtotime($date));

if ($outp != "") {$outp .= ",";}
    $outp .= '{"ptype":"'.$neworder['ptype'][$a].'",';
    $outp .= '"sno":"'.$neworder['sno'][$a].'",';
	$outp .= '"fdate":"'.$neworder['fdate'][$a].'",';
	$outp .= '"pname":"'.$neworder['pname'][$a].'",';
	$outp .= '"tdate":"'.$neworder['tdate'][$a].'",';
		$outp .= '"gid":"'.$neworder['gid'][$a].'",';

	$outp .= '"QUANTITY":"'.$neworder['QUANTITY'][$a].'",';
	$outp .= '"time":"'.$neworder['DEL_TIME'][$a].'",';
    $outp .= '"image":"'.$neworder['image'][$a].'"}'; 
	

	
}
$outp ='{"records":['.$outp.']}';
$conn->close();

echo($outp);
?>