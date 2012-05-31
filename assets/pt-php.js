function togdisp(e, x) {
	var hoverdiv = document.getElementById(e);
	if (!x) x = hoverdiv.style.display == 'none' ? 1 : 2;
	if (x == 1) hoverdiv.style.display = 'block';
	if (x == 2) hoverdiv.style.display = 'none';
	return false;
}

function togboard(board) {
	/* set hidden input on form to new board */
	var form = document.getElementById('formboard');
	form.value = board;
	/* reset class in all headers */
	var head = document.getElementsByName('nav');
	for (i = 0; i < head.length; i++) {
		head[i].className = 'navbarboard';
	}
	/* set active board's header to active class */
	var header = document.getElementById(board);
	header.className = 'navbarboard navbarcurboard';
	return false;
}