function displayStatus(msg, isError) {
    if (msg && msg != '') {
        isError = (typeof isError === 'undefined') ? false : isError;

        var $statusDiv = $('#statusBox');

        if (!$statusDiv.length) {
            $statusDiv = $(document.createElement('div')).prop('id', 'statusBox');

            if (document.location.href.indexOf("/admin/") >= 0) {
                $('#contentAdmin').prepend($statusDiv);
            }
            else {
                $('#content').prepend($statusDiv);
            }
        }

        var $newMessage = $(document.createElement('div')).text(msg);
        if (isError) {
            $newMessage.prop("class", "errorText");
        }
        $statusDiv.append($newMessage);
        window.scroll(0,0);
    }
}

function displayErrorMessage(fieldID, msg) {
    var $labelElement = $("label[for='" + fieldID + "']");

    if (!$labelElement.length) {
        // see if we can find it
        $labelElement = $('#' + fieldID + '_lbl');
        if (!$labelElement.length) {
            // walk the DOM
            $labelElement = $('#' + fieldID).parent().prev("div.fieldLabel, div.fieldLabelThin");
        }
    }

    // highlight the incorrect field
    if ($labelElement.length) {
        $labelElement.addClass("errorText");
    }

    // add it to the status box at the top of the page whether we found the element or not
    displayStatus(msg, true);
}

