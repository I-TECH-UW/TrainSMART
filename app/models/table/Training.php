<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
*/
require_once('ITechTable.php');

class Training extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'training';

	/**
	* Returns course name
	*/
	public function getCourseName($training_id) {
		if(!$training_id) {
			return '';
		}

		$select = $this->select()
		->from($this->_name, array())
		->setIntegrityCheck(false)
	//	->join(array('c' => 'course'), "$this->_name.training_title_option_id = c.id",'training_title_option_id')
		->join(array('t' => 'training_title_option'), "training_title_option_id = t.id",'training_title_phrase')
		->where("$this->_name.id = $training_id");

		$row = $this->fetchRow($select);
		return $row->training_title_phrase;
	}

	/**
	* Returns course/training title and title id
	*/
	public function getCourseInfo($training_id) {
		if(!$training_id) {
			return '';
		}

		$select = $this->select()
		->from($this->_name, array('training_title_option_id'))
		->setIntegrityCheck(false)
	//	->join(array('c' => 'course'), "$this->_name.training_title_option_id = c.id",'training_title_option_id')
		->join(array('t' => 'training_title_option'), "training_title_option_id = t.id",'training_title_phrase')
		->where("$this->_name.id = $training_id");

		return $this->fetchRow($select);
	}

	/**
	* Returns custom phrases
	*/
	public function getCustom($training_id) {
		$select = $this->select()
		->from($this->_name, array('id'))
		->setIntegrityCheck(false)
		->joinLeft(array('tc1' => 'training_custom_1_option'), "$this->_name.training_custom_1_option_id = tc1.id",'custom1_phrase')
		->joinLeft(array('tc2' => 'training_custom_2_option'), "$this->_name.training_custom_2_option_id = tc2.id",'custom2_phrase')
		->where("$this->_name.id = $training_id");

		return $this->fetchRow($select);
	}

	/**
	* Returns row with joins (for extended training info)
	*/
	public function getTrainingInfo($training_id) {

		$select = $this->select()
		->from($this->_name, array('*'))
		->setIntegrityCheck(false)
	//  ->join(array('c' => 'course'), "$this->_name.training_title_option_id = c.id",'training_title_option_id')
		->join(array('t' => 'training_title_option'),               "training_title_option_id = t.id",                        array('training_title' => 'training_title_phrase'))
		->joinLeft(array('tl' => 'training_location'),              "$this->_name.training_location_id = tl.id",             'training_location_name')
		->joinLeft(array('tg' => 'training_got_curriculum_option'), "$this->_name.training_got_curriculum_option_id = tg.id",'training_got_curriculum_phrase')
		->joinLeft(array('tlvl' => 'training_level_option'),        "$this->_name.training_level_option_id = tlvl.id",       'training_level_phrase')
		->joinLeft(array('torg' => 'training_organizer_option'),    "$this->_name.training_organizer_option_id = torg.id",    array('training_organizer' => 'training_organizer_phrase'))
		//->joinLeft(array('tt' => 'training_topic_option'), "$this->_name.training_topic_option_id = tt.id",'training_topic_phrase')
		->where("$this->_name.id = $training_id");
		$rowRay = $this->fetchRow($select);
		if ($rowRay)
			$rowRay = $rowRay->toArray();

		// now get pepfar
		$select = $this->select()
		->from('training_to_training_pepfar_categories_option', array())
		->setIntegrityCheck(false)
		->join(array('tp' => 'training_pepfar_categories_option'), "training_to_training_pepfar_categories_option.training_pepfar_categories_option_id = tp.id",'pepfar_category_phrase')
		->where("training_to_training_pepfar_categories_option.training_id = $training_id");

		$rows = $this->fetchAll($select);
		foreach($rows as $r) {
			$pepfar[] = $r->pepfar_category_phrase;
		}
		if(!isset($pepfar)) $pepfar = array('n/a');
		$rowRay['pepfar'] = implode(', ', $pepfar);

		// get training topic(s)
		$select = $this->select()
		->from('training_to_training_topic_option', array())
		->setIntegrityCheck(false)
		->join(array('tp' => 'training_topic_option'), "training_to_training_topic_option.training_topic_option_id = tp.id",'training_topic_phrase')
		->where("training_to_training_topic_option.training_id = $training_id AND training_topic_option_id != 0");

		$rows = $this->fetchAll($select);
		foreach($rows as $r) {
			$topic[] = $r->training_topic_phrase;
		}
		if(!isset($topic)) $topic = array('n/a');
		$rowRay['training_topic_phrase'] = implode(', ', $topic);

		return $rowRay;
	}



	/**
	* Returns single training topic
	*/
	public function getTrainingSingleTopic($training_id) {
		$select = $this->select()
		->from('training_to_training_topic_option', array('training_topic_option_id'))
		->setIntegrityCheck(false)
		->where("training_to_training_topic_option.training_id = $training_id AND training_topic_option_id != 0");

		$row = $this->fetchRow($select);
		return ($row) ? $row->training_topic_option_id : 0;
	}

	/**
	* Returns rows of a user's incomplete trainings (i.e., no trainers or no participants)
	* May also return all trainings user has created
	*/
	public function getIncompleteTraining($user_id, $showBudgetCode = false, $where = false, $having = "countTrainer = 0 OR countPerson = 0") {

		$select = $this->select()
			->from($this->_name, array('id', 'training_start_date'))
			->setIntegrityCheck(false)
			->join(array('tto' => 'training_title_option'), "training_title_option_id = tto.id",array('training_title' => 'training_title_phrase'))
			->joinLeft(array('tl' => 'training_location'), "$this->_name.training_location_id = tl.id",'training_location_name')
			->joinLeft(array('tt' => 'training_to_trainer'), "$this->_name.id = tt.training_id", array('countTrainer' => 'COUNT(tt.trainer_id)'))
			->joinLeft(array('pt' => 'person_to_training'), "$this->_name.id = pt.training_id", array('countPerson' => 'COUNT(pt.person_id)'))
			->joinLeft(array('uc' => 'user'), "$this->_name.created_by = uc.id", array('creator' =>"COALESCE(CONCAT(uc.first_name, ' ', uc.last_name), 'system')"))
			->group("$this->_name.id")
			->where("$this->_name.is_deleted = 0 AND has_known_participants = 1 " . (($where) ? " AND $where" : ''))
			->order("$this->_name.training_start_date DESC");

		if ($showBudgetCode) {
			// the group concat adds 5 seconds to a 6 second query on the tanzaniapartners database, so only query
			// for it when a site is using it (tanzaniapartners does not)
			$select->joinLeft(array('bc' => 'person_to_training_budget_option'), "bc.id = pt.budget_code_option_id", array('budget_code' => 'GROUP_CONCAT(DISTINCT budget_code_phrase)'));
		}

		$sql = $select->__toString();
		if($having) {
			$select->having($having);
		}

		return $this->fetchAll($select);
	}

	public function getUnapprovedTraining($where = false) {
		$where = $where ? ' AND ' .$where : "";

		$sql = "
		SELECT
		`training`.*,
		`t`.`training_title_phrase` AS `training_title`,
		`tl`.`training_location_name`,
		`ta`.`message`,
		CASE WHEN uc.id IS NULL THEN 'system' ELSE CONCAT(uc.first_name, ' ', uc.last_name) END  AS `creator`
		FROM `training`
		INNER JOIN `training_title_option` AS `t` ON training_title_option_id = t.id
		LEFT JOIN `training_location` AS `tl` ON training.training_location_id = tl.id
		INNER JOIN (SELECT MAX(id) as \"id\", training_id FROM `training_approval_history` GROUP BY training_id) AS `tamax` ON training.id = tamax.training_id
		INNER JOIN `training_approval_history` AS `ta` ON ta.id = tamax.id
		LEFT JOIN `user` AS `uc` ON training.created_by = uc.id
		WHERE
		(training.is_deleted = 0 AND is_approved = 0 ) $where
		GROUP BY
		`training`.`id`
		ORDER BY
		`training`.`training_start_date` DESC";
		return  $this->getAdapter()->fetchAll($sql);
	}

	/**
	* Duplicate training session
	*/
	public function duplicateTraining($training_id) {

		$select = $this->select()
		->from($this->_name, array('*'))
		->where("$this->_name.id = $training_id");

		$row = $this->fetchRow($select);

		$rowDup = $row->toArray();
		unset($rowDup['id']);
		unset($rowDup['uuid']);

		$dupId = $this->insert($rowDup);

	// Duplicate other tables now
		$db = $this->getDefaultAdapter();

	// pepfar
		$db->query("
			INSERT INTO training_to_training_pepfar_categories_option
			(id, training_id, training_pepfar_categories_option_id, duration_days, created_by, timestamp_created)
			SELECT 0, $dupId, training_pepfar_categories_option_id, duration_days, ".(Session::getCurrentUserId()).", now() ".
			" FROM training_to_training_pepfar_categories_option
			WHERE training_id = $training_id
			");

	// funding
		$db->query("
			INSERT INTO training_to_training_funding_option
			(id, training_id, training_funding_option_id, created_by, timestamp_created)
			SELECT 0, $dupId, training_funding_option_id, ".(Session::getCurrentUserId()).", now() ".
			" FROM training_to_training_funding_option
			WHERE training_id = $training_id
			");

	//added June 12,2008 ToddW
	// participants
		$db->query("
			INSERT INTO person_to_training
			(person_id, training_id, created_by, timestamp_created)
			SELECT person_id, $dupId,".(Session::getCurrentUserId()).", now() ".
			" FROM person_to_training
			WHERE training_id = $training_id
			");

	// trainers
		$db->query("
			INSERT INTO training_to_trainer
			(trainer_id, training_id, duration_days, created_by, timestamp_created)
			SELECT trainer_id, $dupId, duration_days,".(Session::getCurrentUserId()).", now() ".
			" FROM training_to_trainer
			WHERE training_id = $training_id
			");

	// todo custom1 option custom23 refresher


		return $dupId;
	}

	/**
	* import training session
	*/
	public function importTraining() {

		$select = $this->select()
		->from($this->_name, array('*'))
		->where("$this->_name.id = $training_id");

		$row = $this->fetchRow($select);

		$rowDup = $row->toArray();
		unset($rowDup['id']);
		unset($rowDup['uuid']);

		$dupId = $this->insert($rowDup);

	// import other tables now
		$db = $this->getDefaultAdapter();

	// pepfar
		$db->query("
			INSERT INTO training_to_training_pepfar_categories_option
			(id, training_id, training_pepfar_categories_option_id, duration_days, created_by, timestamp_created)
			SELECT 0, $dupId, training_pepfar_categories_option_id, duration_days, ".(Session::getCurrentUserId()).", now() ".
			" FROM training_to_training_pepfar_categories_option
			WHERE training_id = $training_id
			");

	// funding //todo refresher and title and other stuff (maybe?)
		$db->query("
			INSERT INTO training_to_training_funding_option
			(id, training_id, training_funding_option_id, created_by, timestamp_created)
			SELECT 0, $dupId, training_funding_option_id, ".(Session::getCurrentUserId()).", now() ".
			" FROM training_to_training_funding_option
			WHERE training_id = $training_id
			");

	//added June 12,2008 ToddW
	// participants
		$db->query("
			INSERT INTO person_to_training
			(person_id, training_id, created_by, timestamp_created)
			SELECT person_id, $dupId,".(Session::getCurrentUserId()).", now() ".
			" FROM person_to_training
			WHERE training_id = $training_id
			");

	// trainers
		$db->query("
			INSERT INTO training_to_trainer
			(trainer_id, training_id, duration_days, created_by, timestamp_created)
			SELECT trainer_id, $dupId, duration_days,".(Session::getCurrentUserId()).", now() ".
			" FROM training_to_trainer
			WHERE training_id = $training_id
			");



		return $dupId;
	}

	public function findFromParticipant($person_id, $orgListAllowed = "") {
		$select = $this->select()->from('training')->setIntegrityCheck(false);

		$select->from( '', "GROUP_CONCAT(tt.training_topic_phrase ORDER BY training_topic_phrase SEPARATOR ', ') AS topics");

		$select->join(array('ptot' => 'person_to_training'), 'training.id = ptot.training_id','ptot.training_id');
		$select->join(array('p' => 'person'),                'p.id = ptot.person_id','p.id');
		//$select->join(array('c' => 'course'), 'training.training_title_option_id = c.id', 'c.training_title_option_id');
		$select->join(array('t' => 'training_title_option'), "training_title_option_id = t.id",array('training_title' => 'training_title_phrase'));

		// Topics (using GROUP_CONCAT from above)
		$select->joinLeft(array('ttt' => 'training_to_training_topic_option'), "ttt.training_id = training.id",array());
		$select->joinLeft(array('tt' => 'training_topic_option'),              "tt.id = ttt.training_topic_option_id",array());
		$select->group("training.id");

		$select->join(array('tc' => 'training_location'),    'training.training_location_id = tc.id', 'training_location_name');

		$select->where('p.id = '.$person_id);
		$select->where('training.is_deleted = 0');
		$select->order('training_start_date DESC');

		$rows = $this->fetchAll($select);
		$rowArray = $rows->toArray();

		if($orgListAllowed) { // need to hide links to training if user has no rights to view organizer's trainings'
			$orgListAllowed = explode(',', $orgListAllowed);
		}

		foreach($rowArray as $key => $row) {
			if(!$row['topics']) {
				$rowArray[$key]['topics'] = '(n/a)';
			}
			if(is_array($orgListAllowed) && !in_array($row['training_organizer_option_id'], $orgListAllowed)) { // unset if no access
				$rowArray[$key]['training_id'] = null;
			}
		}
		return $rowArray;
	}

	public function findFromTrainer($person_id) {
		$select = $this->select()->from('training')->setIntegrityCheck(false);
		$select->join(array('ttot' => 'training_to_trainer'),
			'training.id = ttot.training_id','ttot.training_id');
		$select->join(array('tr' => 'trainer'),
			'ttot.trainer_id = tr.person_id','tr.person_id');
	//	$select->join(array('c' => 'course'),
	//  						'training.training_title_option_id = c.id', 'c.training_title_option_id');
		$select->join(array('t' => 'training_title_option'), "training_title_option_id = t.id",array('training_title' => 'training_title_phrase'));

		$select->joinLeft(array('tc' => 'training_location'),
			'training.training_location_id = tc.id', 'training_location_name');

		$select->where('tr.person_id = '.$person_id);
		$select->where('training.is_deleted = 0');
		$select->order('training_start_date DESC');
		$rows = $this->fetchAll($select);
		$rowArray = $rows->toArray();
		return $rowArray;
	}

	/**
	* Update person score in training
	*/
	public static function updateScore($ptt_id, $label, $value) {
		if(!is_numeric($ptt_id)) {
			return false;
		}

		$scoreTable = new Training(array('name' => 'score'));

		$select = $scoreTable->select()->from('score')->setIntegrityCheck(false);
		$select->where("person_to_training_id = $ptt_id AND score_label = ?", $label);
		$row = $scoreTable->fetchAll($select)->toArray();

	// update
		if($row) {
			$scoreTable->update(
				array('score_value' => $value),
				$scoreTable->getAdapter()->quoteInto("person_to_training_id = $ptt_id AND score_label = ?",$label)
				);
		}
	// insert
		else {
			$scoreTable->insert(array(
				'person_to_training_id' => $ptt_id,
				'score_label' => $label,
				'score_value' => $value,
				)
			);
		}
	}

	/**
	* Get list of trainings to assign
	* @var $where defaults to trainings from last 30 days
	*/
	public function getTrainingsAssign($person_id = false, $where = "training_start_date < NOW() AND training_start_date > DATE_SUB(NOW(), INTERVAL 30 DAY)") {

		$select = $this->_trainingsQuery();
		$select->joinLeft(array('ptt' => 'person_to_training'), "ptt.training_id = $this->_name.id", array('person_id'));
		$select->where("$this->_name.has_known_participants = 1 AND $this->_name.is_deleted = 0" . (($where) ? " AND $where" : ''));
		$rows = $this->fetchAll($select)->toArray();
		return $rows;

	}

	/**
	* Get list of trainings to assign
	* @var $where defaults to trainings from last 30 days
	*/
	public function getTrainings($where = '') {

		$select = $this->_trainingsQuery();
		if ($where)
			$select = $select->where($where);
		$rows = $this->fetchAll($select)->toArray();

		return $rows;
	}

	private function _trainingsQuery() {
		$select = $this->select()
		->from($this->_name, array('*', 'training_id' => 'id'))
		->setIntegrityCheck(false)
		->join(array('t' => 'training_title_option'), "`training`.training_title_option_id = t.id",array('training_title' => 'training_title_phrase'))
		->joinLeft(array('tl' => 'training_location'), "$this->_name.training_location_id = tl.id",'training_location_name')
		->joinLeft(array('to' => 'training_organizer_option'), "$this->_name.training_organizer_option_id = to.id", array('training_organizer_phrase'))
		->joinLeft(array('tcotto' => 'training_category_option_to_training_title_option'), "tcotto.training_title_option_id = t.id")
		->joinLeft(array('tc' => 'training_category_option'), "tc.id = tcotto.training_category_option_id", array('training_category_phrase'))
		->group("$this->_name.id")
		->where("$this->_name.is_deleted = 0" )
		->order("$this->_name.training_start_date DESC")
		->order("training_category_phrase ASC");

		return $select;
	}




}
