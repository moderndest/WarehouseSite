<?php
session_start();

//print_r($_SESSION['LineItems']);
echo $txtStockNo = $_REQUEST['txtStockNo'];
//echo "<br>";
echo $txtDate = $_REQUEST['txtDate'];
//echo "<br>";
echo $Reason = $_SESSION['Reason'];
//echo "<br>";
echo $txtSupplierCode = $_REQUEST['txtSupplierCode'];
//echo "<br>";
//echo $txtSupplierCode = $_REQUEST['txtSupplierCode'];
//echo "<br>";
//echo $txtSupplierName = $_REQUEST['txtSupplierName'];
//echo "<br>";
//echo $txtSupplierAddress = $_REQUEST['txtSupplierAddress'];
//echo "<br>";
//echo $txtPhone = $_REQUEST['txtPhone'];
//echo "<br>";


include'ConnectdbIssueWO.php';

if($txtStockNo == 'NEW')
{
	$sql = "SELECT * FROM `runningnumber` WHERE Name = 'IW'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$prefix=$data[0];
	$number=$data[1];
	$number=sprintf("%03d",++$number);
	$txtStockNoNEW=$prefix.$number;
	
	$txtStockNo=$txtStockNoNEW;
	
	$sql_header="INSERT INTO `Inventory`(`StockNo`,`Date`,`SupplierCode`,`Reason`) VALUES ('{$txtStockNo}','{$txtDate}','{$txtSupplierCode}','{$Reason}')";
	
	echo $sql_header;
	$result = mysql_query($sql_header);
	
	$sql = "SELECT * FROM runningnumber WHERE Name = 'IW'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$number=$data[1];
	$number++;
	$sql="UPDATE runningnumber SET Max = {$number} WHERE Name= 'IW'";
	//echo $sql;
    $result=mysql_query($sql);	 
}
else
{
	$sql_header= "UPDATE `Inventory` SET `Date`='{$txtDate}', `SupplierCode`='{$txtSupplierCode}',`Reason`='{$Reason}' WHERE `StockNo`='{$txtStockNo}'";
	
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
			$QuantityOut = $LineItems[$i]["QuantityOut"];
			$WorkOrder = $LineItems[$i]["WorkOrder"];
			$sql_line = "INSERT INTO `InventoryLine`(`StockNo`, `ProductCode`, `QuantityOut`, `WorkOrder`) VALUES ('{$txtStockNo}','{$ProductCode}','{$QuantityOut}','{$WorkOrder}')";
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
$print ="window.opener.location.href=\"mainIssueWO.php?StockNo=".$txtStockNo."\";";

echo( "<script language=\"JavaScript\">");
echo($print);
echo ("window.close();</script>");
?>