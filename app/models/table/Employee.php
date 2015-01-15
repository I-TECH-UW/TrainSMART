<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  ALL HAIL ANGRY CAT
 *
 */

require_once ('ITechTable.php');

class Employee extends ITechTable {
	protected $_name = 'employee';
	protected $_primary = 'id';

    /**
     * deletes mechanism associations from an employee
     * @param $employee_id     - employee id
     * @param $association_ids - the ids to remove
     * @return bool
     */
    public static function disassociateMechanismsFromEmployee($employee_id, $association_ids)
	{
	    if ((!$association_ids) || (!$employee_id) ||
            (!preg_match('/^\d+[,\d+]+$/', $association_ids))) {
            return false;
        }
	    
	    $table = new ITechTable ( array ('name' => 'link_mechanism_employee' ) );
	    try {
	        $table->delete("id IN ($association_ids) AND employee_id = $employee_id");
	    } catch(Exception $e) {
	        error_log($e);
	        return false;
	    }
	    return true;
	     
	}

    /**
     * associate mechanisms and percentages with an employee
     * @param int    $employee_id           - employee id
     * @param string $mechanism_ids         - comma-delimited string of ids to associate with the employee
     * @param string $mechanism_percentages - comma-delimited string of percentages to associate with each id in $mechanism_ids
     * @return bool
     */
	public static function saveMechanismAssociations ( $employee_id, $mechanism_ids, $mechanism_percentages)
	{
	    if ((!$mechanism_ids) || (!$employee_id) ||
            (!preg_match('/^\d+[,\d+]+$/', $mechanism_ids)) ||
            (!preg_match('/^\d+[,\d+]+$/', $mechanism_percentages))) {
            return false;
        }

        $ids = explode(',', $mechanism_ids);
        $percentages = explode(',', $mechanism_percentages);

        if (count($ids) != count($percentages)) {
            return false;
        }

        $linkTable = new ITechTable ( array ('name' => 'link_mechanism_employee' ) );
	    foreach($ids as $i => $mechID) {
	        try {
                $row = $linkTable->createRow(array('employee_id' => $employee_id, 'mechanism_option_id' => $ids[$i], 'percentage' => $percentages[$i]));
                $row->save();

	        } catch(Exception $e) {
	            error_log($e);
	            return false;
	        }
	    }
	    return true;
	}
	
}
