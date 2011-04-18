
function IR_submit()
{
	if(document.IR_form.IR_path.value=="")
	{
		alert("Please enter the image path.")
		document.IR_form.IR_path.focus();
		return false;
	}
	else if(document.IR_form.IR_link.value=="")
	{
		alert("Please enter the target link.")
		document.IR_form.IR_link.focus();
		return false;
	}
	else if(document.IR_form.IR_target.value=="")
	{
		alert("Please enter the target status.")
		document.IR_form.IR_target.focus();
		return false;
	}
	else if(document.IR_form.IR_title.value=="")
	{
		alert("Please enter the image title.")
		document.IR_form.IR_title.focus();
		return false;
	}
	else if(document.IR_form.IR_desc.value=="")
	{
		alert("Please enter the image description.")
		document.IR_form.IR_desc.focus();
		return false;
	}
	else if(document.IR_form.IR_type.value=="")
	{
		alert("Please enter the gallery type.")
		document.IR_form.IR_type.focus();
		return false;
	}
	else if(document.IR_form.IR_status.value=="")
	{
		alert("Please select the display status.")
		document.IR_form.IR_status.focus();
		return false;
	}
	else if(document.IR_form.IR_order.value=="")
	{
		alert("Please enter the display order, only number.")
		document.IR_form.IR_order.focus();
		return false;
	}
	else if(isNaN(document.IR_form.IR_order.value))
	{
		alert("Please enter the display order, only number.")
		document.IR_form.IR_order.focus();
		return false;
	}
}

function IR_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_IR_display.action="options-general.php?page=information-reel/information-reel.php&AC=DEL&DID="+id;
		document.frm_IR_display.submit();
	}
}	

function IR_redirect()
{
	window.location = "options-general.php?page=information-reel/information-reel.php";
}
