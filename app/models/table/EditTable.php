<?php
/*
 * Created on Mar 17, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- jonah.ellison@fuseiq.com
 *  
 */

require_once('ITechTable.php');


class EditTable extends ITechTable
{
	
    /**
     * gets database rows where is_deleted = 0, unsets any result values with the value 'unknown'
     *
     * *special behavior if 'creator_id' or 'creator_name' are in the cols array
     *
     * @param string       $table - table name
     * @param array|string $cols  - can be a single string or an array of strings where the first array item is the column to match on
     * @param string|bool  $where - where clause or false
     * @param int|bool     $limit - number of records to return or false
     * @return array
     */
    public static function getRowsSingle($table, $cols, $where = false, $limit = false)
    {

        $topicTable = new EditTable(array('name' => $table));
        if (is_string($cols)) {
            $cols = array($cols, 'id');
        } else {
            $cols[] = 'id';
        }
        $default_sort = $cols[0];

        if ( (array_search('creator_id', $cols) !== false) || (array_search('creator_name', $cols) !== false) ) {
            if ( array_search('creator_id', $cols) !== false ) {
                unset($cols[array_search('creator_id', $cols)]);
            }
            if ( array_search('creator_name', $cols) !== false ) {
                unset($cols[array_search('creator_name', $cols)]);
            }
            $default_sort = reset($cols);
            $select = $topicTable->select()->from($table, $cols)->setIntegrityCheck(false);
            $select->join(array('u' => 'user'), $table.".created_by = u.id", array("creator_name"=>'CONCAT(u.first_name," ", u.last_name)'));
        } else {
            $select = $topicTable->select()->from($table, $cols);
        }

        // look for char start
        if ( $where ) {
            $select->where($where);
        }
        $select->where('is_deleted = 0');
        $select->order($default_sort.' ASC');

        if ( $limit ) {
            $select->limit($limit, 0);
        }

        try {
            $rows = $topicTable->fetchAll($select);
        } catch(Zend_Exception $e) {
            error_log($e);
        }

        $rowArray = $rows->toArray();

        //	unset 'unknown'
        foreach($rowArray as $key => $row) {
            if ( $row[$cols[0]] == 'unknown' )
                unset($rowArray[$key]);
        }

        return $rowArray;
    }
	
	/**
   * Check for associations, return array of ids that are foreign keys in the dependent table
   * $tableDependent == 'self' is a keyword for self referencing dependencies such as location with its parent
   */
  public static function getDependencies($table, $tableDependent, $colDependent) {
    $topicTable = new EditTable(array('name' => $table));
    
    $joinTo = ($tableDependent == 'self'?$topicTable->_name:$tableDependent);
    
    //hacky
    $jpkey = 'id';
    if ( $joinTo == 'trainer' ) {
    	$jpkey = 'person_id';
    }
    
    $select = $topicTable->select()
        ->from($topicTable->_name, array('id'))
        ->setIntegrityCheck(false)
        ->joinLeft(array('d' => $joinTo), "d.$colDependent = $table.id", array('countDependent' => "COUNT(d.".$jpkey.")"))
        ->group("$table.id")
        ->where("$table.is_deleted = 0")
        ->having("countDependent != 0");  
  
    
    $rows = $topicTable->fetchAll($select);
    $ids = array();
    foreach($rows as $r) {
      $ids[] = $r->id;
    }
    
    return $ids;
    
  }
  
  
    /**
     * Merge SQL
     * @param $table
     * @param $table_dependent
     * @param array $ids
     * @param $id_primary
     */
	public static function merge($table, $table_dependent, array $ids, $id_primary) {
    $topicTable = new EditTable(array('name' => $table));    
    $dependTable = new EditTable(array('name' => $table_dependent));
    
    $depend_col = "{$table}_id";

    // remove primary id from list of ids    
    foreach ($ids as $key => $id) {
      if ($id == $id_primary) unset($ids[$key]);      
    }
    $ids_sql = implode(',', $ids);
    
    // update dependent table
    $dependTable->update(array($depend_col => $id_primary), "$depend_col IN ($ids_sql)");
    
    // delete from option table
    $topicTable->delete("id IN ($ids_sql)");
	}

	/**
	 * Update default SQL.
     * @param $table
     * @param $id
     * @param null $extraWhere
     */
	public static function setDefault($table, $id, $extraWhere = NULL) { 
    $topicTable = new EditTable(array('name' => $table));    

    $where = "id = $id";
    if ($extraWhere) {
      $where .= " AND $extraWhere";
    }

    // reset all to 0
    $topicTable->update(array('is_default' => 0), $extraWhere);
    $topicTable->update(array('is_default' => 1), $where);    
  }

}

