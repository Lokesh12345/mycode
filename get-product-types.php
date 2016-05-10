<?php
include"db.php";
$sql = "SELECT PROD_TYPE FROM daily_delivery where CONS_ID='".$_GET['cid']."' and SUPP_ID='".$_GET['sid']."' group by PROD_TYPE";

$result = $conn->query($sql);
 echo'<option value="">Please select type </option><option value="">All</option>';
if ($result->num_rows > 0) {
    // output data of each row
	
    while($row = $result->fetch_assoc()) {
        ?>
		<option value="<?php echo $row['PROD_TYPE'];?>"><?php echo $row['PROD_TYPE'];?></option>
		<?php
    }
} else {
   echo"<option>Sorry No Types found</option>";
}

?>