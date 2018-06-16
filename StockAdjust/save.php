<?php
session_start();

//print_r($_SESSION['LineItems']);
$txtStockNo = $_REQUEST['txtStockNo'];
//echo "<br>";
$txtDate = $_REQUEST['txtDate'];

//echo "<br>";
//$Quantity  = $_REQUEST['Quantity'];
//.$i; $i++;





//echo "<br>";
//echo $txtWarehouseCode = $_REQUEST['txtWarehouseCode'];
//echo "<br>";
//echo $txtWarehouseName = $_REQUEST['txtWarehouseName'];
//echo "<br>";
//echo $txtWarehouseAddress = $_REQUEST['txtWarehouseAddress'];
//echo "<br>";
//echo $txtPhone = $_REQUEST['txtPhone'];
//echo "<br>";


include'Connectdb.php';

if($txtStockNo == 'NEW')
{
	$sql = "SELECT * FROM `runningnumber` WHERE Name = 'SB'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$prefix=$data[0];
	$number=$data[1];
	$number=sprintf("%03d",++$number);
	$txtStockNoNEW=$prefix.$number;
	
	$txtStockNo=$txtStockNoNEW;
	
	$sql_header="INSERT INTO `inventory`(`StockNo`,`Date`) VALUES ('{$txtStockNo}','{$txtDate}')";
	
	//echo $sql_header;
	$result = mysql_query($sql_header);
	
	$sql = "SELECT * FROM runningnumber WHERE Name = 'SB'";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);
	$number=$data[1];
	$number++;
	$sql="UPDATE runningnumber SET Max = {$number} WHERE Name= 'SB'";
	//echo $sql;
    $result=mysql_query($sql);
	
	//ADD NEW LINEITEM
	$i =0;
		foreach($_SESSION['LineItems'] as $lineItem)
		{
			$nametxt = "Quantity".$i;
			$QuantitySQL = $_REQUEST[$nametxt];
			$sql=sprintf("INSERT INTO  InventoryLine(StockNo,ProductCode,Quantity) ".
				 "VALUES ('%s','%s',%d);",
				 $txtStockNo,$lineItem['ProductCode'],
				 $QuantitySQL);
			$i++;
			mysql_query($sql) or die(mysql_error());
		}
	 
}
else
{
	$sql_header= "UPDATE `Inventory` SET `Date`='{$txtDate}' WHERE `StockNo`='{$txtStockNo}'";
	
	//echo $sql_header;
	$result=mysql_query($sql_header);
	//echo "<br>";
	//echo "<br>";
	$deleteline="DELETE FROM `InventoryLine` WHERE `StockNo`='{$txtStockNo}'";
	//echo $deleteline;
	$result=mysql_query($deleteline);
	
	
	//ADD NEW LINEITEM
	$i =0;
		foreach($_SESSION['LineItems'] as $lineItem)
		{
			$nametxt = "Quantity".$i;
			$QuantitySQL = $_REQUEST[$nametxt];
			$sql=sprintf("INSERT INTO  InventoryLine(StockNo,ProductCode,Quantity) ".
				 "VALUES ('%s','%s',%d);",
				 $txtStockNo,$lineItem['ProductCode'],
				 $QuantitySQL);
			$i++;
			mysql_query($sql) or die(mysql_error());
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