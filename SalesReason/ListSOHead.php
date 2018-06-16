<?php
	//require("Config.php");
	require("Connectdb.php");

		 $sql="SELECT * FROM sohead ".
		 "ORDER BY SOCOde ASC";

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
function setSOCode(SOCode)
{
	window.opener.location.href="main.php?txtSOCode="+ SOCode;
	window.close();
}
</script>

<!----------------------------------------------------------------------------------------------------------------------------------->


</head>

<body>
<h1> Sale Order Number</h1>
<form name="form1" method="post" action="">
  <table width="201" border="1">
    <tr>
      <th width="134" scope="col">Sale Order Number</th>
    </tr>
	
<!----------------------------------------------------------------------------------------------------------------------------------->

<?php
 	$dataHtml="";
	while(list($SOCode)=mysql_fetch_row($result))
	{
		$dataHtml.="<tr>\n";
		$dataHtml.="<td><div align=\"center\"><a href='javascript:setSOCode(\"$SOCode\");'>$SOCode</a></div></td>\n";
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