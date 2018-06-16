<?php
session_start();
unset($_SESSION['LineItems']);
session_destroy();
header("Location: WR.php");

?>