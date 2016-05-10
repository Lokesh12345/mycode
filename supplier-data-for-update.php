<?php
include"tdb.php";
$result = $conn->query("SELECT supplier_products.sno,supplier_products.PROD_ID, supplier_products.PRICE,supplier_products.EFF_FROM,supplier_products.DICOUNT_NO,supplier_products.DIS_FROM,supplier_products.DIS_TO,supplier_products.STATUS,products.PROD_TYPE,products.COMP_NAME,products.PROD_NAME,products.IMAGE FROM supplier_products INNER JOIN products ON supplier_products.PROD_ID=products.id where supplier_products.PROD_ID=".$_GET['id']." and supplier_products.SUPP_ID=".$_GET['user']);

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"id":"'. $rs["sno"].'",';
    $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
	 $outp .= '"PROD_NAME":"'.$rs["PROD_NAME"].'",';
	 $outp .= '"PROD_TYPE":"'.$rs["PROD_TYPE"].'",';
	  $outp .= '"status":"'.$rs["STATUS"].'",';
	  $outp .= '"PRICE":"'.$rs["PRICE"].'",';
	  $outp .= '"EFF_FROM":"'.$rs["EFF_FROM"].'",';
	  $outp .= '"DICOUNT_NO":"'.$rs["DICOUNT_NO"].'",';
	   $outp .= '"DIS_FROM":"'.$rs["DIS_FROM"].'",';
	      $outp .= '"DIS_TO":"'.$rs["DIS_TO"].'",';

    $outp .= '"IMAGE":"'. $rs["IMAGE"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>