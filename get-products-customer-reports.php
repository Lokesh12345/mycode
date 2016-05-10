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
$sqlt = "SELECT sum(price* QUANTITY) as price FROM daily_delivery ".$cond." CONS_ID='".$_GET['cid']."' and SUPP_ID='".$_GET['sid']."'";
$resultt = $conn->query($sqlt);

if ($resultt->num_rows > 0) {
    // output data of each row
    while($rowt = $resultt->fetch_assoc()) {
        $all=$rowt["price"];
    }
} else {
  $all=0;
}

$result = $conn->query("SELECT price as price,QUANTITY as quantity,PROD_TYPE,PROD_ID,DATE_DELIVER FROM `daily_delivery` ".$cond." CONS_ID='".$_GET['cid']."' and  DEL_STATUS='D'and SUPP_ID='".$_GET['sid']."' order by DATE_DELIVER desc");

$outp = "";
$sprice="";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
include"all-products-name.php";
$price=$rs["price"]*$rs["quantity"];
$productsf=$productsf[$rs["PROD_ID"]]."".$rs["PROD_ID"];
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"price":"'.$price.'",';
    $outp .= '"quantity":"'.$rs["quantity"].'",';
	
	$outp .= '"cname":"'.$compname[$rs["PROD_ID"]].'",';
	 $outp .= '"DATE_DELIVER":"'.$rs["DATE_DELIVER"].'",';
    $outp .= '"PROD_TYPE":"'.$productsf.'",'; 
	  $outp .= '"all":"'.$all.'"}'; 
 
   
}
	
  

$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>