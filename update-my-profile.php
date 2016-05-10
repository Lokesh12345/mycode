<?php
include"db.php";

$date=date("Y-m-d h:i:s ");
$sql = "update users set `MOBILE`='{$_POST['mobile']}',`EMAIL`='{$_POST['email']}',`NAME`='{$_POST['name']}',`update_Date`='{$date}',PASSWORD='{$_POST['pwd']}' where id='{$_POST['id']}'";

if ($conn->query($sql) === TRUE) {
    echo "yes";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>