<?php
require_once 'Abstract.php';

/**
 * Output_HTML
 *
 * processor to take an array/object payload and convert it to XML.
 *
 */
Class Output_Csv extends Output_Abstract
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

		// ouput headers
		if(isset($this->input['csvheaders'])) {
		  $csvheaders = $this->input['csvheaders'];
		  unset($this->input['csvheaders']);
		} else {
		  $csvheaders = array_keys(reset($this->input));  
		}
		

		$now       = gmdate('D, d M Y H:i:s') . ' GMT';
		//TA:78
		//$mime_type = 'text/x-csv; charset=utf-8'; it does not help
		$mime_type = 'text/x-csv'; 
		
		$ext       = 'csv';
		
		// send the write header statements to the browser
		$this->headers['Content-Type'] = $mime_type;
		
		$this->headers['Expires'] = $now;
		
		$this->headers['charset'] = 'utf-8'; //TA:5000
		
		// lem9 & loic1: IE need specific headers
		$isIE = strstr( $_SERVER['HTTP_USER_AGENT'], 'MSIE' );
		if ( $isIE ) {
			$this->headers['Content-Disposition'] = 'inline; filename="report.csv"';
			$this->headers['Cache-Control'] = 'must-revalidate, post-check=0, pre-check=0';
			$this->headers['Pragma'] = 'public';
		} else {
			$this->headers['Content-Disposition'] = 'attachment; filename="report.csv"';
			$this->headers['Pragma'] = 'no-cache';
		}
		
    	$this->payload = $this->makeCSVTable( $csvheaders, $this->input );        

    } // public function main()
    
    
  public function makeCSVTable( $header, $rows )
  {
  	$result = '';
    // Handles the "separator" and the optional "enclosed by" characters
    $sep     = ',';
    $enc_by  = '"';

    // double the "enclosed by" character
    $esc_by  = $enc_by;

    $add_character = "\015\012";

    $schema_insert = '';
    foreach ( $header as $field ) {
      if ($enc_by == '') {
        $schema_insert .= $field;
      } else {
        $schema_insert .= $enc_by
                        . str_replace($enc_by, $esc_by . $enc_by, $field)
                        . $enc_by;
      }
      $schema_insert     .= $sep;
    } // end while

    $result .= trim(substr($schema_insert, 0, -1));
    $result .= $add_character;

    $i = 0;
    $fields_cnt = count($header);

	if ( is_array($rows) ) {
	    foreach ( $rows as $row ) {
	      $schema_insert = '';
	      foreach ( $row as $j => $value ) {
	        if (!isset($value)) {
	          //$schema_insert .= 'NULL';
			  $schema_insert .= '';
	        } else if ($value == '0' || $value != '') {
	          // test bugfix - array to string
	          if (is_array($value)){ 
	          	$done = '';  //todo test, adding ternary fallback
	          	foreach ($value as $akey => $aval){
	          		if (is_array($aval) ) // bugfix - sometimes strings get here, 12/28/12
	          			$done .= implode(', ', $aval) . PHP_EOL;
	          		else
	          			$done .= $aval . PHP_EOL;
	          	}
	          	$value = $done;
		      }
		      
	          // loic1 : always enclose fields
	          $value = preg_replace("/\015(\012)?/", "\012", $value);
	           if ($enc_by == '') {
	            $schema_insert .= $value;
	          } else {
	            $schema_insert .= $enc_by
	                           . str_replace($enc_by, $esc_by . $enc_by, $value)
	                           . $enc_by;
	          }
	        } else {
	          $schema_insert .= '';
	        }
	         
	        if ($j < $fields_cnt-1) {
	          $schema_insert .= $sep;
	        }
	      } // end for
	
	      $result .= $schema_insert;
	      $result .= $add_character;
	
	      ++$i;
	
	    } // end for
	    
	 //iterate over a dataobject
	} else if ( is_object($rows) and $header ) {
	    while ( $rows->fetch() ) {
	      $schema_insert = '';
	    	$j = 0;
	      foreach ( $header as $field => $label ) {
		        if ( isset($rows->$field) ) {
		           if (method_exists($rows,'get'.$field)) {
		               $value = $rows->{'get'.$field}();
		            } else {
		            // should this call toValue() ???
		            $value = $rows->toValue($field);
		           }
		        }
		        
		        if (!isset($value)) {
		          //$schema_insert .= 'NULL';
				  $schema_insert .= '';
		        } else if ($value == '0' || $value != '') {
		          
		          // loic1 : always enclose fields
		          $value = preg_replace("/\015(\012)?/", "\012", $value);
		           if ($enc_by == '') {
		            $schema_insert .= $value;
		          } else {
		            $schema_insert .= $enc_by
		                           . str_replace($enc_by, $esc_by . $enc_by, $value)
		                           . $enc_by;
		          }
		        } else {
		          $schema_insert .= '';
		        }
		         
		        if ($j < $fields_cnt-1) {
		          $schema_insert .= $sep;
		        }
		        
		        $j++;
	      } // end while
	
	      $result .= $schema_insert;
	      $result .= $add_character;
	
	      ++$i;
	
	    } // end for
	    
	}
    
 	    return $result;
  } // end of the 'getTableCsv()' function

    
 }
?>