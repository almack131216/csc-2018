/*
    ====================================
    amactive custom scripts
    ====================================
*/
( function() {

	var toggleExpandedClass = function(el) {
		const parent = el.parentElement;
		// console.log('PARENT: ', parent);
		const expandable = parent.getElementsByClassName('expandable')[0];
		// console.log('EXAPNDABLE: ', expandable);
		let showtext;
		
		if (el.className.indexOf('expanded') !== -1) {
			el.className = el.className.replace( ' expanded', '' );
			showtext = el.getAttribute('data-more');
		} else {
			el.className += ' expanded';
			showtext = el.getAttribute('data-less');
		}

		if (expandable.className.indexOf('expanded') !== -1) {
			expandable.className = expandable.className.replace( ' expanded', '' );
		} else {
			expandable.className += ' expanded';
		}
		el.innerHTML = showtext;
	}

	var toggleMore = function toggleMore(e) {
        // console.log('toggleMore ');
		e.preventDefault();
		toggleExpandedClass(e.target);
	};

	window.toggleMore = toggleMore;

} )();

/* product photos - print */
function makepage(src,getPageTitle){
  // We break the closing script tag in half to prevent
  // the HTML parser from seeing it as a part of
  // the *main* page.

  return "<html>\n" +
    "<head>\n" +
    "<title>" + getPageTitle + "</title>\n" +
    "<script>\n" +
    "function step1() {\n" +
    "  setTimeout('step2()', 10);\n" +
    "}\n" +
    "function step2() {\n" +
    "  window.print();\n" +
    "  window.close();\n" +
    "}\n" +
    "</scr" + "ipt>\n" +
    "</head>\n" +
    "<body onLoad='step1()'>\n" +
    "<img src='" + src + "'/>\n" +
    "</body>\n" +
    "</html>\n";
}

function printme(getPageTitle,imgID){
	image = document.getElementById(imgID);
	
	src = image.src;
	link = "about:blank";
	var pw = window.open(link, "_new");
	pw.document.open();
	pw.document.write(makepage(src,getPageTitle));
	pw.document.close();
}
