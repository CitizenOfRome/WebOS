<?php
	session_start();if(!isset($_SESSION["CURRENT"])) header("Location:/");define("_CURRENT", $_SESSION["CURRENT"]);session_write_close();
	ignore_user_abort(true);
	$isApp=false;
	$con = mysql_connect("localhost:3306", "root", "", true);	if(!$con || !(mysql_select_db("OS", $con))) { mysql_close($con);  die("CONNECTION Failed."); }
	function _is_trusted($appId=_APPID) {
		global $con;
		$list = mysql_fetch_row(mysql_query("SELECT trustedApps FROM Users WHERE userName='"._CURRENT."'", $con));
		/*mysql_close($con);*/
		//echo (int)in_array($appId, explode(",", $list[0]));
		return in_array($appId, explode(",", $list[0]));
	}
	function _add_trust($appId) {
		if(!_is_trusted(_APPID) && (_get_app_owner($appId)!=_CURRENT))	return false;
		//echo "TRUST";
		global $con;
		$appId = mysql_real_escape_string($appId, $con);
		$list = mysql_fetch_row(mysql_query("SELECT trustedApps FROM Users WHERE userName='"._CURRENT."'", $con));
		$arr = explode(",", $list[0]);
		array_push($arr, $appId);
		$arr = array_unique($arr);
		$result = mysql_query("UPDATE Users SET trustedApps='".implode(",", $arr)."' WHERE userName='"._CURRENT."'", $con);
		/*mysql_close($con);*/
		return $result;
	}
	function _is_user($user) {
		global $con;
		$user = mysql_real_escape_string($user, $con);
		$name = mysql_fetch_row(mysql_query("SELECT userName FROM Users WHERE userName='".$user."'", $con));
		/*mysql_close($con);*/
		if(isset($name[0]))	return true;
		else	return false;
	}
	function _is_app($appId = _APPID) {
		global $con;
		$appId = mysql_real_escape_string($appId, $con);
		$id = mysql_fetch_row(mysql_query("SELECT appId FROM Apps WHERE appId='".$appId."'", $con));
		/*mysql_close($con);*/
		if($id[0] === $appId)	return true;
		else	return false;
	}
	function _get_app_owner($appId = _APPID) {
		global $con;
		//echo "<b>".$appId."</b>";
		$appId = mysql_real_escape_string($appId, $con);
		$owner = mysql_fetch_row(mysql_query("SELECT owner FROM Apps WHERE appId='".$appId."'", $con));
		//echo $owner;
		/*mysql_close($con);*/
		return	$owner[0];
	}
	function _get_meta($file) {
		if(!_is_readable($file))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		$Tabl = $isApp ? "AppParts" : "Files";
		$table = mysql_fetch_row(mysql_query("SELECT meta FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con));
		/*mysql_close($con);*/
		if(isset($table[0]))	return explode(",", $table[0]);
		else return false;
	}
	function _set_meta($file, $meta) {
		if(!_is_writeable($file))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		$meta = mysql_real_escape_string($meta, $con);
		global $isApp;
		$Tabl = $isApp ? "AppParts" : "Files";
		$return = mysql_query("UPDATE ".$Tabl." SET meta='".implode(",", $meta)."' WHERE name='".$name."' AND path='".$path."'", $con);
		/*mysql_close($con);*/
		return $return;
	}
	function _fileowner($file) {
		$file = _realpath($file);
		if(!_is_app($file))	return strstr($file, '/', true);
		else {
			global $con;
			$appId = strstr($file, '/', true);
			$owner = mysql_fetch_row(mysql_query("SELECT fileId FROM Apps where appId='".$appId."'", $con));
			/*mysql_close($con);*/
			return $owner[0];
		}
	}
	function _realpath($file) {
		global $con;
		$file = mysql_real_escape_string(strtolower(trim(strtr($file, "\\", "/")," /\n\r\0\t\x0B")));
		if(!_is_trusted()) return NULL;//_CURRENT."/."._APPID.".tmp";Make the file, here...
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file; if($owner=="")	$owner=$file;
		//echo "<br/>"._APPID."($owner)$file-".(int)($owner == _APPID);
		$isApp=false;
		//echo "[[".(int)($owner==_APPID)."]]".(int)_is_user($owner);
		if(_is_user($owner)) {//if path is okay...
			//echo "user";
			$isApp=false;
			return $file; 
		}
		if($owner == _APPID) { //if path is okay..
			//echo "app";
			$isApp=true;
			return $file;
		}
		switch($owner) {
			case ""://simple name *.* or /*.*
			case ".":
			case "..":
				//appDir, after dynamic check&store is implemented...
				if(_APPID) {//always true for Apps
					$isApp=true;//warning:could be altered by user, if given a chance...
					//echo "***iaAPP";
					$owner = _APPID;
				}
				elseif(_CURRENT) {
					//echo "h1";
					$isApp=false;
					$owner = _CURRENT;
				}
				else	$file = NULL;
				if($file)	$file = $owner."/".ltrim($file, "./ \n\r\0\t\x0B");
			break;
			case "file:":
				$rep = array("file:///" => "");
				$file = _realpath(strtr($file, $rep));
			break;
			case "http:":
			case "https:":
				//Do Nothing, ie allow the file
			break;
			case "compress.zlib:":
				$rep = array("compress.zlib://" => "");//wrapper should be added later...
				//$file = "compress.zlib://"._realpath(strtr($file, $rep));
				$file = _realpath(strtr($file, $rep));
			break;
			case "compress.bzip2:":
				$rep = array("compress.bzip2://" => "");
				$file = _realpath(strtr($file, $rep));
			break;
			default:
				$file = NULL;
			break;
		}
		/*mysql_close($con);*/
		return $file;
	}
	function _get_file_id($file) {
		if(!(_is_readable($file) || _is_writeable($file)))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		$Tabl = $isApp ? "AppParts" : "Files";
		$table = mysql_fetch_row(mysql_query("SELECT fileId FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con));
		/*mysql_close($con);*/
		if(is_array($table))	return $table[0];
		else return false;
	}
	function _get_app_id($file) {
		//if(!_file_exists($file))	return false;
		$file = _realpath($file);
		global $isApp;
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file; if($owner=="")	$owner=$file;
		if($isApp)	return $owner;
		else	return false;
	}
	function _include($file) {
		if(_is_dir($file))	$file = $file."/"."index.php";
		if(_is_readable($file))	return include "../Apps/"._get_file_id($file);
		else	return false;
	}
	function _include_once($file) {
		if(_is_dir($file))	$file = $file."/"."index.php";
		if(_is_readable($file))	return include_once "../Apps/"._get_file_id($file);
		else	return false;
	}
	function _require($file) {
		if(_is_dir($file))	$file = $file."/"."index.php";
		//echo _get_file_id($file);
		if(_is_readable($file))	return require "../Apps/"._get_file_id($file);
		else	return false;
	}
	function _require_once($file) {
		if(_is_dir($file))	$file = $file."/"."index.php";
		if(_is_readable($file))	return require_once "../Apps/"._get_file_id($file);
		else	return false;
	}
	function _is_empty($file) {
		global $con;
		$file = _realpath($file);
		global $isApp;
		$Tabl = $isApp ? "AppParts" : "Files";
		$table = array(); $result = mysql_query("SELECT * FROM ".$Tabl." where path='".$file."'", $con);
		while($row = mysql_fetch_array($result)) {$table = $row;}
		/*mysql_close($con);*/
		if((int)count($table) === 0)	return TRUE;
		else return FALSE;
	}
	function _get_can_read($file) {
		if(!_is_readable($file))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		if(!$isApp)	$table = mysql_fetch_row(mysql_query("SELECT canRead FROM Files where name='".$name."' AND path='".$path."'", $con));
		else	{
			$appId = _get_app_id($file);
			$table = mysql_fetch_row(mysql_query("SELECT canRead FROM Apps where appId='".$appId."'", $con));
		}
		/*mysql_close($con);*/
		if(isset($table[0]))	return explode(",", $table[0]);
		else return false;
	}
	function _set_can_read($file, $user) {
		if(!_is_writeable($file))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		$user = mysql_real_escape_string($user, $con);
		global $isApp;
		if(!$isApp)	$return = mysql_query("UPDATE Files SET canRead='".implode(",", $user)."' WHERE name='".$name."' AND path='".$path."'", $con);
		else	{
			$appId = _get_app_id($file);
			$return = mysql_query("UPDATE Apps SET canRead='".implode(",", $user)."' WHERE appId='".$appId."'", $con);
		}
		/*mysql_close($con);*/
		return $return;
	}
	function _is_readable($file) {
		if(!_file_exists($file))	return false;
		global $con;
		$file = _realpath($file);
		//echo "<br\>rd:".$file;
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		//die((int)$isApp);
		if(!$isApp)	$table = mysql_fetch_row(mysql_query("SELECT canRead FROM Files where name='".$name."' AND path='".$path."'", $con));
		else	{
			$appId = _get_app_id($file);
			$table = mysql_fetch_row(mysql_query("SELECT canRead FROM Apps where appId='".$appId."'", $con));
			//echo $table[0];
		}
		/*mysql_close($con);*/
		//echo count($table);
		if(count($table)!=0 && (in_array(_CURRENT, explode(",", $table[0])) || in_array("*", explode(",", $table[0]))))	return TRUE;
		else return FALSE;
	}
	function _get_can_write($file) {
		if(!_is_writeable($file))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		if(!$isApp)	$table = mysql_fetch_row(mysql_query("SELECT canWrite FROM Files where name='".$name."' AND path='".$path."'", $con));
		else	{
			$appId = _get_app_id($file);
			$table = mysql_fetch_row(mysql_query("SELECT canWrite FROM Apps where appId='".$appId."'", $con));
		}
		/*mysql_close($con);*/
		if(isset($table[0]))	return explode(",", $table[0]);
		else return false;
	}
	function _set_can_write($file, $user) {
		if(!_is_writeable($file))	return false;
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		$user = mysql_real_escape_string($user, $con);
		global $isApp;
		if(!$isApp)	$return = mysql_query("UPDATE Files SET canWrite='".implode(",", $user)."' WHERE name='".$name."' AND path='".$path."'", $con);
		else	{
			$appId = _get_app_id($file);
			$return = mysql_query("UPDATE Apps SET canWrite='".implode(",", $user)."' WHERE appId='".$appId."'", $con);
		}
		/*mysql_close($con);*/
		return $return;
	}
	function _is_writeable($file) {
		if(!_file_exists($file))	return false;
		global $con;
		$file = _realpath($file);
		//echo "<br\>wr:".$file;
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		$table = array();
		if(!$isApp)	$table = mysql_fetch_row(mysql_query("SELECT canWrite FROM Files where name='".$name."' AND path='".$path."'", $con));
		else	{
			$appId = _get_app_id($file);
			$table = mysql_fetch_row(mysql_query("SELECT canWrite FROM Apps where appId='".$appId."'", $con));
			//echo "(".$appId.")";
		}
		/*mysql_close($con);*/
		//echo count($table);
		if(count($table)!=0 && (in_array(_CURRENT, explode(",", $table[0])) || in_array("*", explode(",", $table[0]))))	return TRUE;
		else return FALSE;
	}
	function _is_writable($file) {
		return _is_writeable($file);
	}
	function _file_exists($file){
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		$Tabl = $isApp ? "AppParts" : "Files";
		//echo "($Tabl-$file-$path)";
		$table = mysql_fetch_row(mysql_query("SELECT fileId FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con));
		if(is_Array($table))	return true;
		else return false;
	}
	function _clean_file($file) {
		$file = _realpath($file);
		global $isApp;
		if(!$isApp)	return true;
		
	}
	function _fopen($file, $mode = "r", $false = false, $context="") {
		$return = false;
		if(_is_file($file) || !_file_exists($file)) { //to prevent dir access
			global $con;
			$file = _realpath($file);
			$name = basename($file);
			$path = dirname($file); if($path==".") $path = "";
			$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
			global $isApp;
			if(!_file_exists($file) && (_is_user($owner) || $owner == _APPID))	{
				$folder = $path;
				//echo $folder;
				if(_file_exists($folder) && _is_writeable($folder)) {
					//echo $folder;
					//$admin = mysql_fetch_row(mysql_query("SELECT admin FROM Users WHERE userName='"._CURRENT."'", $con));
					if(!$isApp)	$c = mysql_query("INSERT INTO Files (fileId, name, owner, canRead, canWrite, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$owner."', '".$owner."', '".$path."', 0, '".date("Y-m-d H:i:s")."')", $con);
					else	$c = mysql_query("INSERT INTO AppParts (fileId, name, appId, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$path."', 0, '".date("Y-m-d H:i:s")."')", $con);//meta isnt set
					if(!$c) {
						/*mysql_close($con);*/
						return FALSE;
					}
				}
			}
			$fileId = _get_file_id($file);
			$_dir = $isApp ? "../Apps/" : "../Users/";
			$size = _filesize($file);
			if(isset($mode[strlen($mode)-1]) && $mode[strlen($mode)-1]=="+"){
				if(_is_readable($file) && _is_writeable($file))	$return = fopen($_dir.$fileId, $mode, $context);
			}
			else switch($mode[0]) {
				case "r":
					if(_is_readable($file))		$return = fopen($_dir.$table[0], $mode, $context);
				break;
				case "w":
				case "x":
				case "a":
					if(_is_writeable($file))	$return = fopen($_dir.$table[0], $mode, $context);
				break;
			}
		}
		define(md5((string)$return), $file);
		/*mysql_close($con);*/
		return $return;
	}
	function _fwrite($resource, $data, $length = -1) {
		if($length == -1)	$length = strlen($data);
		global $con;
		$file = constant(md5((string)$resource));
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$user =($owner == _APPID) ? _get_app_owner($owner) : _CURRENT;
		$return=false;
		$psize = _filesize($file);
		$return = fwrite($resource, $data, $length);
		$stat = fstat($resource);
		$size = $stat["size"];
		$free = _disk_free_space($file);
		if($size-$psize > $free)	ftruncate($resource, $free);
		_clean_file($file);
		if(!(mysql_query("UPDATE Users SET memUsed='".(_disk_used_space($file)+$size-$psize)."' WHERE userName='".$user."'", $con))) $return=FALSE;
		/*mysql_close($con);*/
		return $return;
	}
	function _fputs($resource, $data = "", $length = -1) {
		return _fwrite($resource, $data, $length);
	}
	function _fputcsv($resource, $array, $delimiter=",", $enclosure='"') {
		$data = $enclosure.implode($delimiter, $array).$enclosure;
		return _fwrite($resource, $data, strlen($data));
	}
	function _fflush($resource) {
		$tmp = tmpfile();
		fflush($tmp);
		$data = "";
		while(($char = fgetc($tmp)) !== false) {
			$data .= $char;
		}
		return _fwrite($resource, $data);
	}
	function _ftruncate($resource, $length) {
		global $con;
		$file = constant(md5((string)$resource));
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$user =($owner == _APPID) ? _get_app_owner($owner) : _CURRENT;
		$psize = _filesize($file);
		$mem = _disk_used_space();
		$size = $length;
		if(!(mysql_query("UPDATE Users SET memUsed='".($mem+$size-$psize)."' WHERE userName='".$user."'", $con))) { /*mysql_close($con);*/ return FALSE;}
		/*mysql_close($con);*/
		return ftruncate($resource, $length);
	}
	function _filesize($file) {
		if(_file_exists($file)) {
			$file = _realpath($file);
			$name = basename($file);
			$path = dirname($file); if($path==".") $path = "";
			global $isApp;
			$Tabl = $isApp ? "AppParts" : "Files";
			if(_is_file($file)) {
				$_dir = $isApp ? "../Apps/" : "../Users/";
				if(file_exists($_dir._get_file_id($file)))	return filesize($_dir._get_file_id($file));
				else	return 0;
			}
			else if(_is_dir($file)) {
				$size = 0;
				global $con;
				$result = mysql_query("SELECT * FROM ".$Tabl." where path='".mysql_real_escape_string($file)."'", $con);
				/*mysql_close($con);*/
				while($row = mysql_fetch_array($result)) {
					$size += _filesize($row["path"]."/".$row["name"]); 
				}
				return $size;
			}
			else return false;
		}
		else return false;
	}
	function _copy($src, $des, $context=NULL) {
		$fs = _fopen($src, "r", false, $context);
		$data = fread($fs, _filesize($src));
		fclose($fs);
		$fd = _fopen($des, "w", false, $context);
		$wrt = _fwrite($fd, $data);
		fclose($fd);
		if($wrt && $data)	return true;
		else	return false;
	}
	function _rename($src, $des, $context=NULL) {
		_copy($src, $des, $context=NULL);
		_unlink($src);
	}
	function _move_uploaded_file($src, $des) {
		if(is_uploaded_file($src)) {//TODO:is_temp_dir()
			//echo "in(".$des.")";
			$des = _realpath($des);
			$data = file_get_contents($src);
			unlink($src);
			//echo $data[3];
			//echo _get_app_id($des);
			//echo (int)_is_readable($des);
			//echo (int)_is_file($des);
			//echo (int)_file_exists($des);
			return _file_put_contents($des, $data);
		}
		else return false;
	}
	function _disk_free_space($file=_APPID) {
		global $con;
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$user = ($owner==_APPID) ? _get_app_owner($owner) : _CURRENT;
		//echo $file.(int)($owner===_APPID);
		//echo "\n<br\>*".$user."*";
		$_mem = mysql_fetch_row(mysql_query("SELECT memory,memUsed FROM Users WHERE userName='".$user."'", $con));
		//echo mysql_error($con);
		/*mysql_close($con);*/
		return $_mem[0] - $_mem[1];
	}
	function _diskfreespace($file=_APPID) {
		return _disk_free_space($file);
	}
	function _disk_total_space($file=_APPID) {
		global $con;
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$user =($owner == _APPID) ? _get_app_owner($owner) : _CURRENT;
		$_mem = mysql_fetch_row(mysql_query("SELECT memory FROM Users WHERE userName='".$user."'", $con));
		/*mysql_close($con);*/
		return $_mem[0];
	}
	function _disk_used_space($file=_APPID) {
		global $con;
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$user =($owner == _APPID) ? _get_app_owner($owner) : _CURRENT;
		$_mem = mysql_fetch_row(mysql_query("SELECT memUsed FROM Users WHERE userName='".$user."'", $con));
		/*mysql_close($con);*/
		return $_mem[0];
	}
	function _is_dir($file) {
		$file = _realpath($file);
		if(_file_exists($file)) {
			global $con;
			$name = basename($file);
			$path = dirname($file); if($path==".") $path = "";
			global $isApp;
			$Tabl = $isApp ? "AppParts" : "Files";
			$table = mysql_fetch_row(mysql_query("SELECT type FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con));
			/*mysql_close($con);*/
			if((int)$table[0]===1)	return true;
			else return false;
		}
		else return false;
	}
	function _is_file($file) {
		$file = _realpath($file);
		if(!_file_exists($file))	return false;
		global $con;
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		global $isApp;
		$Tabl = $isApp ? "AppParts" : "Files";
		$table = mysql_fetch_row(mysql_query("SELECT type FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con));
		//echo "\n<br\>file-$Tabl-$name-$path-".$table[0];
		/*mysql_close($con);*/
		if((int)$table[0]===0)	return true;
		else return false;
	}
	function _file($file, $flag=0, $context=NULL) {
		$file = _realpath($file);
		if(_is_readable($file)) {
			global $isApp;
			$_dir = $isApp ? "../Apps/" : "../Users/";
			return file($_dir._get_file_id($file), $flag);
		}
		return false;
	}
	function _stat($file) {
		$file = _realpath($file);
		if(_is_readable($file)) {
			global $isApp;
			$_dir = $isApp ? "../Apps/" : "../Users/";
			return stat($_dir._get_file_id($file));
		}
		return false;
	}
	function _readfile($file, $flag=0, $context=NULL) {
		$file = _realpath($file);
		if(_is_readable($file)) {
			global $isApp;
			$_dir = $isApp ? "../Apps/" : "../Users/";
			return readfile($_dir._get_file_id($file), $flag);
		}
		return false;
	}
	function _parse_ini_file($file, $process_sections = false, $scanner_mode = INI_SCANNER_NORMAL) {
		$file = _realpath($file);
		if(_is_readable($file)) {
			global $isApp;
			$_dir = $isApp ? "../Apps/" : "../Users/";
			return parse_ini_file($_dir._get_file_id($file), $process_sections, $scanner_mode);
		}
		return false;
	}
	function _file_get_contents($file, $flag=0, $context=NULL, $offset=-1, $maxlen=-1) {
		$file = _realpath($file);
		if(_is_readable($file)) {
			global $isApp;
			$_dir = $isApp ? "../Apps/" : "../Users/";
			return file_get_contents($_dir._get_file_id($file), $flag);
		}
		return false;
	}
	function _file_put_contents($file, $data="", $flag=0, $context=NULL) {
		$file = _realpath($file);
		//echo "<br/>fps:".$file.(int)_is_file($file);
		if(_is_file($file) || !_file_exists($file)) {
			//echo "put";
			$name = basename($file);
			$path = dirname($file); if($path==".") $path = "";
			$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
			global $isApp;
			global $con;
			if(!_is_writeable($file))	{
				$folder = $path;
				//echo "nofile(".$file.")".$path.(int)_file_exists($folder);
				if(_is_writeable($folder)) {
					//echo "wrt".(int)$isApp;
					if(!$isApp)	$c = mysql_query("INSERT INTO Files (fileId, name, owner, canRead, canWrite, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$owner."', '".$owner."', '".$path."', 0, '".date("Y-m-d H:i:s")."')", $con);
					else	$c = mysql_query("INSERT INTO AppParts (fileId, name, appId, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$path."', 0, '".date("Y-m-d H:i:s")."')", $con);//meta isnt set
					if(!$c) {
						/*mysql_close($con);*/
						//echo "creation failed...".mysql_error($con);;
						return FALSE;
					}
				}
			}
			if(_is_writeable($file)) {
				//echo "exists+wrt";
				$free = _disk_free_space($file);
				$size = strlen((string)$data); $psize = _filesize($file);
				//echo $data;
				//echo $size."|".$psize."|".$free;
				if($size-$psize <= $free) {
					//echo "size";
					$_dir = $isApp ? "../Apps/" : "../Users/";
					$user = $isApp ? _get_app_owner($owner) : _CURRENT;
					if(!(mysql_query("UPDATE Users SET memUsed='".(_disk_used_space($file)+$size-$psize)."' WHERE userName='".$user."'", $con))) {
						/*mysql_close($con);*/
						//echo "updation failed...".mysql_error($con);;
						return FALSE;
					}
					/*mysql_close($con);*/
					//echo $_dir._get_file_id($file);
					//echo $data;
					$return = file_put_contents($_dir._get_file_id($file), $data, $flag);
					_clean_file($file);
					return $return;
				}
			}
			return false;
		}
		else	return false;
	}
	function _mkdir($file, $mode=0770, $recursive=false, $context=NULL) {
		global $con;
		$file = _realpath($file);
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$return = false;
		global $isApp;
		if(!_file_exists($file) && _is_user($owner))	{ //if isWriteable && folder exists, create dir in dB
			$folder = $path;
			if(_is_writeable($folder)) {
				if(!$isApp)	$return = mysql_query("INSERT INTO Files (fileId, name, owner, canRead, canWrite, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$owner."', '".$owner."', '".$path."', 1, '".date("Y-m-d H:i:s")."')", $con);
				else	$return = mysql_query("INSERT INTO AppParts (fileId, name, appId, path, type, addedOn) VALUES ('".md5($file.mt_rand())."', '".$name."', '".$owner."', '".$path."', 1, '".date("Y-m-d H:i:s")."')", $con);//meta isnt set
				/*mysql_close($con);*/
			}
			else if($recursive==true) {
				_mkdir($folder, "", true);
				$return = _mkdir($file);
			}
		}
		return $return;
	}
	function _rmdir($file, $context=NULL) {
		$file = _realpath($file);
		global $isApp;
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		$user = $isApp ? _get_app_owner($owner) : _CURRENT;
		if(_is_writeable($file) && _is_dir($file) && _is_empty($file) && $file!==$user) {
			global $con;
			$name = basename($file);
			$path = dirname($file); if($path==".") $path = "";
			$Tabl = $isApp ? "AppParts" : "Files";
			$return = mysql_query("DElETE FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con);
			/*mysql_close($con);*/
			return $return;
		}
		else return false;
	}
	function _unlink($file, $context=NULL) {
		$file = _realpath($file);
		if(!_is_writeable($file)) return false;
		global $con;
		$name = basename($file);
		$path = dirname($file); if($path==".") $path = "";
		$owner = strstr($file, '/', true); if($owner=="")	$owner=$file;
		global $isApp;
		if(_is_file($file)) {
			$_dir = $isApp ? "../Apps/" : "../Users/";
			$user = $isApp ? _get_app_owner($owner) : _CURRENT;
			$Tabl = $isApp ? "AppParts" : "Files";
			$utab = mysql_query("UPDATE Users SET memUsed='".(_disk_used_space()-_filesize($file))."' WHERE userName='".$user."'", $con);
			$ftab = mysql_query("DElETE FROM ".$Tabl." where name='".$name."' AND path='".$path."'", $con);
			$del = unlink($_dir._get_file_id($file));
			/*mysql_close($con);*/
			return $utab && $ftab && $del;
		}
		else if(_is_dir($file)) {
			/*mysql_close($con);*/
			return _rmdir($file);
		}
		/*mysql_close($con);*/
		return false;
	}
	function _get_mime_type($file) {
		$file = _realpath($file);
		global $isApp;
		$mime_types = array(		
			'txt' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html', 'shtm' => 'text/html', 'php' => 'text/html', 'asp' => 'text/html', 'aspx' => 'text/html', 'phtml' => 'text/html', 'php3' => 'text/html', 'phps' => 'text/html', 'css' => 'text/css', 'js' => 'application/javascript', 'json' => 'application/json', 'xml' => 'application/xml', 'swf' => 'application/x-shockwave-flash', 'flv' => 'video/x-flv', 
			// images
			'png' => 'image/png', 'jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'bmp' => 'image/bmp', 'ico' => 'image/vnd.microsoft.icon', 'tiff' => 'image/tiff', 'tif' => 'image/tiff', 'svg' => 'image/svg+xml', 'svgz' => 'image/svg+xml', 
			// archives
			'zip' => 'application/zip', 'rar' => 'application/x-rar-compressed', 'exe' => 'application/x-msdownload', 'msi' => 'application/x-msdownload', 'cab' => 'application/vnd.ms-cab-compressed', 
			// audio/video
			'mp3' => 'audio/mpeg', 'mpeg' => 'audio/mpeg', 'wav' => 'audio/x-wav', 'qt' => 'video/quicktime', 'mov' => 'video/quicktime', 
			// adobe
			'pdf' => 'application/pdf', 'psd' => 'image/vnd.adobe.photoshop', 'ai' => 'application/postscript', 'eps' => 'application/postscript', 'ps' => 'application/postscript', 
			// office
			'doc' => 'application/msword', 'rtf' => 'application/rtf', 'xls' => 'application/vnd.ms-excel', 'ppt' => 'application/vnd.ms-powerpoint', 'odt' => 'application/vnd.oasis.opendocument.text', 'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
		$ext = array_pop(explode('.',$file));
		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		}
		else if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$_dir = $isApp ? "../Apps/" : "../Users/";
			$file = realpath($_dir._get_file_id($file));
			$mimetype = finfo_file($finfo, $file);
			finfo_close($finfo);
			return $mimetype;
		}
		else {
			return 'application/octet-stream';
		}
	}
?>