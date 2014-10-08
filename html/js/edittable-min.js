/** edittable.js / Created by Fuse IQ for ITech / jonah.ellison@fuseiq.com
 *
 * Purpose: Creates an YUI DataTable that allows for inline editing and dynamically adding/deleting rows
 *
 * Instructions: Must have a div container with an id of labelAdd + "Table", e.g, <div id="personTable"></div>
 *
 * @tableData: JSON DataSource instance (YAHOO.util.DataSource)
 * @columnDefs: Array of object literal Column definitions
 * @labelAdd: Text of add link (e.g., "Add person").  idContainer generated using @labelAdd and appending "Table"
 */

function makeEditTable(labelAdd, tableData, columnDefs, noDelete, noEdit) {
    labelSafe = labelAdd.replace(' ','');
    idContainer = labelSafe + "Table";
    
    
    if(document.getElementById(idContainer) == null) {
      alert(idContainer + ' id not found.');
    }
	ITECH.curtablecontainer = idContainer;

    InlineCellEditing = new function() {

        this.config = {
          editOnly:   "<div class=\"editTableLinks\"><a href=\"#\" onclick=\"return false;\">" + tr('Edit') + "</a></div>",
          deleteOnly: "<div class=\"editTableLinks\"><a href=\"#\" onclick=\"return false;\">" + tr('Delete') + "</a></div>",
          editLinks:  "<div class=\"editTableLinks\"><a href=\"#\" onclick=\"return false;\">" + tr('Edit') + "</a> &nbsp;<a href=\"#\" onclick=\"return false;\">" + tr('Delete') + "</a></div>",
          deletingText: "<div class=\"editTableDelete\">" + tr('Deleting...') + "</div>",
          deleteConfirm: tr('Are you sure you want to delete') + " \"%s?\"",
          autoTabOnSubmit: false
        }
        
		   //for some reason, this function never return, so set our global table objects here
       //ITECH.curtablecontainer = this;
        
       

        // Dynamic edit links onClick event handled here
        this.onTableClick = function(type, args, me) {
          target = YAHOO.util.Event.getTarget(args[0]);

          if(target.tagName.toLowerCase() == 'a') { // Our edit links?
              var cellTarget = target.parentNode.parentNode;
              elRow = this.myDataTable.getTrEl(cellTarget);
              oRecord = this.myDataTable.getRecord(elRow);
              

              switch(target.innerHTML) {

                case tr("Delete"):

                  // Don't confirm empty records
                  isEmpty = this.myDataTable.removeEmpty(oRecord);
                
                  //if(isEmpty || confirm("Are you sure you want to delete \"" + rs[key] + "?\"")) {
                  
                  rs[key] = rs[key].replace(/<(?:.|\s)*?>/g, ""); // strip HTML tags

                  if(isEmpty || confirm(this.config.deleteConfirm.replace("%s", rs[key]))) {
                     this.myDataTable.deleteAjax(oRecord);
                     $("#" + labelSafe + "_total").text(this.myDataTable.getRecordSet().getLength()-1); //TA:17: 09/05/2014
                     $("#" + labelSafe + "_delete_data").val($("#" + labelSafe + "_delete_data").val()?$("#" + labelSafe + "_delete_data").val()+','+oRecord.getData("id"):oRecord.getData("id")); //TA:17: 09/05/2014
                  }
                  break;

                case tr("Edit"):

                  el = this.myDataTable.getTdEl({record:oRecord, column:this.myDataTable.getColumn(0)});
                  this.myDataTable.showCellEditor(el);
                  break;

                default:
                  break;

              }
          }
          
          if(target.tagName.toLowerCase() == 'input') { // input/checkbox
            // Weird MSIE issue -- if checkbox is clicked, YUI will fire the click event twice, unchecking a checked box.  So we just change the check status again....
            if(YAHOO.env.ua.ie > 0) {
              setTimeout(function() { target.checked = !(target.checked); }, 50);
            }
          }

        }
        
        
        // Add a row to the table (Dynamic "Add..." link fires here)
        this.addRow = function() {
    
          this.myDataTable.addRow({edit:this.config.editLinks});

          // Show cell editor
          numRows = this.myDataTable.getRecordSet().getLength();
          el = this.myDataTable.getTdEl({record:this.myDataTable.getRecord(--numRows), column:this.myDataTable.getColumn(0)});
          
          oCellEditor = this.myDataTable.getCellEditor();
          oCellEditor.isSaving = true; // pretend to be saving so blur event doesn't hide cell editor

          this.myDataTable.showCellEditor(el);
          
          return false;
        }
        
        // Add row w/data
        this.addDataRow = function(jsonData, row_name) {
          jsonData.edit = (noEdit) ? this.config.deleteOnly : this.config.editLinks;
          jsonData.row_name = row_name; // Name to display when "delete" is clicked
          this.myDataTable.addRow(jsonData);
          $("#" + labelSafe + "_total").text(this.myDataTable.getRecordSet().getLength()); //TA:17: 09/05/2014
          
          //TA:17: 09/17/2014
          var arr = new Array();
          for(var i=0; i<this.myDataTable.getRecordSet().getLength(); i++){
          	var row = this.myDataTable.getRecord(i);
          	if(row.getData('row_name')){
          		var data = JSON.parse(JSON.stringify(row));
          		arr.push(data['_oData']);
          	}
         }
          $('#' + labelSafe + '_new_data').val('{"data":' +  JSON.stringify(arr) + '}');
          ////
          
        }
        
    
        
        //
        // Setup our new DataTable object
        //

        // Add an "Edit" column
        columnDefs.push({key:"edit",label:""});


        // Add edit links to datasource
        for(i in tableData) {
          var found = false;
          for(d in noDelete) {
            if(noDelete[d] == tableData[i].id) {
              found = true;
              tableData[i].edit = (noEdit) ? '' : this.config.editOnly;
              break;
            }
          }
          if(!found) {
            if(noEdit) {
              tableData[i].edit = this.config.deleteOnly;
            } else {
              tableData[i].edit = this.config.editLinks;  
            }
          }
        }

        // Instantiate our DataTable object
        this.myDataSource = new YAHOO.util.DataSource(tableData);
        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
        this.myDataSource.responseSchema = { fields: [] }; // Generate schema
    		for(i in columnDefs) {
    			this.myDataSource.responseSchema.fields[i] = columnDefs[i]['key'];
    		}

        this.myDataTable = new YAHOO.widget.DataTable(ITECH.curtablecontainer, columnDefs, this.myDataSource);
        this.myDataTable.config = this.config;

        // Set up editing flow (customize our cell editor)
        this.myDataTable.doBeforeShowCellEditor = function(oCellEditor) {

            dt = this;
            oldValue = oCellEditor.value;
            oCellEditor.isSaving = false; // i.e., AJAX

            // Set up a listener for our cell editor
            YAHOO.util.Event.addListener(oCellEditor.container, "keyup", function(v){

                // Save on "tab" or "enter", then move to next column
                if(v.keyCode === 9 || v.keyCode === 13) {
                  shouldMove = true;

                  if(oldValue != oCellEditor.value) {
                    shouldMove = dt.onEventSaveCellEditor(oCellEditor);
                  } else if(!oCellEditor.value) {
                    shouldMove = dt.removeEmpty(oCellEditor.record);
                  }

                  if(shouldMove){ // move to next column
                    var increment = (v.keyCode === 9 && v.shiftKey) ? -1 : 1; // Shift+Tab?
                    nextCol = oCellEditor.column.getKeyIndex() + increment;
                    el = dt.getTdEl({record:oCellEditor.record, column:dt.getColumn(nextCol)});
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
          if(newData == oldData || (!newData && !oldData)) { // no need to save
            dt.cancelCellEditor();
            return true;
          }

          oCellEditor.isSaving = true;

          // Inform user we are attempting to save
          var elSaving = document.getElementById("editTableSaving");
          if(elSaving == null) { // dynamically create
            elSaving = oCellEditor.container.appendChild(document.createElement('div'));
            elSaving.id = "editTableSaving";
          }
          elSaving.innerHTML = tr("Saving...");

          var nodes = oCellEditor.container.getElementsByTagName('input'); // disable all inputs
          for(i in nodes) {
            nodes[i].disabled = true;
          }
          var nodes = oCellEditor.container.getElementsByTagName('button'); // disable OK button
          nodes[0].disabled = true;


          //
          // Send AJAX save request
          //

          var ajaxCallback = {
            success: function(o) {

              // SUCCESS
              var status = YAHOO.lang.JSON.parse(o.responseText); //, true);

              // error handling
              if(status.error != null) {
                status.error = status.error.replace('%s', newData);

                // user trying to add a value that is flagged as deleted in the database
                if(status.insert != null && status.insert == -2) {
                  if(confirm(status.error)) { // undelete
                    elSaving.innerHTML = '<span class="errorText">' + tr('Undeleting...') + '</span>';
                    queryString = "id=" + oCellEditor.record.getData("id") + "&" + oCellEditor.column.key + "=" + encodeURIComponent(newData) + "&undelete=1";
                    cObj = YAHOO.util.Connect.asyncRequest('POST', document.location + "/outputType/json", ajaxCallback, queryString);
                  } else {
                    this.cancelCellEditor();
                  }
                } else {
                  elSaving.innerHTML = '<span class="errorText">' + status.error + '</span>';
                }

                nodes = oCellEditor.container.getElementsByTagName('*');
                for(i in nodes) {
                  nodes[i].disabled = false;
                  if(nodes[i].type == 'text') {
                    nodes[i].focus();
                  }
                }
                return;
              } else if(status.insert != null && status.insert > 0) {
                oCellEditor.record.setData("id", status.insert);
              }

              if(status.undelete != null) { // use original value from database
                oCellEditor.value = status.undelete;
              }


              //
              // Perform update animation
              //
              var elCell = oCellEditor.cell;

              // Grab the row el and the 2 colors
              var elRow = this.getTrEl(elCell);
              var origColor = YAHOO.util.Dom.getStyle(elRow, "backgroundColor");
              var pulseColor = '#51965b';


              // Create a temp anim instance that nulls out when anim is complete
              var rowColorAnim = new YAHOO.util.ColorAnim(elCell, {
                      backgroundColor:{to:origColor, from:pulseColor}, duration:2});

              var onComplete = function() {
                  rowColorAnim = null;
                  YAHOO.util.Dom.setStyle(elRow.cells, "backgroundColor", "");
              }
              rowColorAnim.onComplete.subscribe(onComplete);
              rowColorAnim.animate();


              oCellEditor.isSaving = false;
              // Update table display and close cell editor
              this.saveCellEditor();

              if(this.config.autoNextOnSubmit) { // move to next cell
                nextCol = oCellEditor.column.getKeyIndex() + 1;
                el = dt.getTdEl({record:oCellEditor.record, column:dt.getColumn(nextCol)});
                dt.showCellEditor(el);
              }



              return true;


            },
            failure: function() {
              // FAILURE
              // display error message
              elSaving.innerHTML = "Couldn't save, sorry!";
              oCellEditor.isSaving = false;
              return false;

            },
            scope: this
          }

          queryString = "id=" + oCellEditor.record.getData("id") + "&" + oCellEditor.column.key + "=" + encodeURIComponent(newData);

          cObj = YAHOO.util.Connect.asyncRequest('POST', document.location + "/outputType/json", ajaxCallback, queryString);


        }

        // Delete and Ajax update
        this.myDataTable.deleteAjax = function(oRecord) {
          var ajaxDelCallback = {
            success: function(o) {
                var status = YAHOO.lang.JSON.parse(o.responseText);
                if(status.error != null) {
                  alert("Could not delete, sorry.  The server said:\n\n" + status.error);
                } else {
                  this.deleteRow(oRecord);
                }
              },
            failure: function() { alert('Could not delete this record, sorry!'); },//TA:19:TODO throws this alert
            scope: this
          };

          // Inform user we are attempting to delete
          oEditCell = this.getTdEl({record:oRecord, column:this.getColumn("edit")});
          oEditCell.innerHTML = this.config.deletingText;

          queryString = "id=" + oRecord.getData("id") + "&delete=1&edittabledelete=1";
          cObj = YAHOO.util.Connect.asyncRequest('POST', document.location + "/outputType/json", ajaxDelCallback, queryString);
          //TA:19:TODO https://pepfarskillsmart.trainingdata.org/employee/outputType/json, ajaxDelCallback, id=27&delete=1&edittabledelete=1 
        }

        //
        // DataTable helpers
        //

        // Remove record if empty
        this.myDataTable.removeEmpty = function(oRecord) {
          var isEmpty = true;
          rs = oRecord.getData();
          for(key in rs) {
            if(key != "edit" && rs[key] != "") {
              isEmpty = false;
            }
          }

          if(isEmpty) {
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
          return !oArgs.editor.isSaving; // Don't blur if AJAX is trying to save
        }

        this.highlightEditableCell = function(oArgs) {
            var elCell = oArgs.target;
            if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
                this.highlightCell(elCell);
            }
        };

        //
        // DataTable subscrive events
        //
        this.myDataTable.subscribe("cellMouseoverEvent", this.highlightEditableCell);
        this.myDataTable.subscribe("cellMouseoutEvent", this.myDataTable.onEventUnhighlightCell);
        this.myDataTable.subscribe("cellClickEvent", this.myDataTable.onEventShowCellEditor);
        this.myDataTable.subscribe("editorBlurEvent", this.myDataTable.onEditorBlur);
        this.myDataTable.subscribe("editorCancelEvent", this.myDataTable.onEditorCancel);

        // Hook into custom event to customize save-flow of "radio" editor
        this.myDataTable.subscribe("editorUpdateEvent", function(oArgs) {
            if(oArgs.editor.value == "") {
              this.removeEmpty(oArgs.editor.record);
            }
            if(oArgs.editor.column.key === "active") {
                this.saveCellEditor();
            }
        });
        this.myDataTable.subscribe("editorBlurEvent", function(oArgs) {
            this.cancelCellEditor();
        });

        //
        // ITECH.curtablecontainer custom events for our dynamic elements (to keep datatable object in scope)
        //

        // Create "add more" link at bottom of table
        elTableContainer = YAHOO.util.Dom.get(ITECH.curtablecontainer);
        var elLinkAdd = elTableContainer.appendChild(document.createElement("a"));
        elLinkAdd.href = "javascript:void(0)";
        
        if(!noEdit) {
          elLinkAdd.innerHTML = "Add " + labelAdd;  
        }
        
        elLinkAdd.className = "editTableAdd";
        elLinkAdd.eventClick = new YAHOO.util.CustomEvent("eventClick", this);
        elLinkAdd.eventClick.subscribe(this.addRow, this);
        YAHOO.util.Event.on(elLinkAdd, 'click', function(ev) { this.eventClick.fire(ev); return false; });

        // Monitor table clicks (specifically for the edit links)
        
        elTableContainer.tableClick = new YAHOO.util.CustomEvent("tableClick", this);
        elTableContainer.tableClick.subscribe(this.onTableClick, this);
        YAHOO.util.Event.on(elTableContainer, 'click', function(ev) { this.tableClick.fire(ev); return false; });

    };

    return InlineCellEditing;
}

    