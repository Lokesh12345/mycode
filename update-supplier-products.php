<?php
include"db.php";
$active = isset($_POST['active']) ? $_POST['active'] : '0';
$sql = "update `supplier_products` set `PRICE`='{$_POST['price']}',`EFF_FROM`='{$_POST['effectfrom']}',`DICOUNT_NO`='{$_POST['discount']}',`DIS_FROM`='{$_POST['validform']}',`STATUS`='{$active}' where `sno`='{$_POST['pid']}'";
if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>