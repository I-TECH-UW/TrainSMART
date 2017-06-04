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



class MultiOptionList extends ITechTable
{
	protected $_primary = 'id';

	public function insert(array $data) {
	    require_once('models/Session.php');

	    $data['timestamp_created'] = new Zend_Db_Expr('NOW()');
	    $data['created_by'] = Session::getCurrentUserId();
	    //don't set is_deleted
	    return Zend_Db_Table_Abstract::insert($data);
	}

	/**
	 * Returns an array of options and whether or not they are selected for this user.
	 * Used for rendering sets of checkboxes.
     * @param string $intersection_table - the table containing the link between option owner and linked options
     * @param string $owner_col - the column name in $intersection_table that contains the owner id
     * @param string $owner_id - the owner id to retrieve options for
     * @param string $option_table - the table that contains options that could be linked with owner in this table
     * @param bool $option_cols - the columns in $option_table to retrieve
     * @param bool $join_cols
     * @param bool $remove_unknown - whether to unset null variables in the returned dataset
     * @return array
     */
	public static function choicesList($intersection_table, $owner_col, $owner_id, $option_table, $option_cols = false, $join_cols = false, $remove_unknown = true)
    {
        //do something like this:
        /*
                SELECT tlo.id,
            tlo.language_phrase,
            ttlo.trainer_language_option_id
        FROM trainer_language_option as tlo
        LEFT JOIN trainer_to_trainer_language_option as ttlo  ON ttlo.trainer_language_option_id = tlo.id AND ttlo.trainer_id = xx
        WHERE tlo.is_deleted = 0
        */
        $cols[] = 'id';
        if (is_array($option_cols)) {
            $cols = array_merge($cols, $option_cols);
        } else if ($option_cols) {
            $cols[] = $option_cols;
        }
        if (is_array($join_cols)) {
            foreach ($join_cols as $ecol) {
                $cols [] = 'c.' . $ecol;
            }
        } else if ($join_cols) {
            $cols[] = 'c.' . $join_cols;
        }

        $optionsTable = new MultiOptionList(array('name' => $option_table));
        $select = $optionsTable->select()->from($option_table, $cols)->setIntegrityCheck(false);
        if ($owner_id || $join_cols) {
            if (!$owner_id) {
                $owner_id = 0;
            }
            $select->joinLeft(array('c' => $intersection_table),
                $option_table . '.id = c.' . $option_table . '_id AND c.' . $owner_col . ' = ' . $owner_id, 'c.' . $owner_col);
        }

        //might want to add to base class eventually
        $info = $optionsTable->info();
        if ((array_search('is_deleted', $info['cols']) !== false)) {
            $select->where('is_deleted = 0');
        }
    	if(isset($cols[1])) {
            $select->order(($cols[1]).' ASC');
        }
        $profiler = $optionsTable->getAdapter()->getProfiler();
        $profiler->setEnabled(true);
        #$optionsTable->getAdapter()->getProfiler()->setEnabled(true);

    	$rows = $optionsTable->fetchAll($select);
        #$query = $optionsTable->getAdapter()->getProfiler()->getLastQueryProfile()->getQuery();
        #$values = $optionsTable->getAdapter()->getProfiler()->getLastQueryProfile()->getQueryParams();
        $query = $profiler->getLastQueryProfile()->getQuery();
        $values = $profiler->getLastQueryProfile()->getQueryParams();

        $profiler->setEnabled(false);

    	$rowArray = $rows->toArray();
        //	unset 'unknown'
    	if ( $option_cols and $remove_unknown ) {
	    	foreach($rowArray as $key => $row) {
	    		if ( is_array($option_cols) ) {
		    		foreach($option_cols as $option_col) {
			    		if ( $row[$option_col] == 'unknown' ) {
                            unset($rowArray[$key]);
                        }
		    		}
	    		} else {
			    	if ( $option_cols == 'unknown' ) {
                        unset($rowArray[$key]);
                    }
	    		}
	    	}
    	}

    	return $rowArray;
	}

public static function hardDeleteOption($table, $owner_col, $owner_id, $option_col, $option_id) {
    	$optionsTable = new MultiOptionList(array('name' => $table));
//     	$select = $optionsTable->select()->from($table)->where("$owner_col = ?",$owner_id)->where("$option_col = ?",$option_id);
//       	$row = $optionsTable->fetchAll($select)->current();
//  		if ( $row ) {
//             $row->delete();
//         }
        //TA:#416.2
        $where = "$owner_col = $owner_id";
        if($option_col && $option_id){
            if(is_numeric($option_id)){
                $where .= " and " . $option_col . "=" . $option_id;
            }else{
                $where .= " and " . $option_col . "='" . $option_id . "'";
            }
        }
        $select = $optionsTable->delete($where);
	}

	public static function insertOption($table, $owner_col, $owner_id, $option_col, $option_id, $extra_col = '', $extra_value = '') {
     	$optionsTable = new MultiOptionList(array('name' => $table));
	    $createRow = $optionsTable->createRow();
	    $createRow->$owner_col = $owner_id;
	    $createRow->$option_col = $option_id;
      
	    if ($extra_col && !is_array($extra_col)) {
            $extra_col = array($extra_col);
	    }
	    
        if($extra_col && is_array($extra_col) ) {
        foreach($extra_col as $col)
            $createRow->$col = $extra_value[$col];
        }

     	$createRow->save();
	}

    /**
    * Matches as posted set of multiple drop downs or checkboxes with an option list intersection table.
    * May update extra columns within link table
    *
    * @param string $linktable - the table name that stores the link information
    * @param string $optionLookupTable - the table name that has the options to be linked
    * @param string $owner_col - column name in $linktable that contains the id to be linked
    * @param string $owner_id - the id to be linked
    * @param string $option_col - column name in link table that contains the id to be linked to the option
    * @param array $valuesFromPost - contains the main column values
    * @param array|string $extra_col - additional columns to update
    * @param array $extra_values - array or array of arrays with values to update ; the indexes must match $valuesFromPost and $extra_col
    */
    public static function updateOptions($linktable, $optionLookupTable, $owner_col, $owner_id, $option_col, $valuesFromPost, $extra_col = '', $extra_values = array()) {
        if ( $valuesFromPost === null ) {
            return;
        }
        $optionRowsArray = MultiOptionList::choicesList($linktable,$owner_col,$owner_id, $optionLookupTable, false, $option_col);

        // get extra field value(s) associate drop-down
        if ($extra_col && !is_array($extra_col)) {
            $extra_values = array($extra_values);
            $extra_col = array($extra_col);
        }

        if ( $valuesFromPost && is_array($extra_col) ) {
            foreach($extra_col as $colkey => $col) {
                foreach($valuesFromPost as $key => $id) {
                    if ( $id !== '' ) {
                        $extra[$id][$col] = (isset($extra_values[$colkey][$key])) ? $extra_values[$colkey][$key] : 0;
                    }
                }
            }
        }

        foreach($optionRowsArray as $option_row) {
            $option_id = $option_row['id'];
            //turn off
            if ( $option_row[$owner_col] and (($valuesFromPost == null) or ((array_search($option_id,$valuesFromPost) === false) and
                (!isset($valuesFromPost[$option_id]) or !$valuesFromPost[$option_id])))) {

                MultiOptionList::hardDeleteOption($linktable, $owner_col,$owner_id,$option_col, $option_id);
            //turn on
            } else if ( (!$option_row[$owner_col]) and $valuesFromPost and (array_search($option_id,$valuesFromPost) !== false) ) {
                MultiOptionList::insertOption($linktable, $owner_col,$owner_id,$option_col, $option_id, $extra_col, (isset($extra) && is_array($extra[$option_id]) ?$extra[$option_id]:''));
            } else if($extra_col) {
                // update extra field
                if(isset($extra[$option_id])) {
                    $optionsTable = new MultiOptionList(array('name' => $linktable));

                    $update_fields = array();
                    foreach($extra_col as $col) {
                        $update_fields[$col] = $extra[$option_id][$col];
                    }

                    $optionsTable->update($update_fields,"$option_col={$option_id} AND $owner_col={$option_row[$owner_col]}");
                }
            }
        }
    }

  	// get a array of option id's attached to a record
    // example: MultiOptionList::getLinkedOptions($id, 'training_id', 'training_to_training_topic', 'training_topic_option_id')
    // returns: [1, 4, 3, 11]
	public function getLinkedOptions($id, $idCol, $table, $desiredCol){
		try {
			
			$db = Zend_Db_Table_Abstract::getDefaultAdapter();
			$sel = $db->select(array($desiredCol))->from($table)->where("$idCol = ?", $id);
			$res = $db->fetchCol($sel);
			return $res ? $res : null;
		} catch (Exception $e) {
			//echo $e->getMessage();
			return null;
		}
	}

}

?>
