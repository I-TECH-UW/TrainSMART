<?php
require_once('ITechTable.php');
require_once('Helper.php');

class Peoplefind extends ITechTable
{
	//protected $_primary = 'id';
	protected $_name = 'person';

	public function peoplesearch($param) {
  
    $query = $this->buildquery($param);
    $select = $this->dbfunc()->query($query);
    return $select->fetchAll();

  } 
  
  public function buildquery($param){
    $sql = '';
    $where = array();
    
    $studentLink = Settings::$COUNTRY_BASE_URL . "/studentedit/personview/id/";
    $tutorLink = Settings::$COUNTRY_BASE_URL . "/tutoredit/tutoredit/id/";
    
    //TA:81 filter people by user id login
    $str_pid = " p.id ";
    $str_inst = " IS NOT NULL ";
    $helper = new Helper();
    $institutions = $helper->getUserInstitutions($helper->myid(), false);
    if ((is_array($institutions)) && (count($institutions) > 0)) {
        $insids = implode(",", $institutions);
        $str_pid = " distinct(p.id) ";
        $str_inst = ' in (' . $insids . ') ';
        $where_inst = ' AND (p.id in (
select personid from tutor where personid in
(select id_tutor from link_tutor_institution where id_institution in
(' . $insids . ')))
or
id in (
select personid from student where id in(
select id_student from link_student_cohort where id_cohort in (
select id from cohort where institutionid in
(' . $insids . '))))
or
id in (
select personid from tutor where personid not in
(select id_tutor from link_tutor_institution))
or
id in (
select personid from student where id not in
(select id_student from link_student_cohort))) ';
    }
    
    // There is still room for optimization here (only include the join that is
    // needed based on requested person type- student, tutor, etc.), but this is
    // so vastly superior to generating 3 queries per row of main results!
    
    // SELECT CLAUSE //TA: fixed bug with fetching person's gender 7/30/2014
    switch ($param['type']){
      case "every":
        // If person was a student, then became a tutor- always show as tutor
        //TA:36 add cohort id to the returned fields
        $sql = 'SELECT '. $str_pid. ', first_name, last_name, gender,
          CASE
            WHEN tutor_type IS NOT NULL THEN tutor_type
            WHEN student_type IS NOT NULL THEN student_type
            ELSE \'person\'
          END AS type,
          CASE 
            WHEN tutor_id IS NOT NULL THEN tutor_link
            WHEN student_id IS NOT NULL THEN student_link
          END AS link,
          CASE 
            WHEN tutor_id IS NOT NULL THEN tutor_institutionname
            WHEN student_id IS NOT NULL THEN student_institutionname
            ELSE NULL
          END AS institutionname,
        		cohort_id,
        		cadre,
        		student_inst_id,
        		tutor_inst_id,
        		facility_id,
          cohort '; // will have dup rows if student in multiple cohorts over time
        break;
      case "student":
        $sql = 'SELECT p.id, first_name, last_name, gender, 
          student_type AS type,
          student_link AS link,
          student_institutionname AS institutionname,
        cohort_id,
        cadre,
        		student_inst_id,
        		facility_id,
          cohort '; // will have dup rows if student in multiple cohorts over time
        break;
      case "key":
      case "tutor":
        $sql = 'SELECT p.id, first_name, last_name, gender,
          tutor_type AS type,
          tutor_link AS link,
          tutor_institutionname AS institutionname,
        		tutor_inst_id,
        		facility_id,
          \'N/A\' AS cohort ';
        break;
    }    

    // FROM CLAUSE
    $sql .= ' FROM person p  '    ;
    
    // STUDENT JOIN
    if ($param['type'] == 'student' || $param['type'] == 'every') {
    	//TA:36 add cohort id to the returned fields, DROPPED condition corrected, add cadre, add institute, add facility
      $sql .= '
        LEFT JOIN (
          SELECT 
            \'student\' AS student_type,
            s.id AS student_id, 
            personid AS person_id, 
            CONCAT(\'' . $studentLink . '\', CAST(personid AS CHAR(20)) ) AS student_link,
            CASE 
              WHEN c.institutionid ' . $str_inst . ' THEN i2.institutionname 
              WHEN s.institutionid ' . $str_inst . ' THEN i1.institutionname
                  ELSE \'N/A\' 
            END AS student_institutionname,
            CASE 
              WHEN sc.id_cohort IS NOT NULL 
              THEN 
                CASE
                  WHEN dropdate IS NOT NULL AND dropdate != \'\' AND dropdate != \'0000-00-00\'
                  THEN CONCAT(c.cohortname,\' (DROPPED)\') 
                  ELSE c.cohortname
                END
              ELSE \'N/A\'
            END AS cohort,
            sc.id_cohort as cohort_id,
            cadre as cadre,
            		CASE 
              WHEN c.institutionid ' . $str_inst . ' THEN c.institutionid 
              WHEN s.institutionid ' . $str_inst . ' THEN s.institutionid 
            END AS student_inst_id
          FROM student s 
            LEFT JOIN link_student_cohort sc
              ON s.id = sc.id_student
            LEFT JOIN cohort c
              ON sc.id_cohort = c.id
            LEFT JOIN institution i1
              ON s.institutionid = i1.id
            LEFT JOIN institution i2
              ON c.institutionid = i2.id
        ) AS s
          ON p.id = s.person_id  
      ';
    }
    
    // TUTOR JOIN
    if ($param['type'] == 'key' || $param['type'] == 'tutor' || $param['type'] == 'every') {
      $sql .= '
        LEFT JOIN (
          SELECT
            CASE WHEN is_keypersonal = 0 THEN \'tutor\' ELSE \'key personal\' END AS tutor_type,
            t.id AS tutor_id, 
            personid AS person_id, 
            is_keypersonal,
            CONCAT(\'' . $tutorLink . '\', CAST(personid AS CHAR(20)) ) AS tutor_link,
            CASE 
              WHEN lti.id_institution ' . $str_inst . ' THEN i2.institutionname
              WHEN t.institutionid ' . $str_inst . ' THEN i1.institutionname
                  ELSE \'N/A\' 
            END AS tutor_institutionname,
            CASE
                WHEN lti.id_institution ' . $str_inst . ' THEN lti.id_institution
                WHEN t.institutionid ' . $str_inst . ' THEN t.institutionid
            END AS tutor_inst_id
          FROM tutor t
            LEFT JOIN link_tutor_institution lti
              ON t.id = lti.id_tutor
            LEFT JOIN institution i1
              ON t.institutionid = i1.id
            LEFT JOIN institution i2
              ON lti.id_institution = i2.id
        ) AS t
          ON p.id = t.person_id
      ';
    }

    // WHERE CLAUSES
    $where[] = ' p.is_deleted = 0 ';
    
    switch ($param['type']){
      case "every":
        $where[] = ' AND (student_id IS NOT NULL OR tutor_id IS NOT NULL) ';
      break;
      case "student":
        $where[] = ' AND student_id IS NOT NULL ';
      break;
      case "key":
        $where[] = ' AND is_keypersonal = 1 ';
      case "tutor":
        $where[] = ' AND tutor_id IS NOT NULL ';
      break;
    }  

    //TA:36 fixing bug
    if($param['firstname']){
    	$where[] = " AND first_name ='" . $param['firstname'] . "'";
    }
    if($param['lastname']){
    	$where[] = " AND last_name ='" . $param['lastname'] . "'";
    }
    if( $param['type'] == 'student' && $param['cohort']){
    	$where[] = " AND cohort_id =" . $param['cohort'];
    }
    if($param['cadre']){
    	$where[] = " AND cadre =" . $param['cadre'];
    }
    if($param['inst']){
    	if($param['type'] == 'tutor'){
    	    //TA:#254 show only active tutors for selected institution
    	 $where[] = " AND p.active='active' AND tutor_inst_id =" . $param['inst'];
    	}else if($param['type'] == 'student'){
    		$where[] = " AND student_inst_id =" . $param['inst'];
    	}
    }
    if($param['fact']){
    	$where[] = " AND facility_id =" . $param['fact'];
    }
    //
    
    $sql .= ' WHERE 1 = 1 AND ' ;
    foreach ($where as $whereClause) {
      $sql .= ' ' . $whereClause . ' ';
    }
    
    // ORDER BY CLAUSE
    $sql .= $where_inst . ' ORDER BY last_name, first_name;';
    
   // print $sql;
    return $sql;
    
  }
  
  
  public function peoplesearch_orig($param) {
		$helper = new Helper();
		$return = array();

		switch ($param['type']){
			case "every":
				$query = $this->buildquery($param,"student");
				$select = $this->dbfunc()->query($query);
				$result = $select->fetchAll();
				foreach ($result as $row){
					$newrow = array();
					foreach ($row as $key=>$value){
						$newrow[$key] = $value;
					}
					$newrow['type'] = "student";
					$newrow['link'] = Settings::$COUNTRY_BASE_URL . "/studentedit/personview/id/" . $row['id'];

					list ($cohort,$institution) = $helper->getCohortInstitution($row['subid'],"student");
					$newrow['cohort'] = $cohort;
					$newrow['institution'] = $institution;

					$return[] = $newrow;
				}

				$query = $this->buildquery($param,"tutor");
				$select = $this->dbfunc()->query($query);
				$result = $select->fetchAll();
				foreach ($result as $row){
					$newrow = array();
					foreach ($row as $key=>$value){
						$newrow[$key] = $value;
					}
					$newrow['type'] = isset($row['is_keypersonal']) && $row['is_keypersonal'] != 1 ? "tutor" : "key personal";
					$newrow['link'] = Settings::$COUNTRY_BASE_URL . "/tutoredit/tutoredit/id/" . $row['id'];

					list ($cohort,$institution) = $helper->getCohortInstitution($row['subid'],"tutor");
					$newrow['cohort'] = $cohort;
					$newrow['institution'] = $institution;

					$return[] = $newrow;
				}

			break;
			case "student":
				$query = $this->buildquery($param,"student");
				$select = $this->dbfunc()->query($query);
				$result = $select->fetchAll();
				foreach ($result as $row){
					$newrow = array();
					foreach ($row as $key=>$value){
						$newrow[$key] = $value;
					}
					$newrow['type'] = "student";
					$newrow['link'] = Settings::$COUNTRY_BASE_URL . "/studentedit/personview/id/" . $row['id'];

					list ($cohort,$institution) = $helper->getCohortInstitution($row['subid'],"student");
					$newrow['cohort'] = $cohort;
					$newrow['institution'] = $institution;

					$return[] = $newrow;
				}
#die ($query);
			break;
			case "key":
			case "tutor":
				$query = $this->buildquery($param,"tutor");
				$select = $this->dbfunc()->query($query);
				$result = $select->fetchAll();
				foreach ($result as $row){
					$newrow = array();
					foreach ($row as $key=>$value){
						$newrow[$key] = $value;
					}
					$newrow['type'] = isset($row['is_keypersonal']) && $row['is_keypersonal'] != 1 ? "tutor" : "key personal";
					$newrow['link'] = Settings::$COUNTRY_BASE_URL . "/tutoredit/tutoredit/id/" . $row['id'];

					//list ($cohort,$institution) = $helper->getCohortInstitution($row['subid'],"tutor");
					//$newrow['cohort'] = $cohort;
					//$newrow['institution'] = $institution;

					$return[] = $newrow;
				}
			break;
		}
		return $return;
	}

	public function buildquery_orig($param,$output){
		$where = array();
		$joins = array();
		$joinstudent = false;
		$jointutor = true;

    $linkBase = Settings::$COUNTRY_BASE_URL . "/tutoredit/tutoredit/id/";

    if ($output == 'student') {
      $linkBase = Settings::$COUNTRY_BASE_URL . "/studentedit/personview/id/";
      $jointutor = false;
    }


    

		foreach ($param as $key=>$value){
			if (trim ($value) != ""){
				switch ($key){
					case "firstname":
						$where[] = "p.first_name LIKE '%" . addslashes($value) . "%'";
					break;
					case "lastname":
						$where[] = "p.last_name LIKE '%" . addslashes($value) . "%'";
					break;
					case "cohort":
						if ($value != 0){
							if ($output == "student"){
								$where[] = "co.id = " . addslashes($value) . "";
								$joinstudent = true;
								$joins[] = "INNER JOIN link_student_cohort lsco ON lsco.id_student = s.id";
								$joins[] = "INNER JOIN cohort co ON co.id = lsco.id_cohort";
							} elseif ($output == "tutor"){
								$where[] = "co.id = " . addslashes($value) . "";
								$jointutor = true;
								$joins[] = "INNER JOIN link_tutor_institution lti ON lti.id_tutor = t.id";
								$joins[] = "INNER JOIN cohort co ON co.institutionid = lti.id_institution";
							}
						}
					break;
					case "cadre":
						if ($value != 0){
							if ($output == "student"){
								$where[] = "ca.id = " . addslashes($value) . "";
								$joinstudent = true;
								$joins[] = "INNER JOIN cadres ca ON ca.id = s.cadre";
							} elseif ($output == "tutor"){
								$where[] = "c.id = " . addslashes($value) . "";
								$jointutor = true;
								$joins[] = "INNER JOIN link_cadre_tutor lct ON lct.id_tutor = t.id";
								$joins[] = "INNER JOIN cadres c ON c.id = lct.id_cadre";
							}
						}
					break;
					case "inst":
						if ($value != 0){
							if ($output == "student"){
								$where[] = "i.id = " . addslashes($value) . "";
								$joinstudent = true;
#								$joins[] = "INNER JOIN link_student_institution lsi ON lsi.id_student = s.id";
								$joins[] = "INNER JOIN institution i ON i.id = s.institutionid";
							} elseif ($output == "tutor"){
								//$where[] = "i.institutionname LIKE '%" . addslashes($value) . "%'";
								$where[] = "i.id = " . addslashes($value) . "";
								$jointutor = true;
								$joins[] = "INNER JOIN link_tutor_institution lti ON lti.id_tutor = p.id";
								$joins[] = "INNER JOIN institution i ON i.id = lti.id_institution";
							}
						}
					break;
					case "fact":
						if ($value != 0){
							if ($output == "student"){
								$where[] = "f.id = " . addslashes($value) . "";
								$joinstudent = true;
								$joins[] = "INNER JOIN link_student_facility lsf ON lsf.id_student = s.id";
								$joins[] = "INNER JOIN facility f ON f.id = lsf.id_facility";
							}
						}
					break;
				}
			}
		}

		if ($joinstudent){
			$query = "SELECT p.*, s.id AS subid FROM person p ";
			$query .= " INNER JOIN student s ON s.personid = p.id AND p.is_deleted = 0 ";
		} elseif ($jointutor){
			$query = "SELECT p.*, t.id AS subid, t.is_keypersonal, i.institutionname FROM person p ";
			$query .= " INNER JOIN tutor t ON t.personid = p.id AND p.is_deleted = 0 ";
			if ($param['type'] == 'key') {
				$query .= " AND t.is_keypersonal = 1 ";
			}

		} else {
			$query = "SELECT p.* FROM person p ";
		}
		if (count ($joins) > 0){
			$query .= implode ("\n", $joins);
		}
		$where[] = 'p.is_deleted = 0';
		if (count ($where) > 0){
			$query .= " WHERE " . implode (" AND ", $where);
		}
		$query .= " ORDER BY last_name, first_name";


		return ($query);
	}

}



?>