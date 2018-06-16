<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	
	if($_POST['txtDate']!=NULL)	
		$_SESSION['txtDate'] = $_POST['txtDate'];
	if (isset($_REQUEST['ddlReason']) && trim($_REQUEST['ddlReason'])!='')
	{
		$_SESSION['ddlReason'] = $_REQUEST['ddlReason'];

	}
	
	//$_SESSION['ddlReason'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adjust Form</title>

<SCRIPT LANGUAGE="JavaScript">
function disable(id){
  var x = document.getElementById(id);
  x.style.backgroundColor='#c0c0c0';	
}

function checkError(ID)
{
	var Quantity =  document.getElementById(ID).value;
	if(Quantity <= 0)
	{
		document.getElementById(ID).value = "";
		alert("Quantity must more than 0");
	}
}

function Calculate()
{
document.form1.<?php echo "Adjust".$i;?>.value = (document.form1.<?php echo "QuantityCheck".$i;?>.value * document.form1.<?php echo "Quantity".$i;?>.value).toFixed(2);	
}

</script>
<style type="text/css">
.bg {
	background-color: #DCDCDC;
}
</style>

</head>

<?php

//IF STOCKNO SET OPEN DATA
if( isset($_REQUEST['StockNo']))
{
	$_SESSION['open'] = true;
}
else
{
	$_SESSION['open'] = false;
}

$OPENING = $_SESSION['open'];
//IF OPEN == true
if($OPENING)
	{
	$_SESSION['StockNo'] = $_REQUEST['StockNo'];
	$StockNo = $_SESSION['StockNo'];

	include 'Connectdb.php';
	//SELECT HEADER
	$sql = "SELECT  Date FROM inventory WHERE StockNo = '".$StockNo."'  ";
        		$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				$_SESSION['Date'] = $r['Date'] ;
				$_SESSION['ddlReason'] =$r['Reason'];
        		}
    //CLEAR OLD LINEITEM
	unset($_SESSION['LineItems']);			
	//SELECT NEW LINEITEM
	  $sql = "SELECT P.ProductCode,ProductName,I.Quantity ,I.Adjust FROM inventoryline I JOIN product P ON I.ProductCode = P.ProductCode WHERE StockNo ='".$StockNo."' ORDER BY ProductCode ";
	  $result=mysql_query($sql);
		  
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1],
									"Quantity" => $row[2],									
									"Adjust" => $row[3],
								"QuantityCheck" => $row[4] = $row[2]+ $row[3]);
	  }
	  echo $row[4];  
	 //CLOSE OPEN
	 $_SESSION['open'] = false;
				
	mysql_close($conn);
	}
	else if($_SESSION['StockNo'] == "") //IF STOCKNO NOT SET
	{ 
		if(ISSET($_SESSION['StockNo']))
		{
			$StockNo = $_SESSION['StockNo'];
		}
		else
		{
			include 'Connectdb.php';
			$_SESSION['StockNo'] = "NEW";
			$sql = "SELECT ProductCode,ProductName FROM Product ORDER BY ProductCode ";
	  $result=mysql_query($sql);
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1]);
			//mysql_close($conn);	
	  }
		}
	}

//GET DATA FROM SESSION
$WarehouseCode=$_SESSION['WarehouseCode'];
$ProductCode=$_SESSION['ProductCode'];
//IF $WarehouseCode have data
if($WarehouseCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT * FROM Warehouse WHERE WarehouseCode = '".$WarehouseCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $WarehouseCode=$r['WarehouseCode'];
		   $WarehouseName=$r['WarehouseName'];
		   $WarehouseAddress=$r['WarehouseAddress'];
		   $Phone=$r['Phone'];
	   }
	   mysql_close($conn);
}
//IF $ProductCode have data
if($ProductCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT ProductName,ProductType FROM Product WHERE ProductCode = '".$ProductCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   //$ProductCode=$r['ProductCode'];
		   $ProductName=$r['ProductName'];
		   $ProductType=$r['ProductType'];
	   }
	   mysql_close($conn);
}
?>
<!----------------------------------------------------->

<body bgcolor="#99FFCC">

<!--------------- add button -------------->

<form id="form1" name="form1" action="">

<!-- HEAD BUTTON -->
<input type="button" value="NEW" onclick="window.location = 'clear.php'" style="width:65px;" />
<input type="submit" value="SAVE" formaction = "save.php" formmethod = "get" formtarget = "_blank"/>
<input type="submit" name="btnOpen" id="btnOpen" value="OPEN" style="width:65px;" onclick="window.open('ListofStock.php','popup','width=610,height=500,scrollbars');"/>
<input type="submit" value="DELETE" formaction="delete.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
<input type="submit" value="PRINT" formaction="report.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
<a href="http://localhost/warehouse/index.php">
<input type="button" name="Back" id="Back" value="BACK" />
</a><!-- ----------- -->

  <table width="900" border="0">
    <tr>
      <td width="183">*Stock No. :</td>
      <td width="168"><label for="txtStockNo"></label>
      <input name="txtStockNo" type="text" id="txtStockNo" value="<?=$_SESSION['StockNo']?>" readonly="readonly" class = "bg"/></td>
      <td width="88">&nbsp;</td>
      <td width="270">Date :</td>
      <td width="169"><input name="txtDate" type="date" width="100" id="txtDate" value="<?=$_SESSION['Date']?>" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><label for="txtWarehouseCode"></label></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  
  
  <br />
  <br />
  <table width="650" border="2">
  <tr>
  <th width="120" scope="col">Product Code</th>
    <th width="400" scope="col">Product Name</th>
    <th width="100" scope="col">Quantity</th>
    <th width="200" scope="col">Quantity Check</th>
    <th width="100" scope="col">Adjust</th>
    </tr>
  
  
<?php
  //SHOW LINEITEM
  $NoRow=1;
	if(isset($_SESSION['LineItems']))
	{
		$LineItems = $_SESSION['LineItems'];
	
		if(count($LineItems)>0)
		{
			$i =0;
			foreach($LineItems as $rowData)
			{
		
	/*----------------------- EDIT LINEITEM	------------------------------*/
				//IF CLICK EDIT BUTTON
				if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Edit' && $_REQUEST['data'] == $rowData['ProductCode'] )
				{
				//GET OLD DATA
				$EditProductCode = $rowData['ProductCode'];
				$EditProductName = $rowData['ProductName'];
				$EditProductType = $rowData['ProductType'];
				$EditQuantityIn = $rowData['QuantityIn'];
				$EditQuantityOut = $rowData['QuantityOut'];
				
				//BELOW SHOW UPDATE AND CANCEL BUTTON
				?>  

        <tr>
          <td align = center><?=$EditProductCode?></td>
    <td><?=$EditProductName?></td>
    <td align="center"></tr>
            <?php
			}
			
			else //SHOW NORMAL LINEITEM (NOT EDIT)
			{
?>
				

				<tr>
                <td align = center><?=$rowData['ProductCode']?></td>
                <td><?=$rowData['ProductName']?></td>
                <td align="right"><input name="<?php echo "Quantity".$i;?>" type="text" class="bg" id="<?php echo "Quantity".$i; $i++;?>" style="text-align:right; width:80px;" onkeyup="checkError('Quantity');"  value="<?=$rowData['Quantity']?>"size="10" readonly="readonly" /></td>
                <td align="center"><input name="<?php echo "QuantityCheck".$i;?>" style="text-align:right; width:80px;" type="text" id="<?php echo "QuantityCheck".$i; $i++;?>"  value="<?=$rowData['QuantityCheck']?>"size="10" onkeyup="checkError('QuantityCheck');" onchage="Calculate();"" /></td>
                <td align="right"><input name="<?php echo "Adjust".$i;?>" type="text" class="bg" id="<?php echo "Adjust".$i; $i++;?>" style="text-align:right; width:80px;" onkeyup="checkError('Adjust');"  value="<?=$rowData['Adjust']?>"size="10" readonly="readonly" /></td>
                
                
    </tr>
<?php
			}
				$NoRow++;
			}
		}
	}
 
	//CLEAR LINEITEM AFTER ADD
  if(isset($_REQUEST['btAdd']))
	{
		$ProductCode = "";
		$ProductName = "";
		$ProductType = "";
		$QuantityIn = "";
		$QuantityOut = "";
	}
  ?>

</table>
	<p><!-- HIDDEN FIELD IT GET DATA WHEN YOU CLICK ALL BUTTON TO DO ANYTHING --></p>
</form>


</body>
</html>