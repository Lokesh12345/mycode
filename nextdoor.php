<?php 
include"db.php";
$sql = "insert into next_door SET `doorno`='{$_POST['hnumber']}',`doorname`='{$_POST['hname']}',`date`='{$_POST['hdate']}',cid='{$_GET['id']}'";

if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error updating record: " . $conn->error;
}

?>