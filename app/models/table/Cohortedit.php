<?php require_once('ITechTable.php');

class Cohortedit extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'cohort';
	//protected $_address = 'addresses';
	//protected $_tutor = 'tutor';

	public function EditCohort($cohortid) {
		$db = Zend_Db_Table_Abstract::getDefaultAdapter (); 
		$select=$db->query("select * from cohort where id = '$cohortid'");
		$row = $select->fetch();
		return $row;	
	}

	public function UpdateCohort($param){
die ("...............");
		$start			= $param['cohortstart'];
		$grade			= $param['cohortgrade'];
		$institutionid	= $param['institution'];
		$degreeinfo		= $param['degreeinfo'];

		$startdate		= date("Y-m-d",strtotime($start));
		$graddate		= date("Y-m-d",strtotime($grade));
		
		$db = Zend_Db_Table_Abstract::getDefaultAdapter (); 		 
	    $data = array(
	    	'startdate'		=> $startdate,
	  		'graddate'		=> $graddate,
	  		'institutionid'	=> $institutionid,
	  		'degree'		=> $degreeinfo,
	  	);

var_dump ($data);
die();
				 
		$db->update('cohort',$data,'id ='.$param[id]);  
		return $data;
	}
	
}
