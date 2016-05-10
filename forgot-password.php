<?php
include"db.php";
$sql = "SELECT email FROM users where MOBILE='".$_GET['phno']."'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $email=$row['email'];
    }
$pwd=rand();	
$sql = "UPDATE users SET password='".$pwd."' WHERE MOBILE='".$_GET['phno']."'";

if ($conn->query($sql) === TRUE) {
    
} else {
    echo "Sorry some thing went wroong Please try again later";
	exit;
}

$to = "".$email;

$subject = "Meara Milk | Password Request";

$message = "
<html>
<head>
<title>Laundry mail Reset Password</title>
</head>
<body>
<p>Please use the below password to login after logging in reset your password</p>
<table>
<tr>
<th>password:<b>".$pwd."</b></th>

</tr>

</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <info@meramilk.in>' . "\r\n";
$headers .= 'Cc: info@meramilk.in' . "\r\n";

mail($to,$subject,$message,$headers);

echo"A link has been successfully sent your mail please check";

} else {
    echo "Sorry no used found please try registering";
}
?>