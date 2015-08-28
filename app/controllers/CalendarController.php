<?php
/*
 * Created on Feb 11, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechController.php');

class CalendarController extends ITechController
{
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
    }

    public function init()
    {

    }

    public function indexAction()
    {
        require_once 'models/table/Training.php';
        require_once 'models/Session.php';
        require_once 'views/helpers/TrainingViewHelper.php';

        $uid = Session::getCurrentUserId();

        // restricted access?? only show trainings we have the ACL to view
        $org_allowed_ids = allowed_organizer_access($this);
        $allowedWhereClause = "";
        if ($org_allowed_ids) { // doesnt have acl 'training_organizer_option_all'
            $org_allowed_ids = implode(',', $org_allowed_ids);
            $allowedWhereClause = " training_organizer_option_id in ($org_allowed_ids) ";
        }
        // restricted access?? only show organizers that belong to this site if its a multi org site
        $site_orgs = allowed_organizer_in_this_site($this); // for sites to host multiple training organizers on one domain
        $allowedWhereClause .= $site_orgs ? " AND training_organizer_option_id in ($site_orgs) " : "";

        // Future trainings
        $tableObj = new Training();
        $rows = $tableObj->getIncompleteTraining($uid, $allowedWhereClause, '');

        if($rows) {
            require_once 'views/helpers/Calendar.php';
            $cal = new calendar;

            /*
            Show links to access the previous and next months for month calendar formats, and
            links to access the previous and next years for full year calendars.
            */
            $cal->displayPrevNextLinks = true;
            /*
            This section is used when $cal->displayPrevNextLinks is set to true.  The month
            section is only needed when displaying month calendars.
            */
            if (!empty($_GET['mon'])) {
                $cal->calMonth = $_GET['mon'];
            }
            if (!empty($_GET['yr'])) {
                $cal->calYear = $_GET['yr'];
            }

            //Set the calendar format to large month.
            $cal->calFormat = "largeMonth";
            /*
            Show the small calendars for the previous and next months in the header.  This is
            only used for large month calendars.
            */
            $cal->displayPrevNext = false;
            /*
            This determines weather events are displayed in the calendar.  For large month
            calendars this will display a list of events in the calendar cell for the event
            date.  For small month and full year calendars, the date will be highlighted and
            the dates will be displayed when you hover the mouse over the day of the event.
            */
            $cal->displayEvents = true;

            //Tell the class that the week starts on Sunday
            $cal->startingDOW = 0;

            //Tell the class to show the week numbers.
            $cal->showWeek = false;

            $cal->showWorkHours = false; // no starting times

            /*
            This will set a few events to test the display.  Any number of events can be added
            for display in the calendar.
            */
            foreach($rows as $r) {
                $label = $r['training_title'];
                $label .= ($r['training_organizer_option_id']) ? ' - ' . $r['training_organizer_phrase'] . '': '';
                $label = '<a href="' . Settings::$COUNTRY_BASE_URL . '/training/edit/id/' . $r['id'] . '">' . $label . '</a>';
                $label .= '<br>(' . $r['training_length_value'] . ' ' . t($r['training_length_interval'] . (($r['training_length_value']) != 1 ? 's' : '')) . ')';

                $cal->addEvent(strtotime($r['training_start_date']), $label);
            }

            $calHtml = $cal->display();

        } else {
            $calhtml = t('No future trainings found.');
        }


        $this->view->assign('calendar', $calHtml);
        $this->view->assign('calMonth', $cal->calMonth);
        $this->view->assign('calYear',  $cal->calYear);
    }
}
