YAHOO.util.Event.onDOMReady(function () {

 YAHOO.widget.DataTable.MSG_EMPTY= tr('No records found.');
 YAHOO.widget.DataTable.MSG_LOADING = tr('Loading...');

});

function makeDataTable(tableDivId, captionText, dataId, columnDefs) {

YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.example.Basic = new function() {

        this.myDataSource = new YAHOO.util.DataSource(dataId);
        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
        this.myDataSource.responseSchema = {
            fields: []
        };

		for(var i = 0; i < columnDefs.length; i++) {
			this.myDataSource.responseSchema.fields[i] = columnDefs[i]['key'];
		}
       this.myDataTable = new YAHOO.widget.DataTable(tableDivId,
                columnDefs, this.myDataSource, {caption:captionText});
    };
});

}


function makeSelectorDataTable(tableDivId, captionText, dataId, columnDefs, selectFunc) {

YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.example.Basic = new function() {

        this.myDataSource = new YAHOO.util.DataSource(dataId);
        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
        this.myDataSource.responseSchema = {
            fields: []
        };

		for(var i = 0; i < columnDefs.length; i++) {
			this.myDataSource.responseSchema.fields[i] = columnDefs[i]['key'];
		}

       this.myDataTable = new YAHOO.widget.DataTable(tableDivId,
                columnDefs, this.myDataSource, {caption:captionText, selectionMode:"single"});

				// Subscribe to events for row selection
        this.myDataTable.subscribe("rowMouseoverEvent", this.myDataTable.onEventHighlightRow);
        this.myDataTable.subscribe("rowMouseoutEvent", this.myDataTable.onEventUnhighlightRow);
        this.myDataTable.subscribe("rowClickEvent", selectFunc );

        // Programmatically select the first row
//        this.myDataTable.selectRow(this.myDataTable.getTrEl(0));
        this.myDataTable.render();


	};
});

}


function makeJSONDataTable(tableDivId, captionText, action, columnDefs) {
	YAHOO.util.Event.addListener(window, "load", function() {
	    YAHOO.example.XHR_JSON = new function() {
	        this.myDataSource = new YAHOO.util.DataSource(action);
	        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	        this.myDataSource.connXhrMode = "allowAll";
	        this.myDataSource.responseSchema = {
	            resultsList: null,
	            fields: []
	        };

			for(var i = 0; i < columnDefs.length; i++) {
				this.myDataSource.responseSchema.fields[i] = columnDefs[i]['key'];
			}


	        ITECH[tableDivId] = new YAHOO.widget.DataTable(tableDivId, columnDefs,
	                this.myDataSource, {caption:captionText, initialRequest:''});

	     };
	});
}


/**
 * Build table dynimically after page load
 */
function makeDynamicDataTable(tableDivId, captionText, dataId, columnDefs) {

    YAHOO.example.Basic = new function() {

        this.myDataSource = new YAHOO.util.DataSource(dataId);
        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
        this.myDataSource.responseSchema = {
            fields: []
        };

		for(var i = 0; i < columnDefs.length; i++) {
			this.myDataSource.responseSchema.fields[i] = columnDefs[i]['key'];
		}
       this.myDataTable = new YAHOO.widget.DataTable(tableDivId,
                columnDefs, this.myDataSource, {caption:captionText});
    };
}