<?php
/*
 * Created on Feb 14, 2008
 *
 *  Built for web
 *  Fuse IQ -- todd@fuseiq.com
 *
 */

require_once('ITechTable.php');


class Student extends ITechTable
{
	protected $_name = 'student';
	protected $_primary = 'id';

	public function createTableRow( array $data = array() ) {
		$row = parent::createRow($data);
		return $row;
	}

	public static function isReferenced($id) {
		return false;
	}

	

	
}

