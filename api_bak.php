<?php require "/appFunc.php";/*echo _realpath("file:///./Hello.txt");*/ ?>
<?php
	session_start();
	$inTag = $sgl = $dbl = 0;
	function joinFrom($loc, $char, $arr) {
		$strp = $strn = "";
		for($j = $loc; $j < count($arr); $j++) {
			if($arr[$j] != "") {
				$strn.=$arr[$j];
				if($j !== count($arr)-1)	$strn.=$char;
			}
		}
		for($i=0; $i < $loc; $i++) {
			$strp.=$arr[$i].$char;
		}
		$str = array($strp, $strn);
		return $str;
	}
	function inQuotesSetTag($string) {
		global $sgl, $dbl, $inTag;
		for($i = 0; $i < strlen($string); $i++) {
			if($string[$i] == '"' && $sgl != 1)	$dbl = 1 - $dbl;
			if($string[$i] == "'" && $dbl != 1)	$sgl = 1 - $sgl;
			if($dbl == 0 && $sgl == 0) {
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
	function isGrey($string) {
		if(
			stripos(ltrim($string), "_USER(") !== 0 
			&& stripos(ltrim($string,"/ \t\n\r\0\x0B'\""), "http:\\") !== 0 
			&& stripos(ltrim($string,"/ \t\n\r\0\x0B'\""), "ftp:\\") !== 0
		)	return true;
		else	return false;
	}
	function inPHP($string) {
		//dosent work for one liners, i.e. those without funcs/parantheses
		global $inTag;
		//needs parallel chks for Quotes....
		if($inTag == 1)	return true;
		else	return false;
	}
	function checkWhitelist($file_path, $path) {
		$homePath = "$@!#^!%";
		$whitelist = array(
			//kernel
			"_USER",
			//general
			"if", "elseif", "for", "while", "foreach", "switch", "return", "function_exists", "isset",
			//array
			"array","array_change_key_case","array_chunk","array_combine","array_count_values", "array_diff","array_diff_assoc","array_diff_key","array_diff_uassoc","array_diff_ukey","array_fill","array_filter","array_flip","array_intersect","array_intersect_assoc","array_intersect_key","array_intersect_uassoc","array_intersect_ukey","array_key_exists","array_keys","array_map","array_merge","array_merge_recursive","array_multisort","array_pad","array_product","array_pop","array_push","array_rand","array_reduce","array_reverse","array_search","array_shift","array_slice","array_splice","array_sum","array_udiff","array_udiff_assoc","array_udiff_uassoc","array_uintersect","array_uintersect_assoc","array_uintersect_uassoc","array_unique","array_unshift","array_values","array_walk","array_walk_recursive","asort","arsort","compact","count","current","each","end","extract","in_array","key","krsort","ksort","list","natcasesort","natsort","next","pos","prev","range","reset","rsort","shuffle","sizeof","sort","uasort","uksort","usort","sizeof", "print_r",
			//calender
			"cal_days_in_month","cal_from_jd","cal_info","cal_to_jd","easter_date","easter_days","frenchtojd","gregoriantojd","jddayofweek","jdmonthname","jdtofrench","jdtogregorian","jdtojewish","jdtojulian","jdtounix","jewishtojd","juliantojd","unixtojd",
			//date
			"checkdate","date_default_timezone_get","date_default_timezone_set","date_sunrise","date_sunset","date","getdate","gettimeofday","gmdate","gmmktime","gmstrftime","idate","localtime","microtime","mktime","strftime","strptime","strtotime","time",
			//erroe
			"debug_backtrace","debug_print_backtrace","error_reporting","restore_error_handler","restore_exception_handler","set_error_handler","set_exception_handler","trigger_error","user_error",
			//http
			"setcookie","setrawcookie","ezmlm_hash","mail",
			//math
			"abs","acos","acosh","asin","asinh","atan","atan2","atanh","base_convert","bindec","ceil","cos","cosh","decbin","dechex","decoct","deg2rad","exp","expm1","floor","fmod","getrandmax","hexdec","hypot","is_finite","is_infinite","is_nan","lcg_value","log","log10","log1p","max","min","mt_getrandmax","mt_rand","mt_srand","octdec","pi","pow","rad2deg","rand","round","sin","sinh","sqrt","srand","tan","tanh",
			//misc.
			"connection_aborted", "connection_status", "constant", "define", "defined", "die", "exit", "get_browser", "highlight_string", "ignore_user_abort", "sleep", "time_nanosleep", "time_sleep_until", "uniqid", "usleep",

			//string
			"addcslashes", "addslashes", "bin2hex", "chop", "chr", "chunk_split", "convert_cyr_string", "convert_uudecode", "convert_uuencode", "count_chars", "crc32", "crypt", "echo", "explode", "get_html_translation_table", "hebrev", "hebrevc", "html_entity_decode", "htmlentities", "htmlspecialchars_decode", "htmlspecialchars", "implode", "join", "levenshtein", "localeconv", "ltrim", "md5", "metaphone", "money_format", "nl_langinfo", "nl2br", "number_format", "ord", "parse_str", "print", "printf", "quoted_printable_decode", "quotemeta", "rtrim", "setlocale", "sha1", "similar_text", "soundex", "sprintf", "sscanf", "str_ireplace", "str_pad", "str_repeat", "str_replace", "str_rot13", "str_shuffle", "str_split", "str_word_count", "strcasecmp", "strchr", "strcmp", "strcoll", "strcspn", "strip_tags", "stripcslashes", "stripslashes", "stripos", "stristr", "strlen", "strnatcasecmp", "strnatcmp", "strncasecmp", "strncmp", "strpbrk", "strpos", "strrchr", "strrev", "strripos", "strrpos", "strspn", "strstr", "strtok", "strtolower", "strtoupper", "strtr", "substr", "substr_compare", "substr_count", "substr_replace", "trim", "ucfirst", "ucwords", "vprintf", "vsprintf", "wordwrap", "unpack", "pack",
			//files:	
			"clearstatcache", "fclose", "feof", "fflush", "fgetc", "fgetcsv", "fgets", "fgetss", "flock", "fpassthru", "parse_ini_string", "fputcsv", "fputs", "fread", "fscanf", "fseek", "fstat", "ftell", "ftruncate", "fwrite", "rewind", "vfprintf", "fprintf",
			//url
			"base64_decode","base64_encode","get_headers","get_meta_tags","http_build_query","parse_url","rawurldecode","rawurlencode","urldecode","urlencode",
			//regexp
			"ereg_replace", "ereg", "eregi_replace", "eregi", "split", "spliti", "sql_regcase", "preg_filter", "preg_grep", "preg_last_error", "preg_match_all", "preg_match", "preg_quote", "preg_replace_callback", "preg_replace", "preg_split"
			
		);
		/*,
			//javascript
			"alert", "blur", "clearinterval", "cleartimeout", "confirm", "createpopup", "focus", "moveby", "moveto", "prompt", "resizeby", "resizeto", "scroll", "scrollby", "scrollto", "setinterval", "settimeout", "javaenabled", "taintenabled", "back", "forward", "go", "assign", "reload", "replace", "close", "getelementbyid", "getelementsbyname", "getelementsbytagname", "write", "writeln", "appendchild", "blur", "click", "clonenode", "focus", "getattribute", "getelementsbytagname", "haschildnodes", "insertbefore", "item", "normalize", "removeattribute", "removechild", "replacechild", "setattribute", "tostring", "reset", "submit", "select", "createcaption", "createtfoot", "createthead", "deletecaption", "deleterow", "deletetfoot", "deletethead", "insertrow", "deletecell", "insertcell", "url", "indexof", "unescape", "substring", "setdate", "escape", "value", "togmtstring", "date", "getdate", "void", "random", "alpha", "concat", "join", "pop", "push", "reverse", "shift", "slice", "sort", "splice", "tostring", "unshift", "valueof", "tostring", "valueof","getdate", "getday", "getfullyear", "gethours", "getmilliseconds", "getminutes", "getmonth", "getseconds", "gettime", "gettimezoneoffset", "getutcdate", "getutcday", "getutcfullyear", "getutchours", "getutcmilliseconds", "getutcminutes", "getutcmonth", "getutcseconds", "getyear", "parse", "setdate", "setfullyear", "sethours", "setmilliseconds", "setminutes", "setmonth", "setseconds", "settime", "setutcdate", "setutcfullyear", "setutchours", "setutcmilliseconds", "setutcminutes", "setutcmonth", "setutcseconds", "setyear", "todatestring", "togmtstring", "tolocaledatestring", "tolocaletimestring", "tolocalestring", "tostring", "totimestring", "toutcstring", "utc", "abs", "acos", "asin", "atan", "atan2", "ceil", "cos", "exp", "floor", "log", "max", "min", "pow", "random", "round", "sin", "sqrt", "tan", "toexponential", "tofixed", "toprecision", "tostring", "valueof", "charat", "charcodeat", "concat", "fromcharcode", "indexof", "lastindexof", "match", "replace", "search", "slice", "split", "substr", "substring", "tolowercase", "touppercase", "valueof", "anchor", "big", "blink", "bold", "fixed", "fontcolor", "fontsize", "italics", "small", "strike", "sub", "sup", "valueof", "compile", "test", "decodeuri", "decodeuricomponent", "encodeuri", "encodeuricomponent", "escape", "isfinite", "isnan", "number", "parsefloat", "parseint", "string", "unescape", "regexp",
			//ajax
			"xmlhttprequest", "activexobject", "setrequestheader", "send", "function", "open",
			//jquery
			"$", "ready", "change", "click", "dblclick","error", "keydown","keypress","keyup", "load", "mousedown", "mouseenter", "mouseleave", "mousemove", "mouseout", "mouseover", "mouseup", "resize", "scroll", "select", "submit", "unload", "trigger", "triggerhandler", "bind", "delegate", "die", "live", "one", "unbind", "undelegate","show","hide","toggle","slidedown","slideup","slidetoggle","fadein","fadeout","fadeto","animate","stop","clearqueue","delay","dequeue","queue","html","text","attr","val","html","text","attr","val","after","before","insertafter","insertbefore","addclass","removeclass","toggleclass","hasclass","append","prepend","appendto","prependto","wrap","wrapall","wrapinner","unwrap","replaceall","replacewith","empty","remove","removeattr","clone","detach","css","css","css","height","height","width","width","offset","offsetparent","position","scrolltop","scrolltop","scrollleft","scrollleft"
			*/
		$fileParam1 =  array("md5_file", "sha1_file", "basename", "file_exists", "file_get_contents", "file", "fileatime", "filectime", "filemtime", "fileperms", "filesize", "filetype", "fopen", "is_dir", "is_executable", "is_file", "is_link", "is_readable", "is_uploaded_file", "is_writable", "is_writeable", "mkdir", "parse_ini_file", "readfile", "readlink", "tempnam", "touch", "unlink", "highlight_file",  "php_check_syntax", "php_strip_whitespace", "show_source", "simplexml_load_file", "load", "linkinfo");
		$fileParam2 =  array("link", "rename", "symlink");
		$splFunc = array(".." => ".", "included" => "contained", "required" => "needed", "include" => "contain", "require" => "need");
		$operators = array( "+", "-", "*", "/", "&", "=", "%", "!", "~", ">", "<", "^", "|", "\\", "?", ":", ";", ".", ",", "?", "`", "{", "}", "[", "'", '"');
		#ahsdkfhkj
		$count = 0;
		$flag = 0;
		$cnt = 0;
		$changed_data = strtr(php_strip_whitespace($file_path), $splFunc);
		$fp = fopen($file_path, "w+");
		fwrite($fp, $changed_data);
		Fclose($fp);
		$endParam = array(",", ")");
		$set = explode("(", $data = $changed_data);
		foreach($set as $subString) {
			//echo "<h1>".$cnt."</h1>";
			if($flag > 0) {
				$q1 = $subString;
				//$q1_st = array( $q1 => $homePath.$q1, ".." => ".", "~"=>"");
				$chd = joinFrom($cnt, "(", $set);
				if(isGrey($chd[1])) {
					if(in_array(substr(trim($chd[1]), 0, 1), $endParam))	$changed_data = $chd[0].$homePath.$chd[1];
					else	$changed_data = $chd[0].$homePath.'.'.$chd[1];
				}
				echo "<br/><b>^".htmlentities($chd[1]."#".stripos(ltrim($chd[1]), "_USER(")."@".(isGrey($chd[1])?"1":"0").substr(trim($chd[1]), 0, 1).in_array(substr(trim($chd[1]), 0, 1), $endParam))."</b><br/>";
				//echo "<br/>".htmlentities($changed_data)."<br/>";
				if($flag == 2) {
					$q2 = explode(",", $chd[1]);
					$qT = explode(",", $q1);
					if(count($q2) > 1 && stripos($qT[0], ')') === false) {
						$chd1 = joinFrom(1, ',', explode(',', $chd[1]));
						echo "<br/><b>^^".htmlentities($chd1[1]."#".stripos(ltrim($chd1[1]), "_USER(")."@".(isGrey($chd1[1])?"1":"0").substr(trim($chd1[1]), 0, 1))."</b><br/>";
						if(isGrey($chd1[1])) {
							$schd1 = substr_replace($changed_data, "", -strlen($chd1[1]), strlen($chd1[1]));
							if(in_array(substr(trim($chd1[1]), 0, 1), $endParam))	$changed_data = $schd1.$homePath.$chd1[1];
							else	$changed_data = $schd1.$homePath.'.'.$chd1[1];
						}
					}
				}
				$fp = fopen($file_path, "w+");
				fwrite($fp, $changed_data);
				fclose($fp);
				$set = explode("(", $changed_data);
			}
			if(in_array($subString[strlen($subString)-1], $operators) || inQuotesSetTag($subString) || !inPHP($subString)) {
				echo "<h5>'".htmlentities($subString)."'</h5>";
				$flag = 0;
				$cnt++;
				continue;
			}
			$words = str_word_count($subString, 1, '1234567890$_');
			/*if(!function_exists($words[count($words)-1]) && strpos($words[count($words)-1], "$") !== 0)	continue;
			else	$words[count($words)-1] = strtolower($words[count($words)-1]);*/
			if($words[count($words)-1] !== "_USER" && count($words) > 0)	$words[count($words)-1] = strtolower($words[count($words)-1]);
			if(count($words) > 0 && !in_array($words[count($words)-1], $whitelist)) {
				echo "<h5>".$words[count($words)-1]."</h5>";
				$count++;
			}
			if(count($words) > 1) {
				$words[count($words)-2] = strtolower($words[count($words)-2]);
				if($words[count($words)-2] == "function" || $words[count($words)-2] == "new" && !in_array($words[count($words)-1], $whitelist)) {
					array_push($whitelist, $words[count($words)-1]);
					$count--;
					//echo "<h2>func</h2>";
				}
			}
			print_r($words);
			echo "'".$count."'";
			/*echo htmlentities($subString);
			echo count($words);
			echo htmlentities($words[count($words)-1]);*/
			if(in_array($words[count($words)-1], $fileParam1)) {
				$flag = 1;
				$count--;
				//echo "<h2>fl1</h2>";
			}
			else if(in_array($words[count($words)-1], $fileParam2)) {
				$flag = 2;
				$count--;
				//echo "<h2>fl2</h2>";
			}
			else	$flag = 0;
			echo "<b>F:".$flag.$words[count($words)-1]."</b><br/>";
			$cnt++;
		}
		$list = str_word_count(file_get_contents($file_path), 1, '1234567890$_');
		if(!in_array($list[count($list)-1], $whitelist) && !function_exists($list[count($list)-1])) {
			$count--;
			//echo "<h2>end</h2>";
		}
		$fixUser0 = array("$@!#^!%$@!#^!%" => "$@!#^!%");
		$fixUser1 = array("$@!#^!%" => "_USER()");
		//$fixUser2 = array("(," => "");
		$changed_data = strtr(strtr($changed_data, $fixUser0), $fixUser1);
		Echo "<br/>".htmlentities(rawurldecode($changed_data))."<br/>";
		$l_php = array( "php", "php5", "phtml", "php6");
		if(in_array(strtolower(array_pop(explode('.', $_FILES["file"]["name"]))), $l_php)) {
			$changed_data = '<?php require "/appFunc.php"; ?>'.$changed_data;
		}
		//echo"<br/>".file_exists($file_path).$changed_data."<br/>".htmlentities($data);
		//if file is php
		$fp = fopen($file_path, "w+");
		fwrite($fp, $changed_data);
		fclose($fp);
		if($count > 0) {
			echo "File Rejected.$count";
		}
		else {
			echo "File Accepted.$count";
			move_uploaded_file($file_path, $path);
			echo "<h3>"._USER()."</h3>";
		}
	}
	function ownsApp($app) {
		
		return true;
	}
	#echo "./Apps/".md5($_POST["app"])."/".$_FILES["file"]["name"];//enc if needed
	#if(isset($_POST["app"]) && ownsApp($_POST["app"]))
	#	checkWhitelist($_FILES["file"]["tmp_name"], "./Apps/".md5($_SESSION["CURRENT"].$_POST["app"])."/".$_FILES["file"]["name"]);
?>
