<?php

require_once('ITechTable.php');
require_once('Helper.php');

class Studentedit extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'student';
	protected $_person = 'person';
	protected $_title = 'person_title_option';
	protected $_city = 'location_city';
	protected $_location = 'location';
	protected $_cadres = 'cadres';
	protected $_funding = 'link_student_funding';


	public function EditStudent($pupilid) {
		# MAKING MULTI DIMENSION OUTPUT FOR MULTIPLE TABLES
		$output = array();

		# GETTING BASIC PERSON RECORD
		$select = $this->dbfunc()->select()
			->from('person')
			->where('id = ?',$pupilid);
		$row = $this->dbfunc()->fetchAll($select);
		$output['person'] = $row;

		# GETTING STUDENT RECORD
		$select = $this->dbfunc()->select()
			->from('student')
			->where('personid = ?',$pupilid);
		$row = $this->dbfunc()->fetchAll($select);
		$output['student'] = $row;

		# GETTING FUNDING RECORD
		$select = $this->dbfunc()->select()
			->from($this->_funding)
			->where('studentid = ?',$output['student'][0]['id']);
		$row = $this->dbfunc()->fetchAll($select);
		$output['funding'] = $row;

		# GETTING COHORT LINK
		$select = $this->dbfunc()->select()
			->from('link_student_cohort')
			->where('id_student = ?',$output['student'][0]['id']);
		$row = $this->dbfunc()->fetchAll($select);
		$output['link_cohort'] = $row;

		# GETTING PERMANENT ADDRESS
		$select = $this->dbfunc()->select()
			->from(array('a' => 'addresses'),
					array('address1','address2','city','postalcode','state','country','id_addresstype','id_geog1','id_geog2','id_geog3'))
			->join(array('l' => 'link_student_addresses'),
					'a.id = l.id_address')
			->where('l.id_student = ?',$output['student'][0]['id'])
			->where('a.id_addresstype = 1');
		$row = $this->dbfunc()->fetchAll($select);
		$output['permanent_address'] = $row;

		// DETERMINING IF A LINK EXISTS
		$select = $this->dbfunc()->select()
			->from('link_student_cohort')
			->where('id_student = ?',$output['student'][0]['id']);
		$row = $this->dbfunc()->fetchAll($select);
		$output['link_cohort'] = $row;

		# GETTING COHORT IF EXISTS
		if (count ($output['link_cohort']) > 0){
			$select = $this->dbfunc()->select()
				->from('cohort')
				->where('id = ?',$output['link_cohort'][0]['id']);
			$row = $this->dbfunc()->fetchAll($select);
			$output['cohort'] = $row;

			if (count ($row) > 0){
				# GETTING CADRE FROM COHORT
				$select = $this->dbfunc()->select()
					->from('cadres')
					->where('id = ?',$output['cohort'][0]['cadreid']);
				$row = $this->dbfunc()->fetchAll($select);
				$output['cadre'] = $row;
			} else {
				$output['cohort'] = array();
			}

		} else {
			# NO COHORT
			$output['cohort'] = array();

			# NO COHORT = NO CADRE
			$output['cadre'] = array();
		}

		// echo $select->__toString();
		return $output;

		/* $db = $this->dbfunc();
		$select=$db->query("select * from person where id = '$pupilid'");
		$row = $select->fetch($select);
		return $row;*/

	}

	public function ViewStudent($pupilid){
		$select = $this->dbfunc()->select()
			->from('student')
			->where('personid = ?',$pupilid);
		$row = $this->dbfunc()->fetchAll($select);
		return $row;
	}

	// RETRIEVING COHORTS
	public function ListCohort(){
		$select = $this->dbfunc()->select()
			->from('cohort');
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	// RETRIEVING TUTORS FOR ADVISORS
	public function ListTutors($cohort_id = 0){
		$select = $this->dbfunc()->select()
			->from(array('p' => 'person'),
				array('id','first_name','last_name'))
			->join(array('t' => 'tutor'),
				'p.id = t.personid')
			->order('last_name')
			->order('first_name');

		if($cohort_id > 0){
			$select = $select
				->joinLeft(array('lti' => 'link_tutor_institution'), 'lti.id_tutor = t.id')
				->joinLeft(array('c' => 'cohort'), 'lti.id_institution = c.institutionid or t.institutionid = c.institutionid') // bugfix, link_trainer_institution does not seem to contain all trainer institutions, however there is this column institution ID on table trainer anyway. #TODO (orig not left joins either)
				->where("c.id = {$cohort_id}");
		}

		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	//For Lisiting Title
	public function ListTitle(){
		$select = $this->dbfunc()->select()
			->from('person_title_option');
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	public function listCadre(){
		$select = $this->dbfunc()->select()
		->from('cadres')
		->order('cadrename');
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	//For Lisiting Selected  Title
	public function EditTitle($titleid){
		$select = $this->dbfunc()->select()
			->from($this->_title)
			->where('id = ?',$titleid);
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	//For Lisiting City
	public function ListCity(){
		$select = $this->dbfunc()->select()
			->from($this->_city);
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	public function EditCity($cityid){
		$select = $this->dbfunc()->select()
			->from($this->_city)
			->where('id = ?',$cityid);
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

	public function AddCadre($param){
		$db = $this->dbfunc();
		$insert=array('cadrename'=>"$param[cadre]");

		$rowArray = $db->insert($this->_cadres,$insert);
		$id = $db->lastInsertId();
		return $id;
	}

	public function ViewCadre($cadreid){
		$select = $this->dbfunc()->select()
			->from('cadres')
			->where('id = ?',$cadreid);
		$row = $this->dbfunc()->fetchAll($select);
		return $row;
	}



	public function UpdateCadre($param){
		$db = $this->dbfunc();
		$data = array('id'=>$param['cadreid'],
			'cadrename'=>$param['cadre']
		);

		$db->update('cadres',$data,"id = '".$param['cadreid']."'");
		return $data;
	}

	public function UpdatePerson($param){

		$datepick=$param['dob'];
		$dobymd=date("Y-m-d",strtotime($datepick));
		$db = $this->dbfunc();
		$data = array(
			"title_option_id"	=>	$param['title'],
			'first_name'		=>	$param['firstname'],
			'middle_name'		=>	$param['middlename'],
			'last_name'			=>	$param['lastname'],
			'gender'			=>	$param['gender'],
			'birthdate'			=>	$dobymd,
			'home_address_1'	=>	$param['localaddress1'],
			'home_address_2'	=>	$param['localaddress2'],
			'home_postal_code'	=>	$param['localpostalcode'],
			'home_city'			=>	$param['localcity'],
			'home_is_residential' => $param['localisresidential'],
			'email'				=>	$param['email'],
			'email_secondary'	=>	$param['email_secondary'],
			'phone_work'		=>	$param['localphone'],
			'national_id'		=>	$param['nationalid'],
			'phone_mobile'		=>	$param['localcell'],
			//TA: there is no 'phone_mobile_2' column in PERSON table -> link it 'phone_home' column 
			//'phone_mobile_2'	=>	$param['localcell2'],
			'phone_home'	=>	$param['localcell2'],
			'national_id'		=>	$param['nationalid'],
			'custom_field1'	=>	$param['custom_field1'], //TA: added 7/22/2014
			'custom_field2'	=>	$param['custom_field2'], //TA: added 7/22/2014
			'custom_field3'	=>	$param['custom_field3'], //TA: added 7/22/2014
			'marital_status'	=>	$param['marital_status'], //TA: added 7/22/2014
			'spouse_name'	=>	$param['spouse_name'], //TA: added 7/22/2014

			//'home_location_id'=>"$param[city]"
		);

		$db->update('person',$data,"id = '".$param['id']."'");
		return $data;
	}

	public function UpdateStudentCohort($param){
		$db = $this->dbfunc();
		// GETTING STUDENT RECORD
		$select = $this->dbfunc()->select()
			->from('student')
			->where('personid = ?',$param['id']);
		$row = $this->dbfunc()->fetchAll($select);
		$studentid = $row[0]['id'];

		# DETERMINING VALUES TO USE FOR JOIN/DROP DATA

		# CHECKING ON SEPARATION DATE SELECTION
		if ((isset ($param['separationdate'])) && (trim($param['separationdate']) != "")){
			# DATE IS SET, CONVERTING TO PROPER FORMAT
			$dropdate = date("Y-m-d", strtotime($param['separationdate']));
		} else {
			# DATE NOT SET
			$dropdate = "0000-00-00";

			# IF NO DROP DATE, NO DROP REASON CAN EXIST EITHER
			$param['separationreason'] = 0;
		}

		# CHECKING FOR JOIN DATES NEXT
		if ($param['cohortid'] != 0){
			# A COHORT HAS BEEN SELECTED - WE ARE USING THE DATES OF IT TO UPDATE THE COHORT > STUDENT LINK

			# RETRIEVING COHORT
			$select = $this->dbfunc()->select()
				->from('cohort')
				->where('id = ?',$param['cohortid']);
			$row = $this->dbfunc()->fetchAll($select);
			$cohort = $row[0];
			$joindate = $cohort['startdate'];
		} else {
			# NO COHORT SELECTED - JOIN DATE RESET TO BLANK
			$joindate = "0000-00-00";
		}

		if ((isset($param['separationreason'])) && (!is_numeric($param['separationreason'])) && (trim($param['separationreason']) != "")){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'reason' => $param['separationreason'],
				'reasontype' => 'drop',
			);
			$insertresult = $db->insert('lookup_reasons',$insert);
			$id = $db->lastInsertId();
			$param['separationreason'] = $id;
		}

		if ((isset($param['enrollmentreason'])) && (!is_numeric($param['enrollmentreason'])) && (trim($param['enrollmentreason']) != "")){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'reason' => $param['enrollmentreason'],
				'reasontype' => 'join',
			);
			$insertresult = $db->insert('lookup_reasons',$insert);
			$id = $db->lastInsertId();
			$param['enrollmentreason'] = $id;
		}


		# DETERMINING IF A LINK ALREADY EXISTS BETWEEN STUDENT AND A COHORT
		$select = $this->dbfunc()->select()
			->from('link_student_cohort')
			->where('id_student = ?',$studentid);
		$result = $this->dbfunc()->fetchAll($select);
		if (count ($result) == 0){
			# LINK DOES NOT EXIST YET. CREATE LINK

			$link = array(
				'id_cohort'			=>	$param['cohortid'],
			    'id_student'		=>	$studentid,
			    'joindate'			=>	$joindate,
			    'dropdate'			=>	$dropdate,
			    'joinreason'		=>	($param['enrollmentreason'] ? $param['enrollmentreason'] : 0),
			    'dropreason'		=>	($param['separationreason'] ? $param['separationreason'] : 0),
			);

			$rowArray = $db->insert("link_student_cohort",$link);
			$id = $db->lastInsertId();

			$helper = new helper();
			$helper->updatePersonInstitution("student",$studentid,$param['cohortid']);

			return $id;
		} else {
			# LINK EXISTS - UPDATE

			# RETRIEVING LINK ROW
			$row = $result[0];
			$linkid = $row['id'];

			// UPDATING ADDRESS ROW
			$db = $this->dbfunc();
			$link = array(
				'id_cohort'			=>	$param['cohortid'],
			    'joindate'			=>	$joindate,
			    'dropdate'			=>	$dropdate,
			    'joinreason'		=>	$param['enrollmentreason'],
			    'dropreason'		=>	$param['separationreason'],
			);

			$helper = new helper();
			$helper->updatePersonInstitution("student",$studentid,$param['cohortid']);

			$db->update('link_student_cohort',$link,"id = '".$linkid."' AND id_student = " . $studentid);
			return $link;
		}

	}

	public function UpdateStudent($param){

		$db = $this->dbfunc();
		$enroll = $param['enrollmentdate'];
		$separate = $param['separationdate'];
		$enrolldate = date("Y-m-d",strtotime($enroll));

		if (trim($separate) != ""){
			$separatedate = date("Y-m-d",strtotime($separate));
		} else {
			$separatedate = "0000-00-00";
		}

		if ((isset($param['studenttype'])) && (!is_numeric($param['studenttype'])) && (trim($param['studenttype']) != "")){
			# ADDED A NEW INSTITUTE TYPE
			$insert = array (
				'studenttype' => $param['studenttype'],
			);
			$insertresult = $db->insert('lookup_studenttype',$insert);
			$id = $db->lastInsertId();
			$param['studenttype'] = $id;
		}

		# LOCAL GEO
		$param1 = $param['local-geo1'] ? $param['local-geo1'] : 0;
		$param2 = $param['local-geo2'] ? $param['local-geo2'] : 0;
		if (strpos($param2, "_")){
			$param2 = explode("_", $param2);
			$param2 = $param2[count($param2) - 1];
		}
		$param3 = $param['local-geo3'] ? $param['local-geo3'] : 0;
		if (strpos($param3, "_")){
			$param3 = explode("_", $param3);
			$param3 = $param3[count($param3) - 1];
		}

		# POST SCHOOL GEO
		$param4 = $param['postgeo1'] ? $param['postgeo1'] : 0;
		$param5 = $param['postgeo2'] ? $param['postgeo2'] : 0;
		if (strpos($param5, "_")){
			$param5 = explode("_", $param5);
			$param5 = $param5[count($param5) - 1];
		}
		$param6 = $param['postgeo3'] ? $param['postgeo3'] : 0;
		if (strpos($param6, "_")){
			$param6 = explode("_", $param6);
			$param6 = $param6[count($param6) - 1];
		}
		
		//TA: added 7/17/2014
		$hscomldate=date("Y-m-d", strtotime($param['hscomldate']));
		$schoolstartdate=date("Y-m-d", strtotime($param['schoolstartdate']));

		$student = array(//'nationalid'=>"$param[nationalid]",
			//'nationality'=>"$param[nationality]",
			//'studenttype'=>"$param[studenttype]",
			'personid'			=>	$param['id'],
			'studentid'			=>	$param['studentid'],
			'nationalityid'		=>	$param['nationality'],
			'studenttype'		=>	$param['studenttype'],
			'isgraduated'		=>	$param['graduated'],
			'yearofstudy'		=>	$param['yearofstudy'],
			'advisorid'			=>	$param['tutoradvisor'],
			'geog1'				=>	$param1,
			'geog2'				=>	$param2,
			'geog3'				=>	$param3,
			'cadre'				=>	$param['cadre'],
			'comments'			=>	$param['comments'],
			'postgeo1'			=>	$param4,
			'postgeo2'			=>	$param5,
			'postgeo3'			=>	$param6,
			'postaddress1'		=>	$param['postaddress1'],
			'postfacilityname'	=>	$param['postfacilityname'],
			'hscomldate'	=>	$hscomldate, //TA: added 7/17/2014
			'lastinstatt'	=>	$param['lastinstatt'], //TA: added 7/17/2014
			'schoolstartdate' => $schoolstartdate, //TA: added 7/17/2014
			'equivalence'	=>	$param['equivalence'], //TA: added 7/17/2014
			'lastunivatt'	=>	$param['lastunivatt'], //TA: added 7/17/2014
			'personincharge'	=>	$param['personincharge'], //TA: added 7/17/2014
			'emergcontact'	=>	$param['emergcontact'], //TA: added 7/18/2014
		);

		$db->update('student',$student,"personid = '".$param['id']."'");
		$db->getProfiler()->setEnabled(true);
		return $student;
	}

	public function UpdatePermanentAddress($param){

		// GETTING STUDENT RECORD
		$select = $this->dbfunc()->select()
			->from('student')
			->where('personid = ?',$param['id']);
		$row = $this->dbfunc()->fetchAll($select);
		$studentid = $row[0]['id'];

		// DETERMINING IF A LINK EXISTS
		$select = $this->dbfunc()->select()
			->from('link_student_addresses')
			->where('id_student = ?',$studentid);
		$result = $this->dbfunc()->fetchAll($select);

		$param1 = $param['permanent-geo1'] ? $param['permanent-geo1'] : 0;
		$param2 = $param['permanent-geo2'] ? $param['permanent-geo2'] : 0;
		if (strpos($param2, "_")){
			$param2 = explode("_", $param2);
			$param2 = $param2[count($param2) - 1];
		}
		$param3 = $param['permanent-geo3'] ? $param['permanent-geo3'] : 0;
		if (strpos($param3, "_")){
			$param3 = explode("_", $param3);
			$param3 = $param3[count($param3) - 1];
		}
		

		//TA:15: fixed bug to edit student info (add address in DB is not NULL constrain)
		if (count ($result) == 0){
			# ADDING ADDRESS RECORD
			$db = $this->dbfunc();
			$address = array(
				'id_addresstype'	=>	1,
				'address1'			=>	$param['permanent-address1'] ? $param['permanent-address1'] : "",
				'address2'			=>	$param['permanent-address2'] ? $param['permanent-address2'] : "",
				'city'				=>	$param['permanent-city'] ? $param['permanent-city'] : "",
				'postalcode'		=>	$param['permanent-postalcode'] ? $param['permanent-postalcode'] : "",
				'id_geog1'			=>	$param1,
				'id_geog2'			=>	$param2,
				'id_geog3'			=>	$param3,
			);

			$rowArray = $db->insert("addresses",$address);
			$id = $db->lastInsertId();

			# LINKING TO STUDENT RECORD
			$db = $this->dbfunc();
			$linkrec = array(
				'id_address'		=>	$id,
				'id_student'		=>	$studentid,
			);
			$rowArray = $db->insert("link_student_addresses",$linkrec);
			$id = $db->lastInsertId();
			return $address;
		} else {
			// LINK EXISTS - UPDATE ADDRESS

			// RETRIEVING LINK ROW
			$row = $result[0];
			$addressid = $row['id_address'];

			$db = $this->dbfunc();
			$profiler = $db->getProfiler();
			$profiler->setEnabled(true);

			// UPDATING ADDRESS ROW
			$address = array(
				'address1'			=>	$param['permanent-address1'],
				'address2'			=>	$param['permanent-address2'],
				'city'				=>	$param['permanent-city'],
				'postalcode'		=>	$param['permanent-postalcode'],
				'id_geog1'			=>	$param1,
				'id_geog2'			=>	$param2,
				'id_geog3'			=>	$param3,
			);

			$db->update('addresses',$address,"id = '".$addressid."' AND id_addresstype = 1");

			return $address;
		}
	}

	public function UpdateFunding($param){
		// GETTING STUDENT RECORD
		$select = $this->dbfunc()->select()
			->from('student')
			->where('personid = ?',$param['id']);
		$row = $this->dbfunc()->fetchAll($select);
		$studentid = $row[0]['id'];


		if (!isset ($param['funding'])){
			# DELETE ALL ENTRIES
			$query = "DELETE FROM link_student_funding WHERE studentid  = " . $studentid;
			$this->dbfunc()->query($query);
		} else {
			# ADD / UPDATE ENTRIES
			$ids = array();
			foreach ($param['funding'] as $key=>$value){
				if ($param['fundingamount'][$key] != ""){
					$ids[] = $key;

					$query = "SELECT * FROM link_student_funding WHERE studentid = " . $studentid . " AND fundingsource = " . $key;
					$stmt = $this->dbfunc()->query($query);
					$result = $stmt->fetchAll();

					if (count ($result) > 0){
						$row = $result[0];
						$query = "UPDATE link_student_funding SET fundingamount = " . $param['fundingamount'][$key] . " WHERE id = " . $row['id'];
						$this->dbfunc()->query($query);
					} else {
						$query = "INSERT INTO link_student_funding SET studentid = " . $studentid . ", fundingsource = " . $key . ", fundingamount = '" . $param['fundingamount'][$key] . "'";
						$this->dbfunc()->query($query);
					}
				}
			}

			if (count ($ids) > 0){
				# REMOVING OTHER ENTRIES
				$query = "DELETE FROM link_student_funding WHERE studentid = " . $studentid . " AND fundingsource NOT IN (" . implode(",",$ids) . ")";
				$this->dbfunc()->query($query);
			}
		}
	}

	/* public function getstudent($pupiladd){

		 $select = $this->dbfunc()->select()
		->from($this->_city);
		$result = $this->dbfunc()->fetchAll($select);
		//echo $select->__toString();
		return $result;
	 }*/

	public function addfunding($param) {
		$db = $this->dbfunc();
		$fundingsource = $param['fundingsource'];
		$fundingamount = $param['amount'];

		$funding = array(
			'fundingsource'=>$fundingsource,
		    'fundingamount'=>$fundingamount,
			'studentid'=>$param[studentid]);

		//print_r($funding);
		$rowArray = $db->insert($this->_funding,$funding);
		$id = $db->lastInsertId();
		return $id;
	}

	public function getStudentFunding($sid){
		$db = $this->dbfunc();
		$select = $db->select()
			->from('link_student_funding')
			->where('studentid = ?',$sid);
		$result = $this->dbfunc()->fetchAll($select);
		return $result;

	}
//TA:51 10/05/2015
	public function DeleteStudent($param){
	    $person_id = $param['id'];
	    $student_primary_id = $param['sid'];
	    
	    // remove student
	    $sql = "DELETE FROM student WHERE personid = {$person_id}";
	    $result = $this->dbfunc()->query($sql);
	    
	    // set person as deleted
	    $sql = "UPDATE person SET is_deleted=1 where id = {$person_id}";
	    $result = $this->dbfunc()->query($sql);
	
	    // remove student cohort link
	    $sql = "DELETE FROM link_student_cohort WHERE id_student = {$student_primary_id}";
	    $result = $this->dbfunc()->query($sql);
	    
	    // remove student classes link
	    $sql = "DELETE FROM link_student_classes WHERE studentid = {$student_primary_id}";
	    $result = $this->dbfunc()->query($sql);
	    
	    // remove student practicums link
	    $sql = "DELETE FROM link_student_practicums WHERE studentid = {$person_id}";
	    $result = $this->dbfunc()->query($sql);
	    
	    // remove student licenses link
	    $sql = "DELETE FROM link_student_licenses WHERE studentid = {$student_primary_id}";
	    $result = $this->dbfunc()->query($sql);
	
	    // remove student licenses link
	    $sql = "DELETE FROM link_student_funding WHERE studentid = {$student_primary_id}";
	    $result = $this->dbfunc()->query($sql);
	    
	    // remove student licenses link
	    $sql = "DELETE FROM link_student_addresses WHERE id_student = {$student_primary_id}";
	    $result = $this->dbfunc()->query($sql);
	    
	    return true;
	}

 }

?>