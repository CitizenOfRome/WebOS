<?php
	session_start();
	$con = mysql_connect("db01-share", "Custom App-24766", "FrogKissesTheQueen", true);	if(!($con && mysql_select_db("OS", $con)))	die("DATABASE ERROR");
	$userName = mysql_real_escape_string(strtolower($_POST["username"]));
	$passWord = md5($_POST["password"]);
	$result = mysql_fetch_row(mysql_query("SELECT userName FROM Users WHERE userName='".$userName."' AND passWord='".$passWord."'", $con));
	if($result) {
		print("CONNECTED");
		$_SESSION["CURRENT"] = $userName;
		print_r($_SESSION);
	}
	else	die("mismatch");
	mysql_close($con);
?>