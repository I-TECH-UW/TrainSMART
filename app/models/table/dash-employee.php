<?php
require_once('dash.php');
require_once('Helper.php');

class DashviewEmployee extends Dashview
{
    protected $_primary = 'id';
    protected $_name = 'partner';

    public function fetchdetails($where = null)
    {
        $output = array();

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $select = $db->select()
            ->from($this->_name);
        if ($where) // comma seperated string for sql
            $select = $select->where($where);

        $result = $db->fetchAll($select);

        $employees = $this->fetchEmployeeCounts();

        foreach ($result as $row) {
            $output[] = array(
                "col1" => $row['partner'],
                "col2" => isset($employees[$row['id']]) ? $employees[$row['id']] : 0,
                "link" => Settings::$COUNTRY_BASE_URL . "/partner/edit/id/" . $row['id'],
                "type" => 1
            );
        }
        return $output;
    }

    public function fetchEmployeeCounts()
    {
        $db = $this->dbfunc();

        $sql_string = 'SELECT DISTINCT `employee`.`partner_id`, count(id) AS `cnt` FROM `employee` GROUP BY `partner_id`';

        $result = $db->fetchAll($sql_string);
        $ret = array();
        if (!count($result))
            return array();
        foreach ($result as $key => $row)
            $ret[$row['partner_id']] = $row['cnt'];

        return ($ret ? $ret : array());
    }
}
