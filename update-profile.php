<?php
include"db.php";
$active = isset($_POST['active']) ? $_POST['active'] : '0';

$date=date("Y-m-d h:i:s");
$sql = "update users set `REG_TYPE`='{$_POST['persontype']}',`MOBILE`='{$_POST['mobile']}',`NAME`='{$_POST['name']}',`CITY`='{$_POST['city']}',`AREA`='{$_POST['area']}',`COLONY`='{$_POST['colony']}',`COUNTRY`='{$_POST['country']}',`PIN`='{$_POST['pincode']}',`HOUSE_NO`='{$_POST['houseno']}',`HOUSE_NAME`='{$_POST['hname']}',`payment`='{$_POST['payment']}',`status`='{$active}',`update_Date`='{$date}',`service_charge`='{$_POST['scharge']}' where id='{$_POST['id']}'";

if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>