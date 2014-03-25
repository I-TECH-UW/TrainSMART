<?php
/**
 * Table model for MultiAssign helper
 */

class MultiAssignList extends ITechTable
{
	protected $_primary = 'id';

  public static function adminList($table, $parent_table, $parent_field, $option_table, $option_field) {
    $tableObj = new MultiAssignList(array('name' => $table));

    $select = $tableObj->select()
        ->from(array('t' => $table), array('id', "{$parent_table}_id"))
        ->from( '', "GROUP_CONCAT(o.$option_field ORDER BY $option_field SEPARATOR '<br>') AS $option_field")
        ->setIntegrityCheck(false)
        ->join(array('p' => $parent_table), "p.id = t.{$parent_table}_id",array($parent_field))
        ->join(array('o' => $option_table), "o.id = t.{$option_table}_id",array())
        ->group("{$parent_table}_id")
        //->order(current($parent_field)) // "qualification_phrase"
        //->order(current($option_field)); // "training_topic_phrase"
        ;

    return $tableObj->fetchAll($select)->ToArray();
  }

  public static function getAssigned($table, $parent_table, $option_table, $parent_id) {
    $tableObj = new MultiAssignList(array('name' => $table));
    $select = $tableObj->select()
        ->from(array('t' => $table), array('id', "{$option_table}_id"))
        ->where("{$parent_table}_id = ?", $parent_id);

    return $tableObj->fetchAll($select)->ToArray();
  }

  /**
   * Get ALL options, including unassigned
   */
  public static function getOptions($option_table, $option_phrase_field, $intersection_table, $parent_table) {

    $tableObj = new MultiAssignList(array('name' => $option_table));
    $select = $tableObj->select()
        ->setIntegrityCheck(false)
        ->from(array('o' => $option_table), array('id', $option_phrase_field))
        ->joinLeft(array('i' => $intersection_table), "i.{$option_table}_id = o.id AND o.is_deleted = 0", array("{$parent_table}_id"))
        ->where("o.is_deleted = 0")
        ->order($option_phrase_field);

    return $tableObj->fetchAll($select)->ToArray();
  }


  /**
   * Save Assignments (for admin page)
   */
  public static function save($table, $parent_table, $option_table, $parent_id, $option_ids) {
    if(!is_numeric($parent_id) || !is_array($option_ids) || empty($option_ids)) {
      return;
    }

    $option_ids = array_unique($option_ids);

    $tableObj = new MultiAssignList(array('name' => $table));

    $tableObj->delete("{$parent_table}_id=$parent_id", true);

    foreach($option_ids as $id) {
      if($id) {
  	    $createRow = $tableObj->createRow();
  	    $createRow->{"{$parent_table}_id"} = $parent_id;
  	    $createRow->{"{$option_table}_id"} = $id;
       	$createRow->save();
      }
    }
  }




	public function insert(array $data) {
	    require_once('models/Session.php');

	    $data['timestamp_created'] = new Zend_Db_Expr('NOW()');
	    $data['created_by'] = Session::getCurrentUserId();
	    //don't set is_deleted
	    return Zend_Db_Table_Abstract::insert($data);
	}

	/**
	 * Returns an array of options and wether or not they are selected for this user.
	 * Used for rendering sets of checkboxes.
	 */
	public static function choicesList($intersection_table, $owner_col, $owner_id, $option_table, $option_col = false, $join_col = false, $remove_unknown = true) {
    		return MultiOptionList::choicesList($intersection_table, $owner_col, $owner_id, $option_table, $option_col, $join_col, $remove_unknown);

	}

	public static function hardDeleteOption($table, $owner_col, $owner_id, $option_col, $option_id) {
    		return MultiOptionList::hardDeleteOption($table, $owner_col, $owner_id, $option_col, $option_id);
	}

	public static function insertOption($table, $owner_col, $owner_id, $option_col, $option_id, $extra_col = '', $extra_value = '') {
     		return MultiOptionList::insertOption($table, $owner_col, $owner_id, $option_col, $option_id, $extra_col = '', $extra_value = '');

	}

	/**
	 * Matches as posted set of multiple drop downs or checkboxes with an option list intersection table.
	 * May update extra column within linktable
	 */
	public static function updateOptions($linktable, $optionLookupTable, $owner_col, $owner_id, $option_col, $valuesFromPost, $extra_col = '', $extra_values = array()) {
		return MultiOptionList::updateOptions($linktable,$optionLookupTable,$owner_col,$owner_id,$option_col,$valuesFromPost,$extra_col,$extra_values);
	}

}

?>
