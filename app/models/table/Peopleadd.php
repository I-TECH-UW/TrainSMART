<?php
require_once('ITechTable.php');
class Peopleadd extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'student';
	protected $_address = 'addresses';
	protected $_tutor = 'tutor';
	protected $_gender = 'lookup_gender';
	protected $_title = 'person_title_option';
	protected $_person = 'person';
	protected $_facility = 'facility';
	protected $_city = 'location_city';

	public function Peopletitle(){

		$select = $this->dbfunc()->select()
		->from($this->_title);

		//echo $select->__toString();
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	public function PeopleFacility(){

		$select = $this->dbfunc()->select()
		->from($this->_facility);

		//echo $select->__toString();
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	public function PeopleCity(){

		$select = $this->dbfunc()->select()
		->from($this->_city);

		$result = $this->dbfunc()->fetchall($select);
		return $result;
	}

	public function addTutor($param){
		$insert = array(
			"title_option_id"	=>	$param['title'],
			"facility_id"		=>	$param['facility'],
			"first_name"		=>	$param['firstname'],
			"middle_name"		=>	$param['middlename'],
			"last_name"			=>	$param['lastname'],
			"gender"			=>	$param['gender'],
			"birthdate"			=>	(trim($param['dob']) != "") ? date("Y-m-d", strtotime($param['dob'])) : null,
			"home_address_1"	=>	$param['address1'],
			"home_address_2"	=>	$param['address2'],
			"home_postal_code"	=>	$param['zip'],
			"home_location_id"	=>	$param['city']
		);

		$db = $this->dbfunc();
		$tutor = $db->insert($this->_person,$insert);

		$id = $db->lastInsertId();

		# ADDING TUTOR RECORD
		$tutor = array(
			"personid"			=>	$id,
			"institutionid"		=>	$param['institutionid'],
			"is_keypersonal"	=>	$param['type'] == 'key' ? 1 : 0
		);
		$rowArray = $db->insert($this->_tutor, $tutor);
		$tutorid = $db->lastInsertId();
		# ADDING PERM ADDRESS
		$address = array(
			'address1'			=>	$param['address1'],
			'address2'			=>	$param['address2'],
			'postalcode'		=>	$param['zip'],
			"id_geog1"			=>	$this->getRegionLastValue($param['province_id']),
			"id_geog2"			=>	$this->getRegionLastValue($param['district_id']),
			"id_geog3"			=>	$this->getRegionLastValue($param['region_c_id']),
			'locationid'		=>	$param['city'],
			'id_addresstype'	=>	1,
		);
		$result = $db->insert("addresses", $address);
		$addressid = $db->lastInsertId();
		# LINKING ADDRESS TO TUTOR
		$link = array(
			"id_tutor"		=>	$tutorid,
			"id_address"	=>	$addressid,
		);
		$rowArray = $db->insert("link_tutor_addresses", $link);
		
		# LINK TUTOR TO INSTITUTION
		$link = array(
			"id_tutor"		=>	$tutorid,
			"id_institution" =>	$param['institutionid'],
		);
		$rowArray = $db->insert("link_tutor_institution", $link);
		
		
		return $id;
	}

	public function peopleadd($param = NULL) {
		if ($param == NULL)
			return;
		if (isset ($_POST['addpeople'])){
			if ($param['type'] == "student"){
				$db = $this->dbfunc();
				$insert=array(
					'title_option_id'	=>	$param['title'],
					'facility_id'		=>	$param['facility'],
					'first_name'		=>	$param['firstname'],
					'middle_name'		=>	$param['middlename'],
					'last_name'			=>	$param['lastname'],
					'gender'			=>	$param['gender'],
					'birthdate'			=>	trim($param['dob']) != "" ? date("Y-m-d", strtotime($param['dob'])) : null,
					'home_address_1'	=>	$param['address1'],
					'home_address_2'	=>	$param['address2'],
					'home_postal_code'	=>	$param['zip'],
					'home_location_id'	=>	$param['city'],
					'facility_id'		=>	$param['facility'],
				);

				#echo "<pre>";
				#print_r ($_POST);
				#echo "\n\n";
				#print_r($insert);
				#echo "</pre>";
				#die();

				$rowArray = $db->insert($this->_person,$insert);

				#print_r ($rowArray);
				#die('ok...');

				$id = $db->lastInsertId("person");

				#echo "last insert id = " . $id . "<br>";

				$student=array(
					"personid"			=>	$id,
					"geog1"				=>	$this->getRegionLastValue($param['province_id']),
					"geog2"				=>	$this->getRegionLastValue($param['district_id']),
					"geog3"				=>	$this->getRegionLastValue($param['region_c_id']),
					"institutionid"		=>	$param['institutionid'],
				);
				$rowArray = $db->insert("student", $student);

				// add student -> institution link
				//$link_sql = "INSERT INTO link_student_institution SET id_student = {}, id_institution = {}";
				//$db->query($link_sql);

			} elseif ($param['type'] == "tutorNORUN") { // TODO i think this is effectively commented out, marked for ivestigation then removal.
				$insert = array(
					"title_option_id"	=>	$param['title'],
					"facility_id"		=>	$param['facility'],
					"first_name"		=>	$param['firstname'],
					"middle_name"		=>	$param['middlename'],
					"last_name"			=>	$param['lastname'],
					"gender"			=>	$param['gender'],
					"birthdate"			=>	trim($param['dob']) != "" ? date("Y-m-d", strtotime($param['dob'])) : "",
					"home_address_1"	=>	$param['address1'],
					"home_address_2"	=>	$param['address2'],
					"home_postal_code"	=>	$param['zip'],
					"home_location_id"	=>	$param['city'],
				);

				$db = $this->dbfunc();

				#echo "<pre>";
				#print_r ($_POST);
				#echo "\n\n";
				#print_r($insert);
				#echo "</pre>";
				#die();

				$tutor = $db->insert($this->_person, $insert);

				#print_r ($rowArray);
				#die('ok...');

				$id = $db->lastInsertId();

				$tutor = array(
					"personid"			=>	$id,
					"institutionid"		=>	$param['institutionid'],
				);

				$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
				//$rowArray = $db->insert($this->_tutor, $tutor);


			}
			return $id;
		}
	}

}

?>