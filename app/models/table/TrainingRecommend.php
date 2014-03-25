<?php
/*
 * Created on May 18, 2000
 *
 *  Built for web
 *  Fuse IQ -- jonah@fuseiq.com
 *
 */
require_once('ITechTable.php');

class TrainingRecommend extends ITechTable
{
	protected $_primary = 'id';
  protected $_name = 'training_recommend';


	public function insert(array $data) {
	    require_once('models/Session.php');

	    $data['timestamp_created'] = new Zend_Db_Expr('NOW()');
	    $data['created_by'] = Session::getCurrentUserId();
	    $data['is_deleted'] = 0;
	    //don't set is_deleted
	    return Zend_Db_Table_Abstract::insert($data);
	}

	/**
	 * Saves recommended topics for person
	 */
	public function saveRecommendedforPerson($person_id, $training_ids) {
    if(!is_array($training_ids) || !$training_ids) {
     return;
    }

    $training_ids = array_unique($training_ids);

    $tableObj = new ITechTable(array('name' => 'person_to_training_topic_option'));

    $tableObj->delete("person_id=$person_id", true);

    foreach($training_ids as $id) {
      if($id) {
        $createRow = $tableObj->createRow();
        $createRow->person_id = $person_id;
        $createRow->training_topic_option_id = $id;
       	$createRow->save();
      }
    }
  }

	/**
	 * Returns recommended training topics for person
	 */
	public function getRecommendedforPerson($person_id) {
    if(!$person_id) return array();
    $tableObj = new TrainingRecommend();

    $select = $tableObj->select()
        ->from(array('pt' => 'person_to_training_topic_option'), array('training_topic_option_id'))
        ->setIntegrityCheck(false)
        ->join(array('tt' => 'training_topic_option'), "tt.id = pt.training_topic_option_id",array('training_topic_phrase'))
        ->where("pt.person_id = $person_id")
        ->order("training_topic_phrase")
        ;

    return $tableObj->fetchAll($select)->ToArray();
  }

  /**
   * Returns recommended training topics for qualification
   */
  public function getRecommendedTrainingTopics($qualification_id) {
    $tableObj = new TrainingRecommend();

    if(!$qualification_id) return array();

    $select = $tableObj->select()->distinct()
        ->from(array('tr' => 'training_recommend'), array('training_topic_option_id'))
        ->setIntegrityCheck(false)
        ->join(array('tt' => 'training_topic_option'), "tt.id = tr.training_topic_option_id",array('training_topic_phrase'))
        ->where("tr.person_qualification_option_id = $qualification_id AND tt.is_deleted = 0") //training_start_date > NOW()")
        ->order("training_topic_phrase")
        ;

    return $tableObj->fetchAll($select)->ToArray();
  }

  /**
   * Returns list of trainings for perosn
   */
  public function getRecommendedTrainingsforPerson($person_id) {
    if(!$person_id) return array();

    $tableObj = new TrainingRecommend();

    $select = $tableObj->select()
        ->from(array('tr' => 'person_to_training_title_option'), array())
        ->setIntegrityCheck(false)
       // ->join(array('c' => 'course'), "tr.training_title_option_id = c.training_title_option_id",'training_title_option_id')
        ->join(array('t' => 'training'), " tr.training_title_option_id = t.training_title_option_id",array('training_id' => 'id', 'training_start_date','training_title_option_id'))
        ->join(array('tt' => 'training_title_option'), "tt.id = c.training_title_option_id",array('training_title' => 'training_title_phrase'))
    	  ->join(array('tc' => 'training_location'), 't.training_location_id = tc.id', 'training_location_name')
        ->where("tr.person_id = $person_id AND t.is_deleted = 0 AND training_start_date > NOW()")
        ->order("training_start_date")
        ;

    return $tableObj->fetchAll($select)->ToArray();
  }

  /**
   * Returns pre-genereated recommended trainings
   */
  public function getRecommendedClasses($qualification_id) {
    $tableObj = new TrainingRecommend();

    $select = $tableObj->select()
        ->from(array('tr' => 'training_recommend'), array())
        ->from( '', "GROUP_CONCAT(tto.training_topic_phrase ORDER BY training_topic_phrase SEPARATOR ', ') AS topics")
        ->setIntegrityCheck(false)
        ->join(array('ttt' => 'training_to_training_topic_option'), "ttt.training_topic_option_id = tr.training_topic_option_id",array())
        ->join(array('t' => 'training'), " t.id = ttt.training_id",array('training_id' => 'id', 'training_start_date','training_title_option_id'))
   //     ->join(array('c' => 'course'), "t.training_title_option_id = c.id",'training_title_option_id')
        ->join(array('tt' => 'training_title_option'), "tt.id = t.training_title_option_id",array('training_title' => 'training_title_phrase'))

        ->join(array('tto' => 'training_topic_option'), "tto.id = ttt.training_topic_option_id",array())
    	  ->join(array('tc' => 'training_location'), 't.training_location_id = tc.id', 'training_location_name')

        ->where("tr.person_qualification_option_id = $qualification_id AND t.is_deleted = 0 AND training_start_date > NOW()")
        ->group("t.id")
        ->group("tr.person_qualification_option_id")
        ->order("training_start_date")
        ;

    return $tableObj->fetchAll($select)->ToArray();
  }

  /**
   * Returns recommended trainings for person, based on saved topics associated w/ person
   */
  public function getRecommendedClassesforPerson($person_id) {
    if(!$person_id) return array();

    $tableObj = new TrainingRecommend();

    $select = $tableObj->select()
        ->from(array('tttt' => 'person_to_training_topic_option'), array())
        ->from( '', "GROUP_CONCAT(tto.training_topic_phrase ORDER BY training_topic_phrase SEPARATOR ', ') AS topics")
        ->setIntegrityCheck(false)
        ->join(array('ttt' => 'training_to_training_topic_option'), "ttt.training_topic_option_id = tttt.training_topic_option_id",array())
        ->join(array('t' => 'training'), " t.id = ttt.training_id",array('training_id' => 'id', 'training_start_date','training_title_option_id'))
     //   ->join(array('c' => 'course'), "t.training_title_option_id = c.id",'training_title_option_id')
        ->join(array('tt' => 'training_title_option'), "tt.id = t.training_title_option_id",array('training_title' => 'training_title_phrase'))

        ->join(array('tto' => 'training_topic_option'), "tto.id = ttt.training_topic_option_id",array())
        ->join(array('too' => 'training_organizer_option'), "too.id = t.training_organizer_option_id",array('training_organizer_phrase'))
    	  ->join(array('tc' => 'training_location'), 't.training_location_id = tc.id', 'training_location_name')
   	 // ->join(array('tp' => 'location_province'), 'tc.location_province_id = tp.id', 'province_name')

        ->where("tttt.person_id = $person_id AND t.is_deleted = 0 AND training_start_date > NOW()")
        ->group("t.id")
        ->group("tttt.person_id")
        ->order("training_start_date")
        ;

    return $tableObj->fetchAll($select)->ToArray();
  }

public function getUpcomingTrainingTitles($filter_topic_id = false) {
     $tableObj = new TrainingRecommend();

    $select = $tableObj->select()->distinct()
        ->from(array('t' => 'training'),array())
        ->setIntegrityCheck(false)
       // ->join(array('ttt' => 'training_to_training_topic_option'), "ttt.training_id = t.id",array())
       // ->join(array('c' => 'course'), "t.training_title_option_id = c.id",'training_title_option_id')
        ->join(array('tt' => 'training_title_option'), "tt.id = t.training_title_option_id",array('training_title' => 'training_title_phrase', 'id'))
       // ->join(array('tto' => 'training_topic_option'), "tto.id = ttt.training_topic_option_id",array())
        ->where("t.is_deleted = 0 AND training_start_date > NOW()".($filter_topic_id?" AND tto.filter_topic_id = ".$filter_topic_id:""))
        ->order("training_start_date")
        ;

    return $tableObj->fetchAll($select)->ToArray();

}


  /**
   * Returns associations for recommended trainings (for admin page)
   */
  public static function getRecommendedAdmin() {
    $tableObj = new TrainingRecommend();

    $select = $tableObj->select()
        ->from(array('tr' => $tableObj->_name), array('id', 'person_qualification_option_id'))
        ->from( '', "GROUP_CONCAT(tt.training_topic_phrase ORDER BY training_topic_phrase SEPARATOR '<br>') AS topics")
        ->setIntegrityCheck(false)
        ->join(array('pq' => 'person_qualification_option'), "pq.id = tr.person_qualification_option_id",array('qualification_phrase'))
        ->join(array('tt' => 'training_topic_option'), "tt.id = tr.training_topic_option_id",array())
        ->group("person_qualification_option_id")
        ->order("qualification_phrase")
        ->order("training_topic_phrase");


    /*
    $select = $tableObj->select()
        ->from(array('tr' => $tableObj->_name), array('id', 'person_qualification_option_id', 'training_topic_option_id'))
        ->setIntegrityCheck(false)
        ->join(array('pq' => 'person_qualification_option'), "pq.id = tr.person_qualification_option_id",array('qualification_phrase'))
        ->join(array('tt' => 'training_topic_option'), "tt.id = tr.training_topic_option_id",array('training_topic_phrase'))
        ->order("qualification_phrase")
        ->order("training_topic_phrase");
    */

    return $tableObj->fetchAll($select)->ToArray();
  }

  /**
   * Returns primary qualification ids (for drop-down on admin page)
   */
  public static function getQualificationIds() {
    $tableObj = new TrainingRecommend();

    $select = $tableObj->select()
        ->from(array('pq' => 'person_qualification_option'), array('id'))
        ->setIntegrityCheck(false)
        ->where("parent_id IS NULL")
        ->order("qualification_phrase");

    $rows = $tableObj->fetchAll($select);

    $ids = array();
    foreach($rows as $row) {
      $ids[] = $row->id;
    }

    return $ids;

  }

  /**
   * Save Recommendations (for admin page)
   */
  public static function saveRecommendations($qualification_id, array $topic_ids) {
    if(!is_numeric($qualification_id)) {
      return;
    }

    $topic_ids = array_unique($topic_ids);

    $tableObj = new TrainingRecommend();

    $tableObj->delete("person_qualification_option_id=$qualification_id", true);

    foreach($topic_ids as $id) {
      if($id) {
  	    $createRow = $tableObj->createRow();
  	    $createRow->person_qualification_option_id = $qualification_id;
  	    $createRow->training_topic_option_id = $id;
       	$createRow->save();
      }
    }
  }

  /**
   * Get Recommendations for Qualification
   */
  public static function getRecommendations($qualification_id) {
    if($qualification_id == '') {
      return;
    }

    $tableObj = new TrainingRecommend();
    $select = $tableObj->select()
        ->from(array('tr' => $tableObj->_name), array('id', 'person_qualification_option_id', 'training_topic_option_id'))
        ->setIntegrityCheck(false)
        ->join(array('tt' => 'training_topic_option'), "tt.id = tr.training_topic_option_id",array('training_topic_phrase'))
        ->where("person_qualification_option_id=$qualification_id")
        ->order("training_topic_phrase");

    return $tableObj->fetchAll($select);
  }

  /**
   * Get topics with qualification recommendations
   */
  public static function getTopics() {
    $tableObj = new TrainingRecommend();
    $select = $tableObj->select()
        ->from(array('tr' => $tableObj->_name), array('training_topic_option_id'))
        ->setIntegrityCheck(false)
        ->join(array('tt' => 'training_topic_option'), "tt.id = tr.training_topic_option_id",array('id','training_topic_phrase'))
        ->group('training_topic_option_id')
        ->order("training_topic_phrase");

    return $tableObj->fetchAll($select);
  }


  /**
   * Returns trainers in training session

  public static function getTrainers($training_id) {
    $tableObj = new TrainingToTrainer();

    $select = $tableObj->select()
        ->from(array('ttt' => $tableObj->_name), array('id', 'trainer_id', 'duration_days'))
        ->setIntegrityCheck(false)
        ->join(array('p' => 'person'), "p.id = ttt.trainer_id",array('first_name','middle_name','last_name'))
        ->where("ttt.training_id = $training_id")
        ->order("last_name");

    return $tableObj->fetchAll($select);
  }
  * */

  /**
   * Add trainer to training session

  public static function addTrainerToTraining($trainer_id, $training_id, $duration_days) {
    $tableObj = new TrainingToTrainer();

    $select = $tableObj->select()
                ->from($tableObj->_name, array('doesExist' => 'COUNT(*)'))
                ->setIntegrityCheck(false)
                ->where("trainer_id = $trainer_id AND training_id = $training_id");

    $row = $tableObj->fetchRow($select);

    if($row->doesExist) {
      return -1;
    } else {

      $data['trainer_id'] = $trainer_id;
      $data['training_id'] = $training_id;
      $data['duration_days'] = $duration_days;

      try {
        return $tableObj->insert($data);
      } catch(Zend_Exception $e) {
        error_log($e);
      }
    }
  }
  * */



}
