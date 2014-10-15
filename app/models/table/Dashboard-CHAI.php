<?php
require_once('Dashboard.php');
require_once('Helper.php');

class DashboardCHAI extends Dashboard
{
	protected $_primary = 'id';
	// protected $_name = 'location';

	public function fetchdetails($tableName = null, $where = null) {
		
	    $output = array();
		$helper = new Helper();

		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$select = $db->select()
			->from($tableName);
		if ($where) // comma seperated string for sql
			$select = $select->where($where);

		$result = $db->fetchAll($select);

		switch ($tableName) {
		    case 'location':
		      foreach ($result as $row){
		 	    $output[] = array(
		 	      "col1" => $row['id'],
		 	  	  "col2" => $row['location_name'],
		 	  	  "col3" => $row['tier'],
		 	  	  "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['id'],
		 	  	  "type" => 1
		 	    );
		      }
		    break;
		    case 'facility':
		        foreach ($result as $row){
		            $output[] = array(
		                "col1" => $row['id'],
		                "col2" => $row['facility_name'],
		                "col3" => $row['location_id'],
		                "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/something/id/" . $row['id'],
		                "type" => 1
		            );
		        }
		        break;		    
		    
		}
		
		return $output;
	}

	public function fetchEmployeeCounts()
	{
		$db = $this->dbfunc();
		$select = $db->select()->distinct()->from('employee',array('partner_id,count(id) as cnt'));
		$select = $select->group('partner_id');
		$result = $db->fetchAll($select);
		$ret = array();
		if (! count($result))
			return array();
		foreach ($result as $key => $row)
			$ret[$row['partner_id']] = $row['cnt'];

		return ($ret ? $ret : array());
	}
}
?>
