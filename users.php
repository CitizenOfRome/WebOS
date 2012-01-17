<?php
/*OBSOLETE, see appFunc.php
	session_start(); //$_SESSION["CURRENT"];$PATH
	$PATH = "../Users/";
	$REQUEST = $_GET["user"];
	$dir = md5($_GET["user"]);
	$cDir = md5($_SESSION["CURRENT"]);// change it to name+id?->to prevent path guesses and takeovers(simple replace)
	$data = "";
	if($REQUEST == "") {
		$data = $PATH.$cDir."/_PRV/";
	}
	else {
		if(file_exists($PATH.$dir)) {
			$data = $PATH.$dir."/_PUB/";
		}
		else {
			$data = $PATH.$cDir."/_PUB/";
		}
	}
	echo $data;
?>