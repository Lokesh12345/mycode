<?php
include"db.php";
$sql = "UPDATE daily_transaction SET DEL_STATUS='Cancel' WHERE sno=".$_GET['q'];

if ($conn->query($sql) === TRUE) {
    echo "Order cancelled successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

?>