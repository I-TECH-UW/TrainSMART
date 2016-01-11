<?php
/**
 * simple form functions
 * params:
 *		$view = $this
 *		$label = label text
 *		$content = 'text' or 'textarea' or any html blob
 *		$val = value to display for <input> tags
 * They also work off things like the $view's $this->viewonly variable (readonly)
 * to handle displaying extra data or disabling fields.
 * 		currently checking: $view->viewonly, $view->required_fields, $view->is_report, $view->thin_labels
 *
 * they also print a checkbox if $view->is_report is 1
 * is_report checkboxes check $view->criteria[] for report check values.
 *
 * examples:
 * labelAndField($this, t('Input 1'), $myDropDown, 'input_1');
 * labelAndField($this, t('Input 2'), 'text', 'field2', $values['field2']);
 */

/**
 * creates html string for a labelled input field, uses view object to control readonly/required data
 * @param  view   $view       - the view object
 * @param  string $label      - label text
 * @param  string $content    - 'text', 'textarea', 'date', '%' or any html blob
 * @param  string $id    = '' - html element id
 * @param  string $val   = '' - value to display for input tags
 * @return html string
 * returns:
 * string <div class="$class $id">$required$label</div>
 * <div class="fieldInput">$reportcheck$content$cal1</div>
*/

function labelAndField($view, $label, $content, $id = '', $val = '')
{
	$class = $view->thin_labels ? 'fieldLabelThin' : 'fieldLabel';
	$readonly = $view->viewonly ? 'readonly="readonly"' : '';
    $required = '';
    if (isset($view->required_fields) && (array_search($id, $view->required_fields) !== false)) {
        $required = '<span class="required">*</span>';
    }

	$cal = '<a class="calendarbtn" href="#"><img src="'.$view->base_url.'/js/yui/assets/calbtn.gif"></a>';
	if (!$view->calendar_fields) $view->calendar_fields = array();
	$cal1 = (array_search($id, $view->calendar_fields) === false) ? '' : $cal;
	if ($view->autoheight_labels)
		$class .= ' autoHeight ';
	$reportcheck = $view->is_report ? '<div class="leftBorderPad"><input type="checkbox" name="show_'.$id.'"'. (($view->criteria['show_'.$id]) ? 'CHECKED' : '').'/></div></div><div class="leftBorder">' : '';

	if ($content == 'text')
		$content = '<input type="text" id="'.$id.'" name="'.$id.'" value="'.$val.'" '.$readonly.'/>';
	else if ($content == 'date')
		$content = '<input type="text" class="datepicker" id="'.$id.'" name="'.$id.'" value="'.$val.'" '.$readonly.'/> '.$cal;
	else if ($content == 'textarea')
		$content = '<textarea id="'.$id.'" name="'.$id.'" '.$readonly.'>'.$val.'</textarea>';
	else if ($content == '%') //TA:20: 08/29/2014 
		$content = '<input type="number" min="0" max="100" id="'.$id.'" name="'.$id.'" value="'.$val.'" '.$readonly.'/>';
	$o = <<< EOF
	<div class="$class $id">$required$label</div>
	<div class="fieldInput">$reportcheck$content$cal1</div>
EOF;

	return $o;
}

/**
 * @param  view   $view       - the view object
 * @param  string $label      - label text
 * @param  string $id    = '' - html element id
 * @param  string $val   = '' - value to display for input tags
 * @return html string
 */

/* returns:
 * 	<div class="$class $id">$required$label</div>" 
 *	<div class="fieldInput">$reportcheck<input type="checkbox" id="$id" name="$id" $checked $readonly /></div>
 */
function labelAndCheckBox($view, $label, $id = '', $val = '')
{
	$readonly = $view->viewonly ? 'readonly="readonly"' : '';
	$required = ( array_search($id,$view->required_fields) !== false ) ? '<span class="required">*</span>' : '';
	$class = $view->thin_labels ? 'fieldLabelThin' : 'fieldLabel';
	$checked = $val ? 'checked="checked"' : '';
	$reportcheck = $view->is_report ? '<div class="leftBorderPad"><input type="checkbox" name="show_'.$id.'"'. (($view->criteria['show_'.$id]) ? 'CHECKED' : '').'/></div></div><div class="leftBorder">' : '';

	$o = <<< EOF
	<div class="$class $id">$required$label</div>
	<div class="fieldInput">$reportcheck<input type="checkbox" id="$id" name="$id" $checked $readonly /></div>
EOF;
	return $o;

}

/**
 * @param  view    $view        - the view object
 * @param  string  $label1      - label text
 * @param  string  $label2      - label text
 * @param  string  $id1         - html element id
 * @param  string  $id2         - html element id
 * @param  string  $val1 = ''   - value to display for input tags
 * @param  string  $val2 = ''   - value to display for input tags
 * @return html string
 */
 
/*  returns:
 * 	<div class="$class $id">$required$label1</div>
 *	<div class="fieldInput">$reportcheck
 *		<input type="text" id="$id1" name="$id1" value="$val1" $readonly/> $cal1
 *		<span class="$id2">
 *			<label class="label"> $label2 </label>
 *			<input type="text" id="$id2" name="$id2" value="$val2" $readonly/> $cal2
 *		</span>
 *	</div>
 */
function labelTwoFields($view, $label1, $label2, $id1, $id2, $val1 = '', $val2 = '') {
	$readonly = $view->viewonly ? 'readonly="readonly"' : '';
	$class = $view->thin_labels ? 'fieldLabelThin' : 'fieldLabel';
	$required = ( array_search($id1,$view->required_fields) !== false ) ? '<span class="required">*</span>' : '';
	$cal = '<a class="calendarbtn" href="#"><img src="'.$view->base_url.'/js/yui/assets/calbtn.gif"></a>';
	$cal1 = (array_search($id1, $view->calendar_fields) === false) ? '' : $cal;
	$cal2 = (array_search($id2, $view->calendar_fields) === false) ? '' : $cal;
	$class1 = ($cal1 ? ' class="datepicker"':'');
	$class2 = ($cal2 ? ' class="datepicker"':'');
	if ($view->autoheight_labels)
		$class .= ' autoHeight ';
	$reportcheck = $view->is_report ? '<div class="leftBorderPad"><input type="checkbox" name="show_'.$id1.'"'. (($view->criteria['show_'.$id1]) ? 'CHECKED' : '').'/></div></div><div class="leftBorder">' : '';

	$o = <<< EOF
	<div class="$class $id">$required$label1</div>
	<div class="fieldInput">$reportcheck
		<input type="text" id="$id1" name="$id1" value="$val1" $class1 $readonly/> $cal1
		<span class="$id2">

			<label class="label"> $label2 </label>
			<input type="text" id="$id2" name="$id2" value="$val2" $class2 $readonly/> $cal2
		</span>
	</div>
EOF;

	return $o;

}

/**
 * @param  view    $view            - the view object
 * @param  string  $label           - label text
 * @param  string  $float50 = false - html element id
 * @return html string
 */

/* 
	returns:
	<div class=\"$class\">$label</div>;
*/

function label($view, $label, $float50 = false) {
	$label = t($label);
	$class = $view->thin_labels ? 'fieldLabelThin' : 'fieldLabel';
	if ($float50) {
		$class = 'float50';
		return '<label class="label float50">'.$label.'</label>';
	}
	return "<div class=\"$class\">$label</div>";
}

/**
 * mysql timestamp -> m/d/Y output
 * 
 * @param  array or string    $value 
 * @param  string  $formatAsDMY = true - show day of month first (euro style)
 * @return string - date or empty string
 */

function formhelperdate($value, $formatAsDMY = true)
{
	$fmt = $formatAsDMY ? 'd/m/Y' : 'm/d/Y';
	switch ($value) {
		case '':
		case null:
		case '0000-00-00':
		case '0000-00-00 00:00:00':
			return '';
		break;
	}
	if (strpos($value, ',')) // I used group concat for employee partners
	{
		$o = array();
		$values = explode(',', $value);
		foreach($values as $v)
			$o[] = date($fmt, strtotime($v));
		return implode(',<br>', $o);
	}
	return date($fmt, strtotime($value));
}

/**
 * @param  view    $view    - the view object
 * @param  string  $label   - label text
 * @param  string  $id      - html element id
 * @param  string  $val     - value to display for input tags
 * @return html string
 */

function genderDropdown($view, $label, $id, $val)
{
	$class = $view->thin_labels ? 'fieldLabelThin' : 'fieldLabel';
	if ($view->autoheight_labels)
		$class .= ' autoHeight ';
	$readonly = $view->viewonly ? 'readonly="readonly"' : '';
	$reportcheck = $view->is_report ? '<div class="leftBorderPad"><input type="checkbox" name="show_'.$id.'"'. (($view->criteria['show_'.$id]) ? 'CHECKED' : '').'/></div></div><div class="leftBorder">' : '';
	$required = ( in_array($id,$view->required_fields) ) ? '<span class="required">*</span>' : '';

	$o = '
	<div class="'.$class.'">'.$required.t('Gender').'</div>
	<div class="fieldInput">'.
	//<div  class="leftBorderPad"><input type="checkbox" name="showGender" '. ($view->criteria['showGender']) 'CHECKED' : ''; '/></div>'.
  	//<div  class="leftBorder">
	'<select id="'.$id.'" name="'.$id.'" '.$readonly.'>
		<option value="">--'.t('choose').'--</option>
		<option value="male"  ' .($val == 'male'? 'selected="selected"':''). '>'.t('Male').'</option>
		<option value="female"  ' .($val == 'female'? 'selected="selected"':''). '>'.t('Female').'</option>
	</select></div>';//</div>
	return $o;
}

/**
 * @param  view    $view    - the view object
 * @param  string  $label   - label text
 * @param  string  $id      - html element id
 * @param  string  $val     - value to display for input tags
 * @return html string
 */

function confirmDropdown($view, $label, $id, $val)
{
	$class = $view->thin_labels ? 'fieldLabelThin' : 'fieldLabel';
	if ($view->autoheight_labels)
		$class .= ' autoHeight ';
	$readonly = $view->viewonly ? 'readonly="readonly"' : '';
	$reportcheck = $view->is_report ? '<div class="leftBorderPad"><input type="checkbox" name="show_'.$id.'"'. (($view->criteria['show_'.$id]) ? 'CHECKED' : '').'/></div></div><div class="leftBorder">' : '';
	$required = ( in_array($id,$view->required_fields) ) ? '<span class="required">*</span>' : '';

	$o = '
	<div class="'.$class.'">'.$required.$label.'</div>
	<div class="fieldInput">'.$reportcheck.
	'<select id="'.$id.'" name="'.$id.'" '.$readonly.'>
		<option value="">--'.t('choose').'--</option>
		<option value="0" ' .($val === "0" ? 'selected="selected"':''). '>'.t('NO').'</option>
		<option value="1" ' .($val === "1"  ? 'selected="selected"':''). '>'.t('YES').'</option>
	</select></div>';//</div>
	return $o;
}