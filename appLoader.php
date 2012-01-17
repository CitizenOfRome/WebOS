<?php
	//File=$_REQUEST["file"] //ENV, GET, POST, COOKIE and SERVER
	//if(!isset($_REQUEST["id"]))	die("You should be sorry for not having selected a valid app...");
	$con = mysql_connect("localhost:3306", "root");
	$file = mysql_real_escape_string($_REQUEST["file"], $con);
	$owner = strstr($file, '/', true);
	define("_APPID", $owner);
	mysql_close($con);
	require "./appFunc.php";
	//include //appData
	///if(!_is_app($_REQUEST["id"]))	die("You should be sorry for not having selected a valid app...");
	//$appFile = "../Users/"._get_file_id($file);
	header('Content-type: '._get_mime_type($file));
	header('Content-Disposition: inline; filename="'.basename($file).'"');
	header('X-Robots-Tag: noindex');
	/*header('Cache-Control:');
	header('Pragma:');
	header('Expires:');*/
	//echo "$file";
	//echo _fopen("s/dd.txt", "r");
	/**Add pocexp support, ...*/
	_require($file);
?>