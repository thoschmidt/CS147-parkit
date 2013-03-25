<?php

require 'functions.php';

$func = mysql_real_escape_string($_GET["function"]);

if(strcmp($func,"get") == 0)
{
	$userID = mysql_real_escape_string($_GET["userID"]);
	
	$query_str = "SELECT * FROM users WHERE fb_id = '$userID'";
	$result = mysql_query($query_str);
	
	header('Content-type: application/json');
	$rows = array();
	while($r = mysql_fetch_assoc($result)) {
    	$rows[] = $r;
	}
	
	echo json_encode($rows);
}

?>
