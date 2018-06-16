<?php
session_start();

print_r($_SESSION['LineItems']);
echo $txtStockNo = $_REQUEST['txtStockNo'];
echo "<br>";
echo $txtDate = $_REQUEST['txtDate'];
echo "<br>";
echo $ddlReason = $_SESSION['ddlReason'];
echo "<br>";
echo $txtSupplierCode = $_REQUEST['txtSupplierCode'];
echo "<br>";
echo $txtSupplierName = $_REQUEST['txtSupplierName'];
echo "<br>";
echo $txtWarehouseCode = $_REQUEST['txtWarehouseCode'];
echo "<br>";
echo $txtWarehouseCode = $_REQUEST['txtWarehouseCode'];
echo "<br>";
echo $txtWarehouseName = $_REQUEST['txtWarehouseName'];
echo "<br>";
echo $txtWarehouseAddress = $_REQUEST['txtWarehouseAddress'];
echo "<br>";
echo $txtWarehousePhone = $_REQUEST['txtWarehousePhone'];
echo "<br>";


include'Connectdb.php';

if($txtStockNo == 'NEW')
{
	$sql = "SELECT * FROM `runningnumberpo` WHERE Name = 'PC'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$prefix=$data[0];
	$number=$data[1];
	$number=sprintf("%03d",++$number);
	$txtStockNoNEW=$prefix.$number;
	
	$txtStockNo=$txtStockNoNEW;
	
	$sql_header="INSERT INTO `inventory`(`StockNo`,`Date`,`WarehouseCode`,`SupplierCode`,`Reason`) VALUES ('{$txtStockNo}','{$txtDate}','{$txtWarehouseCode}','{$txtSupplierCode}','{$txtReason}')";
	
	echo $sql_header;
	$result = mysql_query($sql_header);
	
	$sql = "SELECT * FROM runningnumberpo WHERE Name = 'PC'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$number=$data[1];
	$number++;
	$sql="UPDATE runningnumberpo SET Max = {$number} WHERE Name= 'PC'";
	//echo $sql;
    $result=mysql_query($sql);	 
}
else
{
	$sql_header= "UPDATE `Inventory` SET `Date`='{$txtDate}', `WarehouseCode`='{$txtWarehouseCode}', `SupplierCode`='{$txtSupplierCode}',`Reason`='{$txtReason}' WHERE `StockNo`='{$txtStockNo}'";
	
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
			$UnitPrice = $LineItems[$i]["Price"];
			$QuantityIn = $LineItems[$i]["QuantityIn"];
			$TotalPrice = $LineItems[$i]["TotalPrice"];
			$PONo = $LineItems[$i]["PurchaseOrder"];
			$sql_line = "INSERT INTO `InventoryLine`(`StockNo`, `ProductCode`,  `Price`,`QuantityIn`,`TotalPrice`, `PurchaseOrder`) VALUES ('{$txtStockNo}','{$ProductCode}','{$UnitPrice}','{$QuantityIn}','{$TotalPrice}','{$PONo}')";
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
$print ="window.opener.location.href=\"PO.php?StockNo=".$txtStockNo."\";";

echo( "<script language=\"JavaScript\">");
echo($print);
echo ("window.close();</script>");
?>