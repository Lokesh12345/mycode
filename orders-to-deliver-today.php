<?php
include"tdb.php";
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d");
$result = $conn->query("SELECT  d.sno,d.DEL_STATUS,p.PROD_NAME,p.IMAGE,d.CONS_ID as cid,p.COMP_NAME,d.CONS_ID,d.PROD_ID,d.TIME_TO_DELIVER,d.QUANTITY,u.NAME,u.HOUSE_NAME,u.HOUSE_NO,u.MOBILE from daily_delivery d,users u,products p where u.id=d.CONS_ID and d.PROD_ID=p.id and d.SUPP_ID='".$_GET['id']."' and d.DATE_DELIVER='".$date."' order by u.HOUSE_NO asc");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

$address=$rs["HOUSE_NO"];

$sqlt = "SELECT doorno FROM next_door where cid='".$rs["cid"]."' and date='".$date."'";
$resultt = $conn->query($sqlt);

if ($resultt->num_rows > 0) {
    // output data of each row
    while($row = $resultt->fetch_assoc()) {
	
$address=$row['doorno'];
    }
}






$date = ''.$rs["TIME_TO_DELIVER"]; 
$time=date('h:i:s a', strtotime($date));
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"TIME_TO_DELIVER":"'.$time.'",';
		   $outp .= '"NAME":"'.$rs["NAME"].'",';
		   $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
		     $outp .= '"IMAGE":"'.$rs["IMAGE"].'",';
			   $outp .= '"HOUSE_NAME":"'.$rs["HOUSE_NAME"].'",';
			     $outp .= '"HOUSE_NO":"'.$address.'",';
				 $outp .= '"MOBILE":"'.$rs["MOBILE"].'",';
				  $outp .= '"sno":"'.$rs["sno"].'",';
				   $outp .= '"DEL_STATUS":"'.$rs["DEL_STATUS"].'",';
		   $outp .= '"PROD_NAME":"'.$rs["PROD_NAME"].'",';
		     
    $outp .= '"QUANTITY":"'. $rs["QUANTITY"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>