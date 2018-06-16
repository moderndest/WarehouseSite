<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List of Warehouse</title>
<script language="javascript">
function setWarehouseID(WarehouseID)
{
	window.opener.location.href="PO.php?txtWarehouseCode="+	WarehouseID;
	window.close();
}
</script>
</head>
<?php
include 'Connectdb.php';
 	$sql = "SELECT * FROM warehouse";
	$result = mysql_query($sql);
	
?>
<body>
<h1> List of Warehouse</h1>

<form id="form1" name="form1" method="post" action="">
  <table width="562" border="2">
    <tr>
      <th width="95" scope="col">Warehouse Code</th>
      <th width="165" scope="col">Name</th>
      <th width="86" scope="col">Address</th>
      <th width="186" scope="col">Phone</th>
    </tr>
<?php
 	$dataHtml="";
	while(list($WarehouseCode,$WarehouseName,$WarehouseAddress,$Phone)=mysql_fetch_row($result))
	{
		$dataHtml.="<tr>\n";
		$dataHtml.="<td ><div align=\"center\"><a href='javascript:setWarehouseID(\"$WarehouseCode\");'>$WarehouseCode</a></div></td>";
		$dataHtml.="<td ><div align=\"center\">$WarehouseName</div></td>";
		$dataHtml.="<td><div align=\"center\">$WarehouseAddress</div></td>";
		$dataHtml.="<td ><div align=\"center\">$Phone</div></td>\n";
		$dataHtml.="</tr>\n";
	}
	echo $dataHtml;
	
	mysql_free_result($result);
?>
  </table>
</form>
</body>
</html>