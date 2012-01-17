<?php require_once "/appFunc.php";/*echo _realpath("file:///./Hello.txt");*/ ?>
<?php
	session_start();
	//$con = mysql_connect("localhost:3306", "root");	if(!$con || !(mysql_select_db("os_phpfogapp_com", $con))) die("CONNECTION FAILED");
	$inTag = $sgl = $dbl = 0;//globals to be used b/n following funcs
	function escape_array(&$arr) {
		global $con;
		$remove = array("%" => "_PERCENT_");
		foreach($arr as $key => $val) {
			//$arr[$key] = addcslashes($val, "\x00\n\r\\\'\x1a\0\"%");
			$arr[$key] = strtr(mysql_real_escape_string($val, $con), $remove);
		}
	}
	escape_array($_POST);
	function inQuotesSetTag($string) {
		global $sgl, $dbl, $inTag;
		for($i = 0; $i < strlen($string); $i++) {
			if($string[$i] == '"' && $sgl != 1)	$dbl = 1 - $dbl;
			if($string[$i] == "'" && $dbl != 1)	$sgl = 1 - $sgl;
			if($dbl == 0 && $sgl == 0) {//set inTag
				if($string[$i] == '<' && $string[$i+1] == '?' && $inTag != 1) {
					$inTag = 1;
					$i++;
				}
				if($string[$i] == '?' && $string[$i+1] == '>' && $inTag == 1) {
					$inTag = 0;
					$i++;
				}
			}
		}
		if($dbl != 0 || $sgl != 0)	return true;
		else	return false;
	}
	function inPHP($string) {
		global $inTag;
		if($inTag == 1)	return true;
		else	return false;
	}
	function hasPHPTags($data) {
		if(strpos($data, "<?php")!==false)	return true;
		else return false;
	}
	function illegal_function($func) {
		if((!in_array($func, get_whitelist()) && function_exists($func)) || strpos($func, "$")!==false)	return true;//blacklisted || variable_func
		else return false;
	}
	//modded file Functions{ , see appFunc.php
	$replacedFunc =  array("fileowner", "realpath", "is_readable", "is_writeable", "is_writable", "file_exists", "fopen", "fwrite", "fputs", "fputcsv", "fflush", "ftruncate", "filesize", "copy", "rename", "move_uploaded_file", "disk_free_space", "diskfreespace", "disk_total_space", "is_dir", "is_file", "file", "stat", "readfile", "parse_ini_file", "file_get_contents", "file_put_contents", "mkdir", "rmdir", "unlink");
	$unModdFunc = array();
	foreach($replacedFunc as $func) {
		array_push($unModdFunc, $func."(");
	}
	$moddFunc = array();
	foreach($unModdFunc as $func) {
		array_push($moddFunc, "_".$func);
	}
	//}
	function get_whitelist() {
	return array(
			//kernel - userdef
			"_is_user", "_is_empty", "_disk_used_space", "_is_trusted", "_is_app", "_get_app_owner", "_get_meta", "_set_meta",  "_get_app_id",  "_get_can_read",   "_set_can_read",  "_get_can_write", "_set_can_write", "_get_mime_type", 
			//general
			"if", "elseif", "for", "while", "foreach", "switch", "return", "function_exists", "isset", "constant", "define", "defined", "get_defined_constants",
			//array
			"array","array_change_key_case","array_chunk","array_combine","array_count_values", "array_diff","array_diff_assoc","array_diff_key","array_diff_uassoc","array_diff_ukey","array_fill","array_filter","array_flip","array_intersect","array_intersect_assoc","array_intersect_key","array_intersect_uassoc","array_intersect_ukey","array_key_exists","array_keys","array_map","array_merge","array_merge_recursive","array_multisort","array_pad","array_product","array_pop","array_push","array_rand","array_reduce","array_reverse","array_search","array_shift","array_slice","array_splice","array_sum","array_udiff","array_udiff_assoc","array_udiff_uassoc","array_uintersect","array_uintersect_assoc","array_uintersect_uassoc","array_unique","array_unshift","array_values","array_walk","array_walk_recursive","asort","arsort","compact","count","current","each","end","extract","in_array","key","krsort","ksort","list","natcasesort","natsort","next","pos","prev","range","reset","rsort","shuffle","sizeof","sort","uasort","uksort","usort","sizeof", "print_r",
			//calender
			"cal_days_in_month","cal_from_jd","cal_info","cal_to_jd","easter_date","easter_days","frenchtojd","gregoriantojd","jddayofweek","jdmonthname","jdtofrench","jdtogregorian","jdtojewish","jdtojulian","jdtounix","jewishtojd","juliantojd","unixtojd",
			//date
			"checkdate","date_default_timezone_get","date_default_timezone_set","date_sunrise","date_sunset","date","getdate","gettimeofday","gmdate","gmmktime","gmstrftime","idate","localtime","microtime","mktime","strftime","strptime","strtotime","time",
			//error
			"debug_backtrace","debug_print_backtrace","error_reporting","restore_error_handler","restore_exception_handler","set_error_handler","set_exception_handler","trigger_error","user_error",
			//http
			"setcookie","setrawcookie","ezmlm_hash",
			//math
			"abs","acos","acosh","asin","asinh","atan","atan2","atanh","base_convert","bindec","ceil","cos","cosh","decbin","dechex","decoct","deg2rad","exp","expm1","floor","fmod","getrandmax","hexdec","hypot","is_finite","is_infinite","is_nan","lcg_value","log","log10","log1p","max","min","mt_getrandmax","mt_rand","mt_srand","octdec","pi","pow","rad2deg","rand","round","sin","sinh","sqrt","srand","tan","tanh",
			//misc.
			"connection_aborted", "connection_status", "constant", "define", "defined", "die", "exit", "get_browser", "highlight_string", "ignore_user_abort", "sleep", "time_nanosleep", "time_sleep_until", "uniqid", "usleep",
			//string
			"addcslashes", "addslashes", "bin2hex", "chop", "chr", "chunk_split", "convert_cyr_string", "convert_uudecode", "convert_uuencode", "count_chars", "crc32", "crypt", "echo", "explode", "get_html_translation_table", "hebrev", "hebrevc", "html_entity_decode", "htmlentities", "htmlspecialchars_decode", "htmlspecialchars", "implode", "join", "levenshtein", "localeconv", "ltrim", "md5", "metaphone", "money_format", "nl_langinfo", "nl2br", "number_format", "ord", "parse_str", "print", "printf", "quoted_printable_decode", "quotemeta", "rtrim", "setlocale", "sha1", "similar_text", "soundex", "sprintf", "sscanf", "str_ireplace", "str_pad", "str_repeat", "str_replace", "str_rot13", "str_shuffle", "str_split", "str_word_count", "strcasecmp", "strchr", "strcmp", "strcoll", "strcspn", "strip_tags", "stripcslashes", "stripslashes", "stripos", "stristr", "strlen", "strnatcasecmp", "strnatcmp", "strncasecmp", "strncmp", "strpbrk", "strpos", "strrchr", "strrev", "strripos", "strrpos", "strspn", "strstr", "strtok", "strtolower", "strtoupper", "strtr", "substr", "substr_compare", "substr_count", "substr_replace", "trim", "ucfirst", "ucwords", "vprintf", "vsprintf", "wordwrap", "unpack", "pack",
			//files:	
			"clearstatcache", "fclose", "feof", "fgetc", "fgetcsv", "fgets", "fgetss", "flock", "fpassthru", "parse_ini_string", "fread", "fscanf", "fseek", "fstat", "ftell", "rewind", "vfprintf", "fprintf", "fileowner", "realpath", "is_readable", "is_writeable", "is_writable", "file_exists", "fopen", "fwrite", "fputs", "fputcsv", "fflush", "ftruncate", "filesize", "copy", "rename", "move_uploaded_file", "disk_free_space", "diskfreespace", "disk_total_space", "is_dir", "is_file", "file", "stat", "readfile", "parse_ini_file", "file_get_contents", "file_put_contents", "mkdir", "rmdir", "unlink", 
			//url
			"base64_decode","base64_encode","get_headers","get_meta_tags","http_build_query","parse_url","rawurldecode","rawurlencode","urldecode","urlencode",
			//regexp
			"ereg_replace", "ereg", "eregi_replace", "eregi", "split", "spliti", "sql_regcase", "preg_filter", "preg_grep", "preg_last_error", "preg_match_all", "preg_match", "preg_quote", "preg_replace_callback", "preg_replace", "preg_split"
			
		);
	}
	function checkAndStore($file_path, $path) {
		$count = 0;
		$voilations = array();
		//$splFunc = array(".." => ".", "included" => "contained", "required" => "needed", "include" => "contain", "require" => "need");
		//$changed_data = strtr(php_strip_whitespace($file_path), $splFunc);
		$changed_data = php_strip_whitespace($file_path);
		if(hasPHPTags($changed_data)) {	//if file is php
			global $sgl, $dbl, $inTag;
			$sgl = $dbl = $inTag = 0;
			$whitelist = get_whitelist();
			$operators = array( "+", "-", "*", "/", "&", "=", "%", "!", "~", ">", "<", "^", "|", "\\", "?", ":", ";", ".", ",", "?", "`", "{", "}", "[", "]", "'", '"');
			$endParam = array(",", ")", ";", "}");//for include?
			$set = explode("(", $changed_data);
			$flar = array("include_once", "require_once", "require", "include");
			$flag=false;
			foreach($set as $subString) {// give # times of error : $count, for funcs
				if($flag) {//for include...
					
				}
				if(inQuotesSetTag($subString) || !inPHP($subString)) {//quote|nonPHP
					continue;
					//opr|in_array($subString[strlen($subString)-1], $operators) || :OBSOLETE, see below
				}
				//echo "<br/>".htmlentities($subString);
				$words = str_word_count($subString, 1, '1234567890$_');//break into words(for possible func names)
				$wrdCnt = count($words);
				if($wrdCnt > 0)	$words[$wrdCnt-1] = strtolower($words[$wrdCnt-1]);//func name
				if($wrdCnt > 0 && illegal_function($words[$wrdCnt-1]) && !method_exists(, $words[$wrdCnt-1])) {
					//$count++;
					array_push($voilations, $words[$wrdCnt-1]);
					//echo "<br/><b>E</b>".$words[$wrdCnt-1];
				}
				if(in_array($words[$wrdCnt-1], $flar))	$flag=true;
				else	$flag=false;//use some array func instead & note the positions for ( * )
				/*OBSOLETED by (function_exists && !in_array)=>voilation
				if($wrdCnt > 1) {//user defined funcs
					$words[$wrdCnt-2] = strtolower($words[$wrdCnt-2]);
					if($words[$wrdCnt-2] == "function" && !function_exists($words[$wrdCnt-1])) {
						array_push($whitelist, $words[$wrdCnt-1]);
						//$count--;
						//echo "<br/><b>U</b>".$words[$wrdCnt-1];
					}
				}
				$lastWord = $words[$wrdCnt-1];*/
			}
			/*if(!in_array($lastWord, $whitelist) && !function_exists($lastWord)) { //if lastword is OK/inexistant
				//echo "<br/><b>L</b>".$lastWord."<br/>";
				array_push($whitelist, $lastWord);
				//$count--;
			}
			$voilations = array_diff($voilations, $whitelist);*/
			$count = count($voilations);
			global $unModdFunc, $moddFunc;
			$changed_data = str_ireplace($unModdFunc, $moddFunc, $changed_data);
			/*OBSOLETED by appLoader.php;$changed_data = '<?php define("_APPID", "'.$appId.'");	require_once "/appFunc.php"; ?>'.$changed_data;*/
			/*Write after all oprns are done...
			$fp = fopen($file_path, "w+");
			fwrite($fp, $changed_data);
			fclose($fp);*/
		}
		//echo "<br/>".htmlentities($changed_data);
		if($count > 0) {
			echo "File Rejected.<b>$count</b>";
			print_r($voilations);
			return FALSE;
		}
		else {
			//dirty_edit($changed_data);?
			echo "File Accepted.<b>$count</b>";
			$dir = dirname($path); if($dir==".") $dir = "";
			if(!_file_exists($dir))	_mkdir($dir, "", true);
			echo "<h1>"._move_uploaded_file($file_path, $path)."</h1>";
			return TRUE;
		}
	}
	function app_exists($appId) {
		global $con;
		$appId = mysql_real_escape_string($appId, $con);
		$owner=mysql_fetch_row(mysql_query("SELECT owner FROM Apps WHERE appId='".$appId."'", $con));
		if(isset($owner[0]))	return true;
		else	return false;
	}
	function ownsApp($appId) {
		//if !app_exists || is_owner
		global $con;
		$appId = mysql_real_escape_string($appId, $con);
		$owner=mysql_fetch_row(mysql_query("SELECT owner FROM Apps WHERE appId='".$appId."'", $con));
		echo "'".$owner[0];
		if(!isset($owner[0])) {
			return true;
		}
		else if($owner[0] !== $_SESSION["CURRENT"])	return false;
		else	return true;
	}
	$appId = md5($_SESSION["CURRENT"].$_POST["app"]);
	define("_APPID", $appId);
	#echo "./Apps/".md5($_POST["app"])."/".$_FILES["file"]["name"];//enc if needed
	if(isset($_POST["app"]) && ownsApp($appId)){
		//echo (int)checkAndStore($_FILES["file"]["tmp_name"], "./Apps/".md5($_SESSION["CURRENT"].$_POST["app"])."/".$_FILES["file"]["name"]);
		//mkdir("../Apps/".$appId);
		$_POST["app"] = strtolower($_POST["app"]);
		$addedON = date("Y-m-d H:i:s");
		if(!app_exists($appId))	{
			mysql_query("INSERT INTO Apps (owner, appName, appId, canRead, canWrite, descr, defHt, defWd, defX, defY, addedOn, updatedOn) VALUES ('".$_SESSION["CURRENT"]."', '".$_POST["app"]."', '".$appId."', '".$_SESSION["CURRENT"]."', '".$_SESSION["CURRENT"]."', '".$_POST["descr"]."', ".$_POST["app_ht"].", ".$_POST["app_wd"].", ".$_POST["app_X"].", ".$_POST["app_Y"].", '".$addedON."', '".$addedON."')", $con);//, files, '".$fileList."'
			echo "1".mysql_error($con);
			mysql_query("INSERT INTO AppParts (fileId, name, appId, path, type, addedOn) VALUES ('".md5($appId.mt_rand())."', '".$appId."', '".$appId."', '', 1, '".date("Y-m-d H:i:s")."')", $con);
			echo "2".mysql_error($con);
			_add_trust($appId);
		}
		else	{
			//$pList = mysql_fetch_row(mysql_query("SELECT files FROM Apps where appId='".$appId."'", $con));
			//$fileList = $pList[0].",".$fileList;, files='".$fileList."'
			mysql_query("UPDATE Apps SET descr='".$_POST["descr"]."', defHt=".$_POST["app_ht"].", defWd=".$_POST["app_wd"].", defX=".$_POST["app_X"].", defY=".$_POST["app_Y"].", updatedOn='".$addedON."'  WHERE appId='".$appId."'", $con);
		}
		$accepted = array();
		for($fCnt=0, $tot = count($_FILES); $fCnt < $tot; $fCnt++) {
			$dir = trim($_POST["dir".$fCnt], "./ \n\r\0\t\x0B");
			if(checkAndStore($_FILES["file".$fCnt]["tmp_name"], $appId."/".$dir."/".$_FILES["file".$fCnt]["name"]))	array_push($accepted, $dir."/file".$fCnt);//TODO:dirs...
		}
		print_r($accepted);
		//store in Db+add to use mem
		//$fileList = implode(",", $accepted);
	}
	else	die("DATA/PERMISSION ERROR:".$_POST["app"]);
	mysql_close($con);
?>
