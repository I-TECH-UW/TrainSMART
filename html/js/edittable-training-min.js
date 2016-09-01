/*
 * edittable-training.js / Created by Fuse IQ for ITech /
 * jonah.ellison@fuseiq.com
 */

/** 
 * Purpose: Creates an YUI DataTable that allows for inline editing and
 * dynamically adding/deleting rows
 * 
 * Instructions: Must have a div container with an id of labelAdd + "Table", e.g, <div id="personTable"></div>
 * 
 * For adding/editing training sessions
 * @labelAdd: Text of add link (e.g., "Add person").  idContainer generated using @labelAdd and appending "Table"
 * @tableData: JSON DataSource instance (YAHOO.util.DataSource)
 * @columnDefs: Array of column names, keyed the same as the datasource columns
 * @jsonUrl: don't know
 * @linkInfo: don't know
 * @editLinkAdd: link to edit row entry
*/

function makeEditTableTraining(labelAdd, tableData, columnDefs, jsonUrl, linkInfo, editLinkAdd) {
	labelSafe = labelAdd.replace(' ', '');
	idContainer = labelSafe + "Table";
	if (document.getElementById(idContainer) == null) {
		alert(idContainer + ' id not found.');
	}
	ITECH.curtablecontainer = idContainer;
	
	var InlineCellEditing = new function() {
		this.config = {
			editLinks : "<a href=\"#\" onclick=\"return false;\">"
					+ tr('Remove') + "</a></div>",
			deletingText : "<div class=\"editTableDelete\">"
					+ tr('Deleting...') + "</div>"
		}
		
		//for some reason, this function never return, so set our global table objects here
		ITECH[ITECH.curtablecontainer] = this;
		
		//TA:113 do not use static variable, count will not working with two tables in one page
		//ITECH.labelTotal = document.getElementById(labelSafe + "_total");
		var labelTotal = document.getElementById(labelSafe + "_total");

		// Dynamic edit links onClick event handled here
		this.onTableClick = function(type, args, me) {
			target = YAHOO.util.Event.getTarget(args[0]);

			if (target.tagName.toLowerCase() == 'a') { // Our edit links?
				var cellTarget = target.parentNode.parentNode;
				elRow = this.myDataTable.getTrEl(cellTarget);
				oRecord = this.myDataTable.getRecord(elRow);

				switch (target.innerHTML) {

				case tr("Remove"):

					// Don't confirm empty records
					isEmpty = this.myDataTable.removeEmpty(oRecord);

					if (isEmpty
							|| confirm(tr("Are you sure you want to remove")
									+ " \""
									+ rs[this.myDataTable.getColumn(1).getKey()]
											.replace(/(<([^>]+)>)/ig, "")
									+ "?\"")) {
						this.myDataTable.deleteAjax(oRecord);
					}
					break;

				default:
					if (YAHOO.env.ua.ie > 0) {
						window.location = target.href; // for IE7
					}
					break;

				}
			}
		}

		// Add a row to the table
		this.addRow = function(jsonData, successCallback) {
			jsonData.edit = this.addEditLink(jsonData);

			ajaxCallback = {
				success : function(o) {
					var status = YAHOO.lang.JSON.parse(o.responseText); // ,
																		// true);

				if (status.error != null) {
					alert(status.error);
					return false;
				} else {
					jsonData.id = status.insert;

					jsonData.edit = this.addEditLink(jsonData);
					jsonData = this.addFieldLink(jsonData);

					this.myDataTable.addRow(jsonData);
					this.myDataTable.updateCount();

					this.flashRow(0);

					successCallback();

					return true;

				}
			},
			failure : function() {
				alert('Could not connect to server.');
				return false;
			},
			scope : this
			};

			var queryString = "a=add&row_id=" + jsonData.id;
			if (jsonData.duration_days != null) {
				queryString += "&days=" + jsonData.duration_days;
			}

			cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl,
					ajaxCallback, queryString);
		}

		// animate row        
		this.flashRow = function(pttId) {

			// Grab the row el and the 2 colors
			if (!pttId) {
				var elRow = this.myDataTable.getLastTrEl();
			} else {
				// find row with ptt id
				numRows = this.myDataTable.getRecordSet().getLength()
				for (rIndex = 0; rIndex < numRows; rIndex++) {
					recordId = this.myDataTable.getRecordSet()
							.getRecord(rIndex).getData("id");
					if (recordId == pttId) {
						var elRow = this.myDataTable.getTrEl(rIndex);
					}
				}
			}

			var origColor = YAHOO.util.Dom.getStyle(elRow, "backgroundColor");
			var pulseColor = '#51965b';

			// Create a temp anim instance that nulls out when anim is complete
			var rowColorAnim = new YAHOO.util.ColorAnim(elRow.cells, {
				backgroundColor : {
					to : origColor,
					from : pulseColor
				},
				duration : 2
			});

			var onComplete = function() {
				rowColorAnim = null;
				YAHOO.util.Dom.setStyle(elRow.cells, "backgroundColor", "");
			}
			rowColorAnim.onComplete.subscribe(onComplete);
			rowColorAnim.animate();
		}

		//
		// Setup our new DataTable object
		//

		// Add an "Edit" column
		if (editLinkAdd.disabled == null) {
			columnDefs.push( {
				key : "edit",
				label : ""
			});
		}

		this.addFieldLink = function(jsonData) {

			if (linkInfo.linkFields != null) {
				for (e in linkInfo.linkFields) {
					var field = linkInfo.linkFields[e];

					if (jsonData[field] != "null") {
						var url = linkInfo.linkUrl.replace("%"
								+ linkInfo.linkId + "%",
								jsonData[linkInfo.linkId]);
						jsonData[field] = "<a href=\"" + url + "\">"
								+ jsonData[field] + "</a>";
					}
				}
			}

			return jsonData;
		}

		this.addEditLink = function(jsonData) {
			if (editLinkAdd && editLinkAdd.length) {
				var html = "";
				for (iLink in editLinkAdd) {
					url = editLinkAdd[iLink].linkUrl.replace("%"
							+ editLinkAdd[iLink].linkId + "%",
							jsonData[editLinkAdd[iLink].linkId]);

					if (typeof jsonData.score_pre != "undefined") {
						url = url.replace("%score_pre%",
								(jsonData.score_pre == null) ? ""
										: jsonData.score_pre);
						url = url.replace("%score_post%",
								(jsonData.score_post == null) ? ""
										: jsonData.score_post);
					}

					html += "<a href=\"" + url + "\">"
							+ editLinkAdd[iLink].linkName + "</a>&nbsp;";
				}
				return html + this.config.editLinks;
			} else {
				return this.config.editLinks;
			}
		}

		// Add edit links to datasource
		for (i in tableData) {
			if (editLinkAdd.disabled == null) {
				tableData[i].edit = this.addEditLink(tableData[i]);
			}

			if (tableData[i]) {
				tableData[i] = this.addFieldLink(tableData[i]);
			}

		}

		// Instantiate our DataTable object
		this.myDataSource = new YAHOO.util.DataSource(tableData);
		this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
		this.myDataSource.responseSchema = {
			fields : []
		}; // Generate schema
		for (i in columnDefs) {
			this.myDataSource.responseSchema.fields[i] = columnDefs[i]['key'];
		}

		this.myDataTable = new YAHOO.widget.DataTable(ITECH.curtablecontainer, columnDefs,
				this.myDataSource);
		this.myDataTable.config = this.config;

		this.myDataTable.updateCount = function() {
			//TA:113 do not use static variable, count will not working with two tables in one page
//			if ((ITECH.labelTotal != undefined) && (ITECH.labelTotal != null)){
//				ITECH.labelTotal.innerHTML = this.getRecordSet().getLength();
//			}
			if ((labelTotal != undefined) && (labelTotal != null)){
				labelTotal.innerHTML = this.getRecordSet().getLength();
			}
		}
		this.myDataTable.updateCount();

		// Set up editing flow (customize our cell editor)
		this.myDataTable.doBeforeShowCellEditor = function(oCellEditor) {

			dt = this;
			oldValue = oCellEditor.value;
			oCellEditor.isSaving = false; // i.e., AJAX

			// Set up a listener for our cell editor
			YAHOO.util.Event.addListener(oCellEditor.container, "keyup",
					function(v) {

						// Save on "tab" or "enter", then move to next column
					if (v.keyCode === 9 || v.keyCode === 13) {
						shouldMove = true;

						if (oldValue != oCellEditor.value) {
							shouldMove = dt.onEventSaveCellEditor(oCellEditor);
						} else if (!oCellEditor.value) {
							shouldMove = dt.removeEmpty(oCellEditor.record);
						}

						if (shouldMove) { // move to next column
						var increment = (v.keyCode === 9 && v.shiftKey) ? -1
								: 1; // Shift+Tab?
						nextCol = oCellEditor.column.getKeyIndex() + increment;
						el = dt.getTdEl( {
							record : oCellEditor.record,
							column : dt.getColumn(nextCol)
						});
						dt.showCellEditor(el);
					}
				}
			});

		}

		//
		// Ajax
		//

		// Perform AJAX saving here (we are overriding YUI's DataTable method)
		this.myDataTable.onEventSaveCellEditor = function(oArgs) {
			oCellEditor = this.getCellEditor();

			var newData = oCellEditor.value;
			var oldData = oCellEditor.record.getData(oCellEditor.column.key);
			if (newData == oldData || (!newData && !oldData)) { // no need to save
				dt.cancelCellEditor();
				return true;
			}

			oCellEditor.isSaving = true;

			// Inform user we are attempting to save
			var elSaving = document.getElementById("editTableSaving");
			if (elSaving == null) { // dynamically create
				elSaving = oCellEditor.container.appendChild(document
						.createElement('div'));
				elSaving.id = "editTableSaving";
			}
			elSaving.innerHTML = tr("Saving...");

			var nodes = oCellEditor.container.getElementsByTagName('input'); // disable
																				// all
																				// inputs
			for (i in nodes) {
				nodes[i].disabled = true;
			}
			var nodes = oCellEditor.container.getElementsByTagName('button'); // disable
																				// OK
																				// button
			nodes[0].disabled = true;

			//
			// Send AJAX save request
			//

			var ajaxCallback = {
				success : function(o) {

					// SUCCESS
				var status = YAHOO.lang.JSON.parse(o.responseText); // , true);

				if (oCellEditor.column.editorOptions && oCellEditor.column.editorOptions.dropdownOptions) { // is dropdown
					oCellEditor.value = oCellEditor.column.editorOptions.dropdownOptions[oCellEditor.value].text;
				}

				// error handling
				if (status.error != null) {
					status.error = status.error.replace('%s', newData);

					// user trying to add a value that is flagged as deleted in
					// the database
					if (status.insert != null && status.insert == -2) {
						if (confirm(status.error)) { // undelete
							elSaving.innerHTML = '<span class="errorText">' + tr('Undeleting...') + '</span>';
							queryString = "id="
									+ oCellEditor.record.getData("id") + "&"
									+ oCellEditor.column.key + "="
									+ encodeURIComponent(newData)
									+ "&undelete=1";
							cObj = YAHOO.util.Connect.asyncRequest('POST',
									document.location + "/outputType/json",
									ajaxCallback, queryString);
						} else {
							this.cancelCellEditor();
						}
					} else {
						elSaving.innerHTML = '<span class="errorText">' + status.error + '</span>';
					}

					nodes = oCellEditor.container.getElementsByTagName('*');
					for (i in nodes) {
						nodes[i].disabled = false;
						if (nodes[i].type == 'text') {
							nodes[i].focus();
						}
					}
					return;
				} else if (status.insert != null && status.insert > 0) {
					oCellEditor.record.setData("id", status.insert);
				}

				if (status.undelete != null) { // use original value from database
					oCellEditor.value = status.undelete;
				}

				//
				// Perform update animation
				//
				var elCell = oCellEditor.cell;

				// Grab the row el and the 2 colors
				var elRow = this.getTrEl(elCell);
				var origColor = YAHOO.util.Dom.getStyle(elRow,
						"backgroundColor");
				var pulseColor = '#51965b';

				// Create a temp anim instance that nulls out when anim is
				// complete
				var rowColorAnim = new YAHOO.util.ColorAnim(elCell, {
					backgroundColor : {
						to : origColor,
						from : pulseColor
					},
					duration : 2
				});

				var onComplete = function() {
					rowColorAnim = null;
					YAHOO.util.Dom.setStyle(elRow.cells, "backgroundColor", "");
				}
				rowColorAnim.onComplete.subscribe(onComplete);
				rowColorAnim.animate();

				oCellEditor.isSaving = false;
				// Update table display and close cell editor
				this.saveCellEditor();

				return true;

			},
			failure : function() {
				// FAILURE
				// display error message
				elSaving.innerHTML = "Couldn't save, sorry!";
				oCellEditor.isSaving = false;
				return false;

			},
			scope : this
			}

			queryString = "row_id=" + oCellEditor.record.getData("id") + "&"
					+ oCellEditor.column.key + "=" + newData;

			cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl,
					ajaxCallback, queryString);

		}

		// Delete and Ajax update
		this.myDataTable.deleteAjax = function(oRecord) {
			var ajaxDelCallback = {
				success : function(o) {
					var status = YAHOO.lang.JSON.parse(o.responseText);
					if (status.error != null) {
						alert("Could not delete, sorry.  The server said:\n\n"
								+ status.error);
					} else {
						this.deleteRow(oRecord);
						this.updateCount();
					}
				},
				failure : function() {
					alert('Could not delete this record, sorry!');
				},
				scope : this
			};

			// Inform user we are attempting to delete
			oEditCell = this.getTdEl( {
				record : oRecord,
				column : this.getColumn("edit")
			});
			oEditCell.innerHTML = this.config.deletingText;

			var queryString = "a=del&row_id=" + oRecord.getData("id");
			cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl,
					ajaxDelCallback, queryString);

		}

		//
		// DataTable helpers
		//

		// Remove record if empty
		this.myDataTable.removeEmpty = function(oRecord) {
			var isEmpty = true;
			rs = oRecord.getData();
			for (key in rs) {
				if (key != "edit" && rs[key] != "") {
					isEmpty = false;
				}
			}

			if (isEmpty) {
				this.deleteRow(oRecord);
				this.cancelCellEditor();
			}
			return isEmpty;
		}

		this.myDataTable.onEditorCancel = function(oArgs) {
			this.removeEmpty(oArgs.editor.record);
		}

		this.myDataTable.onEditorBlur = function(oArgs) {
			this.removeEmpty(oArgs.editor.record);
			return !oArgs.editor.isSaving; // Don't blur if AJAX is trying to
											// save
		}

		this.highlightEditableCell = function(oArgs) {
			var elCell = oArgs.target;
			if (YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
				this.highlightCell(elCell);
			}
		};

		//
		// DataTable subscrive events
		//
		this.myDataTable.subscribe("cellMouseoverEvent",
				this.highlightEditableCell);
		this.myDataTable.subscribe("cellMouseoutEvent",
				this.myDataTable.onEventUnhighlightCell);
		this.myDataTable.subscribe("cellClickEvent",
				this.myDataTable.onEventShowCellEditor);
		this.myDataTable.subscribe("editorBlurEvent",
				this.myDataTable.onEditorBlur);
		this.myDataTable.subscribe("editorCancelEvent",
				this.myDataTable.onEditorCancel);

		// Hook into custom event to customize save-flow of "radio" editor
		this.myDataTable.subscribe("editorUpdateEvent", function(oArgs) {
			if (oArgs.editor.value == "") {
				this.removeEmpty(oArgs.editor.record);
			}
			if (oArgs.editor.column.key === "active") {
				this.saveCellEditor();
			}
		});
		this.myDataTable.subscribe("editorBlurEvent", function(oArgs) {
			this.cancelCellEditor();
		});

		//
		// idContainer custom events for our dynamic elements (to keep datatable
		// object in scope)
		//
		elTableContainer = YAHOO.util.Dom.get(ITECH.curtablecontainer);

		// Monitor table clicks (specifically for the edit links)
		elTableContainer.tableClick = new YAHOO.util.CustomEvent("tableClick",
				this);
		elTableContainer.tableClick.subscribe(this.onTableClick, this);
		YAHOO.util.Event.on(elTableContainer, 'click', function(ev) {
			this.tableClick.fire(ev);
			return false;
		});

	};

	return InlineCellEditing;
}

var cacheScores = {};

/**
 * Update training score via ajax
 */
function updateScore(label, pttId, jsonUrl, promptDefault) {
	promptScore = "";
	if (cacheScores[label] != null && cacheScores[label][pttId] != null) {
		promptScore = cacheScores[label][pttId];
	} else {
		promptScore = promptDefault;
	}

	inputScore = prompt("Please enter a " + label + " score between 1-100:\nOptionally enter Number Questions Correct/Total Questions, for example 80/105, we will calculate the percentage.",
			promptScore);
	if (inputScore) {
		// fraction?
		if(inputScore.indexOf('/') > 0){
			parts = inputScore.split('/');
			inputScore = score = parseInt((parseInt( $.trim(parts[0]) ) / parseInt( $.trim(parts[1]) ) * 100 ));
		}
		// int
		score = parseInt(inputScore);
		if (inputScore != score || score < 1 || score > 100) {
			alert('That is not a valid score.');
			updateScore(label, pttId,  jsonUrl, promptDefault);
			return;
		}

		// perform Ajax to update score
		ajaxCallback = {
			success : function(o) {
				if (typeof ITECH.personsTable != "undefined") {
					ITECH.personsTable.flashRow(pttId);
				}
			},
			failure : function(e) {
				// display error message
			alert("Couldn't save, sorry!");
			return false;
		},
		scope : this
		}

		if (cacheScores[label] == null) {
			cacheScores[label] = {}
		}
		cacheScores[label][pttId] = score;

		queryPost = "ptt_id=" + pttId + "&label=" + label + "&value=" + score;

		cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl, ajaxCallback,
				queryPost);
	}
}