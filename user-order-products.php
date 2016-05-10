<?php
include"tdb.php";
$result = $conn->query("SELECT (select NAME from users where id=a.bys) as name,b.sno as sno,b.PROD_ID as pid,b.SUPP_ID as sid,b.PRICE as price,c.PROD_TYPE as ptype,c.COMP_NAME as comname,c.PROD_NAME as pname,b.DICOUNT_NO,c.IMAGE as image from products c,users a,supplier_products b where a.bys=b.SUPP_ID and b.PROD_ID=c.id and b.STATUS=1 and a.MOBILE='".$_GET['id']."'");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"name":"'. $rs["name"].'",';
	   $outp .= '"sid":"'.$rs["sid"].'",';
    $outp .= '"pid":"'.$rs["pid"].'",';
	 $outp .= '"price":"'.$rs["price"].'",';
	 	 $outp .= '"DICOUNT_NO":"'.$rs["DICOUNT_NO"].'",';

	 $outp .= '"ptype":"'.$rs["ptype"].'",';
	  $outp .= '"comname":"'.$rs["comname"].'",';
	   $outp .= '"pname":"'.$rs["pname"].'",';
    $outp .= '"image":"'. $rs["image"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>