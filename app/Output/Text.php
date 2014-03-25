<?php
require_once 'Output/Abstract.php';
require_once 'Zend/Json.php';

/**
 * Output_HTML
 *
 * processor to take an array/object payload and convert it to XML.
 *
 */
Class Output_Text extends Output_Abstract
{
 
     
    /**
     * __construct
     *
     * entry point for this processor.
     *
     * @param Array $input
     * 
     */
    public function __construct($input)
    {
        parent::__construct();
        $this->input = $input;
        $this->main();

    } // public function __construct($payload)
    
    
    /**
     * main
     * 
     * main action method for the processor. Creates an XML document from the
     * array passed into the constructor
     *
     */
    public function main()
    {
        $this->payload = '';
    	foreach($this->input as $vals) {
    		$this->payload .= is_array($vals) ? implode("\t",$vals) : $vals;
    		$this->payload .= "\n";
    	}
    	
        $this->headers['Content-Type'] = 'text/plain';
        
        return;
    } // public function main()
    
    
 }
?>