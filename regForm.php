<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Registration Form</title>
		<style type="text/css">
			span.ex {
				color:red;
			}
			body{
			font-family:courier;
			background-image:url('mod2.jpg');
			}
			table, td, th {
				
			}
			.right {
				margin-left:50%;
				padding-left:25%
			}
			.center {
				margin-left:25%;
				padding-left:25%
			}
			.left {
				margin-left:0%;
			}
		</style>	
		<script>
			function call_span(obj) {
				//alert(document.getElementsByName(obj)[0].value);
				if(document.getElementsByName(obj)[0].value == "") {
					document.getElementById(obj).innerHTML = " ERROR";
				}
				else	document.getElementById(obj).innerHTML = "";
			}
			function confpass() {
				if(document.regform.password.value != document.regform.confpas.value) {
					document.getElementById('confpas').innerHTML = "PASSWORD ERROR";
				}
				else	call_span('confpas');
			}
			function validate() {
			if(document.regform.username.value == "" || document.regform.password.value == "" || document.regform.confpas.value == "" || 			document.regform.password.value != document.regform.confpas.value || document.regform.clue.value =="" || document.regform.email.value =="" ){
				document.getElementById("submit").innerHTML = "INCOMPLETE FORM";
				call_span('username');
				call_span('password');
				call_span('confpas');
				confpass();
				call_span('clue');
				call_span('email');
				return false;
			}
				return true;
			}
		</script>	
	</head>
	<body>
			<form name="regform" onsubmit="return validate()" action="regNew.php" method="post">
				<table frame="below" border="0" height="450" width="600" class="center" style="padding-left:0%" >
					<tr><td class="right"><h3>UserName:</h3></td><th class="left"><input type="text" name="username" onblur="call_span('username')"/></th><td><span class="ex" id="username" ></span></td><td></td></tr>
					<tr><td class="right"><h3>Password:</h3></td><th class="left"><input type="password" name="password" onblur="call_span('password')"/></th><td><br><span class="ex" id="password" ></span></td><td></td></tr>
					<tr><td class="right"><h3>Confirm Password:</h3></td><th class="left"><input type="password" name="confpas" onblur="javascript:call_span('confpas');confpass();"/></th><td><span class="ex" id="confpas" ></span></td><td></td></tr>
					<tr><td colspan="3" ><hr/></td></tr>
					<tr><td class="right"><h3>Security Tip:</h3></td><th class="left"><input type="text" name="clue" onblur="call_span('clue')"/></th><td><span class="ex" id="clue" ></span></td><td></td></tr>
					<tr><td class="right"><h3>Email Id:</h3></td><th class="left"><input type="text" name="email" onblur="call_span('email')"/></th><td><span class="ex" id="email" ></span></td><td></td></tr>
					<tr><td colspan="0" class="center"><h3>Terms and Conditions:</h3><br/><textarea rows="10" cols="50" name="tc" disabled="disabled">terms will come here</textarea><br/></td></tr>
					<tr><td colspan="1"><span class="ex" id="submit" ></span></td><td></td></tr>
					<tr><td colspan="1" class="center"><input type="submit" name="submit" value=" Agree and Submit" /></td></tr>
				</table>
			</form>		
	</body>
</html>