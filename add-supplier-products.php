<?php
include"db.php";
$prod=$_GET['pid'];
$sid=$_GET['id'];
$active = isset($_POST['active']) ? $_POST['active'] : '0';
$sql = "INSERT INTO `supplier_products` set `PROD_ID`='{$_GET['pid']}',`SUPP_ID`='{$sid}',`PRICE`='{$_POST['price']}',`EFF_FROM`='{$_POST['effectfrom']}',`DICOUNT_NO`='{$_POST['discount']}',`DIS_FROM`='{$_POST['validform']}',`STATUS`='{$active}',`COMP_NAME`='{$_POST['cmpname']}',`PROD_TYPE`='".$_POST['productType']."'";
if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Sorry some thing went Wrong";

}
?>