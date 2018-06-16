<?php
	//require("Config.php");
	require("Connectdb.php");

		 $sql="SELECT * FROM pohead ".
		 "ORDER BY PONo ASC";

	$result = mysql_query($sql,$conn);
?>

<!----------------------------------------------------------------------------------------------------------------------------------->


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>List of PO</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!----------------------------------------------------------------------------------------------------------------------------------->

<script language="javascript">
function setInvoiceNo(PONo)
{
	window.opener.location.href="PO.php?PONo="+PONo;
	window.close();
}
</script>

<!----------------------------------------------------------------------------------------------------------------------------------->


</head>

<body>
<h1> Product Number</h1>
<form name="form1" method="post" action="">
  <table width="201" border="1">
    <tr>
      <th width="134" scope="col">PO No</th>
    </tr>
	
<!----------------------------------------------------------------------------------------------------------------------------------->

<?php
 	$dataHtml="";
	while(list($PONo)=mysql_fetch_row($result))
	{
		$dataHtml.="<tr>\n";
		$dataHtml.="<td><div align=\"center\"><a href='javascript:setInvoiceNo(\"$PONo\");'>$PONo</a></div></td>\n";
		$dataHtml.="</tr>\n";
	}
	echo $dataHtml;
	
	mysql_free_result($result);
?>

<!----------------------------------------------------------------------------------------------------------------------------------->

  </table>
</form>
</body>
</html>
