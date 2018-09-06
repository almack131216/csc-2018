/*
This script is written by Eric (Webcrawl@usa.net)
For full source code, installation instructions,
100's more DHTML scripts, and Terms Of
Use, visit dynamicdrive.com
*/

function printit(){  
	if (window.print) {
		window.print() ;  
	} else {
		var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
		document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
		WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";  
	}
}
/*
function showPrintButton(){
	var NS = (navigator.appName == "Netscape");
	var VERSION = parseInt(navigator.appVersion);
	if (VERSION > 3) {
		document.write('<form><input type=button value="Print this Page" name="Print" onClick="printit()"></form>');        
	}
}
*/

//////////////////////////////////////////////////////////////////////////////////
function initiatePrintLink(){
	if(!document.getElementsByTagName) return false;
	if(!document.getElementsByTagName("a")) return false;
	if(!document.createTextNode) return false;
	//if(!window.sidebar && !document.all) return false;
	if(!document.title) return false;
	if(!location.href) return false;
	var linkText = document.createTextNode("Print this page");
	var pageToBookmark = location.href;
	var pageTitle = document.title;
	var links = document.getElementsByTagName("a");
	for (var i=0; i<links.length; i++){
		if(links[i].getAttribute("id") == "printPage"){
			links[i].style.display = "block";
			links[i].onclick = function() {
									printit();return false;
								}
			links[i].appendChild(linkText);
			return false;
		}else{
			continue;
		}
	}
	return false;
}