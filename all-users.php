<?php
$users=array();
$sql = "SELECT NAME,id FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $users[$row['id']]=$row['NAME'];
    }
} 
?>