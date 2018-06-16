<?php
session_start();

//print_r($_SESSION['LineItems']);
echo $txtStockNo = $_REQUEST['txtStockNo'];
//echo "<br>";
echo $txtDate = $_REQUEST['txtDate'];
//echo "<br>";
echo $Reason = $_SESSION['Reason'];
//echo "<br>";
//echo $txtSupplierCode = $_REQUEST['txtSupplierCode'];
//echo "<br>";
//echo $txtSupplierCode = $_REQUEST['txtSupplierCode'];
//echo "<br>";
//echo $txtSupplierName = $_REQUEST['txtSupplierName'];
//echo "<br>";
//echo $txtSupplierAddress = $_REQUEST['txtSupplierAddress'];
//echo "<br>";
//echo $txtPhone = $_REQUEST['txtPhone'];
//echo "<br>";


include'Connectdb.php';

if($txtStockNo == 'NEW')
{
	$sql = "SELECT * FROM `runningnumber` WHERE Name = 'RM'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$prefix=$data[0];
	$number=$data[1];
	$number=sprintf("%03d",++$number);
	$txtStockNoNEW=$prefix.$number;
	
	$txtStockNo=$txtStockNoNEW;
	
	$sql_header="INSERT INTO `Inventory`(`StockNo`,`Date`,`Reason`) VALUES ('{$txtStockNo}','{$txtDate}','{$Reason}')";
	
	echo $sql_header;
	$result = mysql_query($sql_header);
	
	$sql = "SELECT * FROM runningnumber WHERE Name = 'RM'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$number=$data[1];
	$number++;
	$sql="UPDATE runningnumber SET Max = {$number} WHERE Name= 'RM'";
	//echo $sql;
    $result=mysql_query($sql);	 
}
else
{
	$sql_header= "UPDATE `Inventory` SET `Date`='{$txtDate}',`Reason`='{$Reason}' WHERE `StockNo`='{$txtStockNo}'";
	
	//echo $sql_header;
	$result=mysql_query($sql_header);
	//echo "<br>";
	//echo "<br>";
	$deleteline="DELETE FROM `InventoryLine` WHERE `StockNo`='{$txtStockNo}'";
	//echo $deleteline;
	$result=mysql_query($deleteline);
}

$NoRow = 1;
$LineItems=$_SESSION['LineItems'];
print_r($LineItems);

for ($i=0; $i<=10; $i++)
	{
		if($LineItems[$i]["ProductCode"] != '')
		{
			$ProductCode = $LineItems[$i]["ProductCode"];
			//$ProductName = $LineItems[$i]["ProductName"];
			$QuantityIn = $LineItems[$i]["QuantityIn"];
			$ManufactureOrder = $LineItems[$i]["ManufactureOrder"];
			$sql_line = "INSERT INTO `InventoryLine`(`StockNo`, `ProductCode`, `QuantityIn`, `ManufactureOrder`) VALUES ('{$txtStockNo}','{$ProductCode}','{$QuantityIn}','{$ManufactureOrder}')";
			//echo "<br>";
			//echo "<br>";
			//echo $sql_line;
			$NoRow++;
			$result = mysql_query($sql_line);
		}
	}
//$_SESSION['StockNo'] = $txtStockNo;
mysql_close($conn);
//RETURN NEW STOCK NO TO main.php
$print ="window.opener.location.href=\"main.php?StockNo=".$txtStockNo."\";";

echo( "<script language=\"JavaScript\">");
echo($print);
echo ("window.close();</script>");
?>