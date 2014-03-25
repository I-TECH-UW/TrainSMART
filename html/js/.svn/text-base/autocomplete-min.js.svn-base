	var zindexAutocomplete = 9000;

  function makeAutocomplete(inputId, containerId, action) {
		// A Script Node DataSource
		var myServer = action;
		var mySchema = ["\n", "\t"] ;
		var myDataSource = new YAHOO.widget.DS_XHR(myServer, mySchema);
		myDataSource.responseType = YAHOO.widget.DS_XHR.TYPE_FLAT;

		 var myAutoComp = new YAHOO.widget.AutoComplete(inputId,containerId, myDataSource);
		    myAutoComp.prehighlightClassName = "yui-ac-prehighlight";
		    myAutoComp.typeAhead = true;
		    myAutoComp.useShadow = true;
		    myAutoComp.minQueryLength = 0;
		    myAutoComp.autoHighlight = false;
		    myAutoComp.maxResultsDisplayed = 100;
		    myAutoComp.allowBrowserAutocomplete = false;

  	YAHOO.util.Dom.addClass(inputId,'autocomplete');

    /* fix IE z-index overlap for multiple autocompletes */
    var oParent = YAHOO.util.Dom.getAncestorByClassName(inputId, "fieldInput");
    YAHOO.util.Dom.setStyle(oParent, "z-index", zindexAutocomplete--);

  	/* ajax indicator */
    myAutoComp.dataReturnEvent.subscribe(function ( sType, sArgs ) {
      YAHOO.util.Dom.removeClass(inputId,'ajax-ac');
      return true;
    });

    myAutoComp.doBeforeSendQuery = function( sQuery ) {
       YAHOO.util.Dom.addClass(inputId,'ajax-ac');
       return sQuery;
    }

    return myAutoComp;

}

	function appendExtraInfo(autoComp,numExtras) {
		return appendExtraInfoSeparator(autoComp,numExtras, " (", ")");
	}

	function appendExtraInfoSeparator(autoComp,numExtras, sepPre, sepPost) {
		// This function returns markup that bolds the original query,
		// and also displays to additional pieces of supplemental data.
		autoComp.formatResult = function(aResultItem, sQuery) {
		   var sKey = aResultItem[0]; // the entire result key
		   var sKeyQuery = sKey.substr(0, sQuery.length); // the query itself
		   var sKeyRemainder = sKey.substr(sQuery.length); // the rest of the result

		   SPAN_BEGIN = "<span style='font-weight:bold'>";
		   SPAN_END = "</span>";

		   // match the beginning of the autocomplete?
		   if(sQuery.toUpperCase() != sKeyQuery.toUpperCase()) {
        spanBegin = "";
        spanEnd = "";
       } else {
        spanBegin = SPAN_BEGIN;
        spanEnd = SPAN_END;
       }

		   var aMarkup = ["<div id='ysearchresult'>",
		      spanBegin,
		        sKeyQuery,
		      spanEnd,
		        sKeyRemainder,
		      sepPre];

		      for(var i = 1; i <= numExtras; i++) {

            // match an additional result item (e.g., when searching both last and first names)
            if(!spanBegin && sQuery.toUpperCase() == aResultItem[i].substr(0,sQuery.length).toUpperCase()) {
              aMarkup [aMarkup.length] = SPAN_BEGIN;
              aMarkup [aMarkup.length] = aResultItem[i].substr(0,sQuery.length);
              aMarkup [aMarkup.length] = SPAN_END;
              aMarkup [aMarkup.length] = aResultItem[i].substr(sQuery.length);
            } else {
		      	  aMarkup [aMarkup.length]= aResultItem[i];
            }

		      	if ( i < numExtras )
		      		aMarkup [aMarkup.length]= ", ";
		      }
		      aMarkup [aMarkup.length]= sepPost + "</div>";
		  return (aMarkup.join(""));
		};
	}

	function formatNameAutocomplete(autoComp) {
		// This function returns markup that bolds the original query,
		// and also displays to additional pieces of supplemental data.
		autoComp.formatResult = function(aResultItem, sQuery) {
		   var sKey = aResultItem[0]; // the entire result key
		   var sKeyQuery = sKey.substr(0, sQuery.length); // the query itself
		   var sKeyRemainder = sKey.substr(sQuery.length); // the rest of the result

		   SPAN_BEGIN = "<span style='font-weight:bold'>";
		   SPAN_END = "</span>";

		   var aMarkup = ["<div id='ysearchresult'>"];

		   var spanInner = '';

		      for(var i = 1; i <= 3; i++) {
					if (aMarkup.length > 1)
						aMarkup [aMarkup.length]= ' ';
		            // match an additional result item (e.g., when searching both last and first names)
		            if(sQuery.toUpperCase() == aResultItem[i].substr(0,sQuery.length).toUpperCase()) {
		              aMarkup [aMarkup.length] = SPAN_BEGIN;
		              aMarkup [aMarkup.length] = aResultItem[i].substr(0,sQuery.length);
		              aMarkup [aMarkup.length] = SPAN_END;
		              aMarkup [aMarkup.length] = aResultItem[i].substr(sQuery.length);
		            } else {
				      	  aMarkup [aMarkup.length]= aResultItem[i];
		            }

		      }
		      
		      // birthdate
		      if(typeof aResultItem[7] != "undefined" && aResultItem[7] != "") {
		        aMarkup [aMarkup.length] = " (" + aResultItem[7] + ")";  
		      }
		      // facility
		      if(typeof aResultItem[5] != "undefined" && aResultItem[5] != "" && aResultItem[5] != "0") {
		        aMarkup [aMarkup.length] = " - " + aResultItem[5] + "";  
		      }
		      
		      
		      aMarkup [aMarkup.length]= "</div>";
		  return (aMarkup.join(""));
		};
	}
