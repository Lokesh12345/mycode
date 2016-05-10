<?php
include"db.php";
$prod=$_POST['pid'];
$sid=$_POST['sid'];

foreach($prod as $i)
{
$sql = "INSERT INTO `supplier_products` set `PROD_ID`='{$i}',SUPP_ID={$sid}";
if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
?>