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
	
	public static function disassociateMechanismFromEmployee($mechanism_ids)
	{
	    if ($mechanism_ids === "")
		  return false;
	    
	    $table = new ITechTable ( array ('name' => 'employee_to_partner_to_subpartner_to_funder_to_mechanism' ) );
	    try{
	        $table->delete("id in ($mechanism_ids)");
	    }catch(Exception $e){
	        print $e;
	        return false;
	    }
	    return true;
	     
	}
	
	public static function saveMechanismAssociation ( $id, $mechanism_association)
	{
	    if (empty($mechanism_association))
	        return false;
	
	    $psfmTable = new ITechTable(array('name' => 'partner_to_subpartner_to_funder_to_mechanism'));
	    $stable = new ITechTable ( array ('name' => 'employee_to_partner_to_subpartner_to_funder_to_mechanism' ) );
	    foreach($mechanism_association as $i => $mech){
	        try{
	            $ids = explode('_', $mech['combined_id']);
	            $mechanism_id = $ids[0];
	            $psfm_id = $ids[1];
        	    $psfm = $psfmTable->fetchRow($psfmTable->select()->where("id = ?", $psfm_id));
        	    	            	
	            $row = $stable->createRow();
	            $row->partner_to_subpartner_to_funder_to_mechanism_id = $psfm->id;
	            $row->employee_id = $id;
	            $row->partner_id = $psfm->partner_id;
	            $row->subpartner_id = $psfm->subpartner_id;
	            $row->partner_funder_option_id = $psfm->partner_funder_option_id;
	            $row->mechanism_option_id = $psfm->mechanism_option_id;
	            $row->percentage = $mech['percentage'];
	            $row->created_by = $psfm->created_by;
	            $row->is_deleted = 0;
	            $row->timestamp_created = $psfm->timestamp_created;
	            
	            $row->save();
	        }catch(Exception $e){
	            print $e;
	            return false;
	        }
	    }
	    return true;
	}
	
}
