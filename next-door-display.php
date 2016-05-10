<?php
include"tdb.php";
$result = $conn->query("select * from next_door where cid='".$_GET['id']."' order by sno desc");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$fdate = date("d-M-Y",strtotime($rs["date"]));
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"cid":"'. $rs["cid"].'",';
    $outp .= '"doorno":"'.$rs["doorno"].'",';
	 $outp .= '"date":"'. $fdate.'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>