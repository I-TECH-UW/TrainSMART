<?php
/*
 * Created on Dec 14, 2009
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */
require_once('ITechTable.php');

class Evaluation extends ITechTable
{
	protected $_primary = 'id';
	protected $_name = 'evaluation';

	public static function insertQuestions($parent_id, $text_array, $type_array, $optionalCustomAnswer_array = null) {
		$q_table = new ITechTable(array('name'=>'evaluation_question'));
		$a_table = new ITechTable(array('name'=>'evaluation_custom_answers'));
		foreach($text_array as $i => $q) {
			if ( !empty($text_array[$i]) && !empty($type_array[$i])) {
				$q_row = $q_table->createRow();
				$q_row->evaluation_id = $parent_id;
				$q_row->question_text = $text_array[$i];
				$q_row->question_type = $type_array[$i];
				$q_row->weight = $i;
				$id = $q_row->save();

				if ($id && isset($optionalCustomAnswer_array[$i]) && is_array($optionalCustomAnswer_array[$i]) && count($optionalCustomAnswer_array)) {
					foreach($optionalCustomAnswer_array[$i] as $answer) {
						if (trim($answer) === '')
							continue;
						$a_row = $a_table->createRow();
						$a_row->evaluation_id = $parent_id;
						$a_row->question_id   = $id;
						$a_row->answer_phrase = $answer;
						$a_row->save();
					}
				}
			}
		}
	}

	public static function updateQuestions($parent_id, $text_array, $type_array, $id_array = null, $optionalCustomAnswer_array = null) {
		$q_table = new ITechTable(array('name'=>'evaluation_question'));
		$a_table = new ITechTable(array('name'=>'evaluation_custom_answers'));

		//bugfix - drop custom answers first, custom_answer_table->delete() not working here some reason
		//extra greedy delete because there is now bad data in DB from this.
		//old: ("DELETE FROM evaluation_custom_answers WHERE evaluation_id=$parent_id and question_id=$id")
		$db = self::dbfunc();
		if ($parent_id)
			$db->query("DELETE FROM evaluation_custom_answers WHERE evaluation_id=$parent_id");  // remove old answers linked to the evaluation, not sure of a proper way to reuse the rows, seek() seems broken in this version of zend which makes it rather difficult, #TODO


		$existing = $q_table->fetchAll($q_table->select()->where("evaluation_id = $parent_id"));

		foreach($text_array as $i => $q) {
			if ( !empty($text_array[$i]) && !empty($type_array[$i])) {
				// find row
				$q_row = null;
				if ($id_array[$i] && $id_array[$i] != -1) {
					$q_row = $q_table->find($id_array[$i])->current();
				}
				if ($q_row == null){
					$q_row = $q_table->createRow();
				}
				// populate and save
				$q_row->evaluation_id = $parent_id;
				$q_row->question_text = $text_array[$i];
				$q_row->question_type = $type_array[$i];
				$q_row->weight = $i;
				$id = $q_row->save();

				if ($id && isset($optionalCustomAnswer_array[$i]) && is_array($optionalCustomAnswer_array[$i]) && count($optionalCustomAnswer_array)) {
					foreach($optionalCustomAnswer_array[$i] as $answer) {
						if (trim($answer) == '')
							continue;
						$a_row = $a_table->createRow();
						$a_row->evaluation_id = $parent_id;
						$a_row->question_id   = $id;
						$a_row->answer_phrase = $answer;
						$a_row->save();
					}
				}
			}
			else{
				// delete (empty text, and an id, should delete this question)
				if ($id_array[$i] && $id_array[$i] != -1)
					$q_table->find($id_array[$i])->current()->delete();
			}
		}
		return true;
	}

	public static function fetchAllQuestions($parent_id) {
		$question_table = new ITechTable ( array ('name' => 'evaluation_question' ) );
		$query = $question_table->select()->where('evaluation_id = ?', $parent_id)->order('weight');
		return $question_table->fetchAll($query);
	}

	public static function fetchCustomAnswers($evalid) {
		$question_table = new ITechTable ( array ('name' => 'evaluation_custom_answers' ) );
		$query = $question_table->select()->where('evaluation_id = ?', $evalid)->order('id');
		return $question_table->fetchAll($query);
	}

	public static function fetchRelatedCustomAnswers($evalid) {
		$question_table = new ITechTable ( array ('name' => 'evaluation_custom_answers' ) );
		$query = $question_table->select()->where('evaluation_id = ?', $evalid)->order('id');
		$ansArray = $question_table->fetchAll($query);
		// we dont really need the whole table, so lets just put this into an array ie: row[question_1] = array('custom1', 'custom2')
		if ($ansArray) {
			$ansArray = $ansArray->toArray();
			$rows = array();
			foreach($ansArray as $answerRow) {
				if(! isset($rows[$answerRow['question_id']]))
					$rows[$answerRow['question_id']] = array();
				$rows[$answerRow['question_id']][] = $answerRow['answer_phrase'];
			}
			$ansArray = $rows;
		}

		return ( count($ansArray) ? $ansArray : array() );
	}

	public static function fetchResponseAnswers($evaluation_response_id) {
		$question_table = new ITechTable ( array ('name' => 'evaluation_question_response' ) );
		return $question_table->fetchAll('evaluation_response_id = '.$evaluation_response_id);

	}

	public static function fetchAssignments($evaluation_id) {
		$q_table = new ITechTable(array('name'=>'evaluation_to_training'));
		$select = $q_table->select()
		->from($q_table->_name)
		->setIntegrityCheck(false)
		->where("evaluation_id = $evaluation_id");

		$rows = $q_table->fetchAll($select);
		$rtn = array();
		//just return an array of training ids
		foreach($rows as $r) {
			$rtn []= $r->training_id;
		}

		return $rtn;

	}

	/**
	 * Return evaluation_id and training_id from assignment row
	 *
	 * @param unknown_type $evaluation_to_training_id
	 */
	public static function fetchAssignment($evaluation_to_training_id) {
		$q_table = new ITechTable(array('name'=>'evaluation_to_training'));
		$select = $q_table->select()
		->from($q_table->_name)
		->setIntegrityCheck(false)
		->where("id = $evaluation_to_training_id");

		$rows = $q_table->fetchAll($select);
		//just return an array of training ids
		$r = $rows->current();
		if ($r)
			return array($r->evaluation_id, $r->training_id);

		return array(null,null);
	}

}
