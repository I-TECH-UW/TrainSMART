<?php

require_once ('ReportFilterHelpers.php');
require_once ('models/table/OptionList.php');
//require_once('models/table/Course.php');
require_once ('views/helpers/CheckBoxes.php');
require_once ('models/table/MultiAssignList.php');
require_once ('models/table/TrainingTitleOption.php');

class ReportpreserviceController extends ReportFilterHelpers {
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct ( $request, $response, $invokeArgs );
	}
	
	public function init() {
	}
	
	public function preDispatch() {
		parent::preDispatch ();
		
		if (! $this->isLoggedIn ())
			$this->doNoAccessError ();
	
	}
	public function reportHeaders($fieldname = false, $rowRay = false) {

		require_once ('models/table/Translation.php');
		$translation = Translation::getAll ();

		$headers = array (// fieldname => label
'id' => 'ID', 'cnt' => t ( 'Count' ), 'active' => @$translation ['Is Active'], 'first_name' => @$translation ['First Name'], 'middle_name' => @$translation ['Middle Name'], 'last_name' => @$translation ['Last Name'], 'training_title' => @$translation ['Training Name'], 'province_name' => @$translation ['Region A (Province)'], 'district_name' => @$translation ['Region B (Health District)'], 'pepfar_category_phrase' => @$translation ['PEPFAR Category'], 'training_organizer_phrase' => @$translation ['Training Organizer'], 'training_level_phrase' => @$translation ['Training Level'], 'trainer_language_phrase' => t ( 'Language' ), 'training_location_name' => t ( 'Location' ), 'training_start_date' => t ( 'Date' ), 'training_topic_phrase' => t ( 'Training Topic' ), 'funding_phrase' => t ( 'Funding' ), 'is_tot' => t ( 'TOT' ), 'facility_name' => t ( 'Facility Name' ), 'facility_type_phrase' => t ( 'Facility Type' ), 'facility_sponsor_phrase' => t ( 'Facility Sponsor' ), 'course_recommended' => t ( 'Recommended classes' ), 'recommended' => t ( 'Recommended' ), 'qualification_phrase' => t ( 'Qualification' ) . ' ' . t ( '(primary)' ), 'qualification_secondary_phrase' => t ( 'Qualification' ) . ' ' . t ( '(secondary)' ), 'gender' => t ( 'Gender' ), 'name' => t ( 'Name' ), 'email' => t ( 'Email' ), 'phone' => t ( 'Phone' ), 'cat' => t ( 'Category' ), 'language_phrase' => t ( 'Language' ), 'trainer_type_phrase' => t ( 'Type' ), 'trainer_skill_phrase' => t ( 'Skill' ), 'trainer_language_phrase' => t ( 'Language' ), 'trainer_topic_phrase' => t ( 'Topics Taught' ), 'phone_work' => t ( 'Work Phone' ), 'phone_home' => t ( 'Home Phone' ), 'phone_mobile' => t ( 'Mobile Phone' ), 'type_option_id' => 'Type' );

		// action => array(field => label)
		$headersSpecific = array ('peopleByFacility' => array ('qualification_phrase' => t ( 'Qualification' ) ), 'participantsByCategory' => array ('cnt' => t ( 'Participants' ), 'person_cnt' => t ( 'Unique Participants' ) ) );

		if ($rowRay) {
			$keys = array_keys ( reset ( $rowRay ) );
			foreach ( $keys as $k ) {
				$csvheaders [] = $this->reportHeaders ( $k );
			}

			return array_merge ( array ('csvheaders' => $csvheaders ), $rowRay );

		} elseif ($fieldname) {

			// check report specific headers first
			$action = $this->getRequest ()->getActionName ();
			if (isset ( $headersSpecific [$action] ) && isset ( $headersSpecific [$action] [$fieldname] )) {
				return $headersSpecific [$action] [$fieldname];
			}

			return (isset ( $headers [$fieldname] )) ? $headers [$fieldname] : $fieldname;
		} else {
			return $headers;
		}

	}

	public function reportstudentstrainedAction()
	{
		$this->view->assign('title',$this->view->translation['Application Name']);
	}
	public function studentnameAction()
	{
		require_once ('models/table/Tutoredit.php');
		// For Cadres List
	$tutoredit = new Tutoredit();
	$listcadre = $tutoredit->ListCadre();
	$this->view->assign('getcadre',$listcadre); 
	$this->view->assign('title',$this->view->translation['Application Name']);
	$this->view->assign ( 'mode', 'count' );
	$this->facilityReport ();
	}
	
	public function graduatedAction()
	{
		$this->view->assign('title',$this->view->translation['Application Name']);
	}
	
	
	public function coursebystudentAction()
	{
		$this->view->assign('title',$this->view->translation['Application Name']);
	}

    public function coursebynameAction()
	{
		$this->view->assign('title',$this->view->translation['Application Name']);
	}
	
	public function facilityReport() {

		require_once ('models/table/TrainingLocation.php');

		$criteria = array ();

		//find the first date in the database
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$sql = "SELECT MIN(training_start_date) as \"start\" FROM training WHERE is_deleted = 0";
		$rowArray = $db->fetchAll ( $sql );
		$start_default = $rowArray [0] ['start'];
		$parts = explode ( '-', $start_default );
		$criteria ['start-year'] = $parts [0];
		$criteria ['start-month'] = $parts [1];
		$criteria ['start-day'] = $parts [2];

		if ($this->getSanParam ( 'start-year' ))
			$criteria ['start-year'] = $this->getSanParam ( 'start-year' );
		if ($this->getSanParam ( 'start-month' ))
			$criteria ['start-month'] = $this->getSanParam ( 'start-month' );
		if ($this->getSanParam ( 'start-day' ))
			$criteria ['start-day'] = $this->getSanParam ( 'start-day' );
		if ($this->view->mode == 'search') {
			$sql = "SELECT MAX(training_start_date) as \"start\" FROM training ";
			$rowArray = $db->fetchAll ( $sql );
			$end_default = $rowArray [0] ['start'];
			$parts = explode ( '-', $end_default );
			$criteria ['end-year'] = $parts [0];
			$criteria ['end-month'] = $parts [1];
			$criteria ['end-day'] = $parts [2];
		} else {
			$criteria ['end-year'] = date ( 'Y' );
			$criteria ['end-month'] = date ( 'm' );
			$criteria ['end-day'] = date ( 'd' );
		}

		if ($this->getSanParam ( 'end-year' ))
			$criteria ['end-year'] = $this->getSanParam ( 'end-year' );
		if ($this->getSanParam ( 'end-month' ))
			$criteria ['end-month'] = $this->getSanParam ( 'end-month' );
		if ($this->getSanParam ( 'end-day' ))
			$criteria ['end-day'] = $this->getSanParam ( 'end-day' );

    list($criteria, $location_tier, $location_id) = $this->getLocationCriteriaValues($criteria);
			
		$criteria ['training_title_option_id'] = $this->getSanParam ( 'training_title_option_id' );
		$criteria ['training_title_id'] = $this->getSanParam ( 'training_title_id' );
		$criteria ['training_location_id'] = $this->getSanParam ( 'training_location_id' );
		$criteria ['training_organizer_id'] = $this->getSanParam ( 'training_organizer_id' );
		$criteria ['training_pepfar_id'] = $this->getSanParam ( 'training_pepfar_id' );
		$criteria ['training_topic_id'] = $this->getSanParam ( 'training_topic_id' );
		$criteria ['training_level_id'] = $this->getSanParam ( 'training_level_id' );
		$criteria ['facility_type_id'] = $this->getSanParam ( 'facility_type_id' );
		$criteria ['facility_sponsor_id'] = $this->getSanParam ( 'facility_sponsor_id' );
		$criteria ['facilityInput'] = $this->getSanParam ( 'facilityInput' );
		$criteria ['is_tot'] = $this->getSanParam ( 'is_tot' );

		$criteria ['go'] = $this->getSanParam ( 'go' );
		$criteria ['doCount'] = ($this->view->mode == 'count');
		$criteria ['showProvince'] = ($this->getSanParam ( 'showProvince' ) or ($criteria ['doCount'] and ($criteria ['province_id'] or $criteria ['province_id'] === '0')));
		$criteria ['showDistrict'] = ($this->getSanParam ( 'showDistrict' ) or ($criteria ['doCount'] and ($criteria ['district_id'] or $criteria ['district_id'] === '0')));
		/*$criteria ['showCadre'] = ($this->getSanParam ( 'showCadre' ) or ($criteria ['doCount'] and ($criteria ['cadre'] or $criteria ['cadre'] === '0')));*/
    $criteria ['showRegionC'] = ($this->getSanParam ( 'showRegionC' ) or ($criteria ['doCount'] and ($criteria ['region_c_id'] or $criteria ['region_c_id'] === '0')));
		$criteria ['showTrainingTitle'] = ($this->getSanParam ( 'showTrainingTitle' ) or ($criteria ['doCount'] and ($criteria ['training_title_option_id'] or $criteria ['training_title_option_id'] === '0' or $criteria ['training_title_id'])));
		$criteria ['showLocation'] = ($this->getSanParam ( 'showLocation' ) or ($criteria ['doCount'] and $criteria ['training_location_id']));
		$criteria ['showOrganizer'] = ($this->getSanParam ( 'showOrganizer' ) or ($criteria ['doCount'] and ($criteria ['training_organizer_id'] or $criteria ['training_organizer_id'] === '0')));
		$criteria ['showPepfar'] = ($this->getSanParam ( 'showPepfar' ) or ($criteria ['doCount'] and ($criteria ['training_pepfar_id'] or $criteria ['training_pepfar_id'] === '0')));
		$criteria ['showTopic'] = ($this->getSanParam ( 'showTopic' ) or ($criteria ['doCount'] and ($criteria ['training_topic_id'] or $criteria ['training_topic_id'] === '0')));
		$criteria ['showLevel'] = ($this->getSanParam ( 'showLevel' ) or ($criteria ['doCount'] and $criteria ['training_level_id']));
		$criteria ['showType'] = ($this->getSanParam ( 'showType' ) or ($criteria ['doCount'] and ($criteria ['facility_type_id'] or $criteria ['facility_type_id'] === '0')));
		$criteria ['showSponsor'] = ($this->getSanParam ( 'showSponsor' ) or ($criteria ['doCount'] and $criteria ['facility_sponsor_id']));
		$criteria ['showFacility'] = true; //($this->getSanParam('showFacility') OR ($criteria['doCount'] and $criteria['facility_name']));
		$criteria ['showTot'] = ($this->getSanParam ( 'showTot' ) or ($criteria ['doCount'] and $criteria ['is_tot'] !== '' or $criteria ['is_tot'] === '0'));

		if ($criteria ['go']) {

			$sql = 'SELECT ';

			if ($criteria ['doCount']) {
				$sql .= ' COUNT(pt.person_id) as "cnt", pt.facility_name ';
			} else {
				$sql .= ' DISTINCT pt.id as "id", pt.facility_name, pt.training_start_date  ';
			}
			if ($criteria ['showFacility']) {
				$sql .= ', pt.facility_name ';
			}

			if ($criteria ['showTrainingTitle'] or ($this->view->mode == 'search')) {
				$sql .= ', pt.training_title ';
			}
			if ($criteria ['showDistrict']) {
				$sql .= ', pt.district_name, pt.district_id ';
			}
			if ($criteria ['showCadre']) {
				$sql .= ', cad.id, cad.cadrename ';
			}
			if ($criteria ['showProvince']) {
				$sql .= ', pt.province_name, pt.province_id ';
			}
		  if ($criteria ['showRegionC']) {
        $sql .= ', pt.region_c_name, pt.region_c_id ';
      }
			if ($criteria ['showLocation']) {
				$sql .= ', pt.training_location_name ';
			}

			if ($criteria ['showOrganizer']) {
				$sql .= ', torg.training_organizer_phrase ';
			}

			if ($criteria ['showLevel']) {
				$sql .= ', tlev.training_level_phrase ';
			}

			if ($criteria ['showType']) {
				$sql .= ', fto.facility_type_phrase ';
			}

			if ($criteria ['showSponsor']) {
				$sql .= ', fso.facility_sponsor_phrase ';
			}

			if ($criteria ['showPepfar']) {
				if ($criteria ['doCount']) {
					$sql .= ', tpep.pepfar_category_phrase ';
				} else {
					$sql .= ', GROUP_CONCAT(DISTINCT tpep.pepfar_category_phrase) as "pepfar_category_phrase" ';
				}
			}

			if ($criteria ['showTopic']) {
				if ($criteria ['doCount']) {
					$sql .= ', ttopic.training_topic_phrase ';
				} else {
					$sql .= ', GROUP_CONCAT(DISTINCT ttopic.training_topic_phrase ORDER BY training_topic_phrase) AS "training_topic_phrase" ';
				}
			}

			if ($criteria ['showTot']) {
				//$sql .= ', pt.is_tot ';
				$sql .= ", IF(pt.is_tot,'" . t ( 'Yes' ) . "','" . t ( 'No' ) . "') AS is_tot";
			}

			//JOIN with the participants to get facility

      $num_locs = $this->setting('num_location_tiers');
      list($field_name,$location_sub_query) = Location::subquery($num_locs, $location_tier, $location_id, true);
			
			if ($criteria ['doCount']) {
				$sql .= ' FROM (SELECT training.*, fac.person_id as "person_id", fac.facility_id as "facility_id", fac.type_option_id, fac.sponsor_option_id, fac.facility_name as "facility_name" , tto.training_title_phrase AS training_title,training_location.training_location_name, l.'.implode(', l.',$field_name).
				'       FROM training  ' . 
				'         JOIN training_title_option tto ON (`training`.training_title_option_id = tto.id)' . 
				'         JOIN training_location ON training.training_location_id = training_location.id ' . '         JOIN (SELECT person_id, facility_name, facility_id, location_id, type_option_id, sponsor_option_id,training_id FROM person JOIN person_to_training ON person_to_training.person_id = person.id '.
				'         JOIN facility as f ON person.facility_id = f.id) as fac ON training.id = fac.training_id JOIN ('.$location_sub_query.') as l ON fac.location_id = l.id WHERE training.is_deleted=0) as pt ';
			} else {
				$sql .= ' FROM (SELECT training.*, fac.facility_id as "facility_id", fac.type_option_id, fac.sponsor_option_id ,fac.facility_name as "facility_name" , tto.training_title_phrase AS training_title,training_location.training_location_name, l.'.implode(', l.',$field_name).
				'       FROM training  ' . 
				'         JOIN training_title_option tto ON (`training`.training_title_option_id = tto.id) ' . 
				'         JOIN training_location ON training.training_location_id = training_location.id ' . '         JOIN (SELECT DISTINCT facility_name, facility_id, location_id, training_id, type_option_id, sponsor_option_id FROM person JOIN person_to_training ON person_to_training.person_id = person.id '.
				'         JOIN facility as f ON person.facility_id = f.id) as fac ON training.id = fac.training_id JOIN ('.$location_sub_query.') as l ON fac.location_id = l.id  WHERE training.is_deleted=0) as pt ';
			}

			if ($criteria ['showOrganizer']) {
				$sql .= '	JOIN training_organizer_option as torg ON torg.id = pt.training_organizer_option_id ';
			}
			if ($criteria ['showLevel']) {
				$sql .= '	JOIN training_level_option as tlev ON tlev.id = pt.training_level_option_id ';
			}

			if ($criteria ['showType']) {
				$sql .= '	JOIN facility_type_option as fto ON fto.id = pt.type_option_id ';
			}
			/*if ($criteria ['showCadre']) {
				$sql .= ' Inner JOIN cadres as cad';
			}*/

			if ($criteria ['showSponsor']) {
				$sql .= '	JOIN facility_sponsor_option as fso ON fso.id = pt.sponsor_option_id ';
			}

			if ($criteria ['showPepfar']) {
				$sql .= '	LEFT JOIN (SELECT training_id, ttpco.training_pepfar_categories_option_id, pepfar_category_phrase FROM training_to_training_pepfar_categories_option as ttpco JOIN training_pepfar_categories_option as tpco ON ttpco.training_pepfar_categories_option_id = tpco.id) as tpep ON tpep.training_id = pt.id ';
			}

			if ($criteria ['showTopic']) {
				//$sql .= '	LEFT JOIN training_topic_option as ttopic ON ttopic.id = ttopic.training_topic_option_id ';
				$sql .= '	LEFT JOIN (SELECT training_id, ttto.training_topic_option_id, training_topic_phrase FROM training_to_training_topic_option as ttto JOIN training_topic_option as tto ON ttto.training_topic_option_id = tto.id) as ttopic ON ttopic.training_id = pt.id ';
			}

			$where = array(' pt.is_deleted=0 ');

			if ($criteria ['training_title_option_id'] or $criteria ['training_title_option_id'] === '0') {
				$where []= ' pt.training_title_option_id = ' . $criteria ['training_title_option_id'];
			}

			if ($criteria ['training_title_id'] or $criteria ['training_title_id'] === '0') {
					$where []= ' pt.training_title_option_id = ' . $criteria ['training_title_id'];
			}

			if ($criteria ['facilityInput']) {
				$where []= ' pt.facility_id = \'' . $criteria ['facilityInput'] . '\'';
			}

			if ($criteria ['training_location_id']) {
					$where []= ' pt.training_location_id = \'' . $criteria ['training_location_id'] . '\'';
			}

			if ($criteria ['training_organizer_id'] or $criteria ['training_organizer_id'] === '0') {
				$where []= ' pt.training_organizer_option_id = \'' . $criteria ['training_organizer_id'] . '\'';
			}

			if ($criteria ['training_topic_id'] or $criteria ['training_topic_id'] === '0') {
				$where []= ' ttopic.training_topic_option_id = \'' . $criteria ['training_topic_id'] . '\'';
			}

			if ($criteria ['facility_type_id'] or $criteria ['facility_type_id'] === '0') {
				$where []= ' pt.type_option_id = \'' . $criteria ['facility_type_id'] . '\'';
			}
			if ($criteria ['facility_sponsor_id'] or $criteria ['facility_sponsor_id'] === '0') {
				$where []= ' pt.sponsor_option_id = \'' . $criteria ['facility_sponsor_id'] . '\'';
			}

			if ($criteria ['training_level_id']) {
				$where []= ' pt.training_level_option_id = \'' . $criteria ['training_level_id'] . '\'';
			}

			if ($criteria ['training_pepfar_id'] or $criteria ['training_pepfar_id'] === '0') {
					$where []= ' tpep.training_pepfar_categories_option_id = \'' . $criteria ['training_pepfar_id'] . '\'';
			}

			if (intval ( $criteria ['end-year'] ) and $criteria ['start-year']) {
				$startDate = $criteria ['start-year'] . '-' . $criteria ['start-month'] . '-' . $criteria ['start-day'];
				$endDate = $criteria ['end-year'] . '-' . $criteria ['end-month'] . '-' . $criteria ['end-day'];
				$where []= ' training_start_date >= \'' . $startDate . '\'  AND training_start_date <= \'' . $endDate . '\'  ';
			}

			if ($criteria ['is_tot'] or $criteria ['is_tot'] === '0') {
				$where []= ' pt.is_tot = ' . $criteria ['is_tot'];
			}

			if ($where)
				$sql .= ' WHERE ' . implode(' AND ', $where);

			if ($criteria ['doCount']) {

				$groupBy = array();

				if ($criteria ['showFacility']) {
					$groupBy []= '  pt.facility_id';
				}

				if ($criteria ['showTrainingTitle']) {
						$groupBy []= ' pt.training_title_option_id';
				}
				if ($criteria ['showProvince']) {
					$groupBy []= ' pt.province_id';
				}
				if ($criteria ['showDistrict']) {
					$groupBy []= '  pt.district_id';
				}
				if ($criteria ['showCadre']) {
					$groupBy []= '  pt.id';
				}
        if ($criteria ['showRegionC']) {
           $groupBy []= '  pt.region_c_id';
        }
				if ($criteria ['showLocation']) {
					$groupBy []= '  pt.training_location_id';
				}
				if ($criteria ['showOrganizer']) {
					$groupBy []= '  pt.training_organizer_option_id';
				}
				if ($criteria ['showTopic']) {
					$groupBy []= '  ttopic.training_topic_option_id';
				}
				if ($criteria ['showLevel']) {
					$groupBy []= '  pt.training_level_option_id';
				}
				if ($criteria ['showPepfar']) {
						$groupBy []= '  tpep.training_pepfar_categories_option_id';
				}

				if ($criteria ['showType']) {
					$groupBy []= '  pt.type_option_id';
				}
				if ($criteria ['showSponsor']) {
					$groupBy []= '  pt.sponsor_option_id';
				}
				if ($criteria ['showTot']) {
					$groupBy []= '  pt.is_tot';
				}

				if ($groupBy)
					$groupBy = ' GROUP BY ' . implode(', ',$groupBy);
				$sql .= $groupBy;
			} else {
				if ($criteria ['showPepfar'] || $criteria ['showTopic']) {
					$sql .= ' GROUP BY pt.id';
				}
			}

			$rowArray = $db->fetchAll ( $sql . ' ORDER BY facility_name ASC ' );

			if ($criteria ['doCount']) {
				$count = 0;
				foreach ( $rowArray as $row ) {
					$count += $row ['cnt'];
				}
			} else {
				$count = count ( $rowArray );
			}

			if ($this->_getParam ( 'outputType' ))
				$this->sendData ( $this->reportHeaders ( false, $rowArray ) );

		} else {
			$count = 0;
			$rowArray = array ();
		}

		$criteria ['go'] = $this->getSanParam ( 'go' );

		//not sure why we are getting multiple PEPFARS
		foreach ( $rowArray as $key => $row ) {
			if (isset ( $row ['pepfar_category_phrase'] )) {
				$rowArray [$key] ['pepfar_category_phrase'] = implode ( ',', array_unique ( explode ( ',', $row ['pepfar_category_phrase'] ) ) );
			}
		}

		$this->viewAssignEscaped ( 'results', $rowArray );
		$this->view->assign ( 'count', $count );
		$this->view->assign ( 'criteria', $criteria );

		//facilities list
		$fArray = OptionList::suggestionList ( 'facility', array ('facility_name', 'id' ), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $fArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );

		//locations
		$locations = Location::getAll();
		$this->viewAssignEscaped ( 'locations', $locations );
		//facility types
		$typesArray = OptionList::suggestionList ( 'facility_type_option', 'facility_type_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_types', $typesArray );
		//sponsor types
		$sponsorsArray = OptionList::suggestionList ( 'facility_sponsor_option', 'facility_sponsor_phrase', false, false );
		$this->viewAssignEscaped ( 'facility_sponsors', $sponsorsArray );

		//course
		//$courseArray = Course::suggestionList(false,10000);
		//$this->viewAssignEscaped('courses',$courseArray);
		//location
		// location drop-down
		$tlocations = TrainingLocation::selectAllLocations ($this->setting('num_location_tiers'));
		$this->viewAssignEscaped ( 'tlocations', $tlocations );
		//organizers
		$organizersArray = OptionList::suggestionList ( 'training_organizer_option', 'training_organizer_phrase', false, false, false );
		$this->viewAssignEscaped ( 'organizers', $organizersArray );
		//topics
		$topicsArray = OptionList::suggestionList ( 'training_topic_option', 'training_topic_phrase', false, false, false );
		$this->viewAssignEscaped ( 'topics', $topicsArray );
		//levels
		$levelArray = OptionList::suggestionList ( 'training_level_option', 'training_level_phrase', false, false );
		$this->viewAssignEscaped ( 'levels', $levelArray );
		//pepfar
		$organizersArray = OptionList::suggestionList ( 'training_pepfar_categories_option', 'pepfar_category_phrase', false, false, false );
		$this->viewAssignEscaped ( 'pepfars', $organizersArray );

		//facilities list
		$rowArray = OptionList::suggestionList ( 'facility', array ('facility_name', 'id' ), false, 9999 );
		$facilitiesArray = array ();
		foreach ( $rowArray as $key => $val ) {
			if ($val ['id'] != 0)
				$facilitiesArray [] = $val;
		}
		$this->viewAssignEscaped ( 'facilities', $facilitiesArray );
	}

}
?>