<?php
include"db.php";
$sql = "UPDATE notifications SET status='done' WHERE sid='".$_GET['id']."'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
}
?>