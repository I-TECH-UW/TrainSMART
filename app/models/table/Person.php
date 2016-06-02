<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

require_once('ITechTable.php');


class Person extends ITechTable
{
	protected $_name = 'person';
	protected $_primary = 'id';

	public function createTableRow( array $data = array() ) {
		$row = parent::createRow($data);
		if ( !isset($data['active']) ) {
			$row->active = 'active';
		}

		return $row;
	}

	public static function isReferenced($id) {
		require_once('PersonToTraining.php');

		$participant = new PersonToTraining();
		$select = $participant->select();
		$select->where("person_id = ?",$id);
		if ( $participant->fetchRow($select) )
		return true;

		require_once('TrainingToTrainer.php');

		$trainer = new TrainingToTrainer();
		$select = $trainer->select();
		$select->where("trainer_id = ?",$id);
		if ( $trainer->fetchRow($select) ) {
			return true;
		}

		return false;
	}

	public function getPersonName($person_id) {
		$select = $this->select()
		->from($this->_name, array('first_name', 'middle_name','last_name'))
		->where("id = $person_id");
		return $this->fetchRow($select);
	}

	//TA: use in 'where' and
	public static function tryFind ($first, $middle, $last)
	{
		$first = trim($first);
		$middle = trim($middle);
		$last = trim ($last);

		if ($first == '' && $middle == '' && $last == '')
			return null;

		$p = new Person();
		$select = $p->select()->from($p->_name, array('id', 'first_name', 'middle_name','last_name'));

		if( $first )  $select = $select->where("first_name like ?", $first);
		if( $middle ) $select = $select->where("middle_name like ?", $middle);
		if( $last )   $select = $select->where("last_name like ?", $last);

		$res = $p->fetchRow($select);
		return $res->id ? $res->id : null;
	}

	public static function suggestionList($match = false, $limit = 100, $middleNameLast = false, $priority = array('last_name','first_name','middle_name')) {
		if ( !$middleNameLast )
		$additionalCols = array('p.first_name','p.middle_name','p.last_name','p.id','f.facility_name','f.location_id', 'p.birthdate');
		else
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

	public static function suggestionFindDupes($match_last_name, $limit = 100, $middleNameLast = false, $fieldAndWhere = array()) {
    $additionalCols = array('p.first_name','p.last_name','p.middle_name','person_id' => 'p.id','f.facility_name','p.national_id', 'p.birthdate','p.gender', 'q.qualification_phrase', 'p.file_number');
		$rows = self::suggestionQuery($match_last_name, $limit, "last_name", $additionalCols, false, $fieldAndWhere);
		return $rows->toArray();
	}

	public static function suggestionQuery($match = false, $limit = 100, $field = 'last_name', $fieldsSelect = array('p.last_name','p.first_name','p.birthdate'), $fieldAdditional = false, $fieldAndWhere = false) {

		require_once('models/table/OptionList.php');
		$topicTable = new OptionList(array('name' => 'person'));

		$select = $topicTable->select()->distinct()
		->from(array('p' => 'person'),$fieldsSelect);

		if ( count($fieldsSelect) > 1 ) { //if there's only one field, then assume we just want distinct names and nothing else
			$select->setIntegrityCheck(false)
			->join(array('f' => 'facility'), "p.facility_id = f.id",array('facility_name'))
			//->join(array('l' => 'location'), "f.location_id = l.id",array('location_id', 'p.birthdate'))
			;
		}
        if (array_search('q.qualification_phrase', $fieldsSelect))
            //TA:87 use left join to retrieve duplicate persons list, otherwise it works for 'demo', but does not work for 'cham'
            $select->setIntegrityCheck(false)->joinLeft(array('q' => 'person_qualification_option'), 'p.primary_qualification_option_id = q.id');

		$select->where(' p.is_deleted = 0');

		//look for char start
		if ( $match ) {
			$select->where("$field LIKE ? ", $match.'%');
			if ($fieldAdditional) {
				$select->orWhere("$fieldAdditional LIKE ? ", $match.'%');
			}
		}

		if($fieldAndWhere) {
			foreach($fieldAndWhere as $fieldname => $matchstring) {
				$select->where("$fieldname LIKE ? ", $matchstring.'%');
			}
		}

		//$select->where('trainer.is_deleted = 0 AND trainer.is_active = 1');

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
		$historyTable = new History('person');
		//cheezy way to get the id
		$parts = explode('=',$where[0]);
		$historyTable->tableInsert($this, trim($parts[1]));

		$rslt = parent::update($data,$where);

		return $rslt;
	}
}

