<?php
require_once('ITechTable.php');
require_once('Helper.php');

class Dashview extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'institution';

	public function fetchdetails() {
		$output = array();

		$helper = new Helper();
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();

		// GETTING CURRENT USER ID
		$uid = $helper->myid();

		// GETTING INSTITUTION IDS
		$ids = $helper->getUserInstitutions($uid,false);
		if (count ($ids) > 0){
			// LIMITING INSTITUTIONS TO THE ONES ENABLED FOR THIS USER
			$select = $db->select()
				->from($this->_name)
				->where("id IN (" . implode(",", $ids) . ")");
		} else {
			// SHOWING ALL INSTITUTIONS
			$select = $db->select()
				->from($this->_name);
		}

		$result = $db->fetchAll($select);
		foreach ($result as $row){
			$students	= $helper->getInstitutionStudents($row['id'],"all","count");
			$grads		= $helper->getInstitutionStudents($row['id'],"graduated","count");
			$dropped	= $helper->getInstitutionStudents($row['id'],"dropped","count");
			$tutors		= $helper->getInstitutionTutorCount($row['id']);
			$active_students = $students - $dropped - $grads; //TA:85 active students

			$output[] = array(
				"col1" => $row['institutionname'],
				"col2" => $students,
				"col3" => $tutors,
			    //TA:85 fix bug use active students to calulate ratio
				"col4" => ((is_numeric($tutors)) && ($tutors > 0) && (is_numeric($active_students)) && ($active_students > 0)) ? "1 : " . round(($active_students / $tutors),2) : "N/A",
				"col5" => $grads,
				"col6" => $dropped,  //TA:85 fix bug
			    "col7" => $active_students, //TA:85 active students
				"link" => Settings::$COUNTRY_BASE_URL . "/institution/institutionedit/id/" . $row['id'],
				"type" => 1
			);
			$cohorts = $this->fetchCohorts($row['id']);
			foreach ($cohorts as $cohort){
				$output[] = array(
					"col1" => $cohort['name'],
					"col2" => $cohort['count'],
					"col3" => "",
					"col4" => "",
					"col5" => "",
					"col6" => "",
				    "col7" => $cohort['active_student'], //TA:85 get active student for each cohort
					"link" => Settings::$COUNTRY_BASE_URL . "/cohort/cohortedit/id/" . $cohort['id'],
					"type" => 2
				);
			}
		}
		return $output;
	}

	public function fetchCohorts($iid){
		$output = array();

		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$select = $db->select()
			->from("cohort")
			->where("institutionid = ?",$iid);
		$result = $db->fetchAll($select);
		foreach ($result as $row){
			$output[] = array(
				"id" => $row['id'],
				"name" => $row['cohortname'],
				"count" => $this->fetchCohortStudents($row['id']),
			    "active_student" => $this->fetchCohortActiveStudents($row['id']) //TA:85 get active student for each cohort
			);
		}
		return $output;
	}

	public function fetchCohortStudents($cid){
		$output = array();
		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$select = $db->select()
			->from("link_student_cohort")
			->where("id_cohort = ?",$cid);
		$result = $db->fetchAll($select);
		return count($result);
	}
	
	//TA:85 get active student for each cohort
	public function fetchCohortActiveStudents($cid){
	    $output = array();
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
	    $result = $db->fetchAll("SELECT * FROM link_student_cohort 
join cohort on cohort.id=link_student_cohort.id_cohort
WHERE dropdate = '0000-00-00' 
and cohort.graddate >= now() 
and cohort.startdate <= now()
and id_cohort = $cid");
	    return count($result);
	}
}
?>
