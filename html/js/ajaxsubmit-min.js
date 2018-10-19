function addAjaxSubmit(buttonId, formId, postUrl) {
    (function () {
    	alert(postUrl);//TA:#525
        var _button = new YAHOO.widget.Button(buttonId);
        var handleSuccess = function (o) {
            try {
                var response = o.responseText;
                var key;
                var responseObj = YAHOO.lang.JSON.parse(response);
                displayStatus(responseObj.status);

                if (responseObj.messages) {
                    for (key in responseObj.messages) {
                        displayErrorMessage(key, responseObj.messages[key]);
                    }
                }

                if (responseObj.obj_id) {
                    var new_obj_field = YAHOO.util.Dom.get('obj_id');
                    if (new_obj_field) {
                        new_obj_field.value = responseObj.obj_id;
                    }
                }

                if (responseObj.redirect) {
                    window.location = responseObj.redirect;
                }
            }
            catch (x) {
                alert("ITech script error: " + x);
                alert(response);
                return;
            }

            document.body.style.cursor = "auto";

        };
        var handleFailure = function (o) {
            alert("Submission failed with code: " + o.status + ". The error text is: " + o.statusText);
            document.body.style.cursor = "auto";
        };

        var callback = {
            success: handleSuccess,
            failure: handleFailure
        };

        _button.on('click', function (ev) {
            window.setTimeout(function () {

                    //document.body.style.cursor = "wait";
                    //debugger;
                    //clear error text
                    var els = YAHOO.util.Dom.getElementsByClassName('errorText');
                    if (els.length)
                        YAHOO.util.Dom.setStyle(els, 'display', 'none');
                    var formObject = document.getElementById(formId);
                    YAHOO.util.Connect.setForm(formObject);
                    var request = YAHOO.util.Connect.asyncRequest('POST', postUrl, callback);
                }
                , 200);
        });

    })();
}

