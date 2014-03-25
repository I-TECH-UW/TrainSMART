<?php
/*
 * Created on Feb 14, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */

//instantiate with
//$db = Zend_Db::factory('PDO_MYSQL', $options);
//$table = new Bugs(array('db' => $db));
// $tabletopic = new Bugs(array('name' => 'training_topic_option', 'schema' => 'itech_namibia'));

require_once('ITechTable.php');

/**
 * For lookup tables that only have one user-supplied field
 */
class OptionList extends ITechTable
{
	
	/*
	 * Used for custom 1 and custom 2 fields.
	 * Return the id of the text value. If not found, then insert a new row
	 */
	public static function insertIfNotFound($table, $col, $text) {
    	$topicTable = new OptionList(array('name' => $table));
    	$select = $topicTable->select()->from($table,'id')->where("$col = ?",$text);
      	$row = $topicTable->fetchRow($select);
     	if ( $row )
     		return $row->id;
     	
     	return $topicTable->insert(array($col=>$text));
 	}
	
	/**
	 * $cols can be a single string or an array of strings where the first array item is the column to match on
	 */
	public static function suggestionList($table, $cols, $match = false, $limit = 100, $removeUnknown = true, $where = false) {
    	$topicTable = new OptionList(array('name' => $table));
    	if ( is_string($cols) )
    		$cols = array($cols, 'id');
    	else
    		$cols []= 'id'; 
  
    	$info = $topicTable->info();
      if ( (array_search('is_default',$info['cols']) !== false) ) {
          $cols []= 'is_default';
      }
    		
    	$select = $topicTable->select()->from($table,$cols);
    	
    	//look for char start
    	if ( $match ) {
    		$select->where(($cols[0]).' LIKE ? ', $match.'%');
     	}
     	
     	if ( $topicTable->has_is_deleted_col())
     	  $select->where('is_deleted = 0');
     	
     	if ( $where )
     		$select->where($where);
     	$select->order(($cols[0]).' ASC');
     	
     	if ( $limit )
    		$select->limit($limit,0);
    	
     	$rows = $topicTable->fetchAll($select);
    	$rowArray = $rows->toArray();
    		//	unset 'unknown'
    	if ( $removeUnknown ) {
	    	foreach($rowArray as $key => $row) {
	    		if ( $row[$cols[0]] == 'unknown' )
	    			unset($rowArray[$key]);
	    	}
    	}
    	
    	return $rowArray;
	}
	
	public static function suggestionListHierarchical($table, $col, $match = false, $limit = 100, $cols_extra = array()) {
 		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
        
        $cols1 = $cols2 = '';

      	if ( $cols_extra ) {
      	  
      	  foreach($cols_extra as $pos => $field) {
      	   if($pos % 2 == 0) {
      	     $cols1[] = $field;
      	   } else {
      	     $cols2[] = $field;
      	   }
      	  }
          $cols1 = implode(', ', $cols1) . ','; 
          $cols2 = implode(', ', $cols2) . ',';
      	}      
        
        
        $sql = 'SELECT * FROM '.
        		'(SELECT parent.'.$col.' as "parent_phrase",'.$cols1.' id as "parent_id", null as "id", null as "'.$col.'" '.
				'FROM (SELECT * FROM '.$table.' WHERE parent_id IS NULL AND is_deleted = 0 ) as parent WHERE is_deleted = 0 '.
				'UNION '.
				'SELECT  parent.'.$col.' as "parent_phrase",'.$cols2.' child.parent_id as "parent_id", child.id as "id", child.'.$col.' as "'.$col.'"  '.
				'FROM (SELECT * FROM '.$table.' WHERE parent_id IS NULL AND is_deleted = 0  ) as parent JOIN '.$table.' as child ON child.parent_id = parent.id WHERE child.is_deleted = 0) as un  '.
				'ORDER BY un.parent_phrase ASC, un.'.$col.' ASC';
        
        $rowArray = $db->fetchAll($sql);
    		//	unset 'unknown'
    	foreach($rowArray as $key => $row) {
    		if ( $row[$col] == 'unknown' )
    			unset($rowArray[$key]);
    	}
    	
    	return $rowArray;
	}

	/**
	 * Returns distinct values instead of id/value 
	 */
	public static function suggestionListValues($table, $cols, $match = false, $limit = 100, $removeUnknown = true, $where = false) {
    	$topicTable = new OptionList(array('name' => $table));
    	if ( is_string($cols) )
    		$cols = array($cols);
    		
    	$select = $topicTable->select()->distinct()->from($table,$cols);
    	
    	//look for char start
    	if ( $match ) {
    		$select->where(($cols[0]).' LIKE ? ', $match.'%');
     	}
     	$select->where('is_deleted = 0');
     	if ( $where )
     		$select->where($where);
     	$select->order(($cols[0]).' ASC');
     	
     	if ( $limit )
    		$select->limit($limit,0);
    	
     	$rows = $topicTable->fetchAll($select);
    	$rowArray = $rows->toArray();
    		//	unset 'unknown'
    	if ( $removeUnknown ) {
	    	foreach($rowArray as $key => $row) {
	    		if ( $row[$cols[0]] == 'unknown' )
	    			unset($rowArray[$key]);
	    	}
    	}
    	
    	return $rowArray;
	}


}
