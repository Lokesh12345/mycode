<?php
include"tdb.php";
include"all-products-name.php";
$sql = "SELECT distinct(COMP_NAME) as pid  from supplier_products where SUPP_ID='".$_GET['id']."'";

$result = $conn->query($sql);
$outp = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      
	  if($_GET['date']!='')
	  {
	  $sqlr = "SELECT sum(QUANTITY) as quan,PROD_ID FROM daily_delivery where SUPP_ID='".$_GET['id']."' and COMP_NAME='".$row['pid']."' and DATE_DELIVER='".$_GET['date']."' order by QUANTITY asc";

	  }else
	  {

$sqlr = "SELECT sum(QUANTITY) as quan,PROD_ID,COMP_NAME FROM daily_delivery where SUPP_ID='".$_GET['id']."' and COMP_NAME='".$row['pid']."' order by QUANTITY asc";
}
$resultr = $conn->query($sqlr);

if ($resultr->num_rows > 0) {
    // output data of each row
    while($rowr = $resultr->fetch_assoc()) {
	
	
	
	
	
	
	if($rowr['quan']=="")
	{
	
    $quan=0;
	}else
	{

    $quan=$rowr['quan'];
	}
	
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"quan":"'.$quan.'",';
	
    $outp .= '"prdname":"'.$row['pid'].'"}'; 
	
   
}

	
	
	
	
    }
} 
    }
 $outp ='{"records":['.$outp.']}';
echo($outp);
?>