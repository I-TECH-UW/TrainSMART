<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

require_once('ITechTable.php');

class TrainingTitleOption extends ITechTable
{

  protected $_name = 'training_title_option';
  protected $_primary = 'id';


	public static function suggestionList($match = false, $limit = 100) {
     	$rows = self::suggestionQuery($match,$limit);
    	$rowArray = $rows->toArray();
    		//	unset 'unknown'
    	foreach($rowArray as $key => $row) {
    		if ( $row['training_title_phrase'] == 'unknown' )
    			unset($rowArray[$key]);
    	}

    	return $rowArray;
	}

	protected static function suggestionQuery($match = false, $limit = 100) {
   		require_once('models/table/OptionList.php');
   		$courseTable = new OptionList(array('name' => 'training_title_option'));

    	$select = $courseTable->select()->from('training_title_option',array('id', 'training_title_phrase'));

/*
    	//join with trainings, so we don't return any course names that aren't used

        $select->setIntegrityCheck(false);
        $select->join(array('tt' => 'training_title_option'), "course.training_title_option_id = tt.id",array('course_name' => 'training_title_phrase'));
        $select->join(array('t' => 'training'), "course.id = t.course_id", array('course_id'));
        $select->distinct();
*/
    	//look for char start
    	if ( $match ) {
    		$select->where('training_title_phrase LIKE ? ', $match.'%');
     	}
     	$select->where('is_deleted = 0');
 //    	$select->group('course.training_title_option_id');
     	$select->order('training_title_phrase ASC');

     	if ( $limit )
    		$select->limit($limit,0);

     	$rows = $courseTable->fetchAll($select);

    	return $rows;
	}


}