<?php
include"tdb.php";
$result = $conn->query("select img from adv");
$outp = "";
while ($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"img":"'. $rs["img"].'"}'; 
}
$outp ='{"records":['.$outp.']}';
$conn->close();
echo($outp);
?>