<?php
$results = $conn->query("select PRICE,DICOUNT_NO from supplier_products where SUPP_ID='".$rs['sid']."' and PROD_ID='".$rs['pid']."' order by sno desc limit 1");
while ($rss = $results->fetch_array(MYSQLI_ASSOC)) {
$cost=$rss['PRICE']-$rss['DICOUNT_NO'];
}
?>