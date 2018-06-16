<?php 
	session_start();
	include ("Connectdb.php");
	
	$sql=	"SELECT StockNo,Date,Reason ".
			"FROM Inventory WHERE StockNo like('%MF%') OR StockNo like('%MR%') OR StockNo like('%WK%')OR StockNo like('%WR%') OR StockNo like('%PO%') OR StockNo like('%PR%') ORDER BY StockNo";
		
	$result=mysql_query($sql,$conn);
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>List Of Stock</title>
<style type="text/css">
<!--
.style1 {color: #000;
	font-weight: bold;
	font-size: 24px;
}
-->
</style>
</head>

<script language="javascript">
/*
function setinvoice(product)
{
	window.opener.location.href="SR_View.php?mode=new&product="+product;
	window.close();
}*/

function setinvoice(StockNo)
{
	window.opener.location.href="reportMO.php?txtRPStockNo="+StockNo;
	window.close();
}
</script>



<body>
<span class="style1">Stock</span><br />
  <br />
  <table width="400" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <tr >
      <th>
        <div align="center">Stock No</div>
      </th>
      <th>
        <div align="center">Date</div>
      </th>
      <th>Reason</th>
    </tr>
<?php

  	$dataHtml="";
	while(list($StockNo, $Date,$Reason)=mysql_fetch_row($result))
	{
 
	
		$dataHtml.="<tr>";
		$dataHtml.="<td><a href='javascript:setinvoice(\"$StockNo\");'>$StockNo</a>";
		$dataHtml.="<td>$Date</td>";
		$dataHtml.="<td>$Reason</td>";
		$dataHtml.="</tr>";
 	}
	echo $dataHtml;
	mysql_free_result($result);
?>
  </table>
</body>
</html>
