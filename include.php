<?php
$config["fb_app_id"] = "185548844861355";
$config["fb_app_secret"] = "9f4e519e0f095db1bf387294aaf806a3";
$config["base_url"] = "http://parkit.cs147.org/parkit/";
$config["fb_fields"] = array(
    array("name" => "name"),
    array("name" => "email"),
);


/* Database Settings */
$dbhost = "mysql.cs147.org";
$dbname = "thomass2_mysql";
$dbuser = "thomass2";
$dbpass = "HHwi83su";

mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
mysql_select_db($dbname) or die("MySQL Error: " . mysql_error());