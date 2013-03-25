<?php

require 'functions.php';

$func = mysql_real_escape_string($_GET["function"]);

if(strcmp($func,"put") == 0)
{
	echo "post";
	$buyerID = mysql_real_escape_string($_GET["buyerID"]);
	$spotID = mysql_real_escape_string($_GET["spotID"]);
	$startPurchaseTime = mysql_real_escape_string($_GET["startPurchaseTime"]);
	$endPurchaseTime = mysql_real_escape_string($_GET["endPurchaseTime"]);
	
	$selectStr = "SELECT * FROM purchases WHERE spotID = '$spotID' AND startPurchaseTime = '$startPurchaseTime'";
	$selectResult = mysql_query($selectStr);
	
	$getSellerIDStr = "SELECT sellerID FROM parkingspots WHERE spotID = '$spotID'";
	$sellerIDResult = mysql_query($getSellerIDStr);
	$sellerID = mysql_fetch_assoc($sellerIDResult);
	$sellerID = $sellerID['sellerID'];
	
	if(mysql_fetch_array($selectResult))
	{
		//There was an existing entry / updating purchase
		$updateStr = "UPDATE purchases SET endPurchaseTime = '$endPurchaseTime' WHERE spotID = '$spotID' AND startPurchaseTime = '$startPurchaseTime'";
		echo $updateStr;
		mysql_query($updateStr);
	}
	else
	{		
		$insertStr = "INSERT INTO purchases VALUES (NULL, '$buyerID', '$sellerID', '$spotID' , '$startPurchaseTime' , '$endPurchaseTime')";
		mysql_query($insertStr);
	}
	
	$userSelectStr = "SELECT * FROM users WHERE fb_id = '$sellerID'";
	$userSelectResult = mysql_query($userSelectStr);
	$sellerProfile = mysql_fetch_assoc($userSelectResult);
	
	$cellNumber = $sellerProfile['cellNumber'];
	
	textMessage($cellNumber, $sellerProfile['cellProvider'], urlencode("Someone bought your spot!"));
}
else if(strcmp($func,"getBuyerSpots") == 0)
{
	$buyerID = mysql_real_escape_string($_GET["buyerID"]);

	$query_str = "SELECT parkingspots.lat, parkingspots.lng, parkingspots.address, parkingspots.price, parkingspots.spotID, parkingspots.startAvailableTime, parkingspots.endAvailableTime, purchases.purchaseID, purchases.startPurchaseTime, purchases.endPurchaseTime FROM parkingspots INNER JOIN purchases ON parkingspots.spotID = purchases.spotID AND purchases.buyerID = '$buyerID'";
	$result = mysql_query($query_str);
	
	header('Content-type: application/json');
	$rows = array();
	while($r = mysql_fetch_assoc($result)) {
    	$rows[] = $r;
	}
	echo json_encode($rows);
	
}
else if(strcmp($func,"getSellerSpots") == 0)
{
	$sellerID = mysql_real_escape_string($_GET["sellerID"]);
	$query_str = "SELECT * FROM parkingspots WHERE sellerID = '$sellerID'";
	$result = mysql_query($query_str);
	
	header('Content-type: application/json');
	$rows = array();
	while($r = mysql_fetch_assoc($result)) {
    	$rows[] = $r;
	}
	echo json_encode($rows);
} else if(strcmp($func, "update") == 0)
{
	$newEndPurchaseTime = mysql_real_escape_string($_GET["newEndPurchaseTime"]);
	$purchaseID = mysql_real_escape_string($_GET["purchaseID"]);
	
	$query_str = "UPDATE purchases SET endPurchaseTime = '$newEndPurchaseTime' WHERE purchaseID = '$purchaseID'";
	mysql_query($query_str);
}

?>