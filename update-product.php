<?php
include"db.php";
$active = isset($_POST['active']) ? $_POST['active'] : '0';
$sql = "update `products` set `PROD_TYPE`='{$_POST['ptype']}',`COMP_NAME`='{$_POST['pcname']}',`PROD_NAME`='{$_POST['pname']}',`status`='{$active}' where `id`='{$_POST['id']}'";
if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>