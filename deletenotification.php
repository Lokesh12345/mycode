<?php
include"db.php";
$sql = "DELETE FROM notifications WHERE sno=".$_GET['q'];

if ($conn->query($sql) === TRUE) {
    echo "Notification deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}
?>