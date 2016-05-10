<?php
include"tdb.php";
$result = $conn->query("SELECT  count(sno) as count from notifications where status='New' and sid='".$_GET['id']."' ");

$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"count":"'. $rs["count"].'"}';  
}
$outp ='{"records":['.$outp.']}';


$conn->close();

echo($outp);
?>