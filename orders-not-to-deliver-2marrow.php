<?php
include"tdb.php";
$today=date("Y-m-d");
$add=date_create($today);
$add=date_add($add,date_interval_create_from_date_string("1 days"));
$date=date_format($add,"Y-m-d");
$result = $conn->query("select p.PROD_NAME,p.image,p.COMP_NAME,p.IMAGE,u.NAME,u.HOUSE_NAME,u.HOUSE_NO,u.MOBILE,ca.cid,ca.sid from products p,users u,cancel_orders ca where ca.cid=u.id and  ca.sid='".$_GET['id']."' and ca.pid=p.id and '".$date."' >= ca.fdate  and '".$date."'<=ca.tdate");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

    if ($outp != "") {$outp .= ",";}
    $outp .= '{"NAME":"'.$rs["NAME"].'",';

		   $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
		     $outp .= '"IMAGE":"'.$rs["IMAGE"].'",';
			   $outp .= '"HOUSE_NAME":"'.$rs["HOUSE_NAME"].'",';
			     $outp .= '"HOUSE_NO":"'.$rs["HOUSE_NO"].'",';
				 $outp .= '"MOBILE":"'.$rs["MOBILE"].'",';
		   
		     
    $outp .= '"PROD_NAME":"'. $rs["PROD_NAME"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>