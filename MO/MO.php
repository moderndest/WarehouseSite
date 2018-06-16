<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	if($_REQUEST['txtSupplierCode']!="") 	
	{	
		$MONo=$_SESSION['MONo'];	
		$InvoiceDate=$_SESSION['txtDate'];
		$ProductCode=$_SESSION['txtProductCode'];
		$Reason = $_SESSION['txtReason'];
		$WarehouseCode = $_SESSION['txtWarehouse'];}
	if($_REQUEST['txtWarehouseCode']!=NULL) 	
	{
		$MONo=$_SESSION['MONo'];
		$InvoiceDate=$_SESSION['txtDate'];
		$ProductCode=$_SESSION['txtProduct'];
		$Reason =$_SESSION['txtReason'];
		$SupplierCode = $_SESSION['txtSupplierCode'];}
	
	if($_REQUEST['txtDate']!=NULL)	
	{$_SESSION['txtDate'] = $_REQUEST['txtDate'];
		$InvoiceDate=$_SESSION['txtDate'];
		$ProductCode=$_SESSION['txtProduct'];
		$Reason = $_SESSION['txtReason'];
		$MONo=$_SESSION['MONo'];
		$WarehouseCode = $_SESSION['txtWarehouse'];
		$SupplierCode = $_SESSION['txtSupplierCode'];
	}
	
	if($_REQUEST['txtProductCode']!=NULL)	
	{
		$InvoiceDate=$_SESSION['txtDate'];
		$Reason = $_SESSION['txtReason'];
		$WarehouseCode= $_SESSION['txtWarehouse'];
	 	$SupplierCode = $_SESSION['txtSupplierCode'];}
	 
	 if($_REQUEST['MONo']!=NULL)	
	{
		$_SESSION['MONo'] = $_REQUEST['MONo'];
		$InvoiceDate=$_SESSION['txtDate'];
		$Reason = $_SESSION['txtReason'];	
		$WarehouseCode= $_SESSION['txtWarehouse'];
	 	$SupplierCode = $_SESSION['txtSupplierCode'];
	 	$ProductCode=$_SESSION['txtProduct'];}
	else
	{$_REQUEST['txtDate'] = $_SESSION['txtDate'];}
?>
<style type="text/css">
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 24px;
	font-weight: bold;
}
.style7 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
}
.style8 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	background-color: #3860BB;
}
.style9 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.bg {
	background-color: #DCDCDC;
}
</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MO</title>
</head>
<!---------------- Calculate Unit Price ----------------------->

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
	function getProduct() 
	{
		var ProductCode = document.getElementById("ProductCode").value;
		window.location = "MO.php?P="+ProductCode;
	}
	function getInvoice() 
	{
		var StockNo = document.getElementById("StockNo").value;
		window.location = "MO.php?StockNo="+StockNo;
	}
	function showMOlist() //OPEN RECORD
	{
		window.open("ListMOHead.php","popup","width=400,height=500,scrollbars");
	}
	function getMO() 
	{
		var MONo = document.getElementById("MONo").value;
		window.location = "MO.php?MONo="+MONo;
	}
	

	
	

</script>

<!---------------- Calculate Unit Price ---------------------->
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
	$sql = "SELECT  Reason,Date,I.WarehouseCode,WarehouseName,Phone,WarehouseAddress FROM warehouse W JOIN inventory I on I.WarehouseCode = W.WarehouseCode WHERE StockNo = '".$StockNo."'  ";
        		$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				$Reason =$r['Reason'];
				$InvoiceDate = $r['Date'] ;
				$WarehouseCode =$r['WarehouseCode'];
				$WarehouseName =$r['WarehouseName'];
				$WarehousePhone =$r['Phone'];
				$WarehouseAddress =$r['WarehouseAddress'];
				;
								
					
        		}
					
				$sql = "SELECT I.SupplierCode,SupplierName FROM inventory I JOIN supplier S on S.SupplierCode = I.SupplierCode WHERE StockNo = '".$StockNo."'  ";
				$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				
				$SupplierCode =$r['SupplierCode'];
				$SupplierName =$r['SupplierName'];
				//$AdjustReason =$r['AdjustReason'];
				;
								
					
        		}
    //CLEAR OLD LINEITEM
	unset($_SESSION['LineItems']);			
	//SELECT NEW LINEITEM
	  $sql = "SELECT P.ProductCode,ProductName,Price,QuantityIn,TotalPrice,ManufactureOrder	 FROM InventoryLine I JOIN Product P ON I.ProductCode = P.ProductCode WHERE StockNo ='".$StockNo."'";
	  $result=mysql_query($sql);
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1],
									"Price" => $row[2],
									"QuantityIn" => $row[3],
									"TotalPrice" => $row[4],
									"ManufactureOrder" => $row[5]
									);
							
	  }
	$_SESSION['txtDate']=$InvoiceDate;
	$_SESSION['txtReason']=$Reason;
	$_SESSION['txtSupplierCode']=$SupplierCode;
	$_SESSION['txtSupplierName']=$SupplierName;
	$_SESSION['txtWarehouse']=$WarehouseCode;
	$_SESSION['txtWarehouseName']=$WarehouseName;
	$_SESSION['txtWarehousePhone']=$WarehousePhone;
	$_SESSION['txtWarehouseAddress']=$WarehouseAddress;
	 //CLOSE OPEN
	 $_SESSION['open'] = false;
				
	mysql_close($conn);
	}
	else if($_SESSION['StockNo'] == "") //IF STOCKNO NOT SET
	{ 
		if(ISSET($_SESSION['StockNo']))
		{
			$StockNo = $_SESSION['StockNo'];
			//$Reason=$_SESSION['Reason'] ;
		}
		else
			$_SESSION['StockNo'] = "NEW";
			$_SESSION['Reason'] = "Manufacture Order";
	}
	if($_SESSION['StockNo']=="NEW")
	{
		$Reason = "Manufacture Order";
		$_SESSION['Reason'] = $Reason;
	}
?>

<?php
/* -------- get Customer --------------------------*/
//$SupplierCode=$_SESSION['txtSupplierCode'];
//$SupplierName=$_SESSION['txtSupplierName'];

if(isset($_REQUEST['txtSupplierCode']))
{
   $_SESSION['txtSupplierCode']= $_REQUEST['txtSupplierCode'];
   $SupplierCode=$_REQUEST['txtSupplierCode'];
  		
}
if(isset($_REQUEST['txtWarehouseCode']))
{
   $_SESSION['txtWarehouse']= $_REQUEST['txtWarehouseCode'];
   $WarehouseCode=$_REQUEST['txtWarehouseCode'];	
}
	if(isset($_REQUEST['txtProductCode']))
{
   $_SESSION['txtProduct']= $_REQUEST['txtProductCode'];
   $ProductCode=$_REQUEST['txtProductCode'];
   
}
if(isset($_REQUEST['EditProductCode']))
{
	$_SESSION['EditProductCode']= $_REQUEST['EditProductCode'];
   $EditProductCode=$_REQUEST['EditProductCode'];	
}
if($SupplierCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT SupplierCode,SupplierName FROM supplier WHERE SupplierCode = '".$SupplierCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $SupplierCode=$r['SupplierCode'];
		   $SupplierName=$r['SupplierName'];
	   }
	   mysql_close($conn);
}
$WarehouseCode=$_SESSION['txtWarehouse'];
$InvoiceDate=$_SESSION['txtDate'];
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
/* ----------- Choose Product -------------------------*/

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
if($EditProductCode!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT * FROM product WHERE ProductCode = '".$EditProductCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {

		   $EditProductCode=$r['ProductCode'];
		   $EditProductName=$r['ProductName'];
		   $EditUnitPrice=$r['UnitPrice'];
	   }
	   mysql_close($conn);
}

  	
	/*----------End choose Product ------------------------*/
?>


<body bgcolor="#99FFCC">
<form id="form1" name="form1" method="post" action="">
  <h1>Manufacture Order </h1>
  <p>
    <input type="button" value="NEW" onClick="window.location = 'clearMO.php'" />
    <input type="submit" value="SAVE" formaction = "saveMO.php" formmethod = "get" formtarget = "_blank"/>
    <input type="submit" name="btnOpen" id="btnOpen" value="OPEN" onClick="window.open('ListofStockMO.php','popup','width=610,height=500,scrollbars');"/>
    <input type="submit" value="DELETE" formaction="deleteMO.php" formmethod="get" formtarget="_blank" />
    <input type="submit" value="PRINT" formaction="reportMO.php" formmethod="get" formtarget="_blank"/>
    <a href="http://localhost/warehouse/index.php">
    <input type="button" name="Back" id="Back" value="BACK" />
  </a></p>
  <table width="960" border="0">
    <tr>
      <td width="150"><font color ="red">*</font>Stock No. :</td>
      <td width="144"><label for="txtStockNo"></label>
      <input name="txtStockNo" type="text" class="bg" id="txtStockNo"value="<?=$_SESSION['StockNo']?>" readonly /></td>
      <td width="93">&nbsp;</td>
      <td width="179">&nbsp;</td>
      <td width="372">&nbsp;</td>
    </tr>
    <tr>
      <td><font color ="red">*</font>Reason :</td>
      <td><label for="txtReason"></label>
      <input name="txtReason" type="text"class="bg" id="txtReason" value="<?=$Reason?>" readonly = "true" /></td>
      <td>&nbsp;</td>
      <td><font color ="red">*</font>Date : 
        <label for="txtDate"></label></td>
      <td><input name="txtDate" type="date" id="txtDate" value="<?=$InvoiceDate?>" /></td>
    </tr>
    <tr>
      <td><font color ="red">*</font>Warehouse Code :</td>
      <td><label for="txtWarehouseCode"></label>
      <input name="txtWarehouseCode" type="text" id="txtWarehouseCode" value="<?=$_SESSION['txtWarehouse']?>" /></td>
      <td><input type="submit" name="btnWarehouse" id="btnWarehouse" value="..." onClick="window.open('ListWarehouseMO.php','popup','width=610,height=500,scrollbars');" /></td>
      <td>Warehouse Name :
        <label for="txtWarehouseName"></label></td>
      <td><input name="txtWarehouseName" type="text" class="bg" id="txtWarehouseName"value="<?=$WarehouseName?>" readonly /></td>
    </tr>
    <tr>
      <td height="70">Warehouse Phone :</td>
      <td><label for="txtWarehousePhone"></label>
      <input name="txtWarehousePhone" type="text"class="bg" id="txtWarehousePhone" value="<?=$WarehousePhone?>" readonly /></td>
      <td>&nbsp;</td>
      <td>Warehouse Address :
        <label for="txtWarehouseAddress"></label></td>
      <td><textarea name="txtWarehouseAddress" style="width:200px; "readonly  id="txtWarehouseAddress"class="bg"><?=$WarehouseAddress?>
      </textarea></td>
      <?php
	//IF CLICK BUTTON UPDATE
      if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Update') 
{
	$CheckLine = $_SESSION['LineItems'];
	//FIND LINEITEM TO UPDATE
	foreach($CheckLine as $key => $lineItem)
	{
		if($_SESSION['LineItems'][$key]["ProductCode"] == $_REQUEST['data']) //IF FOUND
		{
			$_SESSION['LineItems'][$key]["QuantityIn"] = $_REQUEST['EditQuantityIn'];
			$_SESSION['LineItems'][$key]["TotalPrice"] = $_REQUEST['EditTotalPrice'];
			echo '<script>form2.submit();</script>';
		}
	}
	$_SESSION['ProductCode'] = ""; //CLEAR LINEITEM LIST OF VALUE
}
	//IF CLICK BUTTON DELETE
    if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Delete') 
{
	$CheckLine = $_SESSION['LineItems'];
	//FIND ITEM
	foreach($CheckLine as $key => $lineItem)
	{
		if($_SESSION['LineItems'][$key]["ProductCode"] == $_REQUEST['data']) //IF FOUND
		{
			unset($_SESSION['LineItems'][$key]); //DELETE ITEM
			echo '<script>form2.submit();</script>';
		}
	}
	$_SESSION['ProductCode'] = ""; //CLEAR LINEITEM LIST OF VALUE
}
	//IF CLICK BUTTON ADD
  if(isset($_REQUEST['btAdd']) && $_POST['txtProductCode'] != "" && $_POST['txtQuantity'] != ""&& $_POST['MONo'] != "" )
{
	$duplicate = false;
	//GET DATA
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"Price" => $_REQUEST['txtUnitPrice'],
						"QuantityIn" => $_REQUEST['txtQuantity'],
						"TotalPrice" => $_REQUEST['txtTotalPrice'],
						"ManufactureOrder" => $_REQUEST['MONo']);	
	//IF LINEITEM NOT EMPTY					
	if($_SESSION['LineItems'] != NULL)
	{
	$CheckLine = $_SESSION['LineItems'];					
	foreach($CheckLine as $key => $lineItem)
	{
		if ($lineItem['ProductCode']==$newLineItem['ProductCode']) //IF Duplicate
		{
			echo "<script type=\"text/javascript\">";
			echo "alert(\"ProductCode {$newLineItem['ProductCode']} is duplicated.\");";
			echo "window.history.back();";
			echo "</script>";
			$duplicate = true;
			break;
		}
	}
	}
	if(!$duplicate) //IF NOT DUPLICATE
	{
		$_SESSION['LineItems'][]= $newLineItem; //ADD NEW LINEITEM
		
	}
	$_SESSION['ProductCode'] = ""; //CLEAR LINEITEM LIST OF VALUE
}
//BUG CASE
else if($_REQUEST['btAdd'] && $_POST['txtQuantity'] == "" )
{
	//CANNOT INPUT QUANTITY IN/OUT IN ONE PRODUCT CODE
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please input QuantitIn \");";
	echo "window.history.back();";
	echo "</script>";
}
else if($_REQUEST['btAdd'] && $_POST['txtProductCode'] == "")
{
	//PRODUCT CODE EMPTY
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please input Product Code\");";
	echo "window.history.back();";
	echo "</script>";
}
else if($_REQUEST['btAdd'])
{
	//QUANTITY EMPTY
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please MO No.\");";
	echo "window.history.back();";
	echo "</script>";
}
?>

    </tr>
  </table>  
  </form>
  
<form name="form2" id="form2" method="post" >
  <p>&nbsp;</p>
  <table width="989" border="1">
    <tr>  
    <th width="75" scope="col"></th>
  <th width="75" scope="col"></th>
      <th width="240" scope="col"><font color ="red">*</font>Product Code</th>
      <th width="182" scope="col">Product Name</th>
      <th width="193" scope="col"><font color ="red">*</font>Quantity IN</th>
      <th width="184" scope="col"><font color ="red">*</font>MO No.</th>
    </tr>
    
    <?php
  //SHOW LINEITEM
  $NoRow=1;
	if(isset($_SESSION['LineItems']))
	{
		$LineItems = $_SESSION['LineItems'];
	
		if(count($LineItems)>0)
		{
		
			foreach($LineItems as $rowData)
			{
		
	/*----------------------- EDIT LINEITEM	------------------------------*/
				//IF CLICK EDIT BUTTON
				if(isset( $_REQUEST['cmd']) && $_REQUEST['cmd']=='Edit' && $_REQUEST['data'] == $rowData['ProductCode'] )
				{
				//GET OLD DATA
				$EditProductCode = $rowData['ProductCode'];
				$EditProductName = $rowData['ProductName'];
				$EditUnitPrice = $rowData['Price'];
				$EditQuantityIn = $rowData['QuantityIn'];
				$EditTotalPrice = $rowData['TotalPrice'];
				$EditMONo = $rowData['ManufactureOrder'];
				
				//BELOW SHOW UPDATE AND CANCEL BUTTON
				?> 
    
    <tr>
      <td align= "center"><input type="button" width="30" height="30" id="btnUpdate" name="btnUpdate" value="Update" onclick= "form2.cmd.value='Update';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;" /></td>
      <td align= "center"><input type="button" name="btnCancel" id="btnCancel" value="Cancel" onclick= "form2.submit();" style="width:65px;"/></td>
      <td align = "center"><input name="EditProductCode"  type="text" id="EditProductCode" style="text-align:right; width:200px;" " value="<?=$EditProductCode?>" size="5" class="bg"readonly="readonly"  /></td>
      <td align="center"><input name="EditProductName" type="text" id="EditProductName" style="text-align:right; width:150px;" " value="<?=$EditProductName?>" size="5"class="bg" readonly  /></td>
      <td align="center"><input name="EditQuantityIn" style="text-align:right; width:120px;" type="text" id="EditQuantityIn"  value="<?=$EditQuantityIn?>"size="10"  "/>
      </td>
        
      <td align="center"><input name="EditMONo" class="bg" style=" width:80px;"type="text" id="EditMONo" value="<?=$EditMONo?>"onchange="getMO(); "/></td>
    </tr>
    <?php
			}
			
			else //SHOW NORMAL LINEITEM (NOT EDIT)
			{
?>
    <tr>
      <td align= "center"><input type="button" name="btnEdit" id="btnEdit" value="Edit" onclick= "form2.cmd.value='Edit';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;" /></td>
      <td align= "center"><input type="button" name="btnDelete" id="btnDelete" value="Delete" onclick= "form2.cmd.value='Delete';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;"/></td>
      <td align = "center"><?=$rowData['ProductCode']?></td>
      <td><?=$rowData['ProductName']?></td>
      <td align="right"><?=$rowData['QuantityIn']?></td>
      <td align="center"><?=$rowData['ManufactureOrder']?></td>
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
		$UnitPrice = "";
		$QuantityIn = "";
		$TotalPrice = "";
		$MONo = "";
	}
  ?>

    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="btAdd" id="btAdd" value="ADD" /></td>
      <td align = "center"><label for="txtProductCode"></label>
        <input name="txtProductCode" style="text-align:center; width:170px"type="text" id="txtProductCode" value="<?=$ProductCode?>" />
      <input type="submit" name="btnProduct" id="btnProduct" value="..." onClick="window.open('ListProductMO.php','popup','width=610,height=500,scrollbars');"/></td>
      <td align = "center"><label for="txtProductName"></label>
      <input name="txtProductName"  type="text" id="txtProductName" value="<?=$ProductName?>" class="bg" readonly /></td>
      <td align = "center"><label for="txtQuantity"></label>
      <input name="txtQuantity" style="text-align:right;" type="text" id="txtQuantity" value="<?=$QuantityIn?>"  onkeyup="checkError('txtQuantity');" " /></td>
      <td><label for="MONo"></label>
        <input name="MONo" style="text-align:center;"type="text" id="MONo" value="<?=$MONo?>"onChange="getMO(); "/>
      <input type="submit" name="btnSOCode" id="btnSOCode" value="..."  onclick="showMOlist();"/></td>
    </tr>
  </table>
  <p><!-- HIDDEN FIELD IT GET DATA WHEN YOU CLICK ALL BUTTON TO DO ANYTHING -->
	  <input name="data" type="hidden" id="data" value="" >
	  <input name="cmd" type="hidden" id="cmd" value="" >
  </p>
  <p>&nbsp;</p>
</form>
</body>
<?php
if($OPENING)
{


	?>	
}


<script>

disable('txtDate');
</script>
<?php } ?>

<script>
disable('txtStockNo');
disable('txtSupplierCode');
disable('txtSuppilerName');
disable('txtReason');
disable('txtWarehouseCode');
disable('txtWarehouseAddress');
disable('txtWarehousephone');
</script>

</html>