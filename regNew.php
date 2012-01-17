<?php
	$con = mysql_connect("db01-share", "Custom App-24766", "FrogKissesTheQueen", true);	if(!$con || !(mysql_select_db("os_phpfogapp_com", $con))) die("CONNECTION ERROR");
	$table = array();
	$result = mysql_query("SELECT userName FROM Users", $con);
	while($row = mysql_fetch_array($result)) {
		array_push($table, $row["userName"]);
	}
	print_r($table);
	//$path = "../Users/";//using virtual path
	$userName = mysql_real_escape_string(strtolower($_POST["username"]), $con);
	//$udir = md5($userName);
	$passWord = md5($_POST["password"]);
	$secTip = mysql_real_escape_string($_POST["clue"], $con);
	$emailId = mysql_real_escape_string($_POST["email"], $con);
	$joinedON = date("Y-m-d H:i:s");
	echo date("Y-m-d H:i:s");
	$memory = 100*1024*1024;
	if(!in_array($userName, $table) && isset($userName)) {
		echo "<br/>".$userName."<br/>".stripslashes($userName);
		$chk = mysql_query("INSERT INTO Users (userName, passWord, secTip, emailId, joinedON, isON, memory, memUsed) VALUES ('".$userName."', '".$passWord."', '".$secTip."', '".$emailId."', '".$joinedON."', 1, ".$memory.", 0)", $con);
		if(!$chk)	die("DATABASE ERROR");
		$path = $name = $owner = $file = $userName;
		$c2 = mysql_query("INSERT INTO Files (fileId, name, owner, canRead, canWrite, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$owner."', '".$owner."', '', 1, '".date("Y-m-d H:i:s")."')", $con);
		if(!$c2)	die("DATABASE ERROR");
	}
	else	die("ERROR");
	mysql_close($con);
?>