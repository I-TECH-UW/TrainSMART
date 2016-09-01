<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */


class Trainer extends ITechTable
{
  protected $_primary = 'person_id';
  protected $_name = 'trainer';

	public static function suggestionList($match = false, $limit = 100, $middleNameLast = false, $priority = array('last_name','first_name','middle_name')) {
		if ( !$middleNameLast )
		    //TA:113
			//$additionalCols = array('p.first_name','p.middle_name','p.last_name','p.id');
		    $additionalCols = array('p.first_name','p.middle_name','p.last_name','p.id','f.facility_name','f.location_id', 'p.birthdate');
		else
		    //TA:113
			//$additionalCols = array('p.first_name','p.last_name','p.middle_name','p.id');
		    $additionalCols = array('p.first_name','p.last_name','p.middle_name','p.id','f.facility_name','f.location_id', 'p.birthdate');

		$rowArray = array();

		foreach( $priority as $keyrow ) {
	   	if ( count($rowArray) < $limit ) {
	   		$select = array('p.'.$keyrow.' as key');
	   		$select = array_merge($select, $additionalCols);
	    		$rows = self::suggestionQuery($match,$limit, $keyrow, $select);
	    		$rowArray  += $rows->toArray();
	    	}
 		}

    	return $rowArray;
	}

	public static function suggestionListByFirstName($match = false, $limit = 100, $middleNameLast = false) {
		return self::suggestionList($match,$limit,$middleNameLast, array('first_name','last_name','middle_name'));
	}

	public static function suggestionListByMiddleName($match = false, $limit = 100, $middleNameLast = false) {
		return self::suggestionList($match,$limit,$middleNameLast, array('middle_name','last_name','first_name'));
	}


  /**
   * Returns ALL people, even if they are not a trainer.  Adds flag if they are not.
   */
	protected static function suggestionQuery($match = false, $limit = 100, $field = 'last_name', $fieldsSelect = array('p.last_name','p.first_name','p.id'), $fieldAdditional = false) {
   		require_once('models/table/OptionList.php');
   		$topicTable = new OptionList(array('name' => 'trainer'));

      $select = $topicTable->select()->from(array('p'=>'person'),$fieldsSelect)->setIntegrityCheck(false);
  		$select->joinLeft(array('t' => 'trainer'),
       						"p.id = t.person_id", array('is_trainer' => '(!ISNULL(t.type_option_id))'));
  		
  		//TA:113
  		if ( count($fieldsSelect) > 1 ) { //if there's only one field, then assume we just want distinct names and nothing else
  		    $select->setIntegrityCheck(false)
  		    ->join(array('f' => 'facility'), "p.facility_id = f.id",array('facility_name'))
  		    //->join(array('l' => 'location'), "f.location_id = l.id",array('location_id', 'p.birthdate'))
  		    ;
  		}

    	//look for char start
    	if ( $match ) {
    		$select->where("$field LIKE ? ", $match.'%');
    		if ($fieldAdditional) {
    		  $select->orWhere("$fieldAdditional LIKE ? ", $match.'%');
        	}
     	}
     	$select->where('p.is_deleted = 0');
    	$select->order("$field ASC");
     //	foreach($fieldsSelect as $otherfield) {
     		$select->order( "last_name ASC" );
     		$select->order( "first_name ASC" );
     		$select->order( "middle_name ASC" );
     //	}

     	if ( $limit )
    		$select->limit($limit,0);

     	$rows = $topicTable->fetchAll($select);


 
    	return $rows;
	}
	
  public function update(array $data,$where) {
   //save a snapshot now
    require_once('History.php');
    $historyTable = new History('trainer');
    //cheezy way to get the id
    $parts = explode('=',$where[0]);
    $pid = trim($parts[1]);
    
    //link to the last history row
    $personHistoryTable = new History('person');
    $hrow = $personHistoryTable->fetchAll( "person_id = $pid" ,"vid DESC" ,1);
    
    $historyTable->tableInsert($this, $hrow->current()->vid);
    
    $rslt = parent::update($data,$where);

    return $rslt;
  }
}
