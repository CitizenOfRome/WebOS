<?php
	echo "<pre>".(int)method_exists("Directory", "read");
	print_r(get_declared_classes());
	print_r(get_declared_interfaces());
	$array = array();
	foreach(get_declared_classes() as $clas) {
		echo $clas;
		//print_r(get_class_methods($clas));
		foreach(get_class_methods($clas) as $meth) {
			if(function_exists($meth))	print("<h1>".$meth."</h1>");
		}
	}
	foreach(get_declared_interfaces() as $clas) {
		echo "III".$clas;
		//print_r(get_class_methods($clas));
		foreach(get_class_methods($clas) as $meth) {
			if(function_exists($meth))	print("<h1>".$meth."</h1>");
		}
	}//no good for when we wanna allow classes...: remove allowed classes from $clas, b4 loop... - still a problem with same method names in multiclasses...
	/*
	$str = "sdfdslkfsdlkjdfkl include 'dsfsdffsdf'; skfgsdkfd";
	$patt = "/include\ (.*)[^\,\)\;\}\0(?>)]/i";
	$rep = "/include\((.*)[^\,\)\;\}\0(?>)]\)/i";
	echo preg_replace($patt, $rep, $str);
	$a="0";
	echo (int)((int)$a===0);
	$finfo = finfo_open(FILEINFO_MIME);
	header('Content-type: '.finfo_file($finfo, realpath($_REQUEST["path"])));
	finfo_close($finfo);
	header('Content-Disposition: inline; filename="abc.db"');
	//header('file:abc.txt');
	$_SERVER['PATH_TRANSLATED'] = "kj/sdf/sfs/abc.txt";
	$_SERVER['REQUEST_URI'] = "kj/sdf/sfs/abc.txt";
	$_SERVER['PATH_INFO'] = "kj/sdf/sfs/abc.txt";
	echo "<h1>kkashdkassd";
	$finfo = finfo_open(FILEINFO_MIME);
	echo finfo_file($finfo, "../index.php");
	foreach (glob("E:/HyperText/OS/*") as $filename) {
		echo $filename.": <b>".finfo_file($finfo, $filename) . "</b><br>\n";
		//echo $filename.": <b>".mime_content_type($filename) . "</b><br>\n";//depreciated
	}
	finfo_close($finfo);
	$abc = "abc";
	echo $abc[0];
	if($abc[1]=="b")	echo $abc[2];
	echo "<iframe src='./test.php'></iframe>";
	echo dirname("http://asasdas/saads.adsas");
	function cnct() {
		$con = mysql_connect("localhost:3306", "root");	if(!$con || !(mysql_select_db("OS", $con))) return FALSE;
		//echo  mysql_real_escape_string("dskhfldsfl'sdhbdsfkjkds");
		mysql_close($con);
	}
	cnct();
	echo  mysql_real_escape_string("dskhfldsfl'sdhbdsfkjkds");
	function _include($file) {
		include $file;
	}
	_include("./index.php");
	_include("./index.php");
	function escape_array(&$arr) {
		//$con = mysql_connect("localhost:3306", "root");	if(!$con || !(mysql_select_db("OS", $con))) return FALSE;
		foreach($arr as $key => $val) {
			$arr[$key] = addcslashes($val, "\x00\n\r\\\'\x1a\0\"");
			//$arr[$key] = addslashes($val);
			//$arr[$key] = mysql_real_escape_string($val, $con);
		}
	}
	echo "<pre>".count($_REQUEST)."\n";
	print_r($_REQUEST);
	$a="escape_array";
	$a($_REQUEST);
	print_r($_REQUEST);
	print_r($_SERVER);
	foreach($_GET as $key => $val) {
		$_GET[$key] = addslashes($val);
	}
	array_walk($_GET, "addslashes");
	<iframe src="./pro/v2.html" style="z-index:5;"></iframe>
	a();
	function a(){echo "HI";}
	ignore_user_abort(true);
	echo count($_GET);
	for($i=0;$i<count($_POST);$i++) { $_POST[$i] = addslashes($_POST[$i]); }
	for($i=0;$i<count($_GET);$i++) { $_GET[$i] = addslashes($_GET[$i]); }
	print_r($_POST);
	print_r($_GET);echo "'".strstr("abcd", '/', true)."'";
	echo file_get_contents("");
	echo file_get_contents('data://text/plain;base64,JHIOHSBLAHH34');
	class A {
		function fopen($string) {
			echo $string;
		}
		function A() {
			A::fopen("I");
		}
	}
	$a = new A();
	$a::fopen("A");*/
	//echo "<pre>";
	//print_r(getallheaders());//client
	//print_r(get_headers("http://localhost/KCL", 0));//server
	//echo implode("\r\n",get_headers("http://localhost/KCL", 0));
	/*$fp = fsockopen("localhost", 80, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		$url = "/";
		$out = "GET ".$url." HTTP/1.1\r\n";
		$hdrs = getallheaders();
		foreach ($hdrs as $name => $value) {
			$out .= "$name: $value\r\n";
		}
		fwrite($fp, $out);
		while (!feof($fp)) {
			echo fgetc($fp);
		}
		fclose($fp);
	}
*/
	/*session_start();
	$file_path = "E:\abc.log";
	$fp = fopen($file_path, "w+");
	print_r(fstat($fp));
	echo file_get_contents($file_path);
	fwrite($fp, htmlentities(file_get_contents("./dummy.php")).mt_rand());
	print_r(fstat($fp));
	fclose($fp);
	print_r(stat($file_path));
	$str=file_get_contents($file_path);
	echo $str."<br />\n";
	echo filesize($file_path)."<br />\n";
	echo strlen($str)."<br />\n";
	echo mb_strlen($str)."<br />\n";
	unlink($file_path);
	rename(realpath("./"), realpath("..")."/OS");
	//eval'printf("abc")';
	$_SESSION["a"] .= "a";
	session_write_close();
	$_SESSION["a"] .= "b";
	echo $_SESSION["a"];
	echo "<br/>".get_magic_quotes_gpc();
	echo "<br/>'".realpath("./fgdfgdfg/dskkjnasd/crap.txt")."'";
	echo "<br/>'".dirname("./fgdfgdfg/dskkjnasd/crap.txt")."'";
	echo "<br/>'".dirname("as")."'";
	echo "<br/>'".print_r(pathinfo("./fgdfgdfg/dskkjnasd/crap.txt"))."'";
	echo "<br/>".basename("./fgdfgdfg/dskkjnasd/crap.txt;''");
	echo "<br/>".basename("./fgdfgdfg/dskkjnasd/");
	echo "<br/>'".(disk_total_space("./")/1024/1024)."'";
	echo "<br/>'".strtr("abcd", "abc", "12")."'";
	function a($b = "123", $c = array("456")) {
		echo "<br/>".$b;
		$d = INI_SCANNER_NORMAL;
		$e = (string)Array("a", "b");
		echo (string)$d;
		echo (string)$e;
		return $c;
	}
	define("A", 1);
	echo A;
	define("A", 2);
	echo A;
	//echo "<br/>".a("a");
	echo "<br/>".realpath(".");
	echo "<br/>".file_get_contents("/appFunc.php");
	//phpinfo();
	//$swap=array(basename($file)=>"");$path = mysql_real_escape_string(strtr($file, $swap));
*/?>