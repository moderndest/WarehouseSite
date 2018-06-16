<?php
session_start();

print_r($_SESSION['LineItems']);
echo $txtStockNo = $_REQUEST['txtStockNo'];
echo "<br>";
echo $txtReason = $_REQUEST['txtReason'];
echo "<br>";
echo $txtDate = $_REQUEST['txtDate'];
echo "<br>";
echo $txtCustomerCode = $_REQUEST['txtCustomerCode'];
echo "<br>";
echo $txtCustomerName = $_REQUEST['txtCustomerName'];
echo "<br>";
echo $txtPhone = $_REQUEST['txtWarehouseCode'];
echo "<br>";
echo $txtAddress = $_REQUEST['txtWarehouseName'];
echo "<br>";
echo $txtTotalAmount = $_REQUEST['txtWarehousePhone'];
echo "<br>";
echo $txtVAT = $_REQUEST['txtWarehouseAddress'];
echo "<br>";

include'Connectdb.php';

if($txtStockNo == 'NEW')
{
	$sql = "SELECT * FROM runningnumber WHERE Name = 'SR'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$Name=$data[0];
	$Max=$data[1];
	$Max=sprintf("%03d",++$Max);
	$txtStockNoNEW=$Name.$Max;
	
	$txtStockNo=$txtStockNoNEW;
	
	$sql_header="INSERT INTO `inventory`(`StockNo`,`Date`,`WarehouseCode`,`CustomerCode`,`Reason`) VALUES ('{$txtStockNo}','{$txtDate}','{$txtWarehouseCode}','{$txtCustomerCode}','{$txtReason}')";
	
	echo $sql_header;
	$result = mysql_query($sql_header);
	
	$sql = "SELECT * FROM runningnumber WHERE Name = 'SR'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$Max=$data[1];
	$Max++;
	$sql="UPDATE runningnumber SET Max = {$Max} WHERE Name = 'SR'";
	echo $sql;
	echo "<br>";
	echo $Max;
	echo "<br>";
    $result=mysql_query($sql);	 
}
else
{
	$sql_header= "UPDATE `inventory` SET `Date`='{$txtDate}',`WarehouseCode`='{$txtWarehouseCode}', `CustomerCode`='{$txtCustomerCode}',`Reason`='{$txtReason}' WHERE `StockNo`='{$txtStockNo}'";
	
	echo $sql_header;
	$result=mysql_query($sql_header);
	echo "<br>";
	echo "<br>";
	$deleteline="DELETE FROM `inventoryline` WHERE `StockNo`='{$txtStockNo}'";
	echo $deleteline;
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
			$ProductName = $LineItems[$i]["ProductName"];
			$UnitPrice = $LineItems[$i]["UnitPrice"];
			$QuantityIn = $LineItems[$i]["QuantityIn"];
			$TotalPrice = $LineItems[$i]["TotalPrice"];
			$SOCode=$LineItems[$i]["SOCode"];
			
			$sql_line = "INSERT INTO `inventoryline`(`StockNo`,`ProductCode`,`Price`, `QuantityIn`, `TotalPrice`, `SaleOrder` ) VALUES ('{$txtStockNo}','{$ProductCode}','{$UnitPrice}','{$QuantityIn}','{$TotalPrice}','{$SOCode}')";
			echo "<br>";
			echo "<br>";
			echo $sql_line;
			$NoRow++;
			
			
			$result = mysql_query($sql_line);
		}
	}

mysql_close($conn);
echo( "<script language=\"JavaScript\">window.close();</script>");
?>