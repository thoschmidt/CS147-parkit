<?php 
require 'functions.php';

$fb_id = mysql_real_escape_string($_GET["fb_id"]);
$fb_name = mysql_real_escape_string($_GET["fb_name"]);
$fb_email = mysql_real_escape_string($_GET["fb_email"]);
$cellNumber = mysql_real_escape_string($_GET["cellNumber"]);
$cellProvider = mysql_real_escape_string($_GET["cellProvider"]);

$query_str = "INSERT INTO users VALUES (NULL, '$fb_id', '$fb_name', '$fb_email', '$cellNumber', '$cellProvider') ON DUPLICATE KEY UPDATE fb_id=fb_id";
echo $query_str;
mysql_query($query_str);

?>