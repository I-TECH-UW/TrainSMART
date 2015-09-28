<?php

require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Tutor.php');

class SyncSetTutor extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'tutor');
	}
	
	
	protected function getColumns() 
	{
		return array( 
		     'personid',
		    'degree',
		     'positionsheld',
		    'comments',
		    'specialty',
		    'contract_type',
		    'degreeinst',
		    'languagesspoken',
		    'positionsheld',
		    'specialty',
		    'contract_type',
		    'facilityid',
		    'institutionid',
		    'tutorsince',
		    'tutortimehere',
		    'degreeyear'
			);
	}
	
	protected function getTable($isLeft = true)
	{
		if($isLeft) {
			return new Tutor($this->sourceDbParams);
		}
		return new Tutor();
	}
	
	public function isDirty($ld,$rd) {
	    foreach($this->getColumns() as $col) {
	        if ( $ld[$col] != $rd[$col])
	            return true;
	    }
	    return false;
	}
	
       public function isConflict($ld, $rd){
        $rows = $this->getRightTable()->fetchAll("(personid='". @$ld->personid. "')");
        if($rows->toArray()) {
            if(count($rows->toArray()) > 1){
                $message =  count($rows->toArray()) . " records are found for personid=" . @$ld->personid;
                $this->log = $this->log . "CONFLICT: " . $message . "\n";
                return $message;
            }
        }
	    return null;
	}
	
	public function fetchFieldMatch($ld)
	{
	   //TA:50  print " fetchFieldMatch: " . @$ld->personid . "; ";//TA:50
		
		$row = $this->getRightTable()->fetchRow("(personid='". @$ld->personid. "')");
		if($row) {
			return $row;
		}
		
		return null;
	}
	
	public function updateMember($left_id, $right_id, $commit = false){
	    $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
	    $rItem = $this->fetchRightItemById($right_id, $this->tableName);
	    $lTable = $this->getLeftTable();
	    $rTable = $this->getRightTable();
	    
	    $this->log = $this->log . "UPDATE: id:" . $right_id . ", personid:" . $rItem->personid . ", ";
	
	    foreach($this->getColumns() as $col) {
	        if(is_array($col)) {
	            list($fk, $type) = $col;
	            $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
	        } else {
	            //update value
	            if($rItem->$col !== $lItem->$col){
	                $this->log = $this->log . $col . ":" . $rItem->$col . "=>" . $lItem->$col . ", ";
	            }
	            $rItem->$col = $lItem->$col;
	        }
	    }
	
	    $this->log = $this->log . "\n";
	
	    //undelete right side if necessary
	    if ( $lTable->has_is_deleted_col() && $rTable->has_is_deleted_col()) {
	        if ( $lItem->is_deleted == 0 )
	            $rItem->is_deleted = 0;
	    }
	
	    if($commit){
	    $result = $rItem->save();
	    if(!$result) {
	        $this->log = $this->log .  "UPDATE ERROR: id=" . $lItem->id . "\n";
	        //throw new Exception("Update failed for table'" . $this->tableName . "':" . $left_id .'=>'. $right_id .' Could not update item.');
	    }
	
	    return $result;
	    }
	    return null;
	}
	
	public function insertMember($left_id, $path, $field, $commit = false) {
	    //check for insert of deleted item
	    try {
	        $lItem = $this->fetchLeftItemById($left_id, $this->tableName);
	        $lTable = $this->getLeftTable();
	        $rTable = $this->getRightTable();
	        $rItem = $rTable->createRow();
	
	        foreach($this->getColumns() as $col) {
	            if(is_array($col)) {
	                list($fk, $type) = $col;
	
	                $rItem->$fk = $this->_map_fk($lItem, $fk, $type);
	            } else {
	                //update value
	                $rItem->$col = $lItem->$col;
	            }
	        }
	
	        if (! $commit) {
	            $new_tutor_id = $this->getNextId();
	        } else {
	        $new_tutor_id = $rItem->save();
	        }
	        $this->log = $this->log . "\nINSERT: id=" . $lItem->id . "=>". $new_tutor_id.   ", personid=" . $lItem->personid  . "\n";
	        if(!$new_tutor_id) {
	            $this->log = $this->log . "\nINSERT ERROR: id=" . $lItem->id . "=>". $new_tutor_id.   ", personid=" . $lItem->personid . "\n";
	            throw new Exception('Insert fail. '. $left_id .' Could not insert member: tutor.');
	        }
	        
	        //upadte tutor id in tutor left table also (it needs for deleting data from links tables)
	        $lTable->getAdapter()->query("update tutor set id=" . $new_tutor_id . " where id=" . $lItem->id);
	        
	        //add link_tutor_addresses details
	        $set_link_addresses = SyncSetFactory::create('link_tutor_addresses', SyncCompare::getDesktopConnectionParams('link_tutor_addresses',$path), $field);
	        //update in sqlite with new tutor_id
	        $left_table_link_addresses = $set_link_addresses->getLeftTable()->getAdapter();
	        $left_table_link_addresses->query("update link_tutor_addresses set id_tutor=" . $new_tutor_id . " where id_tutor=" . $lItem->id);
	        
	        //add link_tutor_languages details
	        $set_link_languages = SyncSetFactory::create('link_tutor_languages', SyncCompare::getDesktopConnectionParams('link_tutor_languages',$path), $field);
	        //update in sqlite with new tutor_id
	        $left_table_link_languages = $set_link_languages->getLeftTable()->getAdapter();
	        $left_table_link_languages->query("update link_tutor_languages set id_tutor=" . $new_tutor_id . " where id_tutor=" . $lItem->id);
	        
	        //add link_tutor_tutortype details
	        $set_link_tutortype = SyncSetFactory::create('link_tutor_tutortype', SyncCompare::getDesktopConnectionParams('link_tutor_tutortype',$path), $field);
	        //update in sqlite with new tutor_id
	        $left_table_link_tutortype = $set_link_tutortype->getLeftTable()->getAdapter();
	        $left_table_link_tutortype->query("update link_tutor_tutortype set id_tutor=" . $new_tutor_id . " where id_tutor=" . $lItem->id);
	        
	        //add link_tutor_institution details
	        $set_link_institution = SyncSetFactory::create('link_tutor_institution', SyncCompare::getDesktopConnectionParams('link_tutor_institution',$path), $field);
	        //update in sqlite with new tutor_id
	        $left_table_link_institution = $set_link_institution->getLeftTable()->getAdapter();
	        $left_table_link_institution->query("update link_tutor_institution set id_tutor=" . $new_tutor_id . " where id_tutor=" . $lItem->id);
	 
	    } catch(Exception $e) {
	        //if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
	        //from multiple imports of the same file
	        if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
	            throw $e;
	    }
	}

	public function isReferenced($rd) {
		return Student::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





