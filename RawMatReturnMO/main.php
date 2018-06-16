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
<title>Raw Material Return MO</title>

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

	include 'Connectdb.php';
	//SELECT HEADER
	$sql = "SELECT  Date,Reason FROM Inventory I WHERE StockNo = '".$StockNo."'  ";
        		$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				$_SESSION['Date'] = $r['Date'] ;
				$_SESSION['Reason'] =$r['Reason'];
					
        		}
    //CLEAR OLD LINEITEM
	unset($_SESSION['LineItems']);			
	//SELECT NEW LINEITEM
	  $sql = "SELECT P.ProductCode,ProductName,QuantityIn,ManufactureOrder FROM InventoryLine I JOIN Product P ON I.ProductCode = P.ProductCode WHERE StockNo ='".$StockNo."'";
	  $result=mysql_query($sql);
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1],
									"QuantityIn" => $row[2],
									"ManufactureOrder" => $row[3],
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
			$_SESSION['Reason'] = "Raw Material Return MO";
		}
		
	}
if($_SESSION['StockNo'] == "NEW")
		{	
			$Reason ="Raw Material Return MO";
			$_SESSION['Reason'] = $Reason;
			}
/*
//IF CHOOSE SupplierCode
if(isset($_REQUEST['SupplierCode']))
{
   $_SESSION['SupplierCode']= $_REQUEST['SupplierCode'];
   $SupplierCode=$_REQUEST['SupplierCode'];	
}  */
//IF CHOOSE ProductCode
if(isset($_REQUEST['ProductCode']) && $_REQUEST['cmd']!='Edit')
{
   $_SESSION['ProductCode']= $_REQUEST['ProductCode'];
   $ProductCode=$_REQUEST['ProductCode'];	
}
//IF CHOOSE ManufactureOrder
if(isset($_REQUEST['MONo']) && $_REQUEST['cmd']!='Edit')
{
   $_SESSION['ManufactureOrder']= $_REQUEST['MONo'];
   $ManufactureOrder=$_REQUEST['MONo'];	
}

//GET DATA FROM SESSION
//$SupplierCode=$_SESSION['SupplierCode'];
$ProductCode=$_SESSION['ProductCode'];
$ManufactureOrder=$_SESSION['ManufactureOrder'];

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
//IF $ManufactureOrder have data
if($ManufactureOrder!= NULL)
{
	include 'Connectdb.php';
       $sql="SELECT MONo FROM MOhead WHERE MONo = '".$ManufactureOrder. "' ";
	   $result=mysql_query($sql);
	   while($r=mysql_fetch_assoc($result))
	   {
		   $ManufactureOrder=$r['ManufactureOrder']; 
	   }
	   mysql_close($conn);
}

?>
<!----------------------------------------------------->

<body bgcolor="#99FFCC">

<h1>
  <!--------------- add button -------------->
  
Raw Material Return MO</h1>
<form id="form1" name="form1" action="">
  
  <!-- HEAD BUTTON -->
  <input type="button" value="NEW" onClick="window.location = 'clear.php'" style="width:65px;">
<input type="submit" value="SAVE" formaction = "save.php" formmethod = "get" formtarget = "_blank"/>
<input type="submit" name="btnOpen" id="btnOpen" value="OPEN" style="width:65px;" onclick="window.open('ListofStock.php','popup','width=610,height=500,scrollbars');"/>
<input type="submit" value="DELETE" formaction="delete.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
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
			$_SESSION['LineItems'][$key]["ManufactureOrder"] = $_REQUEST['EditManufactureOrder'];
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
  if(isset($_REQUEST['btAdd']) && $_POST['txtProductCode'] != "" && $_POST['txtManufactureOrder'] != "" && $_POST['txtQuantityIn'] == "")
{
	$duplicate = false;
	//GET DATA
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"ProductType" => $_REQUEST['txtProductType'],
						"QuantityIn" => $_REQUEST['txtQuantityIn'],
						"ManufactureOrder" => $_REQUEST['txtManufactureOrder']);
							
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
else if( $_REQUEST['btAdd'] && $_POST['txtProductCode'] != "" && $_POST['txtQuantityIn'] != "" && $_POST['txtManufactureOrder'] != "" )
{
	$duplicate = false;
	
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"ProductType" => $_REQUEST['txtProductType'],
						"QuantityIn" => $_REQUEST['txtQuantityIn'],
						"ManufactureOrder" => $_REQUEST['txtManufactureOrder']);	
						
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
//else if($_REQUEST['btAdd'] && $_POST['txtQuantityIn'] != "")
//{
	//CANNOT INPUT QUANTITY IN/In IN ONE PRODUCT CODE
//	echo "<script type=\"text/javascript\">";
//	echo "alert(\"Please input ManufactureOrder or QuantityIn \");";
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
  <table width="809" border="2">
  <tr>
  <th width="78" scope="col"></th>
  <th width="78" scope="col"></th>
    
    <th width="120" scope="col"><font color="red">*</font>Product Code</th>
    <th width="213" scope="col">Product Name</th>
    <th width="123" scope="col"><font color="red">*</font>Quantity In</th>
    <th width="155" scope="col"><font color="red">*</font>MO No.</th>
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
				$EditQuantityIn = $rowData['QuantityIn'];
				$EditManufactureOrder = $rowData['ManufactureOrder'];
				
				
				//BELOW SHOW UPDATE AND CANCEL BUTTON
				?>  

        <tr>
                <td align= center><input type="button" width="30" height="30" id="btnUpdate" name="btnUpdate" value="Update" OnClick= "form2.cmd.value='Update';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;"></td>
                <td align= center><input type="button" name="btnCancel" id="btnCancel" value="Cancel" OnClick= "form2.submit();" style="width:65px;"/></td>
                
    <td align = center><?=$EditProductCode?></td>
    <td><?=$EditProductName?></td>
    <td align="center"><input name="EditQuantityIn" type="text" style="text-align:right; width:80px;" id="EditQuantityIn" value="<?=$EditQuantityIn?>" size="10" onkeyup="checkError('EditQuantityIn');" /></td>
    <td align = "center"><input name="EditManufactureOrder" type="text" class = "bg" id="EditManufactureOrder" style="text-align:right; width:80px;"  value="<?=$EditManufactureOrder?>" size="10" readonly="readonly" /></td>
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
                <td align="right"><?=$rowData['QuantityIn']?></td>
                <td align = "center"><?=$rowData['ManufactureOrder']?></td>
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
		$ManufactureOrder = "";
	}
  ?>
  
  <tr>
   <td>&nbsp;</td>
   	<td align= center><input type="submit" name="btAdd" id="btAdd" value="ADD" style="width:65px;"/></td>

    <td><label for="txtProductCode"></label>
      <input name="txtProductCode" type="text" id="txtProductCode" value="<?=$ProductCode?>" size="10" required />
      <input type="button" name="btProduct" id="btProduct" value="..." onclick="window.open('ListofProduct.php','popup','width=610,height=500,scrollbars');" /></td>
    
    <td align= center><label for="txtProductName"></label>
    <input name="txtProductName" class = "bg" type="text" id="txtProductName" style="width:180px;" value="<?=$ProductName?>" readonly="readonly"/></td>
    <td align= "center"><label for="txtQuantityIn"></label>
      <input  name="txtQuantityIn" type="text" id="txtQuantityIn" style="text-align:right; width:80px;" value="<?=$QuantityIn?>" onkeyup="checkError('txtQuantityIn');" <?php if($selected == "Adjust in" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>/></td>
    
    <td><label for="txtManufactureOrder"></label>
      <input name="txtManufactureOrder" type="text" id="txtManufactureOrder" value="<?=$_SESSION['ManufactureOrder']?>" size="10" required="required" />
      <input type="button" name="btManufactureOrder" id="btManufactureOrder" value="..." onclick="window.open('ListofMO.php','popup','width=610,height=500,scrollbars');" /></td>
    </tr>

</table>
	<p><!-- HIDDEN FIELD IT GET DATA WHEN YOU CLICK ALL BUTTON TO DO ANYTHING -->
	  <input name="data" type="hidden" id="data" value="" >
	  <input name="cmd" type="hidden" id="cmd" value="" >
      
      
	</p>
</form>

</body>
</html>