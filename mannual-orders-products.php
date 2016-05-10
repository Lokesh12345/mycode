<?php
include"tdb.php";
$result = $conn->query("SELECT b.sno as sno,b.PROD_ID as pid,b.PRICE as price,c.PROD_TYPE as ptype,c.COMP_NAME as comname,c.PROD_NAME as pname,c.IMAGE as image from products c,supplier_products b where b.PROD_ID=c.id and b.STATUS=1 and b.SUPP_ID='".$_GET['id']."'");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"price":"'. $rs["price"].'",';
    $outp .= '"pid":"'.$rs["pid"].'",';
	 $outp .= '"ptype":"'.$rs["ptype"].'",';
	  $outp .= '"comname":"'.$rs["comname"].'",';
	   $outp .= '"pname":"'.$rs["pname"].'",';
    $outp .= '"image":"'. $rs["image"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>