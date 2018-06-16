<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List of Supplier</title>
<script language="javascript">
function setCustomerID(CustomerID)
{
	window.opener.location.href="main.php?txtCustomerCode="+ CustomerID;
	window.close();
}
</script>
</head>
<?php
include 'Connectdb.php';
 	$sql = "SELECT * FROM customer";
	$result = mysql_query($sql);
	
?>
<body>
<h1> List of Customer</h1>

<form id="form1" name="form1" method="post" action="">
  <table width="562" border="2">
    <tr>
      <th width="90" scope="col">Customer Code</th>
      <th width="165" scope="col">Name</th>
      <th width="200" scope="col">Address</th>
      <th width="110" scope="col">Phone</th>
    </tr>

<?php
 	$dataHtml="";
	while(list($CustomerCode,$CustomerName,$Address,$Phone)=mysql_fetch_row($result))
	{
		$dataHtml.="<tr>\n";
		$dataHtml.="<td><div align=\"center\"><a href='javascript:setCustomerID(\"$CustomerCode\");'>$CustomerCode</a></div></td>";
		$dataHtml.="<td><div align=\"center\">$CustomerName</div></td>";
		$dataHtml.="<td><div align=\"center\">$Address</div></td>";
		$dataHtml.="<td><div align=\"center\">$Phone</div></td>\n";
		$dataHtml.="</tr>\n";
	}
	echo $dataHtml;
	
	mysql_free_result($result);
?>
  </table>
</form>
</body>
</html>