/* Asynchronous file upload */


function initUploadButton(){
	var onUploadButtonClick = function(e){

		uploadForm = YAHOO.util.Dom.get('uploadFileForm');
		uploadField = uploadForm.upload;

		// Check if file extension is allowed
		if (typeof fileupload_allowed != "undefined") {
			is_valid = false;
			for (i in fileupload_allowed) {
				ext = fileupload_allowed[i];
				if (ext == uploadField.value.substring(uploadField.value.length - ext.length)) {
					is_valid = true;
					break;
				}
			}
			if (!is_valid) {
				alert("Invalid file. Please check the extension and try again.");
				uploadForm.reset();
				return false;
			}
		}

		this.disabled = true;
		uploadForm.className = "ajaxload";


		//the second argument of setForm is crucial,
		//which tells Connection Manager this is a file upload form
		YAHOO.util.Connect.setForm('uploadFileForm', true);

		var uploadHandler = {
			upload: function(o) {

				this.disabled = false;
				uploadForm.className = "";

				var status = YAHOO.lang.JSON.parse(o.responseText);

				if(status.error != null) {
					alert(status.error);
					return;
				} else {
					status.filename = '<a href="' + status.link_url + '">' + status.filename + '</a>';
					ITECH.AttachedDocumentsTable.addDataRow(status, status.filename);
					uploadForm.reset();
				}

			},
			failure: function() { alert('Could not upload file, sorry!'); },
			scope: this
		};
		YAHOO.util.Connect.asyncRequest('POST', uploadForm.action, uploadHandler);
	};

	YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);
}

function initSettingsUploadButton(uploadCallback){
	var onUploadButtonClick = function(e){

		uploadForm = YAHOO.util.Dom.get('uploadFileForm');
		uploadField = uploadForm.upload;

		// Check if file extension is allowed
		if (typeof fileupload_allowed != "undefined") {
			is_valid = false;
			for (i in fileupload_allowed) {
				ext = fileupload_allowed[i];
				if (ext == uploadField.value.substring(uploadField.value.length - ext.length)) {
					is_valid = true;
					break;
				}
			}
			if (!is_valid) {
				alert("Invalid file. Please check the extension and try again.");
				uploadForm.reset();
				return false;
			}
		}

		this.disabled = true;
		uploadForm.className = "ajaxload";


		//the second argument of setForm is crucial,
		//which tells Connection Manager this is a file upload form
		YAHOO.util.Connect.setForm('uploadFileForm', true);

		var uploadHandler = {
			upload: function(o) {

				this.disabled = false;
				uploadForm.className = "";

				var status = YAHOO.lang.JSON.parse(o.responseText);

				if(status.error != null) {
					alert(status.error);
					return;
				} else {
					var fn = status.filename;
					status.filename = '<a href="' + status.link_url + '">' + status.filename + '</a>';
					var img = YAHOO.util.Dom.get('logo_img');
					img.src = status.link_url;
					uploadForm.reset();

					// ugly to use a global this way
					if (typeof ITECH.uploadCallback !== 'undefined') {
						ITECH.uploadCallback(status.id, fn);
					}
				}

			},
			failure: function() { alert('Could not upload file, sorry!'); },
			scope: this
		};
		YAHOO.util.Connect.asyncRequest('POST', uploadForm.action, uploadHandler);
	};

	YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);
}
