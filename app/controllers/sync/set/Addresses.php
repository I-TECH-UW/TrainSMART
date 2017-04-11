<?php
//TA:50 added 
require_once ('app/controllers/sync/set/Simple.php');
require_once ('app/models/table/Addresses.php');

class SyncSetAddresses extends SyncSetSimple
{

	function __construct($sourceDbParams, $syncfile_id)
	{
		parent::__construct($sourceDbParams, $syncfile_id, 'addresses');
	}
	
	
	protected function getColumns() 
	{
		return array( 
				'id_geog1', 
				'id_geog2', 
		          'address1',
				'address2', 
		      'city',
		    'postalcode',
			);
	}
	
	public function isDirty($ld,$rd) {
	    foreach($this->getColumns() as $col) {
	        if($ld[$col] === "")
	            $ld[$col] = '0';
	        if($rd[$col] === "")
	            $rd[$col] = '0';
	        if ( $ld[$col] != $rd[$col]){
// 	            print " Dirty:" . $col . "=>". $ld[$col] . "=" . $rd[$col] . "; ";
	            return true;
	        }
	    }
	    return false;
	}
	
	public function isConflict($ld, $rd){
	    return false;
	}
	
	protected function getTable($isLeft = true){
	    if($isLeft) {
			return new Addresses($this->sourceDbParams);
		}
		return new Addresses();
	}
	
	public function fetchFieldMatch($ld){ 
	    //TA:50  print " fetchFieldMatch: " . @$ld->id_geog1 . "; ";//TA:50
// 	    $where = array();
// 	    if(@$ld->id_geog1 !== "")
// 	        $where[] = "id_geog1='" . @$ld->id_geog1 . "'";
// 	    if(@$ld->id_geog2 !== "")
// 	        $where[] = "id_geog2='" . @$ld->id_geog2 . "'";
// 	    if(@$ld->id_geog3 !== "")
// 	        $where[] = "id_geog3='" . @$ld->id_geog3 . "'";
// 		  $row = $this->getRightTable()->fetchRow("(" . implode(" and ", $where) . ")");
        $row = $this->getRightTable()->fetchRow("(" . "id=" . @$ld->id . ")");
		if($row) {
			return $row;
		}
		
		return null;
	}
	
	public function insertMember($left_id, $path, $field, $commit = false) {
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
	
	        //also copy uuid and timestamp_created
	        if ( $lTable->has_uuid_col() && $rTable->has_uuid_col() ) {
	            $rItem->uuid = $lItem->uuid;
	        } else if ( $rTable->has_uuid_col() ) {
	            $rItem->uuid = uniqid();
	        }
	        if($lTable->has_time_created_col() && $rTable->has_time_created_col()){//TA:50
	            $rItem->timestamp_created = $lItem->timestamp_created;
	        }
	        if ( $rTable->has_is_default_col() )
	            $rItem->is_default = 0;
	
	        if (! $commit) {
	            $new_addresses_id = $this->getNextId();
	        } else {
	        $new_addresses_id = $rItem->save();
	        }
	        $this->log = $this->log . "INSERT:  id=" . $new_addresses_id . "\n";
	        if(!$new_addresses_id) {
	            $this->log = $this->log .  "INSERT ERROR: id=" . $new_addresses_id . "\n";
	            throw new Exception('Insert fail. '. $left_id .' Could not insert member: addresses.');
	        }
	         
	        //upadte addresses id in addresses left table also (it needs for deleting data from links tables)
	        $lTable->getAdapter()->query("update addresses set id=" . $new_addresses_id . " where id=" . $lItem->id);
	
	        //upadte addresses id in link_student_addresses
	        $set_link_student = SyncSetFactory::create('link_student_addresses', SyncCompare::getDesktopConnectionParams('link_student_addresses',$path), $field);
	        //update in sqlite with new addresses
	        $left_table_link_student = $set_link_student->getLeftTable()->getAdapter();
	        $left_table_link_student->query("update link_student_addresses set id_address=" . $new_addresses_id . " where id_address=" . $lItem->id);
	         
	        //upadte addresses id in link_tutor_addresses
	        $set_link_tutor = SyncSetFactory::create('link_tutor_addresses', SyncCompare::getDesktopConnectionParams('link_tutor_addresses',$path), $field);
	        //update in sqlite with new addresses
	        $left_table_link_tutor = $set_link_tutor->getLeftTable()->getAdapter();
	        $left_table_link_tutor->query("update link_tutor_addresses set id_address=" . $new_addresses_id . " where id_address=" . $lItem->id);
	
	
	    } catch(Exception $e) {
	        //if it's a unique constraint violation, then move on, most likely it's an acceptible duplicate
	        //from multiple imports of the same file
	        if ( strstr($e->getMessage(),'Integrity constraint violation: 1062 Duplicate entry') === false)
	            throw $e;
	    }
	}

	public function isReferenced($rd) {
		return Person::isReferenced($rd->id);
	}
	
	protected function _map_fk($lItem, $fk, $type) {
		return parent::_map_fk($lItem,$fk,$type);
	}
	
}





