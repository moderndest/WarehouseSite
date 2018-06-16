<?php 
	session_start();
	include ("ConnectdbIssueWO.php");
	
	$sql=	"SELECT * ".
			"FROM WOhead ORDER BY WONo";
		
	$result=mysql_query($sql,$conn);
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>List Of WorkOrder</title>
<style type="text/css">
<!--
.style1 {color: #FF6600;
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

function setinvoice(WONo)
{
	window.opener.location.href="mainIssueWO.php?WONo="+WONo;
	window.close();
}
</script>
<body>
<span class="style1"><font color="#000000">List of WorkOrder</font></span><br />
  <br />
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" >
    <tr bgcolor="#FFFFFF" align="center">
      <th>
        <div align="center">WorkOder No</div>
      </th>
    </tr>
<?php

  	$dataHtml="";
	while(list($WorkOrder)=mysql_fetch_row($result))
	{
 
	
		$dataHtml.="<tr>";
		$dataHtml.="<td><a href='javascript:setinvoice(\"$WorkOrder\");'>$WorkOrder</a>";
		//$dataHtml.="<td>$ProductName</td>";
		$dataHtml.="</tr>";
 	}
	echo $dataHtml;
	mysql_free_result($result);
?>
  </table>
</body>
</html>
