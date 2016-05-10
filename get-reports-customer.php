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
$result = $conn->query("SELECT price as price,QUANTITY as quantity,PROD_TYPE,DATE_DELIVER FROM `daily_delivery` ".$cond." CONS_ID='".$_GET['cid']."' and SUPP_ID='".$_GET['sid']."' and DEL_STATUS='D' order by DATE_DELIVER desc");

$outp = "";
$sprice="";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$price=$rs["price"]*$rs["quantity"];

    if ($outp != "") {$outp .= ",";}
    $outp .= '{"price":"'.$price.'",';
    $outp .= '"quantity":"'.$rs["quantity"].'",';
	 $outp .= '"DATE_DELIVER":"'.$rs["DATE_DELIVER"].'",';
    $outp .= '"PROD_TYPE":"'.$rs["PROD_TYPE"].'",'; 
	$outp .= '"all":"'.$all.'"}'; 
   
}

$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>