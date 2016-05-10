<?php
include"tdb.php";
include"all-products-name.php";
$result = $conn->query("select * from cancel_orders where cid='".$_GET['id']."' order by sno desc");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$fdate = date("d-M-Y",strtotime($rs["fdate"]));
$tdate = date("d-M-Y",strtotime($rs["tdate"]));
$prdname=$productsf[$rs['pid']];
$cmp=$compname[$rs['pid']];
$img=$imagerer[$rs['pid']];
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"prdname":"'.$prdname.'",';
    $outp .= '"cmp":"'.$cmp.'",';
	$outp .= '"fdate":"'.$fdate.'",';
	$outp .= '"tdate":"'.$tdate.'",';
	 $outp .= '"img":"'. $img.'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>