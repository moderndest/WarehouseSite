<?php
session_start();
unset($_SESSION['LineItems']);
session_destroy();
//session_start();
//$_SESSION['StockNo'] = "NEW";
header("Location: main.php");

?>
				

				<tr>
                <td align = center><?=$rowData['ProductCode']?></td>
                <td><?=$rowData['ProductName']?></td>
                <td align="right"><input name="Quantity" style="text-align:right; width:80px;" type="text" id="Quantity"  value="<?=$rowData['Quantity']?>"size="10" onkeyup="checkError('Quantity');"  onchange="Editcalculateprice();"/></td>
    </tr>
<?php



?>