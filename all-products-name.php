<?php
$productsf=array();
$compname=array();
$imagerer=array();
$sqlp = "SELECT PROD_NAME,id,COMP_NAME,IMAGE FROM products";
$resultp = $conn->query($sqlp);

if ($resultp->num_rows > 0) {
    // output data of each row
    while($rowp = $resultp->fetch_assoc()) {
        $productsf[$rowp['id']]=$rowp['PROD_NAME'];
		$compname[$rowp['id']]=$rowp['COMP_NAME'];
		$imagerer[$rowp['id']]=$rowp['IMAGE'];
    }
}

?>