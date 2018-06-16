<?php 
 	$dbhost = "localhost";	//database server address
	$dbuser = "root";		//username
	$dbpass = "root";		//password
	$dbname = "Warehouse";		//database name
	
	//connect to database
	$conn = mysql_connect($dbhost,$dbuser,$dbpass);	//connect
	if(!$conn)
	{
		echo "<h3> ERROR Cannot Connect Database</h3>";
		exit();
	}
	mysql_select_db($dbname, $conn);		//select database to use
	
	$charset = "SET character_set_results=tis620";
	mysql_query($charset) or die('Invalid query: ' . mysql_error()); 
?>