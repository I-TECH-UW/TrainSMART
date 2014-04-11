<?php
require_once 'Output/Abstract.php';
require_once 'Zend/Json.php';
/**
 * Output_Json
 *
 * processor to take an array payload and convert it to JSON.
 */
class Output_Json extends Output_Abstract {
	
	/**
	 * __construct
	 *
	 * entry point for this processor.
	 *
	 * @param Array $input        	
	 *
	 */
	public function __construct($payload) {
		parent::__construct ();
		$this->input = $payload;
		$this->main ();
	} // public function __construct($payload)
	
	/**
	 * main
	 *
	 * convert the payload to JSON using Zend_Json
	 */
	public function main() {
		$this->payload = Zend_Json::encode ( $this->input );
		$this->headers ['Content-Type'] = 'application/json';
		return;
	} // public function main()
}
?>