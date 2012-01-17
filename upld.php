<script language = "javascript">
	var i = 1;
	function addF() {
		document.getElementById("data").innerHTML += '<label for="file'+i+'">File'+i+':</label>	<input type="file" name="file'+i+'" id="file'+i+'" /><label for="dir'+i+'">[Path(eg:dir1/dir2/) :</label><input type="text" name="dir'+i+'" id="dir'+i+'" />]<br/>';
		i++;
	}
</script>
<form action="api.php" method="post" enctype="multipart/form-data"><br/>
	<div id = "data">
		<label for="app">AppName :</label>	<input type="text" name="app" id="app" /><br/>
		<label for="descr">Descr. :</label>	<textarea name="descr" id="descr"></textarea><br/>
		<label for="app_ht">app_ht :</label>	<input type="text" name="app_ht" id="app_ht" /><br/>
		<label for="app_wd">app_wd :</label>	<input type="text" name="app_wd" id="app_wd" /><br/>
		<label for="app_X">app_X :</label>	<input type="text" name="app_X" id="app_X" /><br/>
		<label for="app_Y">app_Y :</label>	<input type="text" name="app_Y" id="app_Y" /><br/>
		<label for="file0">File :</label>	<input type="file" name="file0" id="file0" /> <label for="dir0">[Path(eg:dir1/dir2/) :</label><input type="text" name="dir0" id="dir0" />]<br/>
	</div>
	<input type="submit" name="submit" value="Save file(s)" />
	<input type="button" name="add" value="Add another file" onclick="javascript:addF();" />
	<br/><br/>
</form>