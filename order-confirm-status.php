<?php
include"db.php";
$sno=$_GET['q'];
$status=$_GET['status'];
if($status=='Confirm')
{
$sql = "UPDATE daily_transaction SET DEL_STATUS='$status' WHERE sno=".$sno;

if ($conn->query($sql) === TRUE) {
    echo "Order Confirmed successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
}
else
{
$sql = "DELETE FROM daily_transaction WHERE sno=".$sno;

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}
}
?>