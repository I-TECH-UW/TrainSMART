<?php
/*
 * Created on Dec 1, 2009
 *
 *  Built for Engender/ITech
 *  Fuse IQ -- todd@fuseiq.com
 */

require_once ('ITechTable.php');

class History extends ITechTable {
	protected $_name = '';
	protected $_primary = 'vid';
	
	private $_parent_table = '';
	
	public function __construct($parent_table, $config = array()) {
		$this->_name = $parent_table . '_history';
		$this->_parent_table = $parent_table;
		parent::__construct ( $config );
	}
	
	/**
	 * To be called after an update
	 *
	 */
	public function tableInsert($source_table, $id) {
		if ($id) {
			$data = array ();
			foreach ( $source_table->_cols as $col ) {
				if (array_search ( $col, $this->_cols ) !== false && ($col != 'timestamp_created'))
					$data [] = $col;
			
			}
      //special case for trainer history which uses person_id as the key
      $tableinfo = $source_table->info();
			if ( $tableinfo['name'] == 'trainer') {
			   $sql = 'INSERT into ' . $this->_name . ' (' . implode ( ',', $data ) . ', vid, pvid, timestamp_created) SELECT ' . implode ( ',', $data ) . ', null, ' . $id . ', timestamp_updated FROM trainer WHERE person_id = (SELECT person_id FROM person_history WHERE vid = ' . $id . ')';
			} else {
          $sql = 'INSERT into ' . $this->_name . ' (' . implode ( ',', $data ) . ', ' . $this->_parent_table . '_id, timestamp_created) SELECT ' . implode ( ',', $data ) . ', ' . $id . ', timestamp_updated FROM ' . $this->_parent_table . ' WHERE ' . $this->_parent_table . '.id = ' . $id;
			}
      $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
			$rowArray = $db->query ( $sql );
		}
	}
	
	/**
	 * Fetch an overview
	 * @id int, master row id
	 * @return date,  user, changes
	 */
	public function fetchAllPerson($id) {
		$rtn = array ();
		
		if ($id) {
			//this is all person specific stuff, will need to be modified for other tables
			
			$select = $this->select ()->from ( $this->_name, array ( 'person_history.*' ))->where ( $this->_parent_table."_history.".$this->_parent_table . "_id = ?", $id )->order ( 'person_history.vid ASC' );
			if (array_search ( 'facility_id', $this->_cols ) !== false) {
				$select->setIntegrityCheck ( false );
				$select->joinLeft ( array ('f' => 'facility' ), "facility_id = f.id", array ('facility_name' ) );
			}
			if (array_search ( 'primary_qualification_option_id', $this->_cols ) !== false) {
				$select->setIntegrityCheck ( false );
				$select->joinLeft ( array ('pq' => 'person_qualification_option' ), "primary_qualification_option_id = pq.id", array ('qualification' => 'qualification_phrase' ) );
			}
			if (array_search ( 'primary_responsibility_option_id', $this->_cols ) !== false) {
				$select->setIntegrityCheck ( false );
				$select->joinLeft ( array ('pr1' => 'person_primary_responsibility_option' ), "primary_responsibility_option_id = pr1.id", array ("primary responsibility" => 'responsibility_phrase'  ) );
			}
			if (array_search ( 'secondary_responsibility_option_id', $this->_cols ) !== false) {
				$select->setIntegrityCheck ( false );
				$select->joinLeft ( array ('pr2' => 'person_secondary_responsibility_option' ), "secondary_responsibility_option_id = pr2.id", array ("secondary responsibility" => 'responsibility_phrase'  ) );
			}

			$select->joinLeft ( array ('tr' => 'trainer_history' ), "tr.pvid = person_history.vid", array('ifnull(tr.timestamp_updated,person_history.timestamp_created) as timestamp_updated'));
        $select->setIntegrityCheck ( false );
        $select->joinLeft ( array ('ato' => 'person_active_trainer_option' ), "active_trainer_option_id = ato.id", array ("active trainer" => 'active_trainer_phrase'  ) );
     $select->joinLeft ( array ('tao' => 'trainer_affiliation_option' ), "affiliation_option_id = tao.id", array ("affilition" => 'trainer_affiliation_phrase'  ) );
        
			$rows = parent::fetchAll ( $select );
			
			//get current state
       $select = $this->select ()->from ( $this->_parent_table, array ('person.*' ) )->where ("person.id = ?", $id );
      if (array_search ( 'facility_id', $this->_cols ) !== false) {
        $select->setIntegrityCheck ( false );
        $select->joinLeft ( array ('f' => 'facility' ), "facility_id = f.id", array ('facility_name' ) );
      }
      if (array_search ( 'primary_qualification_option_id', $this->_cols ) !== false) {
        $select->setIntegrityCheck ( false );
        $select->joinLeft ( array ('pq' => 'person_qualification_option' ), "primary_qualification_option_id = pq.id", array ('qualification' => 'qualification_phrase') );
      }
      if (array_search ( 'primary_responsibility_option_id', $this->_cols ) !== false) {
        $select->setIntegrityCheck ( false );
        $select->joinLeft ( array ('pr1' => 'person_primary_responsibility_option' ), "primary_responsibility_option_id = pr1.id", array ("primary responsibility" => 'responsibility_phrase'  ) );
      }
      if (array_search ( 'secondary_responsibility_option_id', $this->_cols ) !== false) {
        $select->setIntegrityCheck ( false );
        $select->joinLeft ( array ('pr2' => 'person_secondary_responsibility_option' ), "secondary_responsibility_option_id = pr2.id", array ("secondary responsibility" => 'responsibility_phrase'  ) );
      }
      
      $select->joinLeft ( array ('tr' => 'trainer' ), "tr.person_id = person.id" );
      $select->setIntegrityCheck ( false );
      $select->joinLeft ( array ('ato' => 'person_active_trainer_option' ), "active_trainer_option_id = ato.id", array ("active trainer" => 'active_trainer_phrase'  ) );
      $select->joinLeft ( array ('tao' => 'trainer_affiliation_option' ), "affiliation_option_id = tao.id", array ("affilition" => 'trainer_affiliation_phrase'  ) );
      
     $currentRow = parent::fetchAll ( $select )->current();
										
			$previous = null;
			while($rows->next()) {;}
			$rowArray = $rows->toArray();
			$rowArray []= $currentRow->toArray ();
			foreach ( $rowArray as $change ) {
				if ($previous != null) {
					if ($diff = array_diff_assoc ( $previous, $change )) {
            if ($diff) {
							unset($diff['person_id']);
							unset($diff['training_id']); // we dont need to see these
							unset($diff['uuid']);
							$tmp = $diff;                // dont alter the data used for compare
							unset($tmp['vid']);
							unset($tmp['timestamp_updated']);
							unset($tmp['timestamp_created']);
							if (count($tmp)) {
								// old method was just these 4 lines>>
							$delta = array ();
							$delta ['timestamp_updated'] = $previous ['timestamp_created'];
							$delta ['modified_by'] = $previous ['modified_by'];
							$delta ['changes'] = $diff;
							$rtn [] = $delta;
							}
						}
					}
				}
				
				$previous = $change;
			}
		}

		return $rtn;
	}

}