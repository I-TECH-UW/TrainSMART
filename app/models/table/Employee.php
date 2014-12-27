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
	
	public static function disassociateMechanismFromEmployee($association_ids)
	{
	    if (!$association_ids)
		  return false;
	    
	    $table = new ITechTable ( array ('name' => 'link_employee_mechanism' ) );
	    try {
	        $table->delete("id in ($association_ids)");
	    } catch(Exception $e) {
	        error_log($e);
	        return false;
	    }
	    return true;
	     
	}
	
	public static function saveMechanismAssociation ( $id, $mechanism_association)
	{
	    if (empty($mechanism_association))
	        return false;


        $linkTable = new ITechTable ( array ('name' => 'link_employee_mechanism' ) );
	    foreach($mechanism_association as $mech) {
	        try {
                $row = $linkTable->createRow(array('employee_id' => $id, 'mechanism_option_id' => $mech['id'], 'percentage' => $mech['percentage']));
                $row->save();

	        } catch(Exception $e) {
	            error_log($e);
	            return false;
	        }
	    }
	    return true;
	}
	
}
