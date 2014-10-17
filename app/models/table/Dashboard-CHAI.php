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
		 	      "id" => $row['id'],
		 	  	  "name" => $row['location_name'],
		 	  	  "tier" => $row['tier'],
		 	      "parent_id" => $row['parent_id'],
		 	      "consumption" => 100,
		 	  	  "link" => Settings::$COUNTRY_BASE_URL . "/dashboard/dash3/id/" . $row['id'],
		 	  	  "type" => 1
		 	    );
		      }
		    break;
		    case 'facility':
		        foreach ($result as $row){
		            $output[] = array(
		                "id" => $row['id'],
		                "name" => $row['facility_name'],
		                "location_id" => $row['location_id'],
		                "consumption" => 100,
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
