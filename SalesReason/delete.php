<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);
$txtStock = $_REQUEST["txtStockNo"];
if($_SESSION['LineItems'] != NULL)
{
include 'Connectdb.php';
echo $delete = "DELETE FROM `inventoryline` WHERE `StockNo` = '{$txtStock}'";
echo '<br>';
echo $delete2 = "DELETE FROM `inventory` WHERE `StockNo` = '{$txtStock}'";
$result = mysql_query($delete);
$result = mysql_query($delete2);
mysql_close($conn);
if($result) 
{
echo( "<script language=\"JavaScript\">window.opener.location.href=\"clear.php\";</script>");

echo( "<script language=\"JavaScript\">window.close();</script>");

}
}else
{
include 'Connectdb.php';
echo $delete = "DELETE FROM `inventory` WHERE `StockNo` = '{$txtStock}'";
$result = mysql_query($delete);
mysql_close($conn);
if($result) 
{
echo( "<script language=\"JavaScript\">window.opener.location.href=\"clear.php\";</script>");
echo( "<script language=\"JavaScript\">window.close();</script>");
}
}
?>