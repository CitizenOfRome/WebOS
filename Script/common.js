//DND{
var dnd = new Array();
var pleft, ptop, xcoor, ycoor;
var curId = null;
document.onmousedown = mouseDown;
document.onmouseup = mouseUp;
function inArray(needle, haystack) {
	var i=0;
	for(i=0; i < haystack.length; i++) {
		if(needle == haystack[i])	return true;
	}
	return false;
}
function mouseDown(e) {
	if (!e)	e = window.event;
	var sender = (typeof( window.event ) != "undefined" ) ? e.srcElement : e.target;//ie:others
	if(inArray(sender.id, dnd)) {
		pleft=parseInt(sender.style.left);
		ptop=parseInt(sender.style.top);
		xcoor = e.clientX;
		ycoor = e.clientY;
		curId = sender.id;
		document.onmousemove=mouseMove;
		document.onmouseout=mouseMove;
	}
	return false;
}
function mouseMove(e) {
	if (!e)	e = window.event;
	var sender = (typeof( window.event ) != "undefined" ) ? e.srcElement : e.target;//ie:others
	if(curId == sender.id) {
		sender.style.left = (pleft + e.clientX - xcoor) + "px";
		sender.style.top = (ptop + e.clientY - ycoor) + "px";
	}
	return false;
}
function mouseUp(e) {
	document.onmousemove=null;
	document.onmouseout=null;
	curId = null;
}
function enableDragNDrop(id) {
	var sender = document.getElementById(id);
	dnd.push(id);
	sender.style.left=(sender.offsetLeft)+"px";//req as style.left can only set but not return
	sender.style.top=(sender.offsetTop)+"px";//style.top is void if not set
}
//}
function setOpacity(obj, value) {
	obj.style.opacity=value/100;
	if(typeof(obj.filters)!="undefined")	obj.filters.alpha.opacity=value;
}
function placeGlass(z_index) {
	if(!document.getElementById("glass"))	document.write("<div id='glass'></div>");
	var glass = document.getElementById("glass");
	glass.style.height = "100%";
	glass.style.width = "100%";
	glass.style.backgroundColor = "grey";
	//glass.style.visibility = "hidden";
	glass.style.zIndex = z_index;
	glass.style.opacity=0.3;
	if(typeof(glass.filters)!="undefined")	glass.filters.alpha.opacity=30;
	//alert(glass.style.zIndex);
}
function createCanvas(id, src, height, width, x, y, z) {
	z = z||0;
	x = x||0;
	y = y||0;
	height = height||"100%";
	width = width||"100%";
	if(!document.getElementById("canvas")) {
		document.write("<div id='canvas'></div>");
	}
	document.getElementById("canvas").innerHTML+='<div id="'+id+'"><iframe class="canvas" src="'+src+'"></iframe></div>';
	var canvas = document.getElementById(id);
	canvas.style.height = height;
	canvas.style.width = width;
	canvas.style.top = y;
	canvas.style.left = x;
	canvas.style.zIndex = z;
	if(canvas)	return true;
}
function changeCanvas(id, src, height, width, x, y, z) {
	z = z||0;
	x = x||0;
	y = y||0;
	height = height||"100%";
	width = width||"100%";
	if(!document.getElementById(id))	return createCanvas(id, src, height, width, x, y, z);
	var canvas = document.getElementById(id);
	canvas.style.height = height;
	canvas.style.width = width;
	canvas.style.top = y;
	canvas.style.left = x;
	canvas.style.zIndex = z;
	canvas.innerHTML='<iframe class="canvas" src="'+src+'"></iframe>';
	return true;
}
function destroyCanvas(id) {
	var canvas = document.getElementById(id);
	canvas.innerHTML="";
	return true;
}
function loadPageInto(lid, url, method, preserve, async) {//swap preserve & async
	if(!document.getElementById(lid))	return false;
	async = async || true;
	method = method || "GET";
	preserve = (preserve != undefined) ? document.getElementById(lid).innerHTML : "";
	var xmlHttp;
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject) { // code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHttp");
	}
	xmlHttp.onreadystatechange=function() {
		if(xmlHttp.readyState==4) {
			document.getElementById(lid).innerHTML = preserve + xmlHttp.responseText;//response or o/p
			return xmlHttp.responseText;
		}
	}
	xmlHttp.open(method, url, async);
	//xmlHttp.open("GET","abc.txt",true);//Type,Give o/p of, last is if async
	if(method=="POST") {
		xmlHttp.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		//xmlHttp.setRequestHeader("enctype", "multipart/form-data");
	}
	xmlHttp.send(null);
}