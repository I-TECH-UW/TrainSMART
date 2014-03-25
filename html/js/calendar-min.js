
        function makeCalendar(containerId, dayId, monthId, yearId, extra_callback) {
        	makeCalendarInstance(containerId, dayId, monthId, yearId, "calendarpicker", "buttoncalendar1", extra_callback);
        }

       function makeAdditionalCalendar(containerId, dayId, monthId, yearId, extra_callback) {
        	makeCalendarInstance(containerId, dayId, monthId, yearId, "calendarpicker2","buttoncalendar2", extra_callback);
        }

        function makeCalendarInstance(containerId, dayId, monthId, yearId, pickerId, buttonCalendarId, extra_callback) {

        function onButtonClick() {

            /*
                 Create an empty body element for the Overlay instance in order
                 to reserve space to render the Calendar instance into.
            */

            oCalendarMenu.setBody("&#32;");

            oCalendarMenu.body.id = containerId + "container";

            // Render the Overlay instance into the Button's parent element

            oCalendarMenu.render(this.get("container"));


            // Align the Overlay to the Button instance

            oCalendarMenu.align();


            /*
                 Create a Calendar instance and render it into the body
                 element of the Overlay.
            */
            var dSelected = YAHOO.util.Dom.get(monthId).value + '/' + YAHOO.util.Dom.get(dayId).value + '/' + YAHOO.util.Dom.get(yearId).value;
            var dPage = YAHOO.util.Dom.get(monthId).value + '/' + YAHOO.util.Dom.get(yearId).value;


            if ( dPage == '/' ) {
            	dPage = '01/1980';
            } else if( dPage.substring(0,1) == '/' ) { // year entered, but no month
              var today = new Date();
              dPage = (today.getMonth() + 1) + dPage;
            }

            var oCalendar = new YAHOO.widget.Calendar(buttonCalendarId, oCalendarMenu.body.id, { pagedate:dPage, selected:dSelected, navigator:true });

            oCalendar.render();


            /*
                Subscribe to the Calendar instance's "changePage" event to
                keep the Overlay visible when either the previous or next page
                controls are clicked.
            */

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
            

            /*
                 Unsubscribe from the "click" event so that this code is
                 only executed once
            */

            // JE: don't unsubscribe because user may change dates then click again...

            //this.unsubscribe("click", onButtonClick);

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
