<?php
$data=$_POST['bat'];
include"db.php";
foreach($data as $key => $value)
{

$sql = "UPDATE daily_delivery SET DEL_STATUS='".$value."',update_by='".$_GET['sid']."' WHERE sno=".$key;

if ($conn->query($sql) === TRUE) {
   
} else {
    
}
}
 echo "Record updated successfully";
?>