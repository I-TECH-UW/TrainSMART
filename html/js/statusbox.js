function displayStatus(msg) {
	alert(msg);
}

function displayErrorMessage(fieldId, msg) {
	var lblElement =  YAHOO.util.Dom.get(fieldId + '_lbl');
	lblElement.innerHTML = lblElement.innerHTML + '<span class="errorText">' + msg + '</span>';
}