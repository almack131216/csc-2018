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
