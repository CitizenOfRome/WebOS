<?php
	//(GUI)contains code for gen. menu & its button..., to probably be loaded thr index.php / integrated with it...
	/*<div>logo.png</div>
	*<div, z-1> rest of the menu box, hidden-activate onclick</div>
	*TODO: get the loaded file's title+logo,etc.../Set a title for the page with the app's title/name in it...
	*/
	require "./appFunc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>AppName - ProductName</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="EN" />
		<meta name="keywords" content="OS,..." />
		<style type="text/css">
			/*<![CDATA[*/
			html, body, .img {
				margin:0;
				padding:0;
				border:0px;
				height:100%;
				width:100%;
				overflow:auto;
			}
			.canvas, #canvas {
				margin:0;
				padding:0;
				z-index:0;
				border:0px;
				height:100%;
				width:100%;
			}
			#ctrl {
				position:absolute;
				top:2%;
				right:3%;
				z-index:500;
				height: 60px;
				width: 60px;
				overflow:hidden;
				opacity:0.2;
				filter:alpha(opacity=20);
				/*border:2px blue dotted;*/
			}
			#ctrl:hover {
				opacity:1;
				filter:alpha(opacity=100);
			}
			#menu {
				z-index:499;
				position:absolute;
				top:5%;
				right:5%;
				height: 90%;
				width: 90%;
				overflow:auto;
				background-color:Snow;
				border:3px DeepSkyBlue solid;
			}
			#ad {
				z-index:300;
				position:absolute;
				top: 79%;
				right: 3%;
				height: 20%;
				width: 20%;
				overflow:hidden;
				opacity:0.8;
				filter:alpha(opacity=80);
				background-color:DeepSkyBlue;
				border:2px Azure solid;
			}
			#glass {
				z-index:-500;
				position:absolute;
				top:0;
				left:0;
				height:100%;
				width:100%;
				opacity:0;
				filter:alpha(opacity=0);
			}
			#menu_opt {
				border-right:2px LightCyan solid;
				padding-right:5px;
				margin-top:2%;
				padding-top:3%;
				height:85%;
				width:25%;
				position:absolute;
				top:0%;
				left:0%;
				overflow:hidden;
			}
			#menu_con {
				padding-left:10px;
				height:100%;
				width:74%;
				position:absolute;
				top:0%;
				left:25%;
				overflow:auto;
			}
			.menu_opt {
				width:100%;
				height:5%;
				text-align:justify;
				color:MidnightBlue;
				border:0px DeepSkyBlue solid;
				font-size:100%;
				font-weight:500;
				font-family:Georgia,"Lucida Console",Serif;
				background-color:Azure;
				padding:3px;
				padding-left:12%;
				margin:2px;
				border-right:2px DeepSkyBlue solid;
			}
			.menu_opt:hover {
				background-color:LightCyan;
				font-weight:600;
			}
			/*]]>*/
		</style>
	</head>
	<body bgcolor="white">
		<div id="ctrl" onclick="JavaScript:toggleMenu();" onmouseover="JavaScript:setOpacity(this, 100);" onmouseout="JavaScript:if(menu.hide)setOpacity(this, 20);">
			<img class="img" src="./Images/favico.png" alt="Control" />
		</div>
		<div id="menu">
			<div id="menu_opt">
				<div class="menu_opt" id="menu_opt_proc">Processes</div>
				<div class="menu_opt" id="menu_opt_set">Settings</div>
			</div>
			<div id="menu_con">ABCD</div>
		</div>
		<div id="ad">
			<img class="img" src="./Images/ad.png" alt="ad" />
		</div>
		<!--<div id="test" style="z-index:500;height:40px;width:40px;position:absolute;">TESTBOX</div>-->
		<div id="canvas">
			<!--<iframe class="canvas" src="./appLoader.php?file=f39a820fa391bc46c264c3a125b26358/sudoku.php">:</iframe>-->
		</div>
		<div id="glass"></div>
		<script type="text/JavaScript" src="./Script/common.js"></script>
		<script type="text/JavaScript">
			//<![CDATA[
			//enableDragNDrop("test");
			function enableSwitch(ad) {//try a mechanism where, it disappears into the background + is at a lower layer than canvas
				ad = ad || document.getElementById("ad");
				ad.style.left=(ad.offsetLeft/window.innerWidth*100)+"%";
				ad.style.top=(ad.offsetTop/window.innerHeight*100)+"%";
				ad.onmouseover = function(e) {
					//ad.style.top=(innerHeight - parseInt(ad.style.top))+"px";
					//alert(ad.style.left);
					//alert(ad.offsetWidth/window.innerWidth*100);
					//setOpacity(ad, 50);
					ad.style.left=(100 - (parseInt(ad.style.left) + parseInt(ad.offsetWidth/window.innerWidth*100)))+"%";
					//toggle b/n pos... if
				}
			}
			enableSwitch();
			loadPageInto("menu_con", "http://localhost/index.php");
			var menu = document.getElementById("menu");
			var ctrl = document.getElementById("ctrl");
			//enableDragNDrop("menu");
			menu.hide=false;
			function toggleMenu() {
				//alert(menu.hide);
				if(menu.hide==false) {
					//alert("hide");
					menu.style.height = "0%";
					menu.style.width = "0%";
					menu.style.visibility = "hidden";
					setOpacity(ctrl, 20);
					placeGlass(-500);
					menu.hide = true;
				}
				else {
					//alert("show");
					menu.style.height = "90%";
					menu.style.width = "90%";
					menu.style.visibility = "visible";
					setOpacity(ctrl, 80);
					placeGlass(495);
					menu.hide = false;
				}
			}
			toggleMenu();
			//var canvas = document.getElementById("canvas");
			//alert(canvas.title);
			createCanvas("sudoku", "./appLoader.php?file=f39a820fa391bc46c264c3a125b26358/sudoku.php");
			//]]>
		</script>
	</body>
</html>