 <?php
include"db.php";
include"all-users.php";
include"all-products-name.php";
  date_default_timezone_set("Asia/Kolkata"); 
$time=date("h:i:s",strtotime("-1 hours"));
$sql = "SELECT fdate,tdate FROM cancel_orders where fdate='".$_GET['fdate']."' and tdate='".$_GET['tdate']."' and pid='".$_GET['pid']."' and sid='".$_GET['sid']."' and cid='".$_GET['uid']."'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
echo"Cancel order for the given dates already exists";
} else {
if($_GET['fdate']==date("Y-m-d"))
{
$sql = "SELECT sno FROM daily_delivery where CONS_ID='{$_GET['uid']}' and SUPP_ID='{$_GET['sid']}' and PROD_ID='{$_GET['pid']}' and DATE_DELIVER='{$_GET['fdate']}' and TIME_TO_DELIVER>'{$time}'";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       $sql = "DELETE FROM daily_delivery WHERE sno=".$row['sno'];
echo $sql;
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} 
    }
}
else
{
if($_GET['fdate']==date("Y-m-d"))
{
echo"Sorry you cant place order now as your order has been deliverd or started deliverying";
exit;
}
}
}
$sql = "INSERT INTO cancel_orders(pid,cid,sid,fdate,tdate,tid)VALUES ('{$_GET['pid']}','{$_GET['uid']}','{$_GET['sid']}','{$_GET['fdate']}','{$_GET['tdate']}','{$_GET['gid']}')";

if ($conn->query($sql) === TRUE) {
    echo "Your Order placed successfully";
	$fdate = date("d-M-Y",strtotime($_GET['fdate']));
$tdate = date("d-M-Y",strtotime($_GET['tdate']));
	$sqlt = "INSERT INTO notifications(sid,detail,status)VALUES ('{$_GET['sid']}','".$users[$_GET['uid']]." has <b>cancelled</b> ".$productsf[$_GET['pid']]." from ".$fdate." to ".$tdate."','New')";
if ($conn->query($sqlt) === TRUE) {
}
	
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
?>