<?php
require_once('dash.php');
require_once('Helper.php');

class DashviewEmployee extends Dashview
{
	protected $_primary = 'id';
	protected $_name = 'partner';

	public function fetchdetails($where = null) {
		$output = array();

		$helper = new Helper();

		$db = Zend_Db_Table_Abstract::getDefaultAdapter ();
		$select = $db->select()
			->from($this->_name);
		if ($where) // comma seperated string for sql
			$select = $select->where($where);

		$result = $db->fetchAll($select);

		$employees  = $this->fetchEmployeeCounts();

		foreach ($result as $row){
			$output[] = array(
				"col1" => $row['partner'],
				"col2" => $employees[$row['id']] ? $employees[$row['id']] : 0,
				"link" => Settings::$COUNTRY_BASE_URL . "/partner/edit/id/" . $row['id'],
				"type" => 1
			);
			// foreach ($cohorts as $cohort){
			// 	$output[] = array(
			// 		"col1" => $cohort['name'],
			// 		"col2" => $cohort['count'],
			// 		"link" => Settings::$COUNTRY_BASE_URL . "/employee/edit/id/" . $employee['id'],
			// 		"type" => 2
			// 	);
			// }
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
