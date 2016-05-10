<?php
include"db.php";
$sql = "SELECT MOBILE,PASSWORD FROM users where `MOBILE`='{$_POST['id']}' and `PASSWORD`='{$_POST['pwd']}'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
        echo "yes";
    
} else {
   echo "no";
}
?>