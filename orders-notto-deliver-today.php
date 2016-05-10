<?php
include"tdb.php";
$date=date("Y-m-d");

$result = $conn->query("select p.PROD_NAME,p.image,p.COMP_NAME,p.IMAGE,u.NAME,u.HOUSE_NAME,u.HOUSE_NO,u.MOBILE,ca.cid,ca.sid from products p,users u,cancel_orders ca where ca.cid=u.id and  ca.sid='".$_GET['id']."' and ca.pid=p.id and '".$date."' >= ca.fdate  and '".$date."'<=ca.tdate");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {

$addres=$rs["HOUSE_NO"];

$sqlt = "SELECT doorno FROM next_door where cid='".$rs["cid"]."' and date='".$date."'";
$resultt = $conn->query($sqlt);

if ($resultt->num_rows > 0) {
    // output data of each row
    while($row = $resultt->fetch_assoc()) {
	
$address=$row['doorno'];
    }
}




    if ($outp != "") {$outp .= ",";}
    $outp .= '{"NAME":"'.$rs["NAME"].'",';

		   $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
		     $outp .= '"IMAGE":"'.$rs["IMAGE"].'",';
			   $outp .= '"HOUSE_NAME":"'.$rs["HOUSE_NAME"].'",';
			     $outp .= '"HOUSE_NO":"'.$address.'",';
				 $outp .= '"MOBILE":"'.$rs["MOBILE"].'",';
		   
		     
    $outp .= '"PROD_NAME":"'. $rs["PROD_NAME"].'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>