<?php
include"db.php";
include"all-products-name.php";

$sid=$_GET['id'];
$productType=$_POST['productType'];
$company=$_POST['company'];
$products=$_POST['products'];
$deldate=$_POST['deldate'];
$frmdate=$_POST['frmdate'];
$todate=$_POST['todate'];
$quantity=$_POST['quantity'];
$time=$_POST['time'];
$id=$_POST['supplier'];
$allday="";
foreach($deldate as $alldays)
{
$allday .=$alldays.",";
}
$date=date("Y-m-d");


foreach($products as $pr)
{
$sql = "SELECT PRICE FROM supplier_products where SUPP_ID='{$sid}' and PROD_ID='{$pr}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
$price=$row['PRICE'];
    }
} 



$rid=rand();
$sql = "INSERT INTO daily_transaction(CONS_ID,SUPP_ID,PROD_TYPE,PROD_ID,DAY,FROM_DATE,TO_DATE,DEL_TIME,CRE_DATE,QUANTITY,DEL_STATUS,trans_type,g_id)VALUES ('{$id}', '{$sid}', '{$productType}','{$pr}','{$allday}','{$frmdate}','{$todate}','{$time}','{$date}','{$quantity}','Confirm','Orginal','".$rid."')";
if ($conn->query($sql) === TRUE) {
    $yes=1;	
	if($frmdate==$date){
$sql = "INSERT INTO daily_delivery (CONS_ID, SUPP_ID, PROD_ID,DATE_DELIVER,TIME_TO_DELIVER,QUANTITY,price,DEL_STATUS,PROD_TYPE,COMP_NAME)
VALUES ('".$id."', '".$sid."', '{$pr}','{$frmdate}','{$time}','{$quantity}','{$price}','D','{$productType}','{$compname[$pr]}')";

if ($conn->query($sql) === TRUE) {
} 
}
} else {
    echo "Sorry failed to place order please try again later";
}


}


if($yes==1)
{
echo "yes";
}


?>