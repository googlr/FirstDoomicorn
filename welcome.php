<?php 
session_start();
echo "SESSION START!"; 
if( ! isset($_SESSION['usrname']) )
  $_SESSION['usrname'] = $_POST["usrname"];
?>

<html>
<body>

Welcome <?php echo $_POST["usrname"]; ?><br>
Your keyword is: <?php echo $_POST["keyword"]; ?><br><br />

<?php
$con = mysql_connect("localhost","root","guoxiujia");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("doomicorn", $con);
$usrname = $_POST["usrname"];
$keyword = $_POST["keyword"];

$sql_name_validate = "select * from CUSTOMER where cname = '$usrname';";
$result_name_validate = mysql_query($sql_name_validate, $con);
if( mysql_num_rows( $result_name_validate ) < 1){
	require("signUp.php");
}
else{
	if($keyword == ""){$sql = "select * from PRODUCT";}
	else{$sql = "select * from PRODUCT where pdescription LIKE '%$keyword%'";}

$result = mysql_query($sql, $con);
if( mysql_num_rows($result) < 1){
echo "Nothing found about ".$keyword,",try keywords like 'Amazon' or 'Music'.";
}
else{
while($row = mysql_fetch_array($result))
  {
$temp = $row['pname'];
$html = <<<HTML
<form method = "post" action = "newPurchase.php">
<input type = "hidden" name = "productName" value = "'$temp'" /><br />
<input type = "submit" value = "Purchase"/><br />
</form>
HTML;
  echo $row['pname'] .$html;
  echo "Description: ".$row['pdescription']. "<br>";
  echo "Price: " . $row['pprice']. "<br> ";
  echo "Status: " . $row['pstatus']. "<br />";
  echo "<br>";
  }
}
}
mysql_close($con);
?>

</body>
</html>
