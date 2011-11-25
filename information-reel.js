	

function scrollIR() {
	objIR.scrollTop = objIR.scrollTop + 1;
	IR_scrollPos++;
	if ((IR_scrollPos%IR_heightOfElm) == 0) {
		IR_numScrolls--;
		if (IR_numScrolls == 0) {
			objIR.scrollTop = '0';
			IRContent();
		} else {
			if (IR_scrollOn == 'true') {
				IRContent();
			}
		}
	} else {
		setTimeout("scrollIR();", 10);
	}
}

var IRNum = 0;
/*
Creates amount to show + 1 for the scrolling ability to work
scrollTop is set to top position after each creation
Otherwise the scrolling cannot happen
*/
function IRContent() {
	var tmp_IR = '';

	w_IR = IRNum - parseInt(IR_numberOfElm);
	if (w_IR < 0) {
		w_IR = 0;
	} else {
		w_IR = w_IR%IR.length;
	}
	
	// Show amount of IR
	var elementsTmp_IR = parseInt(IR_numberOfElm) + 1;
	for (i_IR = 0; i_IR < elementsTmp_IR; i_IR++) {
		
		tmp_IR += IR[w_IR%IR.length];
		w_IR++;
	}

	objIR.innerHTML 	= tmp_IR;
	
	IRNum 			= w_IR;
	IR_numScrolls 	= IR.length;
	objIR.scrollTop 	= '0';
	// start scrolling
	setTimeout("scrollIR();", 2000);
}

