<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
$txtStockNo = $_REQUEST["txtStockNo"];
if($_SESSION['LineItems'] != NULL)
{
include 'Connectdb.php';
echo $delete = "DELETE FROM `inventoryLine` WHERE `StockNo` = '{$txtStockNo}'";
echo '<br>';
echo $delete2 = "DELETE FROM `inventory` WHERE `StockNo` = '{$txtStockNo}'";
$result = mysql_query($delete);
$result = mysql_query($delete2);
mysql_close($conn);
if($result) 
{
echo( "<script language=\"JavaScript\">window.opener.location.href=\"clearPR.php\";</script>");
echo( "<script language=\"JavaScript\">window.close();</script>");
}
}else
{
include 'Connectdb.php';
echo $delete = "DELETE FROM `inventory` WHERE `StockNo` = '{$txtStockNo}'";
$result = mysql_query($delete);
mysql_close($conn);
if($result) 
{
echo( "<script language=\"JavaScript\">window.opener.location.href=\"clearPR.php\";</script>");
echo( "<script language=\"JavaScript\">window.close();</script>");
}
}
?>