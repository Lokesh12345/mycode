<?php
include"tdb.php";
$result = $conn->query("SELECT * FROM users where bys='".$_GET['id']."' and `REG_TYPE`='D'");
$outp = "[";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"MOBILE":"'. $rs["MOBILE"].'",';
    $outp .= '"NAME":"'.$rs["NAME"].'",';
	 $outp .= '"id":"'.$rs["id"].'",';
	 $outp .= '"EMAIL":"'.$rs["EMAIL"].'",';
	  $outp .= '"CITY":"'.$rs["CITY"].'",';
	   $outp .= '"AREA":"'.$rs["AREA"].'",';
	    $outp .= '"COLONY":"'.$rs["COLONY"].'",';
		 $outp .= '"COUNTRY":"'.$rs["COUNTRY"].'",';
		  $outp .= '"PIN":"'.$rs["PIN"].'",';
		   $outp .= '"HOUSE_NO":"'.$rs["HOUSE_NO"].'",';
		    $outp .= '"HOUSE_NAME":"'.$rs["HOUSE_NAME"].'",';
			 $outp .= '"NEXT_DOOR":"'.$rs["NEXT_DOOR"].'",';
			  $outp .= '"payment":"'.$rs["payment"].'",';
			   $outp .= '"type":"'.$rs["REG_TYPE"].'",';
    $outp .= '"status":"'. $rs["status"].'"}'; 
}
$outp .="]";

$conn->close();

echo($outp);
?>