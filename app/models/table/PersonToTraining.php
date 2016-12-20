<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechTable.php');

class PersonToTraining extends ITechTable
{
	protected $_primary = 'id';
  protected $_name = 'person_to_training';

  /**
   * Returns trainers in training session
   */
  public static function getParticipants($training_id) {
    $tableObj = new PersonToTraining();

    $select = $tableObj->select()
        ->from(array('ptt' => $tableObj->_name), array('id', 'person_id', 'duration_days', 'award_id', 'score_percent_change' => new Zend_Db_Expr('ROUND((spost.score_value - spre.score_value) / spre.score_value * 100)')))
        ->setIntegrityCheck(false)
        ->join(array('p' => 'person'), "p.id = ptt.person_id",array('p.first_name','p.middle_name', 'p.last_name','p.birthdate', 'p.phone_home')) //TA:#317
        //->join(array('f' => 'facility'), "p.facility_id = f.id",array('facility_name', 'location_id')) //TA:17: 10/15/2014 - commented: display only persons with known facility id
        ->joinLeft(array('f' => 'facility'), "p.facility_id = f.id",array('facility_name', 'location_id')) //TA:17: 10/15/2014 
        ->joinLeft(array('pq' => 'person_qualification_option'), "p.primary_qualification_option_id = pq.id",array('qualification' => 'qualification_phrase'))
        ->joinLeft(array('pq2' => 'person_qualification_option'), "pq.parent_id = pq2.id",array('primary_qualification' => 'qualification_phrase'))
    //    ->joinLeft(array('pr' => 'person_responsibility_option'), "p.primary_responsibility_option_id = pr.id",array('primary_responsibility'=>'responsibility_phrase'))
   //     ->joinLeft(array('sr' => 'person_responsibility_option'), "p.secondary_responsibility_option_id = sr.id",array('secondary_responsibility'=>'responsibility_phrase'))
   //     ->joinLeft(array('l' => 'location'), "f.location_id = l.id",array('location_id'))
        ->joinLeft(array('spre' => 'score'), "spre.person_to_training_id = ptt.id AND spre.score_label = 'Pre-Test'", array('score_pre' => 'score_value'))
        //TA:#271 get pass_fail also
//         ->joinLeft(array('spost' => 'score'), "spost.person_to_training_id = ptt.id AND spost.score_label = 'Post-Test'", array('score_post' => 'score_value'))
    ->joinLeft(array('spost' => 'score'), "spost.person_to_training_id = ptt.id AND spost.score_label = 'Post-Test'", array('score_post' => 'score_value', 'pass_fail'=>'pass_fail'))
        ->joinLeft(array('scoreother' => 'score'), "scoreother.person_to_training_id = ptt.id AND scoreother.score_label != 'Post-Test' AND scoreother.score_label != 'Pre-Test'", array('score_other_k' => 'GROUP_CONCAT(scoreother.score_label)', 'score_other_v' => 'GROUP_CONCAT(scoreother.score_value)'))
        ->joinLeft(array('award'   => 'person_to_training_award_option'),        "award.id   = award_id"                  ,  array('award_phrase' => 'award_phrase'))
        ->joinLeft(array('budget'  => 'person_to_training_budget_option'),       "budget.id  = budget_code_option_id"     ,  array('budget_code_phrase' => 'budget_code_phrase'))
        ->joinLeft(array('viewloc' => 'person_to_training_viewing_loc_option'),  "viewloc.id = viewing_location_option_id",  array('location_phrase' => 'location_phrase'))
       // ->where("ptt.training_id = $training_id")
        ->where("ptt.training_id = $training_id and p.is_deleted=0") //TA:21: 09/29/2014
        ->group("ptt.id")
        ->order("last_name");
    print $select;
    return $tableObj->fetchAll($select);
  }

  /**
   * Returns array of peron ids who took a course by a name
   */
  public static function getParticipantsByCourseName($training_title) {
    $tableObj = new PersonToTraining();

    $select = $tableObj->select()
        ->from(array('c' => 'course'), array())
        ->setIntegrityCheck(false)
        ->join(array('t' => 'training'), "t.training_title_option_id = c.id")
        ->join(array('ptt' => 'person_to_training'), "ptt.training_id = t.id", array('person_id'))
        ->join(array('tto' => 'training_title_option'), "tto.id = c.training_title_option_id", array('person_id'))
        ->where("tto.training_title_phrase = ?", "{$training_title}");

    $ids = array();
    $rows = $tableObj->fetchAll($select);
    foreach($rows as $r) {
      $ids[] = $r->person_id;
    }

    return $ids;
  }


  /**
   * Add person to training session
   */
  public function addPersonToTraining($person_id, $training_id) {
   	$select = $this->select()
                ->from($this->_name, array('doesExist' => 'COUNT(*)'))
                ->setIntegrityCheck(false)
                ->where("person_id = $person_id AND training_id = $training_id");

    $row = $this->fetchRow($select);

    if($row->doesExist) {
      return -1;
    } else {

      //make sure person isn't deleted
      $person = new Person();
      $prows = $person->find($person_id);
      if ($prows )
        $prow = $prows->current();
      if ( (!$prows) || (!$prow) || $prow->is_deleted )
        return 0;


      $data['person_id'] = $person_id;
      $data['training_id'] = $training_id;

      try {
        return $this->insert($data);
      } catch(Zend_Exception $e) {
        error_log($e);
      }
    }
  }


}
