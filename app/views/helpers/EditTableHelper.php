<?php
/*
* Created on Mar 10, 2008
*
*  Built for itechweb
*  Fuse IQ -- jonah.ellison@fuseiq.com
*/

/**
* Creates an editable YUI DataTable
*
*/

class EditTableHelper {

	public function __construct($label, array $rowRay, array $colDefs) {
		return $this->generateHtml($label, $rowRay, $colDefs);
	}
	
	/**
	 * Generate HTML & JavaScript string for an EditTable
	 *
	 * @param  string  $label                   - label for the table
	 * @param  array   $rowRay                  - array of column-indexed arrays of data from the database
	 * @param  array   $colDefs                 - array of column-indexed table headings for column names
	 * @param  array   $customColDefs = array() - column formatting and settings for the table display
	 * @param  array   $noDelete      = array() - array of ids that are not allowed to be deleted (default is to allow deletion)
	 * @param  boolean $noEdit        = false   - is this table read-only?
	 * @return string                           - string containing HTML & JavaScript table
	 */
	
	public static function generateHtml($label, array $rowRay, array $colDefs, array $customColDefs = array(), array $noDelete = array(), $noEdit = false) {
		
		// Format column defs
		foreach($colDefs as $key => $lbl) {
			$def = 'key:"' . htmlspecialchars($key) . '", label:"' . htmlspecialchars($lbl) . '"';

			if(isset($customColDefs[$key])) {
				$def .= ', ' . $customColDefs[$key];
			}

			if(!$noEdit && strpos($def, "editor") === false) {
				$def .= ', editor:"textbox"';
			}

            $colDefs[$key] = '{' . $def .'}';
		}
		if (isset($customColDefs['id'])) {
			$colDefs[] = '{key:"id",'.$customColDefs['id'].'}';
		} else {
			$colDefs[] = '{key:"id", hidden:"true", width:"0", label:"", className:"hidden"}';
		}

		// Format data
		foreach($rowRay as $key => $ray) {
			$rowRay[$key] = json_encode($ray);
		}

		$labelSafe = str_replace('-','',str_replace(' ','', $label)); // remove spaces and dashes

		$noEdit = ($noEdit) ? 'true' : 'false';

		$js = "<div id=\"{$labelSafe}Table\"></div>\n\n";
		$js .= "<script type='text/javascript'>\n\n";
		$js .= "<!--//--><![CDATA[//><!-- \n\n";
		$js .= "YAHOO.util.Event.onDOMReady(function() {\n\n";
		$js .= "{$labelSafe}DataSource = [ " . implode(",\n", $rowRay) . " ];\n\n";
		$js .= "{$labelSafe}ColDefs = [ " . implode(',', $colDefs) . " ];\n\n";
		$js .= "{$labelSafe}NoDelete = [ " . implode(',', $noDelete) . " ];\n\n";

		$js .= "var {$labelSafe}Table = makeEditTable(\"{$labelSafe}\", {$labelSafe}DataSource, {$labelSafe}ColDefs, {$labelSafe}NoDelete, $noEdit);\n\n";

		//add it to a global namespace as well
		$js .= "ITECH.{$labelSafe}Table = {$labelSafe}Table;\n\n";

		$js .= "}); //--><!]]> </script>\n\n";

		return $js;
	}
	
	/**
	 * Generate HTML & JavaScript for our EditTableTraining
	 *
	 * uses edittable-training.js
	 * TODO: this function documentation needs more work
	 * @param  string          $label         - label for the table
	 * @param  array           $rowRay        - array of column-indexed arrays of data from the database
	 * @param  array           $colDefs       - array of column-indexed table headings for column names
	 * @param  array = array() $colStatic     - column names, with one maybe removed TODO: why?
	 * @param  array = ''      $linkInfo      - link to ???
	 * @param  array = ''      $editLinkInfo  - link to edit row entry
	 * @param  array = array() $customColDefs - allows specification of attributes per column
	 * @return string containing HTML & JavaScript table
	 */
	public static function generateHtmlTraining($label, array $rowRay, array $colDefs, array $colStatic = array(), $linkInfo = '', $editLinkInfo = '', array $customColDefs = array()) {
		// Format column defs

		//Strangeness in column ordering; YUI / IE7 bug
		$colDefsClone = array();
		//   $colDefsClone['id'] = '{key:"id", label:"id", resizeable:false, hidden: true }';
		$colDefsClone['id'] = '{key:"id", hidden:"true", width:"0", label:"", className:"hidden"}';
		foreach($colDefs as $key => $lbl) {

			$customDef = (isset($customColDefs[$key])) ? ', ' . $customColDefs[$key] : '';
            //TA:106 set width for some columns
            if($key == 'id'){
                $colDefsClone[$key] = '{key:"' . htmlspecialchars($key) .
                '", width:15, resizeable:true , label:"' . htmlspecialchars($lbl) . '"' . (!in_array($key, $colStatic) ? ', editor:"textbox"' : '') . $customDef . '}';
                
            }else if($key == 'training_start_date'){//TA:#278 => The best way to add column width
                $colDefsClone[$key] = '{key:"' . htmlspecialchars($key) .
                '", width:50, resizeable:true , label:"' . htmlspecialchars($lbl) . '"' . (!in_array($key, $colStatic) ? ', editor:"textbox"' : '') . $customDef . '}';
                
            }else if($key == 'creator' || $key == 'creator_name' || $key == 'first_name' || $key == 'last_name'){//TA:#278 => The best way to add column width
                $colDefsClone[$key] = '{key:"' . htmlspecialchars($key) .
                '", width:100, resizeable:true , label:"' . htmlspecialchars($lbl) . '"' . (!in_array($key, $colStatic) ? ', editor:"textbox"' : '') . $customDef . '}';
                
            }else if($key == 'facility_name' || $key == 'location_name'){//TA:#317 wrap text
                            $colDefsClone[$key] = '{key:"' . htmlspecialchars($key) . 
 			 '", style:"overflow:auto;", resizeable:true , label:"' . htmlspecialchars($lbl) . '"' . (!in_array($key, $colStatic) ? ', editor:"textbox"' : '') . $customDef . '}';
            }else if($key == 'training_organizer_phrase'){//TA: 
                $colDefsClone[$key] = '{key:"' . htmlspecialchars($key) .
                '", width:60, resizeable:true , label:"' . htmlspecialchars($lbl) . '"' . (!in_array($key, $colStatic) ? ', editor:"textbox"' : '') . $customDef . '}';
            }else{//TA if width is not working then use above way (//TA:#278) how to set up column width
                $colDefsClone[$key] = '{key:"' . htmlspecialchars($key) . 
  			 '", width:'.($key == 'training_title'?120:(strlen($lbl)*6)).', resizeable:true , label:"' . htmlspecialchars($lbl) . '"' . (!in_array($key, $colStatic) ? ', editor:"textbox"' : '') . $customDef . '}';
            }
		}

		// Format data
		foreach($rowRay as $key => $ray) {

			# FIX PUT IN PLACE TO SUPPRESS 'NULL' VALUES
			# 2012.06.24.01.45 CDL
			$_ray = array();
			foreach ($ray as $key2=>$val2){
				$_ray[$key2] = (!is_null($val2) ? $val2 : "");
			}

			$rowRay[$key] = json_encode($_ray);
			//      $rowRay[$key] = '["';
			//      $rowRay[$key] .= implode('","',$ray);
			//      $rowRay[$key] .= '"]';
		}

		$labelSafe = strtolower(str_replace(' ','', $label)); // remove space

		// links to add to fields
		if($linkInfo) {
			$linkInfo = json_encode($linkInfo);
		} else {
			$linkInfo = '""';
		}

		// links to add to "edit" field
		if($editLinkInfo) {
			$editLinkInfo = json_encode($editLinkInfo);
		} else {
			$editLinkInfo = '""';
		}

		$req_url = $_SERVER['REQUEST_URI'];
		$req_url = rtrim($req_url,"/");

		$js = '';
		$js .= "<script type='text/javascript'>\n\n";
		$js .= "YAHOO.util.Event.onDOMReady(function() {\n\n";
		$js .= "var linkInfo = $linkInfo;\n\n";
		$js .= "var editLinkInfo = $editLinkInfo;\n\n";
		$js .= "var {$labelSafe}DataSource = [ " . implode(",\n", $rowRay) . " ];\n\n";
		$js .= "var {$labelSafe}ColDefs = [ " . implode(',', $colDefsClone) . " ];\n\n";
		$js .= "var {$labelSafe}Table = makeEditTableTraining(\"{$labelSafe}\", {$labelSafe}DataSource, {$labelSafe}ColDefs, \"{$req_url}/edittable/$labelSafe/outputType/json\", linkInfo, editLinkInfo);\n\n";
		//add it to a global namespace as well
		$js .= "ITECH.{$labelSafe}Table = {$labelSafe}Table;\n\n";
		$js .= "});</script>\n\n";

		return $js;

	}


}

?>
