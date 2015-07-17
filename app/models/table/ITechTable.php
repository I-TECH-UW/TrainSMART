<?php
require_once('Zend/Db/Table/Abstract.php');
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */


class ITechTable extends Zend_Db_Table_Abstract
{

	public function PK() {
		//TODO: it looks like some of our subclasses are masking the _primary field of the base class

		if ( is_array($this->_primary) )
			return reset($this->_primary);
		else
			return $this->_primary;
	}

	public function Name() {
		return $this->_name;
	}

	public function has_is_deleted_col() {
     $info = $this->info();
      if ( (array_search('is_deleted',$info['cols']) !== false) ) {
          return  true;
      }

      return false;
	}

	public function has_is_default_col() {
     $info = $this->info();
      if ( (array_search('is_default',$info['cols']) !== false) ) {
          return  true;
      }

      return false;
	}

	public function has_uuid_col() {
     $info = $this->info();
      if ( (array_search('uuid',$info['cols']) !== false) ) {
          return  true;
      }

      return false;
	}

  public function get_uuid($pkOrID)
  {
    $db = $this->dbfunc();
    $col = $this->PK();
    if ( is_array($pkOrID) ) {
    	$pkOrID = implode(',', $pkOrID);
    }
    if ( !$db || empty($col) || empty($pkOrID) || empty($this->_name))
    	return null;
    return $db->fetchCol("SELECT uuid FROM {$this->_name} WHERE $col IN ($pkOrID)"); // select uuid from _table_ where id = $pkOrID
  }

	/**
 	 * Return a single row or create a brand new one.
 	 * This allows us to call -->save() on the row object.
 	 */
 	public function findOrCreate($id = false) {
 		if ( ($id !== false) and ($id !== null) ) {
			$personrow = $this->find($id)->current();
			if ( !$personrow )
				$personrow = $this->createRow();
		} else {
			$personrow = $this->createRow();
		}

 		return $personrow;
 	}


 	/**
   * Select all rows (id + column) that aren't deleted
   */
 	public function selectAll($column) {
    $has_is_deleted = $this->has_is_deleted_col();

 		$select = $this->select()
      ->from($this->_name,array('id',$column))
      ->where(($has_is_deleted? ' is_deleted = 0  AND ' : '').' id != 0')
      ->order($column);
    return $this->fetchAll($select);
  }

	public function insert(array $data) {
		require_once('models/Session.php');

		if(in_array('timestamp_created', $this->_getCols())) { $data['timestamp_created'] = $this->now_expr(); }
		if(in_array('created_by', $this->_getCols())) { $data['created_by'] = Session::getCurrentUserId(); }
		if(in_array('is_deleted', $this->_getCols())) { $data['is_deleted'] = 0; }

		return parent::insert($data);
	}

	public function update(array $data,$where) {
		require_once('models/Session.php');
		if(in_array('timestamp_updated', $this->_getCols())) { $data['timestamp_updated'] = $this->now_expr(); }
		if(in_array('modified_by', $this->_getCols())) { $data['modified_by'] = Session::getCurrentUserId(); }

		return parent::update($data,$where);
	}

	/**
   * Override to use is_deleted field
   */
	public function delete($where=false, $force = false) {
		$info = $this->info();
		if ( $force or (array_search('is_deleted',$info['cols']) == false) )
			return parent::delete($where);
		else {
			$data = array();
			$data['is_deleted'] = 1;
			parent::update($data, $where);
		}
	}

	/**
   * Set is_deleted as 0 and return row
   */
	public function undelete($field, $value) {
		require_once('models/Session.php');

    $data['is_deleted'] = 0;
		$data['timestamp_updated'] = new Zend_Db_Expr('NOW()');
    $data['modified_by'] = Session::getCurrentUserId();

    parent::update($data, "$field='$value'");

		return $this->fetchRow($this->select()->where("$field='$value'"));
	}

	/**
	 * Attempt to insert unique value
	 * If not found, then insert a new row and return id.
	 * Return -2 if deleted, return -1 if exists
	 * $alwaysReturnId = true :: return the duplicates ID
	 */
	public function insertUnique($col, $text, $alwaysReturnId = false, $col2 = false, $text2 = false, $col3 = false, $text3 = false) {
    	$select = $this->select()->from($this->_name,array('id','is_deleted'))->where("$col = ?",$text);
    	if ( $col2 ) {
    		$select->where("$col2 = ?",$text2);
    	}
      if ( $col3 ) {
        $select->where("$col3 = ?",$text3);
      }
    	$row = $this->fetchRow($select);


      if($row) {
        if($row->is_deleted) {
          return -2; // trying to add value that was deleted
        } else {
        	if ( $alwaysReturnId )
        		return $row->id;
          return -1; // trying to add duplicate
        }
      }

      $data = array($col=>$text);
      if ( $col2 )
      		$data[$col2] = $text2;
      if ( $col3 )
          $data[$col3] = $text3;

     	return $this->insert($data);
 	}

 	public function is_sqlite() {
 		$db_config = $this->getAdapter()->getConfig();
 		if ( strpos($db_config['dbname'], '.sqlite') ) return true;
 		return false;
 	}

 	public function now_expr() {
 		if ( $this->is_sqlite() )
 		 return new Zend_Db_Expr("DATETIME('now')");

 		return new Zend_Db_Expr('NOW()');
 	}

	public static function getCustomValue($table, $phrase_col, $id) {
		if ( !$id )
			return null;

  			$customTable = new ITechTable(array('name' => $table));
    		$select = $customTable->select()->from($table,$phrase_col)->where("id = ?",$id);
      		$row = $customTable->fetchRow($select);

      		if ( $row )
      			return $row->$phrase_col;

		return null;
	}

	/**
	 * Data dump helper
	 *
	 * @param unknown_type $sorted_data
	 * @param unknown_type $option_table
	 * @param unknown_type $id_col
	 * @param unknown_type $value_col
	 * @param unknown_type $remove_id
	 * @return unknown
	 */
  public function _fill_lookup($sorted_data, $option_table, $id_col, $value_col, $remove_id = true) {
    require_once('OptionList.php');
    $topicTable = new OptionList(array('name' => $option_table));
    $select = $topicTable->select()->from($option_table, array('id',$value_col));
    $rows = $this->fetchAll($select);
    $topic = array();
    foreach($rows as $r) {
       $topic[$r->id] = $r->$value_col;
     }

     foreach($sorted_data as $id=>$datum) {
      if ( isset($datum[$id_col]) && isset($topic[$datum[$id_col]]) ) {
        $sorted_data[$id][$value_col] = $topic[$datum[$id_col]];
      }
       if ( $remove_id )
        unset($sorted_data[$id][$id_col]);
    }

     return $sorted_data;
  }

 /**
  * *Helpers for data dumps
  *
  * @param unknown_type $sorted_data
  * @param unknown_type $option_table
  * @param unknown_type $id_col
  * @param unknown_type $value_col
  * @param unknown_type $remove_id
  * @return unknown
  */
  public function _fill_related($sorted_data, $option_table, $id_col, $value_col, $remove_id = true) {
   // get training topic(s)
    $select = $this->select()
        ->from($option_table, array($value_col))
        ->setIntegrityCheck(false)
        ->join(array('t' => 'training'), "t.$id_col = $option_table.id",'id')
        ->where("$id_col != 0");

    $rows = $this->fetchAll($select);

    $topic = array();

    foreach($rows as $r) {
      if (!isset($topic[$r->id]) )
        $topic[$r->id] = $r->$value_col;
      else
        $topic[$r->id] = $topic[$r->id].', '.$r->$value_col;
    }

    foreach($sorted_data as $t=>$r) {
      if(!isset($topic[$t]))
       $sorted_data[$t][$value_col] = 'n/a';
      else
       $sorted_data[$t][$value_col] = $topic[$t];

      if ($remove_id)
        unset($sorted_data[$t][$id_col]);
    }

    return $sorted_data;
  }

  /**
   * Data dump helper
   *
   * @param unknown_type $sorted_data
   * @param unknown_type $option_table
   * @param unknown_type $intersection_table
   * @param unknown_type $id_col
   * @param unknown_type $value_col
   * @return unknown
   */
  public function _fill_intersection_related($sorted_data, $option_table, $intersection_table, $id_col, $value_col) {

    $selectCols = array('training_id');
    if ($option_table == 'training_funding_option'){
      // and select extra cols from multioptlist's
      $selectCols = array('training_id', "funding_amount");
      $extraCol = 'funding_amount';
    }
    elseif ($option_table == 'training_pepfar_categories_option') {
      $selectCols = array('training_id', "duration_days");
      $extraCol = 'duration_days';
    }

    // get training topic(s)
    $select = $this->select()
        ->from($intersection_table, $selectCols)
        ->setIntegrityCheck(false)
        ->join(array('tp' => $option_table), "$intersection_table.$id_col = tp.id",$value_col)
        ->where("$id_col != 0");

    $rows = $this->fetchAll($select);
    $topic = array(); $extra = array();
    foreach($rows as $r) {
      if (!isset($topic[$r->training_id])) $topic[$r->training_id] = array();
      if (!isset($extra[$r->training_id])) $extra[$r->training_id] = array();
      $topic[$r->training_id][] = $r->$value_col;
      if ($extraCol)
        $extra[$r->training_id][] = $r->{$extraCol};
    }

    if($extraCol == 'duration_days' && $option_table == 'training_pepfar_categories_option')
      $extraCol = 'pepfar_duration_days';

    foreach($sorted_data as $t=>$r) {
      if(!isset($topic[$t]))
       $sorted_data[$t][$value_col] = array('n/a');
      else
       $sorted_data[$t][$value_col] = implode(', ', $topic[$t]);

      if ($extraCol)
        $sorted_data[$t][$extraCol]   = isset($extra[$t]) ? implode(', ', $extra[$t]) : array('n/a');

    }

    return $sorted_data;
  }

  function dbfunc(){

   $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
   return $db;
  }


  // given a string "12_34_456", explode it and return the last part, used by preservice
  public function getRegionLastValue($str)
  {
    if (! $str)
      return 0;
    return array_pop(explode('_', $str));
  }

}