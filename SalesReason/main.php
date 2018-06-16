<?php
/*----------------- DEBUG ---------------------*/
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	
	if($_REQUEST['txtWarehouseCode']!=NULL) 	
	{$CustomerCode = $_SESSION['txtCustomer'];}
	
	if($_REQUEST['txtDate']!=NULL)	
	{$_SESSION['txtDate'] = $_REQUEST['txtDate'];}
	
	if($_REQUEST['txtProductCode']!=NULL)	
	{$WarehouseCode= $_SESSION['txtWarehouse'];
	 $CustomerCode = $_SESSION['txtCustomer'];
	 $InvoiceDate=$_SESSION['txtDate'];}
	 
	 if($_REQUEST['txtSOCode']!=NULL)	
	{$WarehouseCode= $_SESSION['txtWarehouse'];
	 $CustomerCode = $_SESSION['txtCustomer'];
	 $ProductCode=$_SESSION['txtProduct'];
	 $InvoiceDate=$_SESSION['txtDate'];}
	else
	{$_REQUEST['txtDate'] = $_SESSION['txtDate'];}
	/*----------------- DEBUG ---------------------*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<!------------------- Calculate Price ----------------------->
<SCRIPT LANGUAGE="JavaScript">
function calculateprice(){
	document.form2.txtTotalPrice.value = (document.form2.txtQuantityOut.value * document.form2.txtUnitPrice.value).toFixed(2);	
}

function Editcalculateprice(){
	document.form2.EditTotalPrice.value = (document.form2.EditQuantityOut.value * document.form2.EditUnitPrice.value).toFixed(2);	
}

function disable(id){
  var x = document.getElementById(id);
  x.style.backgroundColor='#c0c0c0';	
}
</script>
<!---------------- End Calculate Price ---------------------->

<?php
/* ----------------- Open Invoice ------------------------ */
if($_SESSION['StockNo'] != NULL ||( isset($_REQUEST['txtStockNo']) && $_REQUEST['txtStockNo'] != "NEW"))
	{
		if($_SESSION['StockNo'] != NULL) $Invoice =$_SESSION['StockNo'];
		else $Invoice = $_REQUEST['txtStockNo'];
		$_SESSION['StockNo'] = $Invoice;
		$open=1;
	$Invoice = $_SESSION['StockNo'];
	include 'Connectdb.php';
	$sql = "SELECT * FROM inventory WHERE StockNo = '".$Invoice."'  ";
        		$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				$Reason = $r['Reason'];
				$InvoiceDate = $r['Date'] ;
				$CustomerCode =$r['CustomerCode'];
				$WarehouseCode =$r['WarehouseCode'];
					
        		}
				
				
	if($_SESSION['LineItems']==NULL)
	{
	  $sql = "SELECT * , P.ProductName FROM inventoryline L
JOIN product P ON L.ProductCode = P.ProductCode WHERE StockNo ='".$Invoice."'";
	  $result=mysql_query($sql);
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[1],
		                              "ProductName" => $row[13],
									"UnitPrice" => $row[6],
									"QuantityOut" => $row[4],
									"TotalPrice" => $row[7],
									"SOCode" => $row[11]);
	  }
	}
				
	mysql_close($conn);
	}
	else{
	$Invoice = "NEW";
	$Reason = "Sales";
	$open=0;
	}
/* ------------ End of Open Invoice -------------------- */
/* -------- get Customer --------------------------*/
if(isset($_REQUEST['txtCustomerCode']))
{
   $_SESSION['txtCustomer']= $_REQUEST['txtCustomerCode'];
   $CustomerCode=$_REQUEST['txtCustomerCode'];	
}

if($CustomerCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT * FROM customer WHERE CustomerCode = '".$CustomerCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $CustomerCode=$r['CustomerCode'];
		   $CustomerName=$r['CustomerName'];
		   $DeliveryAddress=$r['AddressDelivery'];
		   $Phone=$r['Phone'];
	   }
	   mysql_close($conn);
}
/* -------- End get Customer --------------------*/

?>

<?php
/* -------- get Warehouse --------------------*/
if(isset($_REQUEST['txtWarehouseCode']))
{
   $_SESSION['txtWarehouse']= $_REQUEST['txtWarehouseCode'];
   $WarehouseCode=$_REQUEST['txtWarehouseCode'];	
}
if($WarehouseCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT * FROM warehouse WHERE WarehouseCode = '".$WarehouseCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $WarehouseCode=$r['WarehouseCode'];
		   $WarehouseName=$r['WarehouseName'];
		   $WarehousePhone=$r['Phone'];
		   $WarehouseAddress=$r['WarehouseAddress'];
	   }
	   mysql_close($conn);
}
/* -------- End get warehouse --------------------*/
?>

<body bgcolor="#99FFCC">
<?php if(isset($_REQUEST['btAdd']))
{
echo '<center>Adding...</center>';
}
	if(isset($_REQUEST['txtDate']))
	{ $InvoiceDate=$_REQUEST['txtDate'];
	}

?>

<form id="form1" name="form1" method="post" action="">
 
  <h1>Stock Issue Out : Sales Reason</h1>
  <p>
    <input type="button" value="NEW" onClick="window.location = 'clear.php'">
    <input type="submit" value="SAVE" formaction = "save.php" formmethod = "get"   formtarget = "_blank"/>
    <input type="submit" name="btnOpen" id="btnOpen" value="OPEN" onclick="window.open('ListInvoice.php','popup','width=650,height=500,scrollbars');"/>
    <input type="submit" value="DELETE" formaction="delete.php" formmethod="get" formtarget="_blank" />
    <input type="submit" value="PRINT" formaction="report.php" formmethod="get" formtarget="_blank"/>
    <a href="http://localhost/warehouse/index.php">
  <input type="button" name="Back" id="Back" value="BACK" />
  </a>
  </p>
  <table width="960" border="0">
    <tr>
      <td width="150"><font color ="red">*</font>Stock No. :</td>
      <td width="144"><label for="txtStockNo"></label>
      <input name="txtStockNo" type="text" id="txtStockNo" value="<?=$Invoice?>" readonly="readonly" /></td>
      <td width="93">&nbsp;</td>
      <td width="179">&nbsp;</td>
      <td width="372">&nbsp;</td>
    </tr>
    <tr>
      <td>Reason :</td>
      <td><label for="txtReason"></label>
      <input name="txtReason" type="text" id="txtReason" value="<?=$Reason?>" readonly="readonly" /></td>
      <td>&nbsp;</td>
      <td><font color ="red">*</font>Date : 
      <label for="txtDate"></label></td>
      <td><input name="txtDate" type="date" id="txtDate" value="<?=$InvoiceDate?>" /></td>
    </tr>
    <tr>
      <td><font color ="red">*</font>Customer Code :</td>
      <td><label for="txtCustomerCode"></label>
      <input name="txtCustomerCode" type="text" id="txtCustomerCode" value="<?=$CustomerCode?>" readonly="readonly" /></td>
      <td><input type="submit" name="btnCustomer" id="btnCustomer" value="..." onclick="window.open('ListCustomer.php','popup','width=610,height=500,scrollbars');" /></td>
      <td>Customer Name :
        <label for="txtCustomerName"></label></td>
      <td><input name="txtCustomerName" type="text" id="txtCustomerName" value="<?=$CustomerName?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td><font color ="red">*</font>Warehouse Code :</td>
      <td><label for="txtWarehouseCode"></label>
      <input name="txtWarehouseCode" type="text" id="txtWarehouseCode" value="<?=$WarehouseCode?>" readonly="readonly" /></td>
      <td><input type="submit" name="btnWarehouse" id="btnWarehouse" value="..." onclick="window.open('ListWarehouse.php','popup','width=610,height=500,scrollbars');" /></td>
      <td>Warehouse Name :
        <label for="txtWarehouseName"></label></td>
      <td><input name="txtWarehouseName" type="text" id="txtWarehouseName" value="<?=$WarehouseName?>" readonly="readonly" /></td>
    </tr>
    <tr>
      <td>Warehouse Phone :</td>
      <td><label for="txtWarehousePhone"></label>
      <input name="txtWarehousePhone" type="text" id="txtWarehousePhone" value="<?=$WarehousePhone?>" readonly="readonly" /></td>
      <td>&nbsp;</td>
      <td>Warehouse Address :
        <label for="txtWarehouseAddress"></label></td>
      <td><textarea name="txtWarehouseAddress" readonly="readonly" id="txtWarehouseAddress" width="250"><?=$WarehouseAddress?>
      </textarea></td>
    </tr>
  </table>  
</form>
  
<form name="form2" id="form2" method="post" >
  <p>&nbsp;</p>
  <table width="868" border="1">
    <tr>
    <th width="60" scope="col"></th>
  <th width="60" scope="col"></th>
      <th width="116" scope="col"><font color ="red">*</font>Product Code</th>
      <th width="221" scope="col">Product Name</th>
      <th width="115" scope="col">Unit Price</th>
      <th width="125" scope="col"><font color ="red">*</font>Quantity Out</th>
      <th width="116" scope="col">Total Price</th>
      <th width="135" scope="col"><font color ="red">*</font>SO Code.</th>
    </tr>
  
 <?php
 /*------------------ Edit Lineitem -------------*/
      if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Update') 
{
	for ($i=0; $i<=10; $i++)
	{
		if($_SESSION['LineItems'][$i]["ProductCode"] == $_REQUEST['data']) 
		{
			$_SESSION['LineItems'][$i]["QuantityOut"] = $_REQUEST['EditQuantityOut'];
			$_SESSION['LineItems'][$i]["TotalPrice"] = $_REQUEST['EditTotalPrice'];
			echo '<script>form1.submit();</script>';
		}
	}
}
    if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Delete') 
{
	for ($i=0; $i<=10; $i++)
	{
		if($_SESSION['LineItems'][$i]["ProductCode"] == $_REQUEST['data']) 
		{
			unset($_SESSION['LineItems'][$i]);
			echo '<script>form1.submit();</script>';
		}
	}
}
  if(isset($_REQUEST['btnAdd']))
{
	$_SESSION['LineItems'][]=array( "ProductCode" => $_REQUEST['txtProductCode'],
									"ProductName" => $_REQUEST['txtProductName'],
									"UnitPrice" => $_REQUEST['txtUnitPrice'],
									"QuantityOut" => $_REQUEST['txtQuantityOut'],
									"TotalPrice" => $_REQUEST['txtTotalPrice'],
									"SOCode" => $_REQUEST['txtSOCode']);
	
	
	
	
}
  
  $NoRow=1;
	if(isset($_SESSION['LineItems']))
	{
		$LineItems = $_SESSION['LineItems'];
	
		if(count($LineItems)>0)
		{
		
			foreach($LineItems as $rowData)
			{
		
	/*----------------------- for Edit	------------------------------*/
				
				if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Edit' && $_REQUEST['data'] == $rowData['ProductCode'] )
				{
				$EditProductCode = $rowData['ProductCode'];
				$EditProductName = $rowData['ProductName'];
				$EditUnitPrice = $rowData['UnitPrice'];
				$EditQuantityOut = $rowData['QuantityOut'];
				$EditTotalPrice = $rowData['TotalPrice'];
				$EditSOCode = $rowData['SOCode']; 
				?>  
        <tr>
                <td><input type="button" width="30" height="30" id="btnUpdate" name="btnUpdate" value="Update" OnClick= "form2.cmd.value='Update';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" ></td>
                <td><input type="button" name="btnCancel" id="btnCancel" value="Cancel" OnClick= "form2.submit();" /></td>
    <td align = center><?=$EditProductCode?></td>
    <td><?=$EditProductName?></td>
    <td align="right"><?=$EditUnitPrice?>
    <input type="hidden" id="EditUnitPrice" value="<?=$EditUnitPrice?>" /></td>
    <td align="right">
      <input name="EditQuantityOut" type="text" id="EditQuantityOut" onchange="Editcalculateprice();" value="<?=$EditQuantityOut?>" size="5" /></td>
    <td align="right">
      <input name="EditTotalPrice" type="text" id="EditTotalPrice" value="<?=number_format($EditTotalPrice,2,'.','')?>" size="10" /></td>
    <td align = center><?=$EditSOCode?></td>
    </tr>
  
            <?php
			}
			
		/*	------------------ End Edit Lineitem------------------------*/
			
			else
			{
?>
				

				<tr>
                <td><input type="button" name="btnEdit" id="btnEdit" value="Edit" OnClick= "form2.cmd.value='Edit';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" /></td>
                <td><input type="button" name="btnDelete" id="btnDelete" value="Delete" OnClick= "form2.cmd.value='Delete';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();"/></td>
                <td align = center><?=$rowData['ProductCode']?></td>
                <td><?=$rowData['ProductName']?></td>
                <td align="right"><?=$rowData['UnitPrice']?></td>
                <td align="right"><?=$rowData['QuantityOut']?></td>
                <td align="right"><?=$rowData['TotalPrice']?></td>
                <td align = center><?=$rowData['SOCode']?></td>
				</tr>
<?php
			}
				$NoRow++;
			}
		}
	}
  /*------------------ End Add Lineitem -------------*/

	/* ----------- Choose Product -------------------------*/
	if(isset($_REQUEST['txtProductCode']))
{
   $_SESSION['txtProduct']= $_REQUEST['txtProductCode'];
   $ProductCode=$_REQUEST['txtProductCode'];	
}
if($ProductCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT * FROM product WHERE ProductCode = '".$ProductCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $ProductCode=$r['ProductCode'];
		   $ProductName=$r['ProductName'];
		   $UnitPrice=$r['UnitPrice'];
	   }
	   mysql_close($conn);
}
  
	/* ----------- Choose SO Code -------------------------*/
	if(isset($_REQUEST['txtSOCode']))
{

   $SOCode=$_REQUEST['txtSOCode'];	

	include 'Connectdb.php';
       $sql="SELECT * FROM sohead WHERE SOCode = '".$SOCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $SOCode=$r['SOCode'];
	   }
	   mysql_close($conn);
}
	/*----------End choose SO Code ------------------------*/
	/*----------Clear for next Lineitem -------------------*/
	
	if(isset($_REQUEST['btnAdd']))
	{
		//$SOCode = "";
		$ProductCode = "";
		$ProductName = "";
		$UnitPrice = "";
		
		$SOCode = "";

	}

	/*----------End clear for next Lineitem ---------------*/
	
	?>
    
    <tr>
    <td>&nbsp;</td>
     <td><input type="submit" name="btnAdd" id="btnAdd" value="ADD" /></td>
      <td><label for="txtProductCode"></label>
      <input name="txtProductCode" type="text" id="txtProductCode" value="<?=$ProductCode?>" readonly="readonly" required />
      <input type="submit" name="btnProduct" id="btnProduct" value="..." onclick="window.open('ListProduct.php','popup','width=610,height=500,scrollbars');"/></td>
      <td><label for="txtProductName"></label>
      <input name="txtProductName" type="text" id="txtProductName" value="<?=$ProductName?>" readonly="readonly" /></td>
      
      <?php
      if(isset($_REQUEST['btnAdd']))
	 {
		$UnitPrice = "";
		$Quantity = '';
		$TotalPrice = '';
		
		echo '<script>form2.submit();</script>';
	 }
	 ?>
      <td><label for="txtUnitPrice"></label>
      <input name="txtUnitPrice" type="text" id="txtUnitPrice" value="<?=$UnitPrice?>" readonly="readonly" /></td>
      <td><label for="txtQuantity"></label>
      <input name="txtQuantityOut" type="text" id="txtQuantityOut" value="<?=$QuantityOut?>" onchange="calculateprice();" required /></td>
      <td><label for="txtTotalPrice"></label>
      <input name="txtTotalPrice" type="text" id="txtTotalPrice" value="<?=$TotalPrice?>" readonly="readonly" /></td>
      <td><label for="txtSOCode"></label>
      <input name="txtSOCode" type="text" id="txtSOCode" value="<?=$SOCode?>" required />
      <input type="submit" name="btnSOCode" id="btnSOCode" value="..."  onclick="window.open('ListSOHead.php','popup','width=610,height=500,scrollbars');"/></td>
    </tr>
  </table>
  <input name="data" type="hidden" id="data" value="" ><input name="cmd" type="hidden" id="cmd" value="" >
</form>
</body>

<?php
if($Open)
{
?>	
}
<!----------------- gray screen ----------------------->
<script>

</script>
<?php } ?>
<script>
disable('txtStockNo');
disable('txtReason');
disable('txtCustomerCode');
disable('txtCustomerName');
disable('txtWarehouseCode');
disable('txtWarehouseName');
disable('txtWarehousePhone');
disable('txtWarehouseAddress');
disable('txtProductCode');
disable('txtProductName');
disable('txtUnitPrice');
disable('txtTotalPrice');</script>
<!-----------------end of gray screen ----------------------->


</html>