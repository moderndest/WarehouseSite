<?php

session_start();
echo $txtStockNo = $_REQUEST['txtStockNo'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="200" border="0">
  <tr>
    <th scope="col"><p>Do you want to delete ?</p></th>
  </tr>
  <tr>
    <th scope="col"></th>
  </tr>
  
  <tr>
    <td><center><input type="submit" value="YES" formaction="delete.php" formmethod="get" formtarget="_blank" /></center></td>
  </tr>
</table>

</form>
</body>
</html>