<?php
include"db.php";
$sql = "UPDATE daily_delivery SET DEL_STATUS='D',update_by='".$_GET['sid']."' WHERE sno=".$_GET['id'];

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>