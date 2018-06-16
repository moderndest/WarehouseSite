<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	if($_REQUEST['txtSupplierCode']!="") 	
	{	
		$WONo=$_SESSION['WONo'];	
		$InvoiceDate=$_SESSION['txtDate'];
		$ProductCode=$_SESSION['txtProductCode'];
		$Reason = $_SESSION['txtReason'];
		$WarehouseCode = $_SESSION['txtWarehouse'];}
	if($_REQUEST['txtWarehouseCode']!=NULL) 	
	{
		$WONo=$_SESSION['WONo'];
		$InvoiceDate=$_SESSION['txtDate'];
		$ProductCode=$_SESSION['txtProduct'];
		$Reason =$_SESSION['txtReason'];
		$SupplierCode = $_SESSION['txtSupplierCode'];}
	
	if($_REQUEST['txtDate']!=NULL)	
	{$_SESSION['txtDate'] = $_REQUEST['txtDate'];
		$InvoiceDate=$_SESSION['txtDate'];
		$ProductCode=$_SESSION['txtProduct'];
		$Reason = $_SESSION['txtReason'];
		$WONo=$_SESSION['WONo'];
		$WarehouseCode = $_SESSION['txtWarehouse'];
		$SupplierCode = $_SESSION['txtSupplierCode'];
	}
	
	if($_REQUEST['txtProductCode']!=NULL)	
	{
		$InvoiceDate=$_SESSION['txtDate'];
		$Reason = $_SESSION['txtReason'];
		$WarehouseCode= $_SESSION['txtWarehouse'];
	 	$SupplierCode = $_SESSION['txtSupplierCode'];}
	 
	 if($_REQUEST['WONo']!=NULL)	
	{
		$_SESSION['WONo'] = $_REQUEST['WONo'];
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
<title>WO</title>
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
		window.location = "WO.php?P="+ProductCode;
	}
	function getInvoice() 
	{
		var StockNo = document.getElementById("StockNo").value;
		window.location = "WO.php?StockNo="+StockNo;
	}
	function showWOlist() //OPEN RECORD
	{
		window.open("ListWOHead.php","popup","width=400,height=500,scrollbars");
	}
	function getWO() 
	{
		var WONo = document.getElementById("WONo").value;
		window.location = "WO.php?WONo="+WONo;
	}
	

	
	
function calculateprice(){
	document.form2.txtTotalPrice.value = (document.form2.txtQuantity.value * document.form2.txtUnitPrice.value).toFixed(2);	
}
function Editcalculateprice(){
	document.form2.EditTotalPrice.value = (document.form2.EditQuantityIn.value * document.form2.EditUnitPrice.value).toFixed(2);	
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
	  $sql = "SELECT P.ProductCode,ProductName,Price,QuantityIn,TotalPrice,WorkOrder FROM InventoryLine I JOIN Product P ON I.ProductCode = P.ProductCode WHERE StockNo ='".$StockNo."'";
	  $result=mysql_query($sql);
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1],
									"Price" => $row[2],
									"QuantityIn" => $row[3],
									"TotalPrice" => $row[4],
									"WorkOrder" => $row[5]
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
			$_SESSION['Reason'] = "Work Order";
	}
	if($_SESSION['StockNo']=="NEW")
	{
		$Reason = "Work Order";
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
  <h1>Work Order </h1>
  <p>
    <input type="button" value="NEW" onClick="window.location = 'clearWO.php'" />
    <input type="submit" value="SAVE" formaction = "saveWO.php" formmethod = "get" formtarget = "_blank"/>
    <input type="submit" name="btnOpen" id="btnOpen" value="OPEN" onClick="window.open('ListofStockWO.php','popup','width=610,height=500,scrollbars');"/>
    <input type="submit" value="DELETE" formaction="deleteWO.php" formmethod="get" formtarget="_blank" />
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
      <td><font color ="red">*</font>Supplier Code :</td>
      <td><label for="txtSupplierCode"></label>
      <input name="txtSupplierCode" type="text" id="txtSupplierCode" value="<?=$SupplierCode?>"  "/></td>
      <td><input type="submit" name="btnCustomer" id="btnCustomer" value="..." onClick="window.open('ListSupplierWO.php','popup','width=610,height=500,scrollbars');" /></td>
      <td>Supplier Name :
        <label for="txtSupplierName"></label></td>
      <td><input name="txtSuppilerName" type="text" class="bg" id="txtSupplierName" value="<?=$SupplierName?>" readonly /></td>
    </tr>
    <tr>
      <td><font color ="red">*</font>Warehouse Code :</td>
      <td><label for="txtWarehouseCode"></label>
      <input name="txtWarehouseCode" type="text" id="txtWarehouseCode" value="<?=$_SESSION['txtWarehouse']?>" /></td>
      <td><input type="submit" name="btnWarehouse" id="btnWarehouse" value="..." onClick="window.open('ListWarehouseWO.php','popup','width=610,height=500,scrollbars');" /></td>
      <td>Warehouse Name :
        <label for="txtWarehouseName"></label></td>
      <td><input name="txtWarehouseName" type="text" class="bg" id="txtWarehouseName"value="<?=$WarehouseName?>" readonly /></td>
    </tr>
    <tr>
      <td>Warehouse Phone :</td>
      <td><label for="txtWarehousePhone"></label>
      <input name="txtWarehousePhone" type="text"class="bg" id="txtWarehousePhone" value="<?=$WarehousePhone?>" readonly /></td>
      <td>&nbsp;</td>
      <td>Warehouse Address :
        <label for="txtWarehouseAddress"></label></td>
      <td><textarea name="txtWarehouseAddress" readonly  id="txtWarehouseAddress"width="250"class="bg"><?=$WarehouseAddress?>
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
  if(isset($_REQUEST['btAdd']) && $_POST['txtProductCode'] != "" && $_POST['txtQuantity'] != ""&& $_POST['WONo'] != "" )
{
	$duplicate = false;
	//GET DATA
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"Price" => $_REQUEST['txtUnitPrice'],
						"QuantityIn" => $_REQUEST['txtQuantity'],
						"TotalPrice" => $_REQUEST['txtTotalPrice'],
						"WorkOrder" => $_REQUEST['WONo']);	
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
else if($_REQUEST['btAdd']&& $_POST['WONo'] == "")
{
	//QUANTITY EMPTY
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please WO No.\");";
	echo "window.history.back();";
	echo "</script>";
}
?>

    </tr>
  </table>  
  </form>
  
<form name="form2" id="form2" method="post" >
  <p>&nbsp;</p>
  <table width="1157" border="1">
    <tr>  
    <th width="65" scope="col"></th>
  <th width="65" scope="col"></th>
      <th width="208" scope="col"><font color ="red">*</font>Product Code</th>
      <th width="144" scope="col">Product Name</th>
      <th width="147" scope="col">Unit Price</th>
      <th width="147" scope="col"><font color ="red">*</font>Quantity IN</th>
      <th width="144" scope="col">Total Price</th>
      <th width="185" scope="col"><font color ="red">*</font>WO No.</th>
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
				$EditWONo = $rowData['WorkOrder'];
				
				//BELOW SHOW UPDATE AND CANCEL BUTTON
				?> 
    
    <tr>
      <td align= "center"><input type="button" width="30" height="30" id="btnUpdate" name="btnUpdate" value="Update" onclick= "form2.cmd.value='Update';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;" /></td>
      <td align= "center"><input type="button" name="btnCancel" id="btnCancel" value="Cancel" onclick= "form2.submit();" style="width:65px;"/></td>
      <td align = "center"><input name="EditProductCode" type="text" id="EditProductCode" style="text-align:right;" width:100px;" " value="<?=$EditProductCode?>" size="5" class="bg"readonly="readonly"  /></td>
      <td align="center"><input name="EditProductName" type="text" id="EditProductName" style="text-align:right; width:120px;" " value="<?=$EditProductName?>" size="5"class="bg" readonly  /></td>
      <td align = "center"><input name="EditUnitPrice" style="text-align:right; width:120px;" "type="text" id="EditUnitPrice" value="<?=number_format($EditUnitPrice,2,'.','')?>" size="10" class="bg" readonly /></td>
      <td align="center"><input name="EditQuantityIn" style="text-align:right; width:120px;" type="text" id="EditQuantityIn"  value="<?=$EditQuantityIn?>"size="10"  onchange="Editcalculateprice();"/>
      </td>
        
      <td align="center"><input name="EditTotalPrice" style="text-align:right; width:120px;" " type="text" id="EditTotalPrice" value="<?=number_format($EditTotalPrice,2,'.','')?>" size="10" /></td>
      <td align="center"><input name="EditWONo" class="bg" style=" width:80px;"type="text" id="EditWONo" value="<?=$EditWONo?>"onchange="getWO(); "/></td>
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
      <td align = "center"><?=$rowData['Price']?></td>
      <td align="right"><?=$rowData['QuantityIn']?></td>
      <td align="right"><?=$rowData['TotalPrice']?></td>
      <td align="center"><?=$rowData['WorkOrder']?></td>
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
		$WONo = "";
	}
  ?>

    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="btAdd" id="btAdd" value="ADD" /></td>
      <td><label for="txtProductCode"></label>
        <input name="txtProductCode" style="text-align:center;"type="text" id="txtProductCode" value="<?=$ProductCode?>" />
      <input type="submit" name="btnProduct" id="btnProduct" value="..." onClick="window.open('ListProductWO.php','popup','width=610,height=500,scrollbars');"/></td>
      <td><label for="txtProductName"></label>
      <input name="txtProductName" type="text" id="txtProductName" value="<?=$ProductName?>" class="bg" readonly /></td>
      <td><label for="txtUnitPrice"></label>
      <input name="txtUnitPrice" style="text-align:right;" type="text" id="txtUnitPrice" value="<?=$UnitPrice?>"class="bg"  readonly="readonly" /></td>
      <td><label for="txtQuantity"></label>
      <input name="txtQuantity" style="text-align:right;"type="text" id="txtQuantity" value="<?=$QuantityIn?>"  onkeyup="checkError('txtQuantity');" onChange="calculateprice();" /></td>
      <td><label for="txtTotalPrice"></label>
      <input name="txtTotalPrice" style="text-align:right;" type="text" id="txtTotalPrice" value="<?=$TotalPrice?>" class="bg" readonly /></td>
      <td><label for="WONo"></label>
        <input name="WONo" style="text-align:center;" type="text" id="WONo" value="<?=$WONo?>"onChange="getWO(); "/>
      <input type="submit" name="btnWONo" id="btnWONo" value="..."  onclick="showWOlist();"/></td>
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