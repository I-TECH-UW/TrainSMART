<?php
require_once 'Output/Abstract.php';
require_once 'Zend/Json.php';
require_once 'PEAR/XML/Serializer.php';

/**
 * Output_Xml
 *
 * processor to take an array/object payload and convert it to XML.
 *
 */
Class Output_Xml extends Output_Abstract
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
		 // An array of serializer options
		$serializer_options = array (
		   'addDecl' => TRUE,
		   'encoding' => 'UTF-8',
		   'indent' => (Globals::$DEBUG ?'  ':''),
		   'linebreak' => (Globals::$DEBUG ?"\n":''),
		   'rootName' => 'itech_results',
		   'defaultTagName' => 'trip',
       		'attributesArray' => 'attributes',
   			'classAsTagName' => TRUE,
   			'contentName' => 'value'
		);
		
		// Instantiate the serializer with the options
		$Serializer = new XML_Serializer($serializer_options);
		
		// Serialize the data structure
		$status = $Serializer->serialize($this->input);
		
		// Check whether serialization worked
		if (PEAR::isError($status)) {
		   die($status->getMessage());
		}
		
        $this->payload = $Serializer->getSerializedData();

        $this->headers['Content-Type'] = 'text/xml';
        
        return;
    } // public function main()
    
    
 }
?>