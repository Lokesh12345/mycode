<?php
include"tdb.php";
$cond="where";
if($_GET['type']!='')
{
$cond .=" PROD_TYPE='".$_GET['type']."' and ";
}
if($_GET['fdate']!='')
{
$cond .=" DATE_DELIVER between '".$_GET['fdate']."' and '".$_GET['tdate']."' and ";
}

$result = $conn->query("SELECT sum(price*QUANTITY) as price,sum(QUANTITY) as quantity,PROD_TYPE FROM `daily_delivery` ".$cond." CONS_ID='".$_GET['cid']."' and SUPP_ID='".$_GET['sid']."' group by PROD_TYPE");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"price":"'.$rs["price"].'",';
    $outp .= '"quantity":"'.$rs["quantity"].'",';
    $outp .= '"PROD_TYPE":"'.$rs["PROD_TYPE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>