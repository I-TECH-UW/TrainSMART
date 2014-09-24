<?php
/*
 * Created on Feb 14, 2008
 * 
 *  Built for web  
 *  Fuse IQ -- todd@fuseiq.com
 *  
 */

require_once ('ITechTable.php');

class Partner extends ITechTable {
	protected $_name = 'partner';
	protected $_primary = 'id';
	

		public static function getAll()
		{
			$pTable = new Partner();
			// $select = $pTable->select()->where("is_deleted = 0");
			return  $pTable->fetchAll($select);
		}
	

}
 