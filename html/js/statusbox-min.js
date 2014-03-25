function displayStatus(msg) {
	if ( msg && msg != '' ) {
		var statusDiv = YAHOO.util.Dom.get('statusBox');

		if(statusDiv == null) {		  
			var contentDiv =  YAHOO.util.Dom.get('content');
			statusDiv = document.createElement('div');
			statusDiv.id = "statusBox";

			if(document.location.href.indexOf("/admin/") > 0) {
				YAHOO.util.Dom.addClass(statusDiv, "admin");
			}

			YAHOO.util.Dom.insertBefore(statusDiv, YAHOO.util.Dom.getFirstChild(contentDiv));
		}		
		
		YAHOO.util.Dom.removeClass(statusDiv, "statusError");    		
		statusDiv.innerHTML = msg;
		scroll(0,0);
	}
}

function setStatusBoxError() {  
	var statusDiv = YAHOO.util.Dom.get('statusBox');
	if(statusDiv != null) {
		YAHOO.util.Dom.addClass(statusDiv, "statusError");
	}
}

function displayErrorMessage(fieldId, msg) {
	var lblElement =  YAHOO.util.Dom.get(fieldId + '_lbl');
	if ( lblElement == null ) {
		var fieldElement = YAHOO.util.Dom.get(fieldId);
		if ( fieldElement ) {
			//try the previous 3 elements
			node = YAHOO.util.Dom.getAncestorByClassName(fieldElement,'fieldInput');
			for(var i = 0; i < 3; i++) {
				if ( lblElement == null) {
					if (YAHOO.util.Dom.hasClass(node,'fieldLabel')) {
						lblElement = node;
					} else {
						node = YAHOO.util.Dom.getPreviousSibling(node);
					}
				}
			}
		}
	}
	
	if ( lblElement == null )
		displayStatus(msg);
	else
		lblElement.innerHTML = lblElement.innerHTML + '<span class="errorText">' + msg + '</span>';
}

// reposition statusbox in admin pages
YAHOO.util.Event.onDOMReady(function () {
	
	var statusDiv = YAHOO.util.Dom.get('statusBox');
	if (statusDiv != null)
	{
		var contentAdmin = YAHOO.util.Dom.get('contentAdmin');
		if (contentAdmin != null) {
			YAHOO.util.Dom.insertBefore(statusDiv, YAHOO.util.Dom.getFirstChild(contentAdmin));
			YAHOO.util.Dom.removeClass(statusDiv, 'admin');
			YAHOO.util.Dom.setStyle(statusDiv, 'margin-left', '0');
			
		}
	}
});