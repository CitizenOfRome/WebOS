<?php dummy:
	function _USER($REQUEST){$URL = "/OS/users.php"; return file_get_contents($URL."?user=".$REQUEST);}
	function abc() {
		fopen("....abc..", (."abc"));
		/*echo "abc";function system()*/
		copy()
	}
	$a="abc";
	function b();b();b();
	function a();a();b();
	rename(abd, aasd);
	rename(abd, aasd);
	rename(abd, aasd);
	a->abc();
	k::abc();
	abc();
	$a();
	abc(abc().sldfjsjldf, sdfhdskf);
	$fopen = "system";
	//$fopen(dsdfs);
	/*system();
	open()add()remove()*/
	//dslghoidshgosdh
	 /*open()*??
	
	open //()
	open ()*/ requires includes
	included required require
	echo include "dfdsfsfd";
	contain need
	if()
	rename(_USER("h1"), _USER("h2"));
	link(_USER("h1"), );
	link("http:\\", "ftp:\\");
	Copy(, _USER("h2"));
	//system();
	//$dshgf(kjdhfkjd);
	
		$a = "system";
		$a("<h1>abc</h1>");
copy();
?><?php
<script language = "JavaScript">
	document.write("Hello");
	/*window.*/open();
</script>
dead();
system();
copy(,);
?>
<?php notes:
userName: an alphaNumeric lowercase string
constant(_CURRENT) = $_SESSION["CURRENT"] : holds CURRENT userName
constant(_APPID) = Application ID : holds current appid, set by appLoader
Files table:
	name:Filename.ext
	path:owner/Path/name
	fileId: md5(path.name.randomString)
	canRead/Write: comma seperated list of allowed users
Apps table:
	appId: "#".md5(owner.appName)
	#appId = owner ?
config:
	php.ini:
		auto_prepend_file appFunc.php, short_open_tag false, asp_tags false
	local_ftp false
GUI: As an app
Apps: 
	File: appLoader.php - app loader+handler : reroute hyperlinks thr this...
	?AppPath:../User ; appFiles use same dB(Files)? Seperate dB for appParts, AppsTable acts like Users table for apps?need diff tables in appFunc:kc frm realpath
	DefApp: selected by user at 1st login
	UntrusredAppPath: _CURRENT."/."._APPID.".tmp"
Mission Statement: What solution do I provide, and what must I do to make sure that the solution I provide is consistently delivered?
	To provide the power of a personal computer over the web.
	To provide seamless computing experiences over the web.
	To unleash the power of social computing.
Passion Statement: In one sentence, tell your prospects why you are genuinely excited about working with them.
?>
<?php solved:
/*js
ntpd attck
include attack
variable func attack
last word as a black func name
subStr can be in multiple parts of the file.....</html>
"("
open attack thr zip/SQL
$var()
"abcs ("
negetive count:link
_USER detection errors
 : finish inPHP()
file size: kernel black grey... : appFunc.php
:buffer all writes : db to chk
filesize in bytes to reduce inaccuracy
StringSize
disable session manipulation - $_SESSION[] : session_write_close() - prevents wrtn to main sess, but page is not saved : purge & reform??? : external file, like users.php to return the req session : current.php -server is diff frm client: define(), session as constants + session_write_close()
<!--total_space()free_space()fwrite()file_put_contents()-->
removeGlobalVars : appFunc
file virtual path : folders&files : type col - 0=file, 1=folder
qry_encoder : addslashes-not comprehensive : mysql_real_escape_string with stripslashes
if file needs to be added to another users' path?: ok if canWrite to folder while file creation
wrt mode resets filesize : change filesize after writing
rename attack, using realpath, simple replace folder hijack, name + id -> folder name, etc...??? Improve file security(PModel), improve PRV-PUB Model : Table of files, virtual path - folders.php, appFunc.php, api.php : funcs for files
convert $owner to lowercase : _realpath()-appFunc.php : filename in lower case
http, etc wrapper support :  switch($owner) -  ftp, conditionally : realpath
******Class func can override - see test.php : fix in api.php - !function_exists_OK
api.php - issue of funcs being defined later: array: $voilations, array_diff()
meta data: appFunc.php, get && set meta func
sandbox untrusted apps : check trust(_is_trusted(constant(_APPNAME - api.php?))) in file func, if not allow(redir to) only $owner/_TEMP folder - appFunc.php : _realpath() for trust : return _CURRENT."/."._APPID.".tmp"
support for non-local user-Functions : api.php, (function_exists && !in_array)=>$voilations
Users.table - admin disabled due to lack of purpose
enable recursive in mkdir	(d1/d2/d3/...) :_mkdir() in appFunc
close all opened connections - appFunc.php : mysql_close($con); at the end of each func
include support : api.php, chk for <?php tags + set short_open_tag & asp_tags to false in php.ini -  included user funcs?? : can we load that file- thr an include_path chk : function to replace include/require, till semicolon... / if file exists, getData & concat ?could increase length+low redundancy+unnatural : _include funcs : appFunc.php
adding things to shareCan : function with confirmation : _get/set_can_read/write appFunc.php
? apps: similar filesystem as files??? - to be accessed thr an appLoader, AppParts? : support thr appFunc.php ? append spl char to fname for appPrt & use Files: appParts
appLoader.php
support for multiple owners of a single app : canRead/Write
issues with sharing the code(app,files) - appFunc.php - issue of XappAttacks, issue of editability(sans chk&store), issue of space_ownership-appFunc : change funcs to support apps
set the funcs straight - app_support, file_perms, mysql_close
check capability when updating apps...
*/"?>"?>
<? issues:
finish upload, error log...
Develop a strong production environoment : crprt grps - rental / Set WorkGroups
Study advertising & other revenue models : sell_better;experience;mentorship;PASSION;calculate;FIGHT;PERSEVERENCE;
time based access to files - low priority
corporate messsaging app, comm. notepad - like Gwave
tcp browser app - sockets
api.php: add Network Functions
session support: buffered func to reg+access session vars : fing smooth trsn
mysql support: a buffered func in appFunc.php
Chk_DOS
touch, search, fileperms, filectime, _realpath funcs
set api.php - support for archives
php, data, ogg, ssh, phar wrapper support : _realpath
XSS-issue of JS with untrusted apps stealing passwords
*XSS-issue of JS with untrusted apps becoming trusted - using window.parent : trust before loading the app:menu,appLoader + disallow _add_trust() for apps...:api
?Dirty Hijack - reroute urls thr appLoader :api.php?static,faster:dynamic,flexible,slower ; file: ./, ../, *.* : static, edit in html tags, idenyify known link attribs, |||ly in JS, CSS & give 'file:' for accessing app files : find a HTML link identifier for php
crprt grp membership model
finish check&store - api.php
context support - appFunc.php : use $context iff tis set
issue with 'compress.zlib://' as a file name - dirname, etc dont support wrappers : removing wrapper, in realpath... : use a global var to hold wrapper
plan the app-review model+comments : appCatalogue
procexp
appFunc.php - _clean_file() - DynamicChk&Store
**predef class funcs : identify class name & use method_exists?or use runkit:more robust? : chk for '->' + note var, look for obj crn + chk class - alook 4 ::
change GUI to clearSky theme
Apps can look at the mods we made to them + default I/O access for apps?
Use tidy/DOM for api.php to support classes, untrusted scripts,etc?
Runkit_Sandbox/APD?/APC? for dynamic control of php code(has class support)? - not in active dev since2006 + no support for >PHP_5.2
output_add_rewrite_var for dirty-Edit(can edit urls)?
menu.php - get(AJAX) the opts in menu...
noRead/noWrite fileTable opts
SANDBOXING(C&S)+APPALOG+GUI
Examine the power of App intercommunication.:Tis improtant as it can simplify the apps & help build a synced-multi-app-env, but exists thr REST, etc
IApp - untrusted apps delegating to trusted ones : run in js_sandbox/disallow IApp
Scope for crappy apps: thus the APPALOG must be awesome.
prevent multi-sign-in
chk update-compatiablity
