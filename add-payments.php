<?php
include"db.php";
$sid=$_GET['sid'];
$cid=$_GET['cid'];
$payment=$_GET['payment'];
$bl=$_GET['preivousbl'];
$date=date("Y-m-d");
$sql = "INSERT INTO payments (cid,sid,Prev_balance,curr_month_payment,payed_date)VALUES ('{$cid}', '{$sid}', '{$bl}','{$payment}','{$date}')";

if ($conn->query($sql) === TRUE) {
    echo "Payment added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>