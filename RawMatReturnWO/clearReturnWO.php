<?php
session_start();
unset($_SESSION['LineItems']);
session_destroy();
//session_start();
//$_SESSION['StockNo'] = "NEW";
header("Location: mainReturnWO.php");

?>