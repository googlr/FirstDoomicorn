<?php
session_start();

$usrname = $_SESSION["usrname"];
$productName = $_POST['productName'];

echo "Dear ".$usrname."<br />";

$con = mysql_connect("localhost","root","guoxiujia");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("doomicorn", $con);

$sql_has_a_pending_order = "
	select * 
	from PURCHASE 
	where cname = '$usrname' 
		and pname = $productName 
		and pustatus = 'pending';
";

$result_has_a_pending_order = mysql_query($sql_has_a_pending_order, $con);
if( mysql_num_rows( $result_has_a_pending_order ) >= 1){
	$sql_product_price = "
	SELECT pprice 
	from PRODUCT 
	where pname = $productName;
	";
	$result_productPrice = mysql_query($sql_product_price, $con);
	$productPrice_firstline = mysql_fetch_array($result_productPrice);
	$productPrice = $productPrice_firstline['pprice'];
	echo $productPrice."<br />";
        $sql_new_purchase = "
	UPDATE PURCHASE SET 
	quantity = quantity + 1, 
	puprice = puprice + $productPrice,
	putime = now()
	where cname = '$usrname' 
		and pname = $productName 
		and pustatus = 'pending';
	";
}
else{
	$sql_new_purchase = "
	INSERT PURCHASE VALUES
	('$usrname',
	$productName,
	Now(),
	1,
	(SELECT pprice from PRODUCT where pname = $productName),
	'pending');
	";
}
echo $sql_new_purchase."<br />";
$result = mysql_query($sql_new_purchase,$con);
if( $result ){
	echo "Your order of ".$productName . "  is pending now!";
}else{
	echo "Sorry, We are unable to process your order, please try again later";
} 

?><br>
