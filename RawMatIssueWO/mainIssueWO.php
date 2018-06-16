<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	
	if($_POST['txtDate']!=NULL)	
		$_SESSION['txtDate'] = $_POST['txtDate'];

	
	//$_SESSION['ddlReason'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Raw Material Issue WO</title>

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

	include 'ConnectdbIssueWO.php';
	//SELECT HEADER
	$sql = "SELECT  Date,Reason,I.SupplierCode,SupplierName,Address,Phone FROM inventory I JOIN Supplier W on I.SupplierCode = W.SupplierCode WHERE StockNo = '".$StockNo."'  ";
        		$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				$_SESSION['Date'] = $r['Date'] ;
				$_SESSION['Reason'] =$r['Reason'];
				//$AdjustReason =$r['AdjustReason'];
				$_SESSION['SupplierCode'] =$r['SupplierCode'];
				$SupplierName =$r['SupplierName'];
				$Address =$r['Address'];
				$Phone =$r['Phone'];
					
        		}
    //CLEAR OLD LINEITEM
	unset($_SESSION['LineItems']);			
	//SELECT NEW LINEITEM
	  $sql = "SELECT P.ProductCode,ProductName,ProductType,QuantityOut,WorkOrder FROM InventoryLine I JOIN Product P ON I.ProductCode = P.ProductCode WHERE StockNo ='".$StockNo."'";
	  $result=mysql_query($sql);
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1],
									"ProductType" => $row[2],
									"QuantityOut" => $row[3],
									"WorkOrder" => $row[4],
								);
							
	  }
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
			$_SESSION['StockNo'] = "NEW";
			$_SESSION['Reason'] = "Raw Material Issue WO";
		}
		
	}
if($_SESSION['StockNo'] == "NEW")
		{	
			$Reason ="Raw Material Issue WO";
			$_SESSION['Reason'] = $Reason;
			}
//IF CHOOSE SupplierCode
if(isset($_REQUEST['SupplierCode']))
{
   $_SESSION['SupplierCode']= $_REQUEST['SupplierCode'];
   $SupplierCode=$_REQUEST['SupplierCode'];	
}
//IF CHOOSE ProductCode
if(isset($_REQUEST['ProductCode']) && $_REQUEST['cmd']!='Edit')
{
   $_SESSION['ProductCode']= $_REQUEST['ProductCode'];
   $ProductCode=$_REQUEST['ProductCode'];	
}
//IF CHOOSE WorkOrder
if(isset($_REQUEST['WONo']) && $_REQUEST['cmd']!='Edit')
{
   $_SESSION['WorkOrder']= $_REQUEST['WONo'];
   $WorkOrder=$_REQUEST['WONo'];	
}

//GET DATA FROM SESSION
$SupplierCode=$_SESSION['SupplierCode'];
$ProductCode=$_SESSION['ProductCode'];
$WorkOrder=$_SESSION['WorkOrder'];

//IF $SupplierCode have data
if($SupplierCode!= NULL)
{
	include 'ConnectdbIssueWO.php';
       $sql="SELECT * FROM Supplier WHERE SupplierCode = '".$SupplierCode. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $SupplierCode=$r['SupplierCode'];
		   $SupplierName=$r['SupplierName'];
		   $Address=$r['Address'];
		   $Phone=$r['Phone'];
	   }
	   mysql_close($conn);
}
//IF $ProductCode have data
if($ProductCode!= NULL)
{
	include 'ConnectdbIssueWO.php';
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
//IF $WorkOrder have data
if($WorkOrder!= NULL)
{
	include 'ConnectdbIssueWO.php';
       $sql="SELECT WONo FROM WOhead WHERE WONo = '".$WorkOrder. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $WorkOrder=$r['WorkOrder']; 
	   }
	   mysql_close($conn);
}

?>
<!----------------------------------------------------->

<body bgcolor="#99FFCC">

<h1>
  <!--------------- add button -------------->
  
Raw Material Issue WO</h1>
<form id="form1" name="form1" action="">
  
  <!-- HEAD BUTTON -->
  <input type="button" value="NEW" onClick="window.location = 'clearIssueWO.php'" style="width:65px;">
<input type="submit" value="SAVE" formaction = "saveIssueWO.php" formmethod = "get" formtarget = "_blank"/>
<input type="submit" name="btnOpen" id="btnOpen" value="OPEN" style="width:65px;" onclick="window.open('ListofStockIssueWO.php','popup','width=610,height=500,scrollbars');"/>
<input type="submit" value="DELETE" formaction="deleteIssueWO.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
<input type="submit" value="PRINT" formaction="report.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
<a href="http://localhost/warehouse/index.php">
<input type="button" name="Back" id="Back" value="BACK" />
</a><!-- ----------- -->

  <table width="900" border="0">
    <tr>
      <td width="166"><font color ="red">*</font>Stock No. :</td>
      <td width="188"><label for="txtStockNo"></label>
      <input name="txtStockNo" type="text" class = "bg" id="txtStockNo" value="<?=$_SESSION['StockNo']?>" size="26" readonly="readonly"/></td>
      <td width="87">&nbsp;</td>
      <td width="185"><font color ="red">*</font>Date :</td>
      <td width="252"><input name="txtDate" type="date" width="180" id="txtDate" value="<?=$_SESSION['Date']?>" /></td>
    </tr>
    <tr>
      
    </tr>
    <tr>
      <td><font color="red">*</font>SupplierCode</td>
      <td colspan="2"><label for="txtSupplierCode"></label>
      <input name="txtSupplierCode" type="text" id="txtSupplierCode" value="<?=$SupplierCode?>" size="26"  readonly="readonly" />
      
      <input type="button" name="btCustomer" id="btCustomer" value="..." onclick="window.open('ListofSupplierIssueWO.php','popup','width=610,height=500,scrollbars');">
      </td>
      <td>Supplier Name:</td>
      <td><input name="txtSupplierName" type="text" class = "bg" id="txtSupplierName" value="<?=$SupplierName?>" size="26" readonly="readonly"/></td>
    </tr>
    <tr>
      <td>Address</td>
      <td colspan="2"><label for="txtAddress"></label>
      <input name="txtAddress" type="text" class = "bg" id="txtAddress" value="<?=$Address?>" size="26" readonly="readonly" /></td>
      <td>Phone</td>
      <td><label for="txtPhone"></label>
      <input name="txtPhone" type="text" class = "bg" id="txtPhone" value="<?=$Phone?>" size="26" readonly="readonly" /></td>
    </tr>
    <tr>
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
			$_SESSION['LineItems'][$key]["QuantityOut"] = $_REQUEST['EditQuantityOut'];
			$_SESSION['LineItems'][$key]["WorkOrder"] = $_REQUEST['EditWorkOrder'];
			echo '<script>form1.submit();</script>';
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
			echo '<script>form1.submit();</script>';
		}
	}
	$_SESSION['ProductCode'] = ""; //CLEAR LINEITEM LIST OF VALUE
}
	//IF CLICK BUTTON ADD
  if(isset($_REQUEST['btAdd']) && $_POST['txtProductCode'] != "" && $_POST['txtWorkOrder'] != "" && $_POST['txtQuantityOut'] == "")
{
	$duplicate = false;
	//GET DATA
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"ProductType" => $_REQUEST['txtProductType'],
						"QuantityOut" => $_REQUEST['txtQuantityOut'],
						"WorkOrder" => $_REQUEST['txtWorkOrder']);
							
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
//Another Case I want to fixed my bug
else if( $_REQUEST['btAdd'] && $_POST['txtProductCode'] != "" && $_POST['txtQuantityOut'] != "" && $_POST['txtWorkOrder'] != "" )
{
	$duplicate = false;
	
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"ProductType" => $_REQUEST['txtProductType'],
						"QuantityOut" => $_REQUEST['txtQuantityOut'],
						"WorkOrder" => $_REQUEST['txtWorkOrder']);	
						
	if($_SESSION['LineItems'] != NULL)
	{
	$CheckLine = $_SESSION['LineItems'];					
	foreach($CheckLine as $key => $lineItem)
	{
		if ($lineItem['ProductCode']==$newLineItem['ProductCode'])
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
	if(!$duplicate)
	{
		$_SESSION['LineItems'][]= $newLineItem;
		
	}
	$_SESSION['ProductCode'] = ""; //CLEAR LINEITEM LIST OF VALUE
}
//BUG CASE
/*
//else if($_REQUEST['btAdd'] && $_POST['txtQuantityOut'] != "")
//{
	//CANNOT INPUT QUANTITY IN/OUT IN ONE PRODUCT CODE
//	echo "<script type=\"text/javascript\">";
//	echo "alert(\"Please input WorkOrder or QuantityOut \");";
//	echo "window.history.back();";
//	echo "</script>";
// } */
else if($_REQUEST['btAdd'] && $_POST['txtProductCode'] == "")
{
	//PRODUCT CODE EMPTY
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please input Product Code\");";
	echo "window.history.back();";
	echo "</script>";
}
/*else if($_REQUEST['btAdd'])
{
	//QUANTITY EMPTY
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please input quantity\");";
	echo "window.history.back();";
	echo "</script>";
} */
?>
      <td>Reason :</td>
      <td>
      <div align="left">
        <p>
          <input name="txtReason" type="text" class = "bg" id="txtReason" value="<?=$_SESSION['Reason']?>" size="26" readonly="readonly" />
        </p>
      </div>
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td></td>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  
</form>
  
  
  <form name="form2" id="form2" method="post" >
  <br />
  <br />
  <table width="786" border="2">
  <tr>
  <th width="78" scope="col"></th>
  <th width="78" scope="col"></th>
    
    <th width="120" scope="col"><font color="red">*</font>Product Code</th>
    <th width="213" scope="col">Product Name</th>
    <th width="123" scope="col"><font color="red">*</font>Quantity Out</th>
    <th width="132" scope="col"><font color="red">*</font>WO No.</th>
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
				$EditProductType = $rowData['ProductType'];
				$EditQuantityOut = $rowData['QuantityOut'];
				$EditWorkOrder = $rowData['WorkOrder'];
				
				
				//BELOW SHOW UPDATE AND CANCEL BUTTON
				?>  

        <tr>
                <td align= center><input type="button" width="30" height="30" id="btnUpdate" name="btnUpdate" value="Update" OnClick= "form2.cmd.value='Update';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;"></td>
                <td align= center><input type="button" name="btnCancel" id="btnCancel" value="Cancel" OnClick= "form2.submit();" style="width:65px;"/></td>
                
    <td align = center><?=$EditProductCode?></td>
    <td><?=$EditProductName?></td>
    <td align="center"><input name="EditQuantityOut" type="text" style="text-align:right; width:80px;" id="EditQuantityOut" value="<?=$EditQuantityOut?>" size="10" onkeyup="checkError('EditQuantityOut');" <?php if($selected == "Adjust in" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>/></td>
    <td align = "center"><input name="EditWorkOrder" type="text" class = "bg" id="EditWorkOrder" style="text-align:right; width:80px;"  value="<?=$EditWorkOrder?>" size="10" readonly="readonly" /></td>
    </tr>
            <?php
			}
			
			else //SHOW NORMAL LINEITEM (NOT EDIT)
			{
?>
				

				<tr>
                <td align= center><input type="button" name="btnEdit" id="btnEdit" value="Edit" OnClick= "form2.cmd.value='Edit';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;" /></td>
                <td align= center><input type="button" name="btnDelete" id="btnDelete" value="Delete" OnClick= "form2.cmd.value='Delete';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;"/></td>
                
                <td align = center><?=$rowData['ProductCode']?></td>
                <td><?=$rowData['ProductName']?></td>
                <td align="right"><?=$rowData['QuantityOut']?></td>
                <td align = "center"><?=$rowData['WorkOrder']?></td>
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
		$QuantityOut = "";
		$WorkOrder = "";
	}
  ?>
  
  <tr>
   <td>&nbsp;</td>
   	<td align= center><input type="submit" name="btAdd" id="btAdd" value="ADD" style="width:65px;"/></td>

    <td><label for="txtProductCode"></label>
      <input name="txtProductCode" type="text" id="txtProductCode" value="<?=$ProductCode?>" size="10" required />
      <input type="button" name="btProduct" id="btProduct" value="..." onclick="window.open('ListofProductIssueWO.php','popup','width=610,height=500,scrollbars');" /></td>
    
    <td align= center><label for="txtProductName"></label>
    <input name="txtProductName" class = "bg" type="text" id="txtProductName" style="width:180px;" value="<?=$ProductName?>" readonly="readonly"/></td>
    <td align= "center"><label for="txtQuantityOut"></label>
      <input  name="txtQuantityOut" type="text" id="txtQuantityOut" style="text-align:right; width:80px;" value="<?=$QuantityOut?>" onkeyup="checkError('txtQuantityOut');" <?php if($selected == "Adjust in" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>/></td>
    
    <td><label for="txtWorkOrder"></label>
      <input name="txtWorkOrder" type="text" id="txtWorkOrder" value="<?=$_SESSION['WorkOrder']?>" size="10" required="required" />
      <input type="button" name="btWorkOrder" id="btWorkOrder" value="..." onclick="window.open('ListofWorkOrderIssueWO.php','popup','width=610,height=500,scrollbars');" /></td>
    </tr>

</table>
	<p><!-- HIDDEN FIELD IT GET DATA WHEN YOU CLICK ALL BUTTON TO DO ANYTHING -->
	  <input name="data" type="hidden" id="data" value="" >
	  <input name="cmd" type="hidden" id="cmd" value="" >
      
      
	</p>
</form>

</body>
</html>