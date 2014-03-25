<?php
/*
 * Created on Sept 7 2012
 * 
 *  Training Partner Module - Table Model
 *  
 *  status:
 *  - suggestion list and query functions break, but are not used
 *  - isUnique function is not used
 */

require_once ('ITechTable.php');

class TrainingPartner extends ITechTable {
	protected $_name = 'organizer_partners';
	protected $_primary = 'id';

	/* table structure...
	 *	id
	 *	organizer_id
	 *	partner1_name
	 *	subpartner
	 *	mechanism_id
	 *	funder_name
	 *	funder_id
	 */

	/**
	 * Returns false if the name already exists
	 */
	public static function isUnique($partnerName, $id = false) {
		$partner = new TrainingPartner ( );
		$select = $partner->select ();
		$select->where ( "partner_name = ?", $partnerName );
		if ( $id )
		  $select->where ( "id != ?", $id);
		if ($partner->fetchRow ( $select ))
			return false;
		
		return true;
	
	}
	
	/**
	 * Returns a output friendly hash of rows and schema from a LIKE query
	 * fieldname => value
	 * /
	public static function suggestionList($match = false, $limit = 100) {
		$rows = self::suggestionQuery ( $match, $limit );
		$rowArray = $rows->toArray ();

		return $rowArray;
	}
	*/
	/**
	 * Returns a db (resultset?) object of rows and schema from a LIKE query
	 * /
	protected static function suggestionQuery($match = false, $limit = 100) {
		require_once ('models/table/OptionList.php');
		$trainingpartnerTable = new OptionList ( array ('name' => 'organizer_partners' ) );
		
		$select = $trainingpartnerTable->select ()->from ( 'organizer_partners', array ('organizer_id', 'partner1_name', 'subpartner', 'mechanism_id', 'funder_name', 'funder_id') )->setIntegrityCheck ( false );
		
		//look for char start
		if ($match) {
			$select->where ( 'organizer_id LIKE ? ', $match . '%' );
		}
		$select->where ( 'organizer_partners.is_deleted = 0' );
		$select->order ( 'organizer_id ASC' );
		
		if ($limit)
			$select->limit ( $limit, 0 );
		
		$rows = $trainingpartnerTable->fetchAll ( $select );

		return $rows;
	}
*/
}
 