<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List of Product</title>
<script language="javascript">
function setProductCode(ProductCode)
{
	window.opener.location.href="MR.php?txtProductCode="+	ProductCode;
	window.close();
}
</script>
</head>
<?php
include 'connectdb.php';
 	$sql = "SELECT * FROM product";
	$result = mysql_query($sql);
	
?>
<body>
<h1> List of Product</h1>

<form id="form1" name="form1" method="post" action="">
  <table width="511" border="2">
    <tr>
      <th width="106" scope="col">Product Code</th>
      <th width="116" scope="col">Name</th>
      <th width="184" scope="col">Type</th>
      <th width="75" scope="col">Unit Price</th>
    </tr>
    <?php
	$dataHtml="";
	while(list($ProductCode,$ProductName,$ProductType,$UnitPrice)=mysql_fetch_row($result))
	{
		$dataHtml.="<tr>\n";
		$dataHtml.="<td ><div align=\"center\"><a href='javascript:setProductCode(\"$ProductCode\");'>$ProductCode</a></div></td>";
		$dataHtml.="<td ><div align=\"center\">$ProductName</div></td>";
		$dataHtml.="<td ><div align=\"center\">$ProductType</div></td>";
		$dataHtml.="<td ><div align=\"center\">$UnitPrice</div></td>\n";
		$dataHtml.="</tr>\n";
	}
	echo $dataHtml;
	
	mysql_free_result($result);
	?>

  </table>
</form>
</body>
</html>