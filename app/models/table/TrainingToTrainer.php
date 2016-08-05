<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechTable.php');

class TrainingToTrainer extends ITechTable
{
	protected $_primary = 'id';
  protected $_name = 'training_to_trainer';
  
  /**
   * Returns trainers in training session
   */
//   public static function getTrainers($training_id) {
//       $tableObj = new TrainingToTrainer();
  
//       $select = $tableObj->select()
//       ->from(array('ttt' => $tableObj->_name), array('id', 'trainer_id', 'duration_days'))
//       ->setIntegrityCheck(false)
//       ->join(array('p' => 'person'), "p.id = ttt.trainer_id",array('first_name','middle_name','last_name'))
//       ->where("ttt.training_id = $training_id")
//       ->order("last_name");
//       return $tableObj->fetchAll($select);
//   }

  /**
   * Returns trainers in training session
   * TA:107
   */
  public static function getTrainers($training_id) {
    $tableObj = new TrainingToTrainer();
    $select = $tableObj->select()
        ->from(array('ttt' => $tableObj->_name), array('id', 'trainer_id', 'duration_days'))
        ->setIntegrityCheck(false)
        ->join(array('p' => 'person'), "p.id = ttt.trainer_id",array('id as person_id','first_name','middle_name','last_name', 'birthdate'))
        ->join(array('f' => 'facility'), "f.id = p.facility_id",array('facility_name', 'location_id'))
        ->where("ttt.training_id = $training_id")
        ->order("last_name");
    return $tableObj->fetchAll($select);
  }

  /**
   * Add trainer to training session
   */
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



}
