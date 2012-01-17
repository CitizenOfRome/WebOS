<?php
	$PATH = "../Users";
	$APPATH = "../Apps";
	mkdir("./Style");
	mkdir("./Images");
	mkdir("./Script");
	mkdir($PATH);
	mkdir($APPATH);
	$con = mysql_connect("db01-share", "Custom App-24766", "FrogKissesTheQueen", true);
	if(!$con) die("FAILED TO CONNECT");
	if(mysql_query("CREATE DATABASE OS", $con))	echo "2";
	else echo "0";
	if(mysql_select_db("OS", $con))	echo "2";
	//mysql_query("DROP TABLE Users", $con);	mysql_query("DROP TABLE Apps", $con);	mysql_query("DROP TABLE Files", $con);	mysql_query("DROP TABLE AppParts", $con);
	echo mysql_error($con);
	$c1=mysql_query("CREATE TABLE Users
	(
		ID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(ID),
		userName TINYTEXT NOT NULL,
		passWord TINYTEXT NOT NULL,
		secTip TEXT,
		emailId TINYTEXT,
		isON TINYINT(1) UNSIGNED,
		joinedON TINYTEXT,
		memory BIGINT UNSIGNED,
		memUsed BIGINT UNSIGNED DEFAULT 0,
		trustedApps LONGTEXT
	);", $con);
	//	admin TINYTEXT NOT NULL,
	echo mysql_error($con);
	$c2=mysql_query("CREATE TABLE Apps
	(
		ID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(ID),
		owner TINYTEXT NOT NULL,
		appName TINYTEXT NOT NULL,
		appId TINYTEXT NOT NULL,
		descr LONGTEXT,
		canRead LONGTEXT,
		canWrite LONGTEXT,
		defHt INT(4) UNSIGNED DEFAULT 240,
		defWd INT(4) UNSIGNED DEFAULT 320,
		defX INT(4) UNSIGNED DEFAULT 0,
		defY INT(4) UNSIGNED DEFAULT 0,
		isApproved TINYINT(1) UNSIGNED DEFAULT 0,
		addedOn TINYTEXT,
		updatedOn TINYTEXT,
		rating INT DEFAULT 0
	);", $con);//defposition(X,Y,Z)?//
	echo mysql_error($con);
	$c3=mysql_query("CREATE TABLE Files
	(
		ID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(ID),
		fileId TEXT NOT NULL,
		name TINYTEXT NOT NULL,
		owner TEXT NOT NULL,
		canRead LONGTEXT,
		canWrite LONGTEXT,
		meta TEXT,
		path TEXT,
		type TINYINT(1) UNSIGNED NOT NULL,
		addedOn TINYTEXT
	);", $con);
	echo mysql_error($con);
	$c4=mysql_query("CREATE TABLE AppParts
	(
		ID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(ID),
		fileId TEXT NOT NULL,
		appId TEXT NOT NULL,
		name TINYTEXT NOT NULL,
		meta TEXT,
		path TEXT,
		type TINYINT(1) UNSIGNED NOT NULL,
		addedOn TINYTEXT
	);", $con);
	echo mysql_error($con);
	if($c1)	echo "c1c";	else echo "c0c";
	if($c2)	echo "d1d";	else echo "d0d";
	if($c3)	echo "e1e";	else echo "e0e";
	if($c4)	echo "f1f";	else echo "f0f";
	//mysql_query("INSERT INTO Files (fileId, name ,size, owner, canRead, canWrite, path, type, addedOn) VALUES ('".md5(mt_rand())."', '', 0, '', '', '', '', 1, '".date("Y-m-d H:i:s")."')", $con);
	mysql_close($con);
	/*TINYTEXT: Format: YYYY-MM-DD HH:MM:SS
	CREATE INDEX usr ON Users (userName, passWord);	*/
	echo "1";
?>