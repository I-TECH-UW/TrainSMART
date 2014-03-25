<?php 
require_once('ITechTable.php');

class Cohortadd extends ITechTable
{
	//protected $_primary = 'id';
	protected $_name = 'cohort';
	protected $_link = 'link_student_cohort';

  public function addCohort($param) {
	 
	 $datepick=$param[cohortstart];
	 $startdate=date("Y-m-d",strtotime($datepick));
	 $datepick1=$param[cohortgraduation];
	 $graduationdate=date("Y-m-d",strtotime($datepick1));
			
	 $insert=array('cohortid' => "$param[cohortid]",
	 			   'startdate'=> "$startdate",
				   'graddate' => "$graduationdate",
				   'degree'=>"$param[degreeinfo]"
				  );
	
	$rowArray = $this->dbfunc()->insert($this->_name,$insert);
	$id = $this->dbfunc()->lastInsertId(); 
	
	 /*$select = $this->dbfunc()->select()
	 	 ->from('cohort')
		 ->where('id = ?',$id);
	 $result = $this->dbfunc()->fetch($select);
	 
	 $cohortid = $result[cohortid];
	 echo $cohortid;*/
	 
	/* $db = $this->dbfunc(); 
 	 $select=$db->query("select * from cohort where id = $id");
	 $row = $select->fetch();
	 
	 $cohortid =  $row[cohortid];*/
	
	$linkinsert=array('cohortid' => "$param[cohortid]",
	 			   'startdate'=> "$startdate",
				   'graddate' => "$graduationdate",
				   'degree'=>"$param[degreeinfo]"
				  );
	
	$insertrow = $this->dbfunc()->insert($this->_name,$linkinsert);
	$id = $this->dbfunc()->lastInsertId(); 
		
	return $id;
	}
 }
  
?>