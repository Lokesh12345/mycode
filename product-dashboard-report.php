<?php
include"tdb.php";
include"all-products-name.php";
$sql = "SELECT distinct(PROD_ID) as pid  from supplier_products where SUPP_ID='".$_GET['id']."'";

$result = $conn->query($sql);
$outp = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      
	  if($_GET['date']!='')
	  {
	  $sqlr = "SELECT sum(QUANTITY) as quan,PROD_ID FROM daily_delivery where SUPP_ID='".$_GET['id']."' and PROD_ID='".$row['pid']."' and DATE_DELIVER='".$_GET['date']."' order by QUANTITY asc";

	  }else
	  {

$sqlr = "SELECT sum(QUANTITY) as quan,PROD_ID FROM daily_delivery where SUPP_ID='".$_GET['id']."' and PROD_ID='".$row['pid']."' order by QUANTITY asc";
}
$resultr = $conn->query($sqlr);

if ($resultr->num_rows > 0) {
    // output data of each row
    while($rowr = $resultr->fetch_assoc()) {
	
	
	
	
	
	
	if($rowr['quan']=="")
	{
	$prdname=$productsf[$row['pid']];
	$image=$imagerer[$row['pid']];
    $quan=0;
	}else
	{
	$prdname=$productsf[$row['pid']];
	$image=$imagerer[$row['pid']];
    $quan=$rowr['quan'];
	}
	
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"quan":"'.$quan.'",';
	 $outp .= '"image":"'.$image.'",';
    $outp .= '"prdname":"'.$prdname.'"}'; 
	
   
}

	
	
	
	
    }
} 
    }
 $outp ='{"records":['.$outp.']}';
echo($outp);
?>