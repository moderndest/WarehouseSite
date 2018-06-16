<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script language="javascript">
function setInvoice(Invoice)
{
	window.opener.location.href="main.php?txtStockNo="+	Invoice;
	window.close();
}
</script>
</head>
<?php
include 'Connectdb.php';
 	$sql = "SELECT * FROM inventory WHERE StockNo LIKE 'SR%'";
	$result = mysql_query($sql);
	
?>
<body>
<h1>Invoice</h1>

<form id="form1" name="form1" method="post" action="">
  <table width="600" border="2">
    <tr>
      <th width="95" scope="col">Stock No.</th>
      <th width="165" scope="col">Date</th>
      <th width="165" scope="col">Warehouse Code</th>
      <th width="165" scope="col">Customer Code</th>
      <th width="32" scope="col">&nbsp;</th>
    </tr>
    <?php
	while($column = mysql_fetch_row($result))
              {
	?>
    <tr>
      <td height="28"><?=$column[0]?></td>
      <td><?=$column[1]?></td>
      <td><?=$column[2]?></td>
      <td><?=$column[4]?></td>
      <td><input type="button" name="button" id="button" value="Choose" onclick = "setInvoice('<?=$column[0]?>');" /></td>
    </tr>
    <?php }
	?>
  </table>
</form>

<?php
	mysql_close($conn);
?>
</body>
</html>