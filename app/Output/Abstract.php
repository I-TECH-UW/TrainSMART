<?php
/**
 * Output_Abstract
 *
 * base class for all output processors.
 *
 */
abstract class Output_Abstract {
	/**
	 * the input from the controller
	 *
	 * @var array
	 */
	protected $input;
	
	/**
	 * the final output
	 *
	 * @var string
	 */
	protected $payload;
	
	/**
	 * the http headers to be set
	 *
	 * @var array
	 */
	protected $headers;
	
	/**
	 * __construct
	 *
	 * Initalize any needed variables.
	 */
	public function __construct() {
		$this->headers = array ();
	}
	
	/**
	 * main
	 *
	 * the main body of processing goes here.
	 */
	abstract public function main();
	
	/**
	 * getHeaders
	 *
	 * returns the header array for processing. The header array is populated
	 * in main if needed.
	 */
	public function getHeaders() {
		return $this->headers;
	} // public function getHeaders()
	
	/**
	 * getPayload
	 *
	 * returns the header array for processing. The header array is populated
	 * in main if needed.
	 */
	public function getPayload() {
		if (empty ( $this->input )) {
			throw new Exception ( 'Invalid payload for output processor' );
		}
		return $this->payload;
	}
} // abstract class Output_Abstract
?>