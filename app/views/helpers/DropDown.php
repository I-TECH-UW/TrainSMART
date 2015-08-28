<?php
/*
 * Created on Mar 24, 2008
 *
 *  Built for itechweb
 *  Fuse IQ -- jonah.ellison@fuseiq.com
 *
 * Creates a dynamic drop-down that can be updated via JSON
 *
 */

class DropDown {
	
	/**
	 * @param $table - a string for the table name, or an array of rows to be used as the option values
	 * @param $column
	 * @param bool $id - option value to select as default
	 * @param bool $jsonUrl - do not begin or end with slash or add output type
	 * @param bool $disabled
	 * @param bool $allowIds
	 * @param bool $multiple
	 * @param array $attributes
	 * @param bool $set_default
	 * @param bool $multiple_choice
	 * @param int $size
	 * @param string $mechanism
	 * @return string
	 */
	public static function generateFunderMechanism(
			$table, 
			$column, 
			$id = false, 
			$jsonUrl = false, 
			$disabled = false, 
			$allowIds = false,
			$multiple = false, 
			$attributes = array(), 
			$set_default = true, 
			$multiple_choice = false, 
			$size = 0,
	        $mechanism = 'default') {
	
	
	
		$mutliple_id = false;
		if ( is_int($multiple) or ($multiple === 0) )
			$mutliple_id = $multiple;
	
		$multiple = ( $multiple !== false ? '[]' : ''); // allow multiple drop-downs w/same table
	
		if (is_string ( $table )) {
			$tableObj = new ITechTable ( array ('name' => $table ) );
			$info = $tableObj->info ();
			$cols = array ($column );
	
			if ( $set_default ) {
				if (array_search ( 'is_default', $info ['cols'] )) {
					$cols = array ($column, 'is_default' );
				}
			}
			$rows = $tableObj->fetchAll ( $tableObj->select ( $cols ) );
		} else if (is_array ( $table ) or is_object ( $table )) {
			$rows = $table;
			//$info = ($rows->getTable()->info ());
			$table = null;
		}
	
		$name = $table . '_id' . $multiple;
		if (isset ( $attributes ['name'] )) {
			$name = $attributes ['name'] ;
			unset ( $attributes ['name'] );
		}
	
		$html = '<select name="' . $name . '" id="select_' . $table . ($multiple && ($mutliple_id !== false) ?'_'.$mutliple_id:'').'"' . (($disabled) ? ' disabled="disabled" ' : ' ');
	
		if ($multiple_choice) {
			$html .= ' multiple="multiple" ';
		}
	
		if ($size) {
			$html .= ' size=' . $size;
		}
	
	
		foreach ( $attributes as $k => $v ) {
			$html .= " {$k}=\"$v\"";
		}
	
		$html .= ' >';
	
		$html .= "\t<option value=\"\">&mdash; " . t ( 'select' ) . " &mdash;</option>\n";

		foreach ( $rows as $r ) {
			if (($allowIds === false) or (array_search ( $r->id, $allowIds ) !== false)) {
				$isSelected = '';
	
				//check for default value in table
				if ($set_default && isset ( $r->is_default ) && $r->is_default && ($id === false || $id === null)) {
					$isSelected = ' selected="selected" ';
				} else if ( $r->id === $id ) { //assign default value
					$isSelected = ' selected="selected" ';
	
				}
	
				$html .= "\t<option value=\"{$r->id}\"$isSelected>{$r->$column}" . " {$mechanism}"  . "</option>\n";
			}
		}
		$html .= "</select>\n\n";
	
		// add edit link
		if ($jsonUrl && ! $disabled) {
			$fieldlabel = explode ( '_', str_replace ( '_phrase', '', $column ) );
			$label = $fieldlabel [0];
			if(isset($fieldlabel[1])){ $label .= ' ' . $fieldlabel [1]; }
	
			if (trim ( $label )) {
				switch ($label) { // modify so label translates nicely, if needed
					case 'training got' :
						$label = "GOT Curriculum";
						break;
					default :
						break;
				}
	
				require_once ('models/table/Translation.php');
				$translate = @Translation::translate ( ucwords ( $label ) );
				if ($translate)
					$label = $translate;
			}
	
			$jsonUrl = "$jsonUrl/table/$table/column/$column";
			$jsonUrl = Settings::$COUNTRY_BASE_URL . '/' . $jsonUrl . '/outputType/json';
	
			$html .= " <a href=\"#\" onclick=\"addToSelect('" . str_replace("'", "\\"."'",t ( 'Please enter your new' )) . " {$label}:', 'select_{$table}', '{$jsonUrl}'); return false;\">" . t ( 'Insert new' ) . "</a>";
		}
	
		return $html;
	}

    /**
	 * @param string     $table - a string for the table name, or an array of rows to be used as the option values
	 * @param string     $column
	 * @param bool       $id - option value to select as default
	 * @param bool       $jsonUrl - do not begin or end with slash or add output type
	 * @param bool       $disabled
	 * @param bool|array $allowIds - an array of ids that are acceptable options for the dropdown (false allows all options)
	 * @param bool       $multiple
	 * @param array      $attributes
	 * @param bool       $set_default
	 * @param bool       $multiple_choice
	 * @param int        $size
	 * @return string
        }

        $return_html = '<select ';
        foreach($elementAttributes as $k => $v) {
            $return_html .= "$k=$v ";
        }
        $return_html .= ">\n";

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $options = $db->fetchAll($query);

        if ($show_select_option) {
            $return_html .= '<option value="">&mdash; ' . t('select') . " &mdash;</option>\n";
        }

        foreach($options as $option) {
            $return_html .= "<option value={$option['id']}" . ($option['id'] == $selected_value ? ' selected="selected"' : '') . '>' . $option['val'] . "</option>\n";
        }
        $return_html .= "</select>\n";

        return $return_html;
    }

	/**
	 */
    public static function generateHtml($table, $column, $id = false, $jsonUrl = false, $disabled = false, $allowIds = false,
                                        $multiple = false, $attributes = array(), $set_default = true, $multiple_choice = false, $size = 0) {

        $mutliple_id = false;
        if ( is_int($multiple) or ($multiple === 0) ) {
            $mutliple_id = $multiple;
        }

        $multiple = ( $multiple !== false ? '[]' : ''); // allow multiple drop-downs w/same table

        if (is_string ( $table )) {
            $tableObj = new ITechTable ( array ('name' => $table ) );
            $info = $tableObj->info ();
            $cols = array ($column );

            if ( $set_default ) {
                if (array_search ( 'is_default', $info ['cols'] )) {
                    $cols = array ($column, 'is_default' );
                }
            }
            $rows = $tableObj->fetchAll ( $tableObj->select ( $cols ) );
        } else if (is_array ( $table ) or is_object ( $table )) {
            $rows = $table;
            //$info = ($rows->getTable()->info ());
			$table = null;
        }

        $name = $table . '_id' . $multiple;
        if (isset ( $attributes ['name'] )) {
            $name = $attributes ['name'] ;
            unset ( $attributes ['name'] );
        }

        $html = '<select name="' . $name . '" id="select_' . $table . ($multiple && ($mutliple_id !== false) ?'_'.$mutliple_id:'').'"' . (($disabled) ? ' disabled="disabled" ' : ' ');

        if ($multiple_choice) {
            $html .= ' multiple="multiple" ';
        }

        if ($size) {
            $html .= ' size=' . $size;
        }

        foreach ( $attributes as $k => $v ) {
            $html .= " {$k}=\"$v\"";
        }

        $html .= ' >';


		 $html .= "\t<option value=\"\">&mdash; " . t ( 'select' ) . " &mdash;</option>\n";

        foreach ( $rows as $r ) {
            if (($allowIds === false) or (array_search ( $r->id, $allowIds ) !== false)) {
                $isSelected = '';

                //check for default value in table
                if ($set_default && isset ( $r->is_default ) && $r->is_default && ($id === false || $id === null)) {
                    $isSelected = ' selected="selected" ';
                } else if ( $r->id === $id ) { //assign default value
                    $isSelected = ' selected="selected" ';

                }

                $html .= "\t<option value=\"{$r->id}\"$isSelected>{$r->$column}</option>\n";
            }
        }
        $html .= "</select>\n\n";

        // add edit link
        if ($jsonUrl && ! $disabled) {
            $fieldlabel = explode ( '_', str_replace ( '_phrase', '', $column ) );
            $label = $fieldlabel [0];
            if(isset($fieldlabel[1])){
                $label .= ' ' . $fieldlabel [1];
            }

            if (trim ( $label )) {
                switch ($label) { // modify so label translates nicely, if needed
                    case 'training got' :
                        $label = "GOT Curriculum";
                        break;
                    default :
                        break;
                }

                require_once ('models/table/Translation.php');
                $translate = @Translation::translate ( ucwords ( $label ) );
                if ($translate) {
                    $label = $translate;
                }
            }

            $jsonUrl = "$jsonUrl/table/$table/column/$column";
            $jsonUrl = Settings::$COUNTRY_BASE_URL . '/' . $jsonUrl . '/outputType/json';

            $html .= " <a href=\"#\" onclick=\"addToSelect('" . str_replace("'", "\\"."'",t ( 'Please enter your new' )) . " {$label}:', 'select_{$table}', '{$jsonUrl}'); return false;\">" . t ( 'Insert new' ) . "</a>";
        }

        return $html;
    }

	/**
	 * A generic helper function for rendering HTML for a dropdown widget
	 * able to be called from a view script.
	 *
	 * @param string       $select_name           - the widget's html id
	 * @param string       $select_title          - the title text
	 * @param array        $vals                  - option values
	 * @param string       $name_key              - the array key for the option name
	 * @param string       $val_key               - the array key for the option value (usually an id)
	 * @param string|bool  $selected      = false - the selected option
	 * @param string|bool  $onchange      = false - a javascript function name to call when changed
	 * @param bool         $required      = false - is this dropdown required input?
	 * @param bool         $enabled       = true  - is this dropdown enabled?
	 * @param bool         $is_multiple   = false - is this dropdown multiple selection?
	 * @param int          $multiple_size = 10    - size of optional multiple selection
	 * @return string                             - html for a dropdown
	 */
	public static function render($select_name, $select_title, $vals, $name_key, $val_key, $selected = false, $onchange = false, $required = false, $enabled = true, $is_multiple = false, $multiple_size=10) {

		$html = '<div class="fieldLabel"  id="' . $select_name . '_lbl">';
		if ($required)
			$html .= '<span class="required">*</span>';
		$html .= $select_title;
		$html .= '</div><div class="fieldInput"><select id="' . $select_name . '" name="' . $select_name . ($is_multiple ? '[]':'') . '" ' .
			($onchange ? "onchange='$onchange'" : '') . ' ' . (!$enabled ? "disabled='disabled'" : '') .
			($is_multiple ? " multiple=\"multiple\" size=\"$multiple_size\"" : '') . '>';
        if (!$is_multiple) {
            $html .= '<option value="">--' . (t('choose')) . '--</option>';
        }
		foreach ( $vals as $val ) {
			$html .= '<option value="' . $val [$val_key] . '" ' . ($selected == $val [$val_key] ? 'selected="selected"' : '') . '>' . $val [$name_key] . '</option>' . "\n";
		}

		$html .= '</select></div>';
	    return $html;
	}

	/**
	 * @param $id
	 * @param $show_id
	 * @param $label
	 * @param $options
	 * @param $value_key
	 * @param $selected_id
	 * @param $show_selected
	 * @param bool $set_default
	 * @param bool $fixedWidth
	 * @param bool $multiple
	 * @return string
	 */

		$html = "<div class='fieldLabel' id='" . $id . "_lbl'>$label</div>\n";
		$html .= "<div class='fieldInput'><div  class='leftBorderPad'>\n";
		$html .= "<input type='checkbox' name='$show_id' " . ($show_selected ? 'checked="checked"' : '') . " />\n";
		$html .= "</div><label for='$show_id' ></label><div  class='leftBorder'>\n";
		$html .= "<select id='" . $id . "_id' name='" . $id . "_id".($multiple?'[]':'')."' ".($fixedWidth?'class="fixed" ':'').($multiple?'multiple="multiple" size="10" ':'').">\n";
		$html .= "<option value=''>--" . t ( 'All' ) . "--</option>\n";

		//look for default
		if ( $set_default && (!$selected_id) && $options && (!isset($_REQUEST['go'])) && array_key_exists('is_default', reset($options)) ) {
			foreach($options as $o) {
				if ( $o['is_default'] )
				  $selected_id = $o['id'];
			}
		}

		foreach ( $options as $vals ) {
			$html .= '<option value="' . $vals ['id'] . '" ' . ($selected_id == $vals ['id'] ? 'selected="selected"' : '') . ' >' . ($vals [$value_key]) . '</option>';
		}
		$html .= "</select></div></div>\n";

		return $html;
	}

	/**
	 * @param $name
	 * @param $selectedVal
	 * @return string
	 */
	public static function qualificationsDropDown($name, $selectedVal)
	{
		$o = array();
		$o[] = '<select id="'.$name.'" name="'.$name.'">';
		$o[] = '<option value="">--'.t('choose').'--</option>';
		$lastParent = null;
		require_once 'models/table/OptionList.php';
		$qualificationsArray = OptionList::suggestionListHierarchical ( 'person_qualification_option', 'qualification_phrase', false, false, array ('0 AS is_default', 'child.is_default' ) );
		foreach ( $qualificationsArray as $vals ) {
			if ( !$vals['id'] ) {
				$lastParent = ($vals['parent_phrase']);
				$o[] = '<option value="'.$vals['parent_id'].'" '.($selectedVal == $vals['parent_id'] ?'selected="selected"':'').'>'.htmlspecialchars($vals['parent_phrase']).'</option>';
			} else {
				$o[] = '<option value="'.$vals['id'].'" '.($selectedVal == $vals['id'] ?'selected="selected"':'').'>&nbsp;&nbsp;'. htmlspecialchars($vals['qualification_phrase']).'</option>';
			}
		}
		$o[] = '</select>';
		return implode(PHP_EOL, $o);
	}

}
