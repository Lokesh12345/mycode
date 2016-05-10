<?php
include"tdb.php";
$users=array();
$result = $conn->query("SELECT name,id from users");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$users[$rs['id']]=$rs['name'];
}
$result = $conn->query("select p.PROD_NAME as pname,p.COMP_NAME as COMP_NAME,p.IMAGE as image,c.cid as uname,u.MOBILE as mobile,c.fdate as fdate,c.tdate as tdate from products p,cancel_orders c,users u where c.sid='".$_GET['id']."' and c.pid=p.id and c.cid=u.id");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
$fdate = date("d-M-Y",strtotime($rs["fdate"]));
$tdate = date("d-M-Y",strtotime($rs["tdate"]));
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"uname":"'. $users[$rs["uname"]].'",';
    $outp .= '"tdate":"'.$tdate.'",';
	 $outp .= '"mobile":"'.$rs['mobile'].'",';
	 $outp .= '"image":"'.$rs["image"].'",';
	    $outp .= '"COMP_NAME":"'.$rs["COMP_NAME"].'",';
	 $outp .= '"pname":"'.$rs["pname"].'",';
    $outp .= '"fdate":"'.$fdate.'"}'; 
   
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>