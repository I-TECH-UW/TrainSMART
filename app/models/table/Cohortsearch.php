<?php 
require_once('ITechTable.php');
require_once('Helper.php');

class CohortSearch extends ITechTable{
	//protected $_primary = 'id';
	protected $_name = 'cohort';



	public function SearchCohort($param) {

#		$stdate=$param['startdate'];
#		$edate = $param['graddate'];
		$startdate	=	isset($param['startdate']) && trim($param['startdate'] != "") ? date("Y-m-d",strtotime($param['startdate'])) : "";
		$endate		=	isset($param['graddate']) && trim($param['graddate'] != "") ? date("Y-m-d",strtotime($param['graddate'])) : "";
		
		$select = $this->dbfunc()->select()
			->from($this->_name);
		if (trim($startdate) != ""){
			$select->where('startdate = ?',$startdate);
		}
		if (trim($endate) != ""){
			$select->where('graddate = ?',$endate);
		}
		
		echo "query = " . $select->__toString();
		die("...");
		
		$result = $this->dbfunc()->fetchAll($select);
		return $result;


/*
     //  switch($param['type']){
       // case"student":
          $select = $this->dbfunc()->select()
            // ->from('cohort')
            //->where('startdate like ?','%'.$param[startdate].'%');
			 ->from($this->_name)
     		 //->where('startdate like ?','%'.urldecode($param['startdate']).'%');
			 //->where('startdate >= ?', urldecode($param['startdate']) );
			 ->where('startdate = ?',$startdate)
			 ->where('graddate = ?',$endate );
            //  break;
           //case"tutor":
          //$select = $this->dbfunc()->select()
              // ->from( array('t' => 'tutor'),array('firstname','gender','degreeinst','id')  )
             // ->where('t.firstname like ?', "%".$param[firstname]."%")
            // ->Where('t.lastname like ?', "%".$param[lastname]."%")
           //   ->Where('t.degreeinst like ?', "%".$param[institution]."%")
          //  ->order('t.id desc');
         //  break;
        //default: 
             //$select = $this->dbfunc()->select()
            // ->from( 'tutor',array('firstname','gender','degreeinst','id')  )
           //->where('firstname like ?', "%".$param[firstname]."%");
         //}
        // echo $select->__toString();
*/
	} 
}

  
 
?>