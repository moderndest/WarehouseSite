<?php

if (isset($_GET['StockNo']) && trim($_GET['StockNo'])!='')
{
$Stock=$_GET['StockNo'];
$_SESSION['txtRPStockNo']=$Stock;
}
?>

<?php

if (isset($_GET['StockNo']) && trim($_GET['StockNo'])!='')
{
$check1 = 'checked';
$Stock=$_GET['StockNo'];
$_SESSION['txtRPStockNo']=$Stock;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Monthly Report</title>

<style type="text/css">
<!--
.style4 {color: #9CB5C6}
.style5 {
font-family: Arial, Helvetica, sans-serif;
font-size: 14px;
font-weight: bold;
}
.style7 {font-family: Arial, Helvetica, sans-serif}
.style8 {font-size: 14px}
.style9 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }

-->
</style>
<script language="javascript">
function getCustomer()//get EmployeeCode
{
var CustomerNo = document.getElementById("CustomerNo").value;
window.location = "Report.php?CustomerNo="+CustomerNo;
}
function showListOfCustomer()
{
window.open("CustomerListReport.php","popup","width=500,height=500");
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<table width="314" border="0">
<tr>
<th width="308" class="style5" scope="col">&nbsp;</th>
</tr>
</table>

<form name="form1" id="form1" method="post" action="pdfGen.php" target="_self">
<table width="730" border="0">
<tr>
<th width="100" scope="col"><table width="730" border="0">
<tr>
<th colspan="7" scope="col"><div align="justify"><span class="style9">

<input name="radio" type="radio" id="Choice" value="1" checked="checked" />
<label for="Choice">Report Form No :</label>
</span></div></th>
</tr>
<tr>
<th width="100" scope="col"><div align="left" class="style5"><strong>Stock No : </strong></div></th>
<th width="100" scope="col"><div align="left" class="style8 style7">
<label for="txtRPStockNo"></label>
<input name="txtRPStockNo" type="text" id="txtRPStockNo" value="<?=$Stock?>" />
</div></th>
<th width="100" scope="col"><div align="left" class="style5">
<input type="button" name="btnStock" id="btnStock" value="..." onclick="window.open('Stock.php','popup','width=650,height=500,scrollbars');"/>
</div></th>
</tr>
<tr>
<td colspan="7">&nbsp;</td>
</tr>
<td colspan="7">-----------------------------------------</td>
<tr>
<td colspan="7"><div align="left" class="style9">
<input name="radio" type="radio" id="Choice2" value="2" />
<strong>Report in Month :</strong> </div>
<div align="left" class="style9"></div>
<div align="left"></div></td>
</tr>


<tr>
<th width="100" scope="col"><div align="left" class="style5"><strong>Month Start : </strong></div></th>
<th width="100" scope="col"><div align="left" class="style8 style7"><strong>
<select name="month">
<option value="13"></option>
<option value="1">January</option>
<option value="2">Febuary</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</strong></div></th>
<th width="100" scope="col"><div align="left" class="style5"><strong>Between</strong></div></th>
</tr>
<tr>
<td><div align="left" class="style9"><strong>Year : </strong></div></td>
<td><div align="left" class="style9"><strong>
<select name="year">
<option value="13"></option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
</select>
</strong></div></td>
<td><div align="left"></div></td>
</tr>
<tr>
<td><div align="left" class="style5"><strong>Month Stop : </strong></div></td>
<td><div align="left" class="style9"><strong><strong>
<select name="month2">
<option value="13"></option>
<option value="1">January</option>
<option value="2">Febuary</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</strong> </strong></div></td>
<td><div align="left"></div></td>
</tr>
<tr>
<td><div align="left" class="style9"><strong>Year : </strong></div></td>
<td><div align="left" class="style9"><strong>
<select name="year2">
<option value="13"></option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
</select>
</strong></div></td>
<td><div align="left"></div></td>
</tr>
<td colspan="7">&nbsp;</td>
</tr>
<td colspan="7">-----------------------------------------</td>
<td colspan="7">&nbsp;</td>
<tr>
<td colspan="7"><div align="left" class="style9">
<input type="radio" name="radio" id="Choice3" value="3" />
<label for="Choice3"><strong>Report summary of each Product in Month :</strong></label>
</div>
<div align="left"></div></td>
</tr>
<tr>
<th width="100" scope="col"><div align="left" class="style5"><strong>Month Start : </strong></div></th>
<th width="100" scope="col"><div align="left" class="style8 style7"><strong>
<select name="month3">
<option value="13"></option>
<option value="1">January</option>
<option value="2">Febuary</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</strong></div></th>

</tr>
<tr>
<td><div align="left" class="style9"><strong>Year : </strong></div></td>
<td><div align="left" class="style9"><strong>
<select name="year3">
<option value="13"></option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
</select>
</strong></div></td>
<td><div align="left"></div></td>

<td><div align="left"></div></td>
</tr>


<tr>
<td><div align="left"></div></td>
<td><div align="left">
<input type="submit" name="Submit" value="Report" />
</div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><div align="left"></div></td>
</tr>
</table></th>
</table>
</form>
</body>

</html>