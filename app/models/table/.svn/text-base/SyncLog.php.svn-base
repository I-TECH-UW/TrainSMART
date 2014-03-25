<?php
/*
 * Created on Oct 22, 2010
 *
 *  Built for web
 *  Fuse IQ -- nick@fuseiq.com
 *
 */

require_once ('ITechTable.php');

class SyncLog extends ITechTable
{
	protected $_name = 'synclog';
	protected $_primary = 'id';

	protected $_fid = null;

	function __construct($syncFileId)
	{
		$this->_fid = $syncFileId;
		parent::__construct();
	}

	
	/*
	 * List of server db items to act on. 
	 * Use leftid NO rightid, rightid NO leftid to update, insert or delete records.
	 * @return log rows collection   
	 */
	public function pendingList($table_filter = false)
	{
		// only return updates to do
		$where = '(fid = '. $this->_fid .' AND timestamp_completed IS NULL)';
		if ( $table_filter )
			$where .= " AND item_type = '".$table_filter."' ";
		
		$result = $this->fetchAll($where);
		return $result;
	}

	/*
	 * List of server db items to act on. 
	 * Use leftid NO rightid, rightid NO leftid to update, insert or delete records.
	 * @return log rows collection   
	 */
	public function pendingTotals()
	{
		// only return updates to do
		$db = $this->getAdapter();
		$sql = "SELECT item_type, 
				COUNT(synclog.action) AS cnt,
				`action` 
			FROM synclog
			WHERE fid = ".$this->_fid." AND timestamp_completed IS NULL AND `action` != 'table-diff-complete'
			GROUP BY item_type, `action`";
		$results = $db->query($sql);
		if ( $results )
			return $results->fetchAll();
			
		return null;
	}
	
	/*
	 * Store source id, destination id and completed flag, for jobs.
	 * The search can run several times, if we already have a log entry update it instead. 
	 * @return log entry id
	 */
	public function add($itemType = null, $leftItemId = null, $rightItemId = null, $action = null, $userMessage = null, $ldata = null, $rdata = null)
	{
		$save = array(
			'fid' => $this->_fid, 
			'item_type' => $itemType, 
			'left_id' => $leftItemId, 
			'right_id' => $rightItemId, 
			'action' => $action, 
			'message' => $userMessage,
			'left_data' => serialize($ldata),
			'right_data' => serialize($rdata),
		);

		$result = $this->insert($save);
		return $result;
	}
	
	public function addTableCompleteMessage($table) {
		$save = array('fid' => $this->_fid, 'item_type'=> $table, 'action' => 'table-diff-complete');
		$result = $this->insert($save);
		return $result;
		
	}
	
	/*
	 * Remove entry from log que, so it doesn't update.
	 * @return 
	 */
	public function markSkip($logRowId, $skip_it = 0)
	{
		$where = '(id="'. $logRowId .'")';
		return $this->update(array('is_skipped' => ($skip_it?1:0)), $where);
	}
	
	/*
	 * Mark the update item as done.
	 * @return 
	 */
	public function markDone($logRowId = null)
	{
		$where = '(id="'. $logRowId .'")';
		$time = $this->now_expr();
		return $this->update(array('timestamp_completed' => $time), $where);
	}
	
	/*
	 * Return number of tables processed
	 */
	public function getDiffStatus()
	{
		/*
		$select = $this->select()->from($this, array('COUNT(*) as count'))->where('(fid="'. $this->_fid .'" AND timestamp_completed IS NULL)');
		$rowPending = $this->fetchRow($select);
		
		$select = $this->select()->from($this, array('COUNT(*) as count'))->where('(fid="'. $this->_fid .'" AND timestamp_completed IS NOT NULL)');
		$rowCompleted = $this->fetchRow($select);
		
		$message = 'Status... '. $rowPending->count .' items searched and '. $rowCompleted->count .' items completed. ';
		if($rowCompleted->count > 0 && $rowCompleted->count >= $rowPending->count) {
			$message .= 'Done';
		}
		*/
		
		$select = $this->select()->from($this, array('COUNT(*) as count'))->where("action = 'table-diff-complete' AND fid=".$this->_fid." AND timestamp_completed IS NULL");
		$rowCompleted = $this->fetchRow($select);
		
		return $rowCompleted->count;
	}
	
	/*
	 * Flush list of server db items to act on. 
	 * @return 
	 */
	public function truncate()
	{
		// only flush job items that have not been done
		$where = '(fid = "'. $this->_fid .'" AND timestamp_completed IS NULL)';
		$result = $this->delete($where);
		return $result;
	}
	

}


