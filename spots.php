<?php

require 'functions.php';

$func = mysql_real_escape_string($_GET["function"]);

if(strcmp($func,"put") == 0)
{
	echo "post";
	$sellerID = mysql_real_escape_string($_GET["sellerID"]);
	$address = mysql_real_escape_string($_GET["address"]);
	$lat = mysql_real_escape_string($_GET["lat"]);
	$lng = mysql_real_escape_string($_GET["lng"]);
	$price = mysql_real_escape_string($_GET["price"]);
	$startAvailableTime = mysql_real_escape_string($_GET["startAvailableTime"]);
	$endAvailableTime = mysql_real_escape_string($_GET["endAvailableTime"]);
	
	$query_str = "INSERT INTO parkingspots VALUES (NULL, '$sellerID', '$lat', '$lng' , '$address' , '$price', '$startAvailableTime', '$endAvailableTime') ON DUPLICATE KEY UPDATE sellerID=sellerID";
	
	mysql_query($query_str);
}
else if(strcmp($func,"get") == 0)
{
	$lat = mysql_real_escape_string($_GET["lat"]);
	$lng = mysql_real_escape_string($_GET["lng"]);
	$radius = mysql_real_escape_string($_GET["radius"]);
	$today = getdate();
	
	$query_str = "SELECT * FROM parkingspots WHERE SQRT(POWER(lat - '$lat', 2) + POWER(lng - $lng, 2)) < '$radius'";// AND (startDate < '$today' AND endDate > '$today')";
	$result = mysql_query($query_str);
	
	header('Content-type: application/json');
	$rows = array();
	while($r = mysql_fetch_assoc($result)) {
    	$rows[] = $r;
	}
	echo json_encode($rows);
}
else if(strcmp($func,"update") == 0)
{
	$spotID = mysql_real_escape_string($_GET["spotID"]);
	$newStartAvailableTime =  mysql_real_escape_string($_GET["newStartAvailableTime"]);
	$newEndAvailableTime = mysql_real_escape_string($_GET["newEndAvailableTime"]);
	
	$query_str = "UPDATE parkingspots SET ";
	if($newStartAvailableTime && $newEndAvailableTime)
	{
		$query_str = $query_str."startAvailableTime = '$newStartAvailableTime', endAvailableTime = '$newEndAvailableTime' ";
	}else if($newStartAvailableTime)
	{
		$query_str = $query_str."startAvailableTime = '$newStartAvailableTime' ";
	}else
	{
		$query_str = $query_str."endAvailableTime = '$newEndAvailableTime' ";
	}
	
	$query_str = $query_str."WHERE spotID = '$spotID'";
	mysql_query($query_str);
}
	

?>