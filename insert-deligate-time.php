<?php
include"db.php";
$active = isset($_POST['active']) ? $_POST['active'] : '0';
$sql = "update users set `EFFECTIVE_FROM`='{$_POST['fdate']}',`EFFECTIVE_TO`='{$_POST['tdate']}',`status`='{$active}' where `id`='{$_POST['did']}'";

if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>