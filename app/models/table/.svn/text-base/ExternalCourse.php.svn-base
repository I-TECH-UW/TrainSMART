<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechTable.php');

class ExternalCourse extends ITechTable
{
	protected $_primary = 'id';
  protected $_name = 'external_course';



	public function findFromParticipant($person_id) {
    	$select = $this->select()->from('external_course');

       	$select->where('person_id = '.$person_id);
     	$select->order('training_start_date DESC');
     	$rows = $this->fetchAll($select);
     	$rowArray = $rows->toArray();
  		return $rowArray;
	}


	public function insert(array $data) {
	    $data['training_length_interval'] = 'day';

	    return parent::insert($data);
	}
	
}
