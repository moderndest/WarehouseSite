<?php
	//require("Config.php");
	require("Connectdb.php");

		 $sql="SELECT * FROM wohead ".
		 "ORDER BY WONo ASC";

	$result = mysql_query($sql,$conn);
?>

<!----------------------------------------------------------------------------------------------------------------------------------->


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>List of WO</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!----------------------------------------------------------------------------------------------------------------------------------->

<script language="javascript">
function setInvoiceNo(WONo)
{
	window.opener.location.href="WO.php?WONo="+WONo;
	window.close();
}
</script>

<!----------------------------------------------------------------------------------------------------------------------------------->


</head>

<body>
<h1> Work Order Number</h1>
<form name="form1" method="post" action="">
  <table width="201" border="1">
    <tr>
      <th width="134" scope="col">WO No</th>
    </tr>
	
<!----------------------------------------------------------------------------------------------------------------------------------->

<?php
 	$dataHtml="";
	while(list($WONo)=mysql_fetch_row($result))
	{
		$dataHtml.="<tr>\n";
		$dataHtml.="<td ><div align=\"center\"><a href='javascript:setInvoiceNo(\"$WONo\");'>$WONo</a></div></td>\n";
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
