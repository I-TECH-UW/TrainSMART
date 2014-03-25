<?php
/*
 * Created on Feb 14, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */

require_once('ITechTable.php');

class Course extends ITechTable
{
  
  protected $_name = 'course';
  protected $_primary = 'id';
  
  /**
   * Search by name and get course ID.  If does not exist, insert course. 
   */   
  public static function insertIfNotFound($training_title_option_id)
  {
    if(!is_numeric($training_title_option_id)) {
      die('Must update Course::insertIfNotFound call.');
    }
    
    $tableObj = new Course();
    $select = $tableObj->select($tableObj->_name, $tableObj->_primary)->where("training_title_option_id = ?", $training_title_option_id);
    
    $row = $tableObj->fetchRow($select);
    
    if(!$row) { // insert
      return $tableObj->insert(array('training_title_option_id' => $training_title_option_id));    
    } else {
      return $row->id;  
    }
          
  }
  
  /**
   * If no training associations, delete course. 
   */   
  public static function deleteIfNotFound($training_title_option_id, $training_id  = 0) {    
    
    $tableObj = new Course();
    $select = $tableObj->select()
      ->from('training', array('COUNT(id) AS training_count'))
      ->setIntegrityCheck(false)    
      ->where("course_id = $training_title_option_id AND id != $training_id");
    
    $row = $tableObj->fetchRow($select);
    if(!$row->training_count) {
      $tableObj = new Course();
      $tableObj->delete("training_title_option_id = $training_title_option_id");  

      $tableObj = new ITechTable(array('name' => 'training_title_option'));
      $tableObj->delete("id = $training_title_option_id");
    }
    
  }  
  
  /**
   * Return course ID based on Training Title 
  public static function getCourseId($name) {
    $select = $this->select()
        ->from($this->_name, array())
        ->setIntegrityCheck(false)
        ->join(array('c' => 'course'), "$this->_name.course_id = c.id",'course_name')
        ->where("$this->_name.id = $training_id");
  }  
   */  
  
	public static function suggestionList($match = false, $limit = 100) {
     	$rows = self::suggestionQuery($match,$limit);
    	$rowArray = $rows->toArray();
    		//	unset 'unknown'
    	foreach($rowArray as $key => $row) {
    		if ( $row['course_name'] == 'unknown' )
    			unset($rowArray[$key]);
    	}
    	
    	return $rowArray;
	}
	
	protected static function suggestionQuery($match = false, $limit = 100) {
   		require_once('models/table/OptionList.php');
   		$courseTable = new OptionList(array('name' => 'course'));
    		
    	$select = $courseTable->select()->from('course',array('id', 'training_title_option_id'));   	
    	
    	
    	//join with trainings, so we don't return any course names that aren't used
    	
        $select->setIntegrityCheck(false);
        $select->join(array('tt' => 'training_title_option'), "course.training_title_option_id = tt.id",array('course_name' => 'training_title_phrase'));        
        $select->join(array('t' => 'training'), "course.id = t.course_id", array('course_id'));
        $select->distinct();
    	
    	//look for char start
    	if ( $match ) {
    		$select->where('course_name LIKE ? ', $match.'%');
     	}
     	$select->where('course.is_deleted = 0 AND tt.is_deleted = 0');
     	$select->group('course.training_title_option_id');
     	$select->order('course_name ASC');
     	
     	if ( $limit )
    		$select->limit($limit,0);
    	
     	$rows = $courseTable->fetchAll($select);
    	
    	return $rows;
	} 
  

}