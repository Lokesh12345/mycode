<?php
include"db.php";
if(!isset($_POST['active']))
{
$active=0;
}
else
{
$active=1;
}

$sql = "SELECT MOBILE from users where MOBILE='".$_POST['mobile']."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    exit;
}



$frim = isset($_POST['firm_name']) ? $_POST['firm_name'] : '';
$date=date("Y-m-d h:i:s ");
$sql = "INSERT INTO users set `EMAIL`='{$_POST['email']}',`REG_TYPE`='{$_POST['persontype']}',`MOBILE`='{$_POST['mobile']}',`NAME`='{$_POST['name']}',`CITY`='{$_POST['city']}',`AREA`='{$_POST['area']}',`COLONY`='{$_POST['colony']}',`COUNTRY`='{$_POST['country']}',`PIN`='{$_POST['pincode']}',`HOUSE_NO`='{$_POST['houseno']}',`HOUSE_NAME`='{$_POST['hname']}',`payment`='{$_POST['payment']}',`status`='{$active}',`bys`='{$_GET['id']}',`update_Date`='{$date}',`PASSWORD`='{$_POST['mobile']}',`SUPPLIER_FIRM_NAME`='{$_POST['firm_name']}',`service_charge`='{$_POST['scharge']}'";

if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>