/* Common ITech JS */

standardDatePickOpts = {
  changeMonth: true,
  changeYear: true,
  yearRange: "-90:+0"
};

YAHOO.util.Event.onDOMReady(function () {
  
  // set focus to first input element
  if(!window.location.hash) {
    var inputNodes = document.getElementsByTagName("input");  
    if(inputNodes.length > 0) {
      inputNodes[0].focus();
    }
  }
     
});

function clearAge() {
	  var ageMinObj = YAHOO.util.Dom.get('age_min');
	  if ( ageMinObj ) ageMinObj.value = 0;
	  var ageMaxObj = YAHOO.util.Dom.get('age_max');
	  if ( ageMaxObj ) ageMaxObj.value = '';
	  return false;
}

/**
 * Add item to drop-down select box and update via JSON
 */ 
function addToSelect(promptMsg, selectId, jsonUrl) {
  var SAVETEXT = "Inserting...";
    
  // titles need training category?
  var catId = false;
  if(selectId == "select_training_title_option") {
    oSelectCategory = document.getElementById("select_training_category_option");
    if(oSelectCategory) {
      if(!oSelectCategory.selectedIndex) {
        if(!confirm(tr("You have not selected a training category for your new title.") + "\n\n" + tr("Do you still wish to add a title without a training category associated with it?"))) {
          return;
        }
      }
      catId = oSelectCategory.options[oSelectCategory.selectedIndex].value;
    }
  }
  
  var newValue = prompt(promptMsg);
  
  if(newValue) {  
    
    var oSelect = document.getElementById(selectId);
    var firstItem = oSelect.options[0];
    var oldIndex = oSelect.selectedIndex;
    
    if(firstItem.text == SAVETEXT) {
      alert(tr("Trying to save... please wait"));
      return false;
    }
    
    // inform user we are attempting to save
    oSelect.options[0] = new Option(SAVETEXT, 0);
    oSelect.selectedIndex = 0;
    oSelect.className = "errorText";
        
    
    // AJAX (drop down)
    var ajaxCallback = {
      success: function(o) {
        
        oSelect.options[0] = firstItem; // reset saving message         
        oSelect.className = "";
        
        var status = YAHOO.lang.JSON.parse(o.responseText);                      
        
        // error handling
        if(status.error != null) {
          status.error = status.error.replace('%s', newValue);
          
          // user trying to add a value that is flagged as deleted in the database
          if(status.insert != null && status.insert == -2) {  
            if(confirm(status.error)) { // undelete
              oSelect.options[0] = new Option('Undeleting...', 0);
              cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl, ajaxCallback, "value=" + newValue + "&undelete=1");
             } else {               
              oSelect.selectedIndex = oldIndex;     
             }
          } else {
            alert(status.error);
            oSelect.selectedIndex = oldIndex;
          }       
        
        } else if(status.insert != null && status.insert > 0) { // SUCCESS
                         
          var addIndex = oSelect.options.length;
          oSelect.options[addIndex] =  new Option(newValue, status.insert);
          oSelect.selectedIndex = addIndex;                    
        }
        
      },
      failure: function() {        
        // display error message
        alert(tr("Couldn't save, sorry!"));
        oSelect.options[0] = firstItem;
        oSelect.className = "";
        return false;        
      },
      scope: this
    }
    
    queryPost = "value=" + newValue;
    if(catId) {
      queryPost += "&cat_id=" + catId;
    }
        
    cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl, ajaxCallback, queryPost);
  }
  
  return false;
}


/**
 * Add checkbox and update via JSON
 */ 
function addCheckbox(promptMsg, checkboxName, containerId, jsonUrl) {
  var SAVETEXT = "Adding checkbox...";  
  var newValue = prompt(promptMsg);
  
  if(newValue) {
    // inform user we are attempting to save
    var elDiv = document.getElementById(containerId).appendChild(document.createElement("div"));
    elDiv.className = "float50";
    elDiv.innerHTML = '<div class="errorText">' + SAVETEXT + '</div>';
    
    // AJAX (checkbox)
    var ajaxCallback = {
      success: function(o) {
        var status = YAHOO.lang.JSON.parse(o.responseText);                      
        
        // error handling
        if(status.error != null) {
          status.error = status.error.replace('%s', newValue);
          
          // user trying to add a value that is flagged as deleted in the database
          if(status.insert != null && status.insert == -2) {  
            if(confirm(status.error)) { // undelete              
              cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl, ajaxCallback, "value=" + newValue + "&undelete=1");
             }
          } else {
            alert(status.error);
            document.getElementById(containerId).removeChild(elDiv);
          }        
                  
        
        } else if(status.insert != null && status.insert > 0) { // SUCCESS
                         
          // dynamically create checkbox               
          var elCheckbox = document.createElement("input");
          elCheckbox.type = "checkbox";
          elCheckbox.name = checkboxName + "[]";
          elCheckbox.id = checkboxName + "_" + status.insert;
          elCheckbox.value = status.insert;
          elCheckbox.checked = "checked";
          
          // dynamically create label
          var elLabel = document.createElement("label");
          elLabel.htmlFor = checkboxName + "_" + status.insert;
          elLabel.innerHTML = " " + newValue;
          
          elDiv.innerHTML = '';
          elDiv.appendChild(elCheckbox);
          elDiv.appendChild(elLabel);
          
        }
        
      },
      failure: function() {        
        // display error message
        alert("Couldn't save, sorry!");
        oSelect.options[0] = firstItem;
        oSelect.className = "";
        return false;        
      },
      scope: this
    }

    cObj = YAHOO.util.Connect.asyncRequest('POST', jsonUrl, ajaxCallback, "value=" + newValue);    
  }  

}

// Filter hierarchical dropdowns using a naming convention of 'Parent_Child'
function filterSubTypeOptions(selectObjParentId, selectObjChildId) {
  
  var selectObj = document.getElementById(selectObjChildId);
  if ( (selectObj == undefined) || (selectObj == null) ) return;
  
  if ( selectObj ) 
  {
    var selectObjParent = document.getElementById(selectObjParentId);
 
    if ( selectObj.originalOptions == undefined ) {
      clone(selectObj);
    }
  
    // allow for multiple-select
    var curTypes = [];
    for(j = 0; j < selectObjParent.options.length; j++) {
      if(selectObjParent.options[j].selected) {
        curTypes.push(selectObjParent.options[j].value);
      }
    }
 
  //var curType = selectObjParent.options[selectObjParent.selectedIndex].value;
  /*
  var oldSubType = 0;
  if(selectObj.selectedIndex != -1) {
    oldSubType = selectObj.options[selectObj.selectedIndex].value ;
    selectObj.selectedIndex = 0; 
  }
  */
  
    var oldSubTypes = [ ];
    for(j = 0; j < selectObj.options.length; j++) {
      if(selectObj.options[j].selected) {
        oldSubTypes.push(selectObj.options[j].value);
      }
    }   
    	
    var i = 0;
    var tmpOpt = '';
 	
    ilength = selectObj.options.length;
 	for(i = 1; i < ilength; i++) {
 	  selectObj.remove(1);
    }
 
   var prefix = '';
   var prefix2 = '';
   var newi = 0;
 
  for (i in selectObj.originalOptions) { 
    tmpOpt = selectObj.originalOptions[i];
  		
  	for(j in curTypes) {
  	  curType = curTypes[j];
      prefix = i.substring(0,curType.length + 1);
      prefix2 = (curType + '_');
   	  if ( prefix == prefix2 ) {
        newi++;
  	    appendOptionLast(selectObj, tmpOpt, i);
  			
  		for(k in oldSubTypes ) {
   		  if ( i == oldSubTypes[k] )
   		    //selectObj.selectedIndex = newi;
   		    selectObj.options[newi].selected = true;
   			selectObj.disabled = false;
          }   			  
        }
      }
    }
  }  

 // disable if no options (other than --choose--)
  if (newi < 1)
    $(selectObj).attr('disabled', 'disabled');

} // func
  
     function clone(myObj)
	{
		
		var i;
		var count = 0;
  		myObj.originalOptions = new Object();
  		for(i = 1; i < myObj.options.length; i++) {
  			if ( myObj.options[i].value != '' )
  				myObj.originalOptions[myObj.options[i].value] = myObj.options[i].text;
  				
 		}
 		for(var f in myObj.originalOptions) {
   			count++;
 		}
 		myObj.originalOptions.length = count;
 	}  
	
function appendOptionLast(selectObj, myText, myVal)
	{
	  var elOptNew = document.createElement('option');
	  elOptNew.text = myText;
	  elOptNew.value = myVal;
	
	  try {
	    selectObj.add(elOptNew, null); // standards compliant; doesn't work in IE
	  }
	  catch(ex) {
	    selectObj.add(elOptNew); // IE only
	  }
	  selectObj.disabled = false;
	}
	
function setChildStatus(selectedIndex, childId, parentId, readonly)  {
	var childObj = YAHOO.util.Dom.get(childId);
	if ( childObj ) {
		if ( selectedIndex) {
			filterSubTypeOptions(parentId,childId);
			if (readonly === 1) {
				childObj.disabled = true;
			} else {
				childObj.disabled = false;
			}
		} else {
			childObj.selectedIndex = 0;
			childObj.disabled = true;
			
		}
		//also filter the child of the child
		var childFunction = "setChildStatus_" + childId;
		try {
			eval(childFunction + "()");
		} catch(err) {
			
		}
	}
}

function setSelected(widget_id, option_id) {
    var dropObj = YAHOO.util.Dom.get(widget_id);
    if  (dropObj) {
    var selected = 0;
     for(i = 0; i < dropObj.options.length; i++) {
       var myVal = dropObj[i].value;
       var has_parent = (myVal.indexOf('_') > 0?true:false);
       if ( has_parent &&  ((myVal.indexOf('_' + option_id) > 0) || (myVal == option_id)) ) {
           dropObj[i].selected = true;
             selected = i;
       } else if ( !has_parent && (myVal == option_id) ) {
         dropObj[i].selected = true;
         selected = i;
       } else {
         dropObj[i].selected = false;
       }
     }
    }

    return selected;
}



function setTrainingTitle(selectedIndex, categoryId, titleId)  {
	var catObj = YAHOO.util.Dom.get(categoryId), titleObj = YAHOO.util.Dom.get(titleId);
  var selectedItem = titleObj.value;
	if ( catObj ) {
    filterSubTypeOptions(categoryId, titleId);
	}

  if ( selectedItem ) { //restore last choice (bugfix)
    $('#'+titleId).val(selectedItem);
  }
}


function openNearestDatePicker() {
	$(this).siblings('input.datepicker').first().datepicker("show");
  return false;
}

/**** some facility functions ****/
function addSponsorRow(event)
{
	template = $('#facility_sponsor_id_lbl,#facility_sponsor_wrapper').clone().attr('id', null);
	template.find('#sponsor_add').remove();
	// reset form
	template.find('option').attr('selected', null);
	template.find('input,select').val(null);
	template.find('input,a').removeClass('hasDatepicker');
	// append
	$('#facility_comments_lbl').before(template);
	// bind datepicker control
	template.find('.datepicker').attr('id', null).datepicker();
	template.find('.calendarbtn').click(openNearestDatePicker);
	return false;
}

function showSponsorDates(obj)
{
	obj = $(obj);
	obj.siblings('.sponsorDates').toggle(1000);
	obj.toggle();
	$('.sponsorDates input').datepicker();
	return false;
}

/**** some employee functions and a confirm save changes or leave box ****/
form_has_changes = false;
form_has_submitted = false;

function exit_callback () {
	if (form_has_changes && !form_has_submitted)
		return tr("If you have made any changes to this page without clicking the Save button, your changes will be lost.  Are you sure you wish to leave this page?");
}

function itech_save_confirm_on_leave ()
{
	window.onbeforeunload = exit_callback;

	// watch form elements
	$(window).load(function () { // our scope is already in doc.ready, however does not seem to be slow enough, region filters change once on page load

		$('select,input').change(function () { form_has_changes = true; });
		$('form').submit(function () { form_has_submitted = true;	});
	});
}

/* SAONLY - south africa only - disable all sub regions if val() = *Multiple Regions* */
$(window).load(function (e) {
  if ((location.hostname.indexOf("pepfarskillsmart.trainingdata.org") !== -1) && location.hostname != 'localhost') /*south africa*/
    return;
  rgnsels = $('#province_id,#district_id,#region_c_id,#region_d_id,#region_e_id,#region_f_id,#region_g_id,#region_h_id,#region_i_id');
  rgnsels.change(function (e) { /* southafrica, hardcoded, they dont want site_enabled if multiple regions selected */
    var off = false;
    rgnsels.each(function (i, val) {
      $(this).removeAttr('disabled')
      var chosen = $(this).find('option:selected').html();
      if (!off && (chosen.indexOf('*Multiple') != -1 || chosen.indexOf('*Not In') != -1)){
        off = true;
        return; //stop processing
      }
      if (off) // turn off next elems
        $(this).attr('disabled', 'disabled');
    });
    if (off)
      $('#facilityInput').attr('disabled', 'disabled');
    else
      $('#facilityInput').removeAttr('disabled');
    
  });
})


function filterFunderOptions(selectObjParentId, selectObjChildId) {
  
  var selectObj = document.getElementById(selectObjChildId);
  if ( (selectObj == undefined) || (selectObj == null) ) return;
  
  if ( selectObj ) {
	  
    var selectObjParent = document.getElementById(selectObjParentId);
    var index = selectObjParent.selectedIndex;
    var selected_option_value = selectObjParent.options[index].value;
    var selected_option_text = selectObjParent.options[index].text;
 
    if ( selectObj.originalOptions == undefined ) {
      clone(selectObj);
    }

    // allow for multiple-select
    var curTypes = [ ];
    for(j = 0; j < selectObjParent.options.length; j++) {
      if(selectObjParent.options[j].selected) {
        curTypes.push(selectObjParent.options[j].value);
      }
    }
  
    var oldSubTypes = [ ];
    for(j = 0; j < selectObj.options.length; j++) {
      if(selectObj.options[j].selected) {
        oldSubTypes.push(selectObj.options[j].value);
      }
    }   
    	
    var i = 0;
    var tmpOpt = '';
 	
    ilength = selectObj.options.length;
    for(i = 1; i < ilength; i++) {
      selectObj.remove(1);
    }
 
    var prefix = '';
    var prefix2 = '';
    var newi = 0;
     
    for (i in selectObj.originalOptions) { 
      tmpOpt = selectObj.originalOptions[i];   
      
      for(j in curTypes){
        curType=curTypes[j];
        prefix=i.substring(0,curType.length+1); 	  
        prefix2=(curType+'_');
     	 
   	    if ( prefix == prefix2 ) {
          newi++;
  	      appendOptionLast(selectObj, tmpOpt, i);
  	      
  	      for(k in oldSubTypes){
  	      	if(i==oldSubTypes[k])
              selectobj.options[newi].selected=true;
            selectObj.disabled=false;
  	        
  	      }  
   	    }
      }
    }
  } // if (selectObj)  

 // disable if no options (other than --choose--)
  if (newi < 1)
    $(selectObj).attr('disabled', 'disabled');

} // func

function setFunderStatus(selectedIndex, childId, parentId)  {
	var childObj = YAHOO.util.Dom.get(childId);
	if ( childObj ) {
		if ( selectedIndex ) {
			childObj.disabled = false;
			filterFunderOptions(parentId,childId);
		} else {
			childObj.selectedIndex = 0;
			childObj.disabled = true;
			
		}
		//also filter the child of the child
		var childFunction = "setChildStatus_" + childId;
		try {
			eval(childFunction + "()");
		} catch(err) {
			
		}
	}
}