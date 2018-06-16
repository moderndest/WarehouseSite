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

</script>
<style type="text/css">

.style7 {font-size: 40px;}

.bg {
	background-color:#DCDCDC ;
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
	$sql = "SELECT  Date,Reason,I.WarehouseCode,WarehouseName,WarehouseAddress,Phone FROM inventory I JOIN warehouse W on I.WarehouseCode = W.WarehouseCode WHERE StockNo = '".$StockNo."'  ";
        		$result = mysql_query($sql);
        		while ($r=mysql_fetch_assoc($result))
        		{
				$_SESSION['Date'] = $r['Date'] ;
				$_SESSION['ddlReason'] =$r['Reason'];
				//$AdjustReason =$r['AdjustReason'];
				$_SESSION['WarehouseCode'] =$r['WarehouseCode'];
				$WarehouseName =$r['WarehouseName'];
				$WarehouseAddress =$r['WarehouseAddress'];
				$Phone =$r['Phone'];
				
				
					
        		}
    //CLEAR OLD LINEITEM
	unset($_SESSION['LineItems']);			
	//SELECT NEW LINEITEM
	  $sql = "SELECT P.ProductCode,ProductName,ProductType,QuantityIn,QuantityOut FROM InventoryLine I JOIN Product P ON I.ProductCode = P.ProductCode WHERE StockNo ='".$StockNo."'";
	  $result=mysql_query($sql);
	  //GET DATA
	  while($row=mysql_fetch_row($result))
	  {
		$_SESSION['LineItems'][]=array("ProductCode" => $row[0],
									"ProductName" => $row[1],
									"ProductType" => $row[2],
									"QuantityIn" => $row[3],
									"QuantityOut" => $row[4]);
							
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
			$_SESSION['StockNo'] = "NEW";
	}

//IF CHOOSE WarehouseCode
if(isset($_REQUEST['WarehouseCode']))
{
   $_SESSION['WarehouseCode']= $_REQUEST['WarehouseCode'];
   $WarehouseCode=$_REQUEST['WarehouseCode'];	
}
//IF CHOOSE ProductCode
if(isset($_REQUEST['ProductCode']) && $_REQUEST['cmd']!='Edit')
{
   $_SESSION['ProductCode']= $_REQUEST['ProductCode'];
   $ProductCode=$_REQUEST['ProductCode'];	
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
<label><div align="left"><span class="style7">ADJUST IN / OUT</span>  </div></label>

    <input type="button" value="NEW" onClick="window.location = 'clear.php'" style="width:65px;">
    <input type="submit" value="SAVE" formaction = "save.php" formmethod = "get" formtarget = "_blank"/>
    <input type="submit" name="btnOpen" id="btnOpen" value="OPEN" style="width:65px;" onclick="window.open('ListofStock.php','popup','width=610,height=500,scrollbars');"/>
    <input type="submit" value="DELETE" formaction="delete.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
    <input type="submit" value="PRINT" formaction="report.php" formmethod="get" formtarget="_blank" style="width:65px;"/>
    <a href="http://localhost/warehouse/index.php">
  <input type="button" name="Back" id="Back" value="BACK" />
  </a>
  <!-- ----------- --></p>
  <table width="900" border="0">
    <tr>
      <td width="183">*Stock No. :</td>
      <td width="168"><label for="txtStockNo"></label>
      <input name="txtStockNo" type="text" id="txtStockNo" value="<?=$_SESSION['StockNo']?>" readonly="readonly" class = "bg"/></td>
      <td width="88">&nbsp;</td>
      <td width="270">*Date :</td>
      <td width="169"><input name="txtDate" type="date" width="100" id="txtDate" value="<?=$_SESSION['Date']?>" /></td>
    </tr>
    <tr>
      
    </tr>
    <tr>
      <td>*WarehouseCode</td>
      <td colspan="2"><label for="txtWarehouseCode"></label>
      <input name="txtWarehouseCode" type="text" id="txtWarehouseCode" value="<?=$WarehouseCode?>"  readonly="readonly" />
      
      <input type="button" name="btCustomer" id="btCustomer" value="..." onclick="window.open('Listofwarehouse.php','popup','width=610,height=500,scrollbars');">
      </td>
      <td>Warehouse Name:</td>
      <td><input name="txtWarehouseName" type="text" class = "bg" id="txtWarehouseName" value="<?=$WarehouseName?>" readonly="readonly"/></td>
    </tr>
    <tr>
      <td>WarehouseAddress</td>
      <td colspan="2"><label for="txtWarehouseAddress"></label>
      <input name="txtWarehouseAddress" class = "bg" type="text" id="txtWarehouseAddress" value="<?=$WarehouseAddress?>" readonly="readonly" /></td>
      <td>Phone</td>
      <td><label for="txtPhone"></label>
      <input name="txtPhone" type="text" class = "bg" id="txtPhone" value="<?=$Phone?>" readonly="readonly" /></td>
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
			$_SESSION['LineItems'][$key]["QuantityOut"] = $_REQUEST['EditQuantityOut'];
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
  if(isset($_REQUEST['btAdd']) && $_POST['txtProductCode'] != "" && $_POST['txtQuantityIn'] != "" && $_POST['txtQuantityOut'] == "")
{
	$duplicate = false;
	//GET DATA
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"ProductType" => $_REQUEST['txtProductType'],
						"QuantityIn" => $_REQUEST['txtQuantityIn'],
						"QuantityOut" => $_REQUEST['txtQuantityOut']);	
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
else if( $_REQUEST['btAdd'] && $_POST['txtProductCode'] != "" && $_POST['txtQuantityIn'] == "" && $_POST['txtQuantityOut'] != "")
{
	$duplicate = false;
	
	$newLineItem=array( "ProductCode" => $_REQUEST['txtProductCode'],
						"ProductName" => $_REQUEST['txtProductName'],
						"ProductType" => $_REQUEST['txtProductType'],
						"QuantityIn" => $_REQUEST['txtQuantityIn'],
						"QuantityOut" => $_REQUEST['txtQuantityOut']);	
						
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
else if($_REQUEST['btAdd'] && $_POST['txtQuantityIn'] != "" && $_POST['txtQuantityOut'] != "")
{
	//CANNOT INPUT QUANTITY IN/OUT IN ONE PRODUCT CODE
	echo "<script type=\"text/javascript\">";
	echo "alert(\"Please input QuantitIn only or QuantityOut only\");";
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
	echo "alert(\"Please input quantity\");";
	echo "window.history.back();";
	echo "</script>";
}
?>
      <td>*Reason(Select to Add) :</td>
      <td>
      <div align="left">
        <p>

        <?php
		
			if($_SESSION['LineItems'] != NULL)
			{
				$ddlCheck = true;
			}
		
		?>
        	<!-- Drop Down -->
			<select name="ddlReason" style="width:100px;" id="ddlReason" onchange="submit();" <?php if($ddlCheck) echo 'disabled'; ?>>
            <option value ="select">Select</option>
      	      <?php
			  	include 'Connectdb.php';
			  	//GET YOUR SELECT DATA
			  	if(isset($_SESSION['ddlReason']))
					$selected = $_SESSION['ddlReason'];
				else
					$selected = "select";
				//SELECT ITEM
				$_SESSION['ddlReason'] =  $selected;
				$sql= "SELECT Reason FROM reason WHERE Reason like('%Ad%')";
				$result = mysql_query($sql,$conn);

				while($data= mysql_fetch_assoc($result))
				{
					echo "<option value ='".$data['Reason']; //.">"
					if($data['Reason'] == $selected) 
						echo "' selected>";
					else 
						echo "' >";
					echo $data['Reason']."</option>";
				}
				mysql_close($conn);
			  ?>
            </select>
              
       </p></div>
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label for="txtAdjustReason"></label></td>
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
  <table width="846" border="2">
  <tr>
  <th width="78" scope="col"></th>
  <th width="78" scope="col"></th>
    
    <th width="120" scope="col">*Product Code</th>
    <th width="222" scope="col">Product Name</th>
    <th width="100" scope="col">Product Type</th>
    <th width="100" scope="col">Quantity In</th>
    <th width="100" scope="col">Quantity Out</th>
   
    
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
				$EditQuantityOut = $rowData['QuantityOut'];
				
				//BELOW SHOW UPDATE AND CANCEL BUTTON
				?>  

        <tr>
                <td align= center><input type="button" width="30" height="30" id="btnUpdate" name="btnUpdate" value="Update" OnClick= "form2.cmd.value='Update';form2.data.value='<?=$rowData['ProductCode']?>';form2.submit();" style="width:65px;"></td>
                <td align= center><input type="button" name="btnCancel" id="btnCancel" value="Cancel" OnClick= "form2.submit();" style="width:65px;"/></td>
                
    <td align = center><?=$EditProductCode?></td>
    <td><?=$EditProductName?></td>
    <td align = center><?=$EditProductType?></td>
    <td align="center"><input name="EditQuantityIn" style="text-align:right; width:80px;" type="text" id="EditQuantity" onchange="Editcalculateprice()" value="<?=$EditQuantityIn?>" size="5" onkeyup="checkError('EditQuantity');" <?php if($selected == "Adjust out" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>/></<?php if($selected == "Adjust in" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>td>
<td align="center">
<input name="EditQuantityOut" type="text" style="text-align:right; width:80px;" id="EditQuantityOut" value="<?=$EditQuantityOut?>" size="10" onkeyup="checkError('EditQuantityOut');" <?php if($selected == "Adjust in" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>/></td>
    
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
                <td align = center><?=$rowData['ProductType']?></td>
                <td align="right"><?=$rowData['QuantityIn']?></td>
                <td align="right"><?=$rowData['QuantityOut']?></td>
                
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
  
  <tr>
   <td>&nbsp;</td>
   	<td align= center><input type="submit" name="btAdd" id="btAdd" value="ADD" style="width:65px;"/></td>

    <td><label for="txtProductCode"></label>
      <input name="txtProductCode" type="text" id="txtProductCode" value="<?=$ProductCode?>" size="6" required />
      <input type="button" name="btProduct" id="btProduct" value="..." onclick="window.open('ListofProduct.php','popup','width=610,height=500,scrollbars');" /></td>
    
    <td align= center><label for="txtProductName"></label>
    <input name="txtProductName" class = "bg" type="text" id="txtProductName" style="width:180px;" value="<?=$ProductName?>" readonly="readonly"/></td>
    
    <td align= center><label for="txtPizzaCrust"></label> 
	<input name="txtProductType" type="text" class = "bg" id="txtProductType" style="width:80px;" value="<?=$ProductType?>" readonly="readonly"/></td>
    
    <td align= center>
	<input name="txtQuantityIn"  type="text" style="text-align:right; width:80px;" id="txtQuantityIn" value="<?=$QuantityIn?>" onkeyup="checkError('txtQuantityIn');" <?php if($selected == "Adjust out" || $selected == "select") echo 'class = "bg" readonly="readonly"' ; ?>/></td>
          
    <td align= center><label for="txtQuantityOut"></label>
      <input  name="txtQuantityOut" type="text" id="txtQuantityOut" style="text-align:right; width:80px;" value="<?=$QuantityOut?>" onkeyup="checkError('txtQuantityOut');" <?php if($selected == "Adjust in" || $selected == "select") echo 'class = "bg" readonly="readonly"'; ?>/></td>
    
    
  </tr>

</table>
	<p><!-- HIDDEN FIELD IT GET DATA WHEN YOU CLICK ALL BUTTON TO DO ANYTHING -->
	  <input name="data" type="hidden" id="data" value="" >
	  <input name="cmd" type="hidden" id="cmd" value="" >
      
      
	</p>
</form>

</body>
</html>