<?php
require_once('ITechTable.php');
require_once('Helper.php');

class Cohortedit extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'cohort';
	protected $_reqirement = 'graduation_requirements';
	protected $_practicum = 'practicum';
	protected $_exams = 'exams';
	protected $_classes = 'classes';
	protected $_link = 'link_student_cohort';

	public function addCohort($param) {

		$startdate = date("Y-m-d",strtotime($param['startdate']));
		$graduationdate = date("Y-m-d",strtotime($param['graddate']));
			
		$insert = array(
			'cohortname'	 => $param['cohortname'],
			'startdate'		 => $startdate,
			'graddate'		 => $graduationdate,
			'degree'		 => $param['degree'],
			'cadreid'		 => $param['cadre'],
			'institutionid'	 => $param['institution'],
		);
		
		$rowArray = $this->dbfunc()->insert($this->_name,$insert);
		$id = $this->dbfunc()->lastInsertId(); 
		return $id;
	}


	public function EditCohort($cohortid) {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter (); 
		$select = $db->query("select * from cohort where id = '$cohortid'");
		$row = $select->fetch();
		return $row;	
	}
	
	public function Listcohort($fetchlist) {
		$db = $this->dbfunc();
		$select = $db->query("select * from cohort ");
		$row = $select->fetchAll();
		return $row;	
	}
	 
	public function UpdateCohort($param){
		$db = $this->dbfunc();
		if (!is_numeric($param['degreeinfo'])){
			# ADDED A NEW DEGREE
			$insert = array (
				'degree' => $param['degreeinfo'],
			);
			$insertlink = $db->insert('lookup_degrees',$insert);
			$id = $db->lastInsertId();
			$param['degreeinfo'] = $id;
		}

		$start = $param['cohortstart'];
		$grad = $param['cohortgrade'];
		$startdate = (($start != "") && ($start != "1969-12-31") ? date("Y-m-d",strtotime($start)) : "0000-00-00");
		$graddate = (($grad != "") && ($grad != "1969-12-31") ? date("Y-m-d",strtotime($grad)) : "0000-00-00");
		
		$db = $this->dbfunc(); 		 
		$data = array(
			'startdate'		 =>	$startdate,
			'graddate'		 =>	$graddate,
			'degree'		 =>	$param['degreeinfo'],
			'cohortid'		 =>	$param['cohortid'],
			'cohortname'	 =>	$param['cohortname'],
			'cadreid'		 => $param['cadre']
		);
		
		// update associated student records with new cadre id
		//$cadre_sql = "UPDATE student SET cadre = (SELECT cadreid FROM cohort WHERE id = {$cid}) WHERE id = ";
		//$db->query($cadre_sql);
		$select = $db->query("SELECT id_student FROM link_student_cohort WHERE id_cohort = {$param['id']}");
		$row = $select->fetchAll();
		foreach($row as $student){
			$db->query("UPDATE student SET cadre = {$param['cadre']} WHERE id = {$student['id_student']}");
		}
		
		$db->update('cohort',$data,'id = ' . $param['id']);
		return $data;
	}
	 
	public function DeleteCohort($param){
		$cohort_id = $param['cohortid'];
		
		// remove cohort
		$sql = "DELETE FROM cohort WHERE id = {$cohort_id}";
		$result = $this->dbfunc()->query($sql);
		
		// remove cohort class link
		$sql = "DELETE FROM link_cohorts_classes WHERE cohortid = {$cohort_id}";
		$result = $this->dbfunc()->query($sql);
		
		// remove student cohort link
		$sql = "DELETE FROM link_student_cohort WHERE id_cohort = {$cohort_id}";
		$result = $this->dbfunc()->query($sql);
		
		return true;
	}
	
	public function Cohortsearch($param) {
		$where = array();
		$joins = array();
		$selects = array();
		$joinstudent = false;
		$jointutor = false;
		
		$joininstitution = false;
		$joincadre = false;

		$helper = new Helper();
		$institutions = $helper->getUserInstitutions($helper->myid(),false);
		
		if ((is_array($institutions)) && (count($institutions) > 0)){
			$insids = implode(",", $institutions);
			$where[] = "c.institutionid IN (" . $insids . ")";
		}
		
		$cadres = $helper->getUserPrograms($helper->myid(),false);
		
		if ((is_array($cadres)) && (count($cadres) > 0)){
		    $insids = implode(",", $cadres);
		    $where[] = "c.cadreid IN (" . $insids . ")";
		}
 
		foreach ($param as $key =>$value){
			if (trim ($value) != ""){
				switch ($key){
					case "cohortid":
						$where[] = "c.cohortid LIKE '%" . addslashes($value) . "%'";
					break;
					case "cohortname":
						$where[] = "c.cohortname LIKE '%" . Addslashes($value) . "%'";
					break;
					case "startdate":
						$where[] = "c.startdate = '" . addslashes(date("Y-m-d", strtotime($value))) . "'";
					break;
					case "graddate":
						$where[] = "c.graddate = '" . addslashes(date("Y-m-d", strtotime($value))) . "'";
					break;
					case "institution":
						$where[] = "i.id = " . addslashes($value);
						$joins[] = "INNER JOIN institution i ON i.id = c.institutionid";

						# MARK THIS SO WE KNOW WE DON'T NEED AN OPTIONAL LEFT JOIN TO RETRIEVE THE INSTITUTION NAME
						$joininstitution = true;
					break;
					case "cadre":
						$where[] = "ca.id = " . addslashes($value);
						$joins[] = "INNER JOIN cadres ca ON ca.id = c.cadreid";

						# MARK THIS SO WE KNOW WE DON'T NEED AN OPTIONAL LEFT JOIN TO RETRIEVE THE CADRE NAME
						$joincadre = true;
					break;
				}
			}
		}

		$selects[] = "i.institutionname";
		$selects[] = "ca.cadrename";

		# IF NO CADRE IS JOINED YET WE DO A LEFT JOIN
		if (!$joincadre){
			$joins[] = "LEFT JOIN cadres ca ON ca.id = c.cadreid";
		}

		# IF NO CADRE IS JOINED YET WE DO A LEFT JOIN
		if (!$joininstitution){
			$joins[] = "LEFT JOIN institution i ON i.id = c.institutionid";
		}

		# STARTING QUERY
		$query = "SELECT c.*";

		# INCLUDING OPTIONAL SELECTS
		if (count($selects) > 0){
			$query .= ", " . implode(", ", $selects);
		}

		# CONTINUING BASE QUERY
		$query .= " FROM cohort c ";

		# ADDING JOINS
		if (count ($joins) > 0){
			$query .= implode ("\n", $joins);
		}

		# ADDING WHERE CLAUSES
		if (count ($where) > 0){
			$query .= " WHERE " . implode (" AND ", $where);
		}

		# ADDING ORDERING
		$query .= " ORDER BY startdate, graddate, cohortname, cohortid";

		#die ($query . "<BR><BR><BR>");

		# QUERYING
		$select = $this->dbfunc()->query($query);

		# RETRIEVING ALL DATA
		$result = $select->fetchAll();

		$helper = new Helper();
		$output = array();
		foreach ($result as $row){
			$students = $helper->getCohortStudents($row['id'],"graduating");
			$item = array();
			foreach ($row as $key =>$value){
				$item[$key] = $value;
			}
			$item['studentcount'] = count($students);
			$output[] = $item;
		}

		return $output;
	} 
	
	public function getAllStudents($cid = false, $unassigned_only = false){
		
		if($unassigned_only){
			
			$select = $this->dbfunc()->select()
				->from(array('p' => 'person'),
						array('id','first_name','last_name','gender','birthdate'))
				->join(array('s' => 'student'),
						's.personid = p.id',
						array("sid"=>'id'))
				->order('p.first_name','p.last_name')
				->where("s.id NOT IN (SELECT id_student FROM link_student_cohort WHERE id_cohort != {$cid})");
			
		} else {
			
			if ($cid !== false){
				$select = $this->dbfunc()->select()
					->from(array('p' => 'person'),
							array('id','first_name','last_name','gender','birthdate'))
					->join(array('s' => 'student'),
							's.personid = p.id',
							array("sid"=>'id'))
					->join(array('l' => 'link_student_cohort'),
							'l.id_student = s.id',
							array('isgraduated','dropdate','joindate'))
					->where('l.id_cohort = ?',$cid)
					->order('p.first_name','p.last_name');
			} else {
				$select = $this->dbfunc()->select()
					->from(array('p' => 'person'),
							array('id','first_name','last_name','gender','birthdate'))
					->join(array('s' => 'student'),
							's.personid = p.id',
							array("sid"=>'id'))
					->order('p.first_name','p.last_name');
			}
			
		}
		
		$result = $this->dbfunc()->fetchAll($select);
		return $result;
	}

/*
	public function getStudentsStart($cid){
		$select = $this->dbfunc()->select()
			->from('link_student_cohort',
				array("totalcount" => "COUNT(*)"))
			->where('id_cohort = ?',$cid);
		$row = $this->dbfunc()->fetchAll($select);
		return $row[0]['totalcount'];
	}

	public function getStudentsDrop($cid){
		$select = $this->dbfunc()->select()
			->from('link_student_cohort',
				array("totalcount" => "COUNT(*)"))
			->where('id_cohort = ?',$cid)
			->where("dropdate <> '0000-00-00'")
			->where("isgraduated = 0");
		$row = $this->dbfunc()->fetchAll($select);
		return $row[0]['totalcount'];
	}

	public function getStudentsGrad($cid){
		$select = $this->dbfunc()->select()
			->from('link_student_cohort',
				array("totalcount" => "COUNT(*)"))
			->where('id_cohort = ?',$cid)
			->where("dropdate = '0000-00-00'")
			->orWhere("isgraduated = 1");
		$row = $this->dbfunc()->fetchAll($select);
		return $row[0]['totalcount'];
	}


*/










/* ---------------------------------------------- */
/* ---------------------------------------------- */
/* ---------------------------------------------- */
/* ---------------------------------------------- */
/* ---------------------------------------------- */
/* ---------------------------------------------- */





	 public function AddRequirement($cohortid,$param) {
	 
	 $insert = array('cohortid' => $cohortid,
	 			 'requirement' => "$param[requirement]",
 'requirementyear' => "$param[requirementyear]"
				 );
	
	$rowArray = $this->dbfunc()->insert($this->_reqirement,$insert);
	 }
	 
	 public function ListRequirement($fetchrequire) {
	 $db = $this->dbfunc();
 	 $select = $db->query("select * from graduation_requirements ");
	 $row = $select->fetchAll();
	 return $row;	
	 }
	 
	 public function ViewRequirement($requireid){
		 
	 $db = $this->dbfunc();
 	 $select = $db->query("select * from graduation_requirements where id = $requireid");
	 $row = $select->fetch();
	 return $row;	
	 }
	 
	 public function UpdateRequirement($requireid,$param){
		 
			$db = $this->dbfunc(); 		 
		 $data = array('requirement' => "$param[requirement]",
 'requirementyear' => "$param[requirementyear]" );
					 
		 $db->update('graduation_requirements',$data,'id = '.$requireid); 
		 return $data;
	 }
	
	 
	 public function AddPracticum($cohortid,$param) {
	 
	 $insert = array('cohortid' => $cohortid,
	 'practicumname' =>"$param[name]",	
	 			 'hourscompleted' => "$param[hourscompleted]",
 				'hoursrequired' => "$param[hoursrequired]"
				 );
	
	$rowArray = $this->dbfunc()->insert($this->_practicum,$insert);
	 }
	 
	 public function ListPracticum($fetchpracticum) {
	 $db = $this->dbfunc();
 	 $select = $db->query("select * from practicum");
	 $row = $select->fetchAll();
	 return $row;	
	 }
	 
	public function ViewPracticum($pracid){
		$db = $this->dbfunc();
		$select = $db->query("select * from practicum where id = $pracid");
		$row = $select->fetch();
		return $row;	
	}
	 
	public function UpdatePracticum($pracid,$param){
	
		$db = $this->dbfunc(); 		 
		$data = array(
			'practicumname'		 => $param['name'],
			'hourscompleted'	 => $param['hourscompleted'],
			'hoursrequired' 	 => $param['hoursrequired']
		);
		
		$db->update('practicum',$data,'id = '.$pracid); 
		return $data;
	}
	 	 
	 /*public function UpdatePracticum($param){
		 
			$db = $this->dbfunc(); 		 
		 $data = array('startdate' =>"$startdate",
		 				 'graddate' =>"$graddate",
						 'degree' =>"$param[degreeinfo]" );
					 
		 $db->update('cohort',$data,'id = '.$param[id]); 
		 return $data;
	 }*/
	 
	 
	public function AddExams($cohortid,$param) {
		$date = $param[examdate]; 
		$examdate = date("Y-m-d",strtotime($date));
		
		$insert = array(
			'cohortid'	 => $cohortid,
			'examname'	 => $param['exam'],
			'examdate'	 => $examdate,
			'grade' 	 => $param['grade'],
		);
		
		$rowArray = $this->dbfunc()->insert($this->_exams,$insert);
	}
	 
	public function ListExams($fetchexams) {
		$db = $this->dbfunc();
		$select = $db->query("select * from exams");
		$row = $select->fetchAll();
		return $row;	
	}
	 
	public function ViewExams($examid){
		$db = $this->dbfunc();
		$select = $db->query("select * from exams where id = $examid");
		$row = $select->fetch();
		return $row;	
	}
	 
	public function UpdateExams($examid,$param){
		$date = $param[examdate]; 
		$examdate = date("Y-m-d",strtotime($date));
		
		$db = $this->dbfunc(); 		 
		$data = array(
			'examname'	 => $param['exam'],	
			'examdate'	 => $examdate,
			'grade' 	 => $param['grade'],
		);
		
		$db->update('exams',$data,'id = '.$examid); 
		return $data;
	}
	 
	 
	public function AddClasses($cohortid,$param) {
		$date = $param[classdate]; 
		$classdate = date("Y-m-d",strtotime($date));

		$insert = array('cohortid' => $cohortid,
			'classname' =>"$param[name]",	
			'startdate' => $classdate
		);
		$rowArray = $this->dbfunc()->insert($this->_classes,$insert);
	}
	 
	public function ListClasses() {
		$db = $this->dbfunc();
		$query = "SELECT c.*, p.first_name, p.last_name
			FROM classes c
			INNER JOIN tutor t ON t.id = c.instructorid
			INNER JOIN person p ON t.personid = p.id ORDER BY c.classname, p.first_name, p.last_name";
		$select = $db->query($query);
		$row = $select->fetchAll();
		return $row;	
	}
	 
	public function ListCurrentClasses($cid) {
		$db = $this->dbfunc();
		$query = "SELECT c.*, p.first_name, p.last_name
			FROM classes c
			INNER JOIN link_cohorts_classes lcc ON lcc.classid = c.id
			INNER JOIN tutor t ON t.id = c.instructorid
			INNER JOIN person p ON t.personid = p.id 
			WHERE lcc.cohortid = '" . $cid . "'
			ORDER BY c.classname, p.first_name, p.last_name";
		$select = $db->query($query);
		$row = $select->fetchAll();
		return $row;	
	}
	 
	public function ViewClasses($classid){
		$db = $this->dbfunc();
		$select = $db->query("select * from classes where id = $classid");
		$row = $select->fetch();
		return $row;	
	}
	 
	public function UpdateClasses($classid,$param){
		 
		$date = $param[classdate]; 
		$classdate = date("Y-m-d",strtotime($date));
		 
		$db = $this->dbfunc(); 		 
		$data = array('cohortid' => $cohortid,
			'classname' =>"$param[name]",	
			'startdate' => $classdate );
		
		$db->update('classes',$data,'id = '.$classid); 
		return $data;
	}

	public function getLicenses($cid){
		$db = $this->dbfunc(); 		 
		$select = $db->query("select * from licenses WHERE cohortid = " . $cid . " ORDER BY licensedate DESC");
		$result = $select->fetchAll();
		return $result;	
	}


 }
 
?>