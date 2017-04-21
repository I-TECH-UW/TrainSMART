var dSelected = YAHOO.util.Dom.get(monthId).value + '/' + YAHOO.util.Dom.get(dayId).value + '/' + YAHOO.util.Dom.get(yearId).value;
    var dPage = YAHOO.util.Dom.get(monthId).value + '/' + YAHOO.util.Dom.get(yearId).value;
    if ( dPage == '/' ) {
    	dPage = '01/1980';
    } else if( dPage.substring(0,1) == '/' ) { // year entered, but no month
      var today = new Date();
      dPage = (today.getMonth() + 1) + dPage;
    }
    
    /* @DEPRICATED */
    function makeCalendar(containerId, dayId, monthId, yearId, extra_callback) {
    	var config = { pagedate:dPage, selected:dSelected, navigator:true };
    	makeCalendarInstance(containerId, dayId, monthId, yearId,  "calendarpicker", "buttoncalendar1", extra_callback, config);
    }

    /* @DEPRICATED */
   function makeAdditionalCalendar(containerId, dayId, monthId, yearId, extra_callback) {
	   var config = { pagedate:dPage, selected:dSelected, navigator:true };
    	makeCalendarInstance(containerId, dayId, monthId, yearId, "calendarpicker2","buttoncalendar2", extra_callback, config);
    }
    
    function makeCalendarDefault(containerId, dayId, monthId, yearId, extra_callback, calendarpicker, buttoncalendar) {
    	var config = { pagedate:dPage, selected:dSelected, navigator:true };
    	makeCalendarInstance(containerId, dayId, monthId, yearId, calendarpicker, buttoncalendar, extra_callback, config);
    }
       
       function makeCalendarUkraine(containerId, dayId, monthId, yearId, extra_callback, calendarpicker, buttoncalendar) {
       	var config = { pagedate:dPage, selected:dSelected, 
       			navigator:false,
       			MONTHS_LONG: ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"],
       			           WEEKDAYS_SHORT : ["Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"] ,
       			           START_WEEKDAY: 1
       			};
       	makeCalendarInstance(containerId, dayId, monthId, yearId, calendarpicker, buttoncalendar, extra_callback, config);
       }

        function makeCalendarInstance(containerId, dayId, monthId, yearId, pickerId, buttonCalendarId, extra_callback, config) {

        function onButtonClick() {

            /*
                 Create an empty body element for the Overlay instance in order
                 to reserve space to render the Calendar instance into.
            */

            oCalendarMenu.setBody("&#32;");

            oCalendarMenu.body.id = containerId + "container";
            oCalendarMenu.render(this.get("container"));
            oCalendarMenu.align();
            var oCalendar = new YAHOO.widget.Calendar(buttonCalendarId, oCalendarMenu.body.id, config);

            oCalendar.render();
            oCalendar.changePageEvent.subscribe(function () {

                window.setTimeout(function () {

                    oCalendarMenu.show();

                }, 0);

            });


            /*
                Subscribe to the Calendar instance's "select" event to
                update the month, day, year form fields when the user
                selects a date.
            */

            oCalendar.selectEvent.subscribe(function (p_sType, p_aArgs) {

                var aDate;

                if (p_aArgs) {

                    aDate = p_aArgs[0][0];

                    YAHOO.util.Dom.get(monthId).value = aDate[1];
                    YAHOO.util.Dom.get(dayId).value = aDate[2];
                    YAHOO.util.Dom.get(yearId).value = aDate[0];

                }

                oCalendarMenu.hide();

            });
            
            if ( extra_callback ) {
                oCalendar.selectEvent.subscribe(extra_callback);
            }

        }

	        // Create an Overlay instance to house the Calendar instance

	        var oCalendarMenu = new YAHOO.widget.Overlay("calendarmenu");

	        // Create a Button instance of type "menu"
	        var oButton = new YAHOO.widget.Button({
	                                            type: "menu",
	                                            id:  pickerId,
	                                            label: null,
	                                            menu: oCalendarMenu,
	                                            container: containerId,
	        											title: tr("Pick a Date") });

	        /*
	            Add a "click" event listener that will render the Overlay, and
	            instantiate the Calendar the first time the Button instance is
	            clicked.
	        */

	        oButton.on("click", onButtonClick);
        }
