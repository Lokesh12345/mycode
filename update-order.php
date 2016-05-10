<?php
include"db.php";
$sql = "SELECT * from daily_transaction where CONS_ID=".$_POST['sid']." and PROD_ID='".$_POST['pid']."' and g_id='".$_POST['gid']."'";

$result = $conn->query($sql);
//get data from mobile//

$quantity=''.$_POST['quantity'];
$fdate="".$_POST['fdate'];
$tdate="".$_POST['tdate'];
$sid="".$_POST['sid'];
$pid="".$_POST['pid'];
$gid="".$_POST['gid'];

// getting data from mobile ends//
if ($result->num_rows > 0) {
    // output data of each row
	$orders=array();//declearing array for storeing the data 
    while($row = $result->fetch_assoc()) {
       $orders['CONS_ID'][]=$row['CONS_ID'];
		 $orders['SUPP_ID'][]=$row['SUPP_ID'];
		   $orders['PROD_TYPE'][]=$row['PROD_TYPE'];
		    $orders['TRANS_NO'][]=$row['TRANS_NO'];
		     $orders['PROD_ID'][]=$row['PROD_ID'];
			  $orders['DAY'][]=$row['DAY'];
			  $orders['FROM_DATE'][]=$row['FROM_DATE'];
			    $orders['TO_DATE'][]=$row['TO_DATE'];
			     $orders['DEL_TIME'][]=$row['DEL_TIME'];
				  $orders['sno'][]=$row['sno'];
				 
		 $orders['QUANTITY'][]=$row['QUANTITY'];
		 $CRE_DATE=date("Y-m-d");
		 
    }
}
if($quantity>$orders['QUANTITY'][0])
{
$transaction_type="Added";
}
else if($quantity<$orders['QUANTITY'][0])
{
$transaction_type="Cancel";
}
else
{
$transaction_type="New Order";
}
$count=count($orders['CONS_ID']);//counting the array
//if orginal record from date is greatert than fdate and quantity is less than orginal record
if($orders['FROM_DATE'][0]>$fdate && $quantity<$orders['QUANTITY'][0])
{
echo"You can not place order less than the orginal date";
}
//if orginal to date is less that tdate and quanty is less than orgnal quanity
else if($orders['TO_DATE'][0]<$tdate && $quantity<$orders['QUANTITY'][0])
{
echo"You can not place order less than the orginal date";
}
//if orginal from date is equal to post fdate and post quanity is greater and orginal quantity and post to date is greater than orginal to date
else if($orders['FROM_DATE'][0]==$fdate && $quantity>$orders['QUANTITY'][0] && $tdate>$orders['TO_DATE'][0])
{
$sql = "UPDATE daily_transaction SET FROM_DATE='{$_POST['fdate']}',TO_DATE='{$_POST['tdate']}',`DEL_STATUS`='NEW',`g_id`='{$gid}',`QUANTITY`='{$quantity}',`trans_type`='Orginal' WHERE `FROM_DATE`='{$orders['FROM_DATE'][0]}' and `CONS_ID`='{$sid}' and `PROD_ID`='{$pid}'";
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
//if orginal from date is greate than fdate and quanity is greater than orginal quantiy and fdate is equal to orginal from date
else if($orders['FROM_DATE'][0]>$fdate && $quantity>$orders['QUANTITY'][0] && $fdate==$orders['FROM_DATE'][0])
{
$sql = "UPDATE daily_transaction SET FROM_DATE='{$_POST['fdate']}',TO_DATE='{$_POST['tdate']},,`g_id`='{$gid}'DEL_STATUS='NEW',QUANTITY='{$quantity}',trans_type='{$transaction_type}' WHERE FROM_DATE='{$orders['FROM_DATE'][0]}' and TO_DATE='{$orders['TO_DATE'][0]}' and CONS_ID='{$sid}' and PROD_ID='{$pid}'";
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
// if orginal to date is less than tdate and quanity is greater than orginal quantity and tdate is equal to orginal to date
 else if($orders['TO_DATE'][0]<$tdate && $quantity>$orders['QUANTITY'][0] )
{
$sql = "UPDATE daily_transaction SET FROM_DATE='{$_POST['fdate']}',TO_DATE='{$_POST['tdate']}',`g_id`='{$gid}',DEL_STATUS='NEW',QUANTITY='{$quantity}',trans_type='{$transaction_type}' WHERE FROM_DATE='{$orders['FROM_DATE'][0]}' and TO_DATE='{$orders['TO_DATE'][0]}' and CONS_ID='{$sid}' and PROD_ID='{$pid}'";
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
//if count is equal to and quantity is equal to orginal quanity
else if($count==1 && $quantity==$orders['QUANTITY'][0])
{
$sql = "UPDATE daily_transaction SET FROM_DATE='{$_POST['fdate']}',TO_DATE='{$_POST['tdate']}',`g_id`='{$gid}',DEL_STATUS='Confirm',QUANTITY='{$quantity}',trans_type='Orginal' WHERE FROM_DATE='{$orders['FROM_DATE'][0]}' and TO_DATE='{$orders['TO_DATE'][0]}' and CONS_ID='{$sid}' and PROD_ID='{$pid}'";
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
//if orginal from date is greater than fdate and quantity is greater than orginal quantity and tdate is less than orginal to date
else if($orders['FROM_DATE'][0]>$fdate && $quantity>$orders['QUANTITY'][0] && $tdate<$orders['TO_DATE'][0])
{
$sql = "INSERT INTO daily_transaction (CONS_ID,SUPP_ID,PROD_TYPE,PROD_ID,DAY,FROM_DATE,TO_DATE,DEL_TIME,CRE_DATE,QUANTITY,trans_type,DEL_STATUS,g_id)VALUES ('{$orders['CONS_ID'][0]}','{$orders['SUPP_ID'][0]}','{$orders['PROD_TYPE'][0]}','{$orders['PROD_ID'][0]}','{$orders['DAY'][0]}','{$fdate}','{$tdate}','{$orders['DEL_TIME'][0]}','{$CRE_DATE}','{$quantity}','{$transaction_type}','NEW','{$gid}')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$add=date_create($tdate);
$add=date_add($add,date_interval_create_from_date_string("1 days"));
$add=date_format($add,"Y-m-d");

$sql = "UPDATE daily_transaction SET FROM_DATE='{$add}',`g_id`='{$gid}',DEL_STATUS='NEW' WHERE FROM_DATE='{$orders['FROM_DATE'][0]}' and TO_DATE='{$orders['TO_DATE'][0]}' and CONS_ID='{$sid}' and PROD_ID='{$pid}' and sno=".$orders['sno'][0];
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
//if orginal to date is less than tdate and quantity is greater than orginal quantity and fdate is greater tha orginal from date
else if($orders['TO_DATE'][0]<$tdate && $quantity>$orders['QUANTITY'][0] && $fdate>$orders['FROM_DATE'][0])
{
$sql = "INSERT INTO daily_transaction (CONS_ID,SUPP_ID,PROD_TYPE,PROD_ID,DAY,FROM_DATE,TO_DATE,DEL_TIME,CRE_DATE,QUANTITY,trans_type,DEL_STATUS,g_id)VALUES ('{$orders['CONS_ID'][0]}','{$orders['SUPP_ID'][0]}','{$orders['PROD_TYPE'][0]}','{$orders['PROD_ID'][0]}','{$orders['DAY'][0]}','{$fdate}','{$tdate}','{$orders['DEL_TIME'][0]}','{$CRE_DATE}','{$quantity}','{$transaction_type}','NEW','{$gid}')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$add=date_create($tdate);
$add=date_add($add,date_interval_create_from_date_string("1 days"));
$add=date_format($add,"Y-m-d");

$sql = "UPDATE daily_transaction SET TO_DATE='{$tdate}',trans_type='{$transaction_type}',`g_id`='{$gid}',DEL_STATUS='NEW' WHERE FROM_DATE='{$orders['FROM_DATE'][0]}' and TO_DATE='{$orders['TO_DATE'][0]}' and CONS_ID='{$sid}' and PROD_ID='{$pid}' and sno=".$orders['sno'][0];
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
else if($fdate<$orders['FROM_DATE'][0] && $quantity>$orders['QUANTITY'][0])
{
$sql = "UPDATE daily_transaction SET FROM_DATE='{$fdate}',DEL_STATUS='NEW',`g_id`='{$gid}',QUANTITY='{$quantity}' WHERE FROM_DATE='{$orders['FROM_DATE'][0]}' and TO_DATE='{$orders['TO_DATE'][0]}' and CONS_ID='{$sid}' and PROD_ID='{$pid}' and sno=".$orders['sno'][0];
if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
}
else
{
//compare the give quantity with orginal record//

if($tdate<$orders['TO_DATE'][0] || $fdate>$orders['FROM_DATE'][0])
{

//compare from dates
if ($ffind=array_search($fdate,$orders['FROM_DATE']))
  {
  $ffind=$ffind-1;
  //compare to date
	if ($tfind=array_search($tdate,$orders['TO_DATE']))
		{
			
			//compate from date and to date if they are equal update quantity
			if($ffind==$tfind-1)
				{
					//sql for updateing the quantity based on the given condition
					$sql = "UPDATE daily_transaction SET QUANTITY='{$quantity}',`g_id`='{$gid}',trans_type='{$transaction_type}',DEL_STATUS='NEW' WHERE FROM_DATE='{$fdate}' and TO_DATE='{$tdate}' and CONS_ID='{$sid}' and PROD_ID='{$pid}'";
				

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
				}
  
		}
		else
			{
				$sql = "UPDATE daily_transaction SET QUANTITY='{$quantity}',`g_id`='{$gid}',trans_type='{$transaction_type}',TO_DATE='{$tdate}',DEL_STATUS='NEW' WHERE FROM_DATE='{$fdate}' and CONS_ID='{$sid}' and PROD_ID='{$pid}'";
					

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
			}
	
	}
	//if from date dont match to the give from date then else comes
	else
		{
		if ($tfind=array_search($tdate,$orders['TO_DATE']))
		{
		$sql = "UPDATE daily_transaction SET QUANTITY='{$quantity}',`g_id`='{$gid}',trans_type='{$transaction_type}',FROM_DATE='{$fdate}',DEL_STATUS='NEW' WHERE TO_DATE='{$tdate}' and CONS_ID='{$sid}' and PROD_ID='{$pid}'";
					

					if (mysqli_query($conn, $sql)) 
						{
							echo "Order updated successfully";
						} 
						else 
						{
							echo "Error updating record: " . mysqli_error($conn);
						}
		}
		// if nothing matches i.e from date and to date then add new record
		ELSE
		{
		$sql = "INSERT INTO daily_transaction (CONS_ID,SUPP_ID,PROD_TYPE,PROD_ID,DAY,FROM_DATE,TO_DATE,DEL_TIME,CRE_DATE,QUANTITY,trans_type,DEL_STATUS,g_id)VALUES ('{$orders['CONS_ID'][0]}','{$orders['SUPP_ID'][0]}','{$orders['PROD_TYPE'][0]}','{$orders['PROD_ID'][0]}','{$orders['DAY'][0]}','{$fdate}','{$tdate}','{$orders['DEL_TIME'][0]}','{$CRE_DATE}','{$quantity}','{$transaction_type}','NEW','{$gid}')";

if ($conn->query($sql) === TRUE) {
    echo "Order updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

		}
			
		}
		}
		else
		{
		echo"You cant change to date beond or less than the orginal order";
		}
		}
  //compare from date ends
?>