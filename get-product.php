<?php
include"db.php";
$sql = "SELECT p.PROD_NAME as pname,p.COMP_NAME,d.PROD_ID as pid FROM daily_delivery d,products p where p.id=d.PROD_ID and d.CONS_ID='".$_GET['cid']."' and d.SUPP_ID='".$_GET['sid']."' group by  p.PROD_NAME";
$result = $conn->query($sql);
 echo'<option value="">Please select type </option><option value="">All</option>';
if ($result->num_rows > 0) {
    // output data of each row
	
    while($row = $result->fetch_assoc()) {
        ?>
		<option value="<?php echo $row['pid'];?>"><?php echo $row['pname'];?></option>
		<?php
    }
} else {
   echo"<option>Sorry No Products found</option>";
}

?>