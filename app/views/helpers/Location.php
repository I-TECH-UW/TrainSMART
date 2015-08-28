<?php
require_once('views/helpers/ITechTranslate.php');



/**
 * @param $tier - id number for location tier
 * @param $locations - reference to locations array
 * @param $id - this item's id
 * @return string parent1_parent2_id
 */
function buildId($tier, &$locations, $id) {
    $parents = '';
    $parent_id = (isset( $locations[$id]) ? $locations[$id]['parent_id']: 0);
    while($tier > 1 ) {
        if ($parent_id) {
            $parents = $parent_id.'_'.$parents;
            $parent_id = $locations[$parent_id]['parent_id'];
        } else {
            $parents = '0_'.$parents;
        }

        $tier--;
    }

    return $parents.$id;
}

/**
 * Get the location filter values
 *
 * @param array $params - the location search user criteria
 * @param array $settings - a copy of this->_countrySettings used for determining available regions
 * @param array $criteria - extra criteria to pass through, matching keys are overwritten
 * @param string $prefix - prefix for region names in $params
 *
 * @return array - $criteria(with city, city_parent_id), location tier, location id
 */
function getCriteriaValues($params, $settings, $criteria = array(), $prefix = '') {
	
    if ( $prefix != '' ) $prefix .= '_';

    $criteria[$prefix.'city'] = $params[$prefix.'city'] ? $params[$prefix.'city'] : ""; // set city

    $rgns = array('province_id', 'district_id','region_c_id','region_d_id','region_e_id','region_f_id','region_g_id','region_h_id','region_i_id');
    // get value from each region sent by form
    foreach($rgns as $rgn_name) {
        $index_name = $prefix.$rgn_name;
        $tmp = $params[$index_name] ? $params[$index_name] : "";
        if (is_array ( $tmp ) ) {
            if ( $tmp [0] === "") { // "All"
                $criteria [$index_name] = array ();
            } else {
                foreach($tmp as $key => $val) {
                    if (strstr ( $val, '_' ) !== false) {
                        $parts = explode ( '_', $val );
                        #$tmp [$key] = $parts [count($parts)-1];
                        $tmp [$key] = array_pop($parts);
                    }
                }
                $criteria [$index_name] = $tmp;
            }
        } else {
            if (strstr ( $tmp, '_' ) !== false) {
                $parts = explode ( '_', $tmp );
                $tmp = array_pop($parts);
            }
            $criteria [$index_name] = $tmp;
        }
    }

    $city_parent_id = 0; // todo: small bug here, on receiving array input for region_ids, city_parent_id returns an array of ids, possibly even wrong ids -- probably ok - its not used in reports anyway...
    if ($settings['display_region_i']) {
        $city_parent_id = $criteria[$prefix.'region_i_id'];
    } else if ($settings['display_region_h']) {
        $city_parent_id = $criteria[$prefix.'region_h_id'];
    } else if ($settings['display_region_g']) {
        $city_parent_id = $criteria[$prefix.'region_g_id'];
    } else if ($settings['display_region_f']) {
        $city_parent_id = $criteria[$prefix.'region_f_id'];
    } else if ($settings['display_region_e']) {
        $city_parent_id = $criteria[$prefix.'region_e_id'];
    } else if ($settings['display_region_d']) {
        $city_parent_id = $criteria[$prefix.'region_d_id'];
    } else if ($settings['display_region_c']) {
        $city_parent_id = $criteria[$prefix.'region_c_id'];
    } else if ($settings['display_region_b']) {
        $city_parent_id = $criteria[$prefix.'region_b_id'];
    } else {
        $city_parent_id = $criteria['_id'];
    }
    $criteria [$prefix.'city_parent_id'] = $city_parent_id;


    $location_tier = 1;
    $location_id = $criteria [$prefix.'province_id'];
    if ( $criteria [$prefix.'district_id'] ) {
        $location_id = $criteria [$prefix.'district_id'];
        $location_tier = 2;
    }
    if ( $criteria [$prefix.'region_c_id'] ) {
        $location_id = $criteria [$prefix.'region_c_id'];
        $location_tier = 3;
    }
    if ( $criteria [$prefix.'region_d_id'] ) {
        $location_id = $criteria [$prefix.'region_d_id'];
        $location_tier = 4;
    }
    if ( $criteria [$prefix.'region_e_id'] ) {
        $location_id = $criteria [$prefix.'region_e_id'];
        $location_tier = 5;
    }
    if ( $criteria [$prefix.'region_f_id'] ) {
        $location_id = $criteria [$prefix.'region_f_id'];
        $location_tier = 6;
    }
    if ( $criteria [$prefix.'region_g_id'] ) {
        $location_id = $criteria [$prefix.'region_g_id'];
        $location_tier = 7;
    }
    if ( $criteria [$prefix.'region_h_id'] ) {
        $location_id = $criteria [$prefix.'region_h_id'];
        $location_tier = 8;
    }
    if ( $criteria [$prefix.'region_i_id'] ) {
        $location_id = $criteria [$prefix.'region_i_id'];
        $location_tier = 9;
    }

    return array($criteria, $location_tier, $location_id);

}

/**
 * outputs html and javascript for location <select> options
 *
 * @param array $locations                     - reference to array of location arrays
 * @param int $tier                            - location tier level
 * @param string $widget_id                    - id for html element
 * @param bool|string $default_val_id = false  - id of the selected value
 * @param bool|string $child_widget_id = false - id of dependent child element
 * @param bool $is_multiple = false            - is multiple selection
 * @param bool $readonly = false               - is read only
 */
function renderFilter(&$locations, $tier, $widget_id, $default_val_id = false, $child_widget_id = false, $is_multiple = false, $readonly = false) {
    if ( $default_val_id === false) {
        foreach ( $locations as $val ) {
            if ( ($val['tier'] == $tier) && $val['is_default'])
                $default_val_id = $val['id'];
        }
    }
    if ( !is_array($default_val_id) && strpos($default_val_id, '_')){ // bugfix - print_all_region_filters() might get actual option value="123_123" from some controllers (partnerController)
        $also_match_id = array_pop(explode('_', $default_val_id));
    }

    ?>
    <select id="<?php echo $widget_id;?>" name="<?php echo $widget_id;?><?php if ($is_multiple) echo '[]';?>" <?php if ($readonly) echo 'disabled="disabled"'?> <?php if ( $is_multiple) echo 'multiple="multiple" size="10"';?>
            <?php if ($child_widget_id ) { ?>onchange="setChildStatus_<?php echo str_replace('-', '_', $widget_id);?>();" <?php }?>>
        <option value="">--<?php tp('choose');?>--</option>
        <?php
        foreach ( $locations as $val ) {
            if ( $val['tier'] == $tier) {
                $selected = '';
                if ( is_array($default_val_id) && (@in_array($val['id'], $default_val_id) ) ) {
                    $selected = 'selected="selected"';
                } else if ( !is_array($default_val_id) && $val['id'] === $default_val_id ) {
                    $selected = 'selected="selected"';
                } else if ( $also_match_id === $val['id'] ) {
                    $selected = 'selected="selected"';
                }
                echo '<option value="'.buildId($tier, $locations, $val['id']).'" '.$selected.'>'.$val['name'].'</option>';
            }
        }
        ?>
    </select>
    <?php
    if ( $child_widget_id ) {?>
        <script type="text/javascript">
            <!--//--><![CDATA[//><!--

            function setChildStatus_<?php echo str_replace('-', '_', $widget_id);?>() {
                var widgetObj = YAHOO.util.Dom.get('<?php echo $widget_id;?>');
                setChildStatus(widgetObj.selectedIndex,'<?php echo $child_widget_id;?>','<?php echo $widget_id;?>', <?php echo $readonly ? $readonly : "0"?>);
            }

            YAHOO.util.Event.onDOMReady(function () {
                setChildStatus_<?php echo str_replace('-', '_', $widget_id);?>();
            });
            //--><!]]>
        </script>
    <?php }
}

/**
 * @param $prefix
 * @param $container
 * @param $data_url
 * @param $num_tiers
 */
function renderCityAutocomplete($prefix, $container, $data_url, $num_tiers) {
	if($prefix)
	$prefix = $prefix.'_';
?>
 <script type="text/javascript">
     <!--//--><![CDATA[//><!--

    var autoComp = makeAutocomplete('<?php echo $prefix;?>city', '<?php echo $container;?>', '<?php echo $data_url;?>' );
    appendExtraInfo(autoComp,<?php echo $num_tiers - 1;?>);
    // array can contain object references, element ids, or both
    function suggestRegions( oSelf , elItem , oData ) {
      var parent_id = elItem[2][<?php echo ($num_tiers)*2 - 1;?>];
      var selected = 0;
      selected = setSelected('<?php echo $prefix;?>province_id', parent_id, false);
      setChildStatus(selected,'<?php echo $prefix;?>district_id','<?php echo $prefix;?>province_id');
      <?php if ($num_tiers > 2 ) { ?>
      selected = setSelected('<?php echo $prefix;?>district_id', elItem[2][<?php echo ($num_tiers)*2 - 2;?>], true);
      <?php } ?>
      <?php if ($num_tiers > 3 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_c_id','<?php echo $prefix;?>district_id');
       selected = setSelected('<?php echo $prefix;?>region_c_id', elItem[2][<?php echo ($num_tiers)*2 - 3;?>], true);
      <?php } ?>

      <?php if ($num_tiers > 4 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_d_id','<?php echo $prefix;?>region_c_id');
       selected = setSelected('<?php echo $prefix;?>region_d_id', elItem[2][<?php echo ($num_tiers)*2 - 4;?>], true);
      <?php } ?>

      <?php if ($num_tiers > 5 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_e_id','<?php echo $prefix;?>region_d_id');
       selected = setSelected('<?php echo $prefix;?>region_e_id', elItem[2][<?php echo ($num_tiers)*2 - 5;?>], true);
      <?php } ?>

      <?php if ($num_tiers > 6 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_f_id','<?php echo $prefix;?>region_e_id');
       selected = setSelected('<?php echo $prefix;?>region_f_id', elItem[2][<?php echo ($num_tiers)*2 - 6;?>], true);
      <?php } ?>

      <?php if ($num_tiers > 7 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_g_id','<?php echo $prefix;?>region_f_id');
       selected = setSelected('<?php echo $prefix;?>region_g_id', elItem[2][<?php echo ($num_tiers)*2 - 7;?>], true);
      <?php } ?>

      <?php if ($num_tiers > 8 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_h_id','<?php echo $prefix;?>region_g_id');
       selected = setSelected('<?php echo $prefix;?>region_h_id', elItem[2][<?php echo ($num_tiers)*2 - 8;?>], true);
      <?php } ?>

      <?php if ($num_tiers > 9 ) { ?>
       setChildStatus(selected,'<?php echo $prefix;?>region_i_id','<?php echo $prefix;?>region_h_id');
       selected = setSelected('<?php echo $prefix;?>region_i_id', elItem[2][<?php echo ($num_tiers)*2 - 9;?>], true);
      <?php } ?>


      var is_new_chk = YAHOO.util.Dom.get('is_new_<?php echo $prefix;?>city');
      if ( is_new_chk ) is_new_chk.checked = false;
    }
     autoComp.itemSelectEvent.subscribe(suggestRegions);
    //--><!]]>
  </script>


<?php
}

/**
 * retrieves parent region ids for up to 3 tiers and returns them as a criteria array
 *
 * TODO: How does city fit into criteria?
 * TODO: Support maximum number of tiers.
 *
 * @param int|string $location_id - id of a location row in database
 * @param string $prefix = '' - prefix to prepend to resulting criteria keys
 *
 * @return array - criteria-style array for region_filters_dropdown()
 */
function locationIDTo3TierCriteriaArray($location_id, $prefix='')
{
    $prefix = $prefix ? $prefix.'_' : '';

    $sql = "SELECT
                    location3.tier,
                    location3.id AS child_id,
                    location2.id AS parent_id,
                    location1.id AS grandparent_id
                FROM
                    location AS location3
                LEFT JOIN location AS location2 ON location3.parent_id = location2.id
                LEFT JOIN location AS location1 ON location2.parent_id = location1.id
                WHERE location3.id = ?";
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $locations = $db->fetchRow($sql, $location_id);

    // TODO: How does city fit into criteria array?
    $criteria = array(
        $prefix.'province_id' => '',
        $prefix.'district_id' => '',
        $prefix.'region_c_id' => '',
        $prefix.'region_d_id' => '',
        $prefix.'region_e_id' => '',
        $prefix.'region_f_id' => '',
        $prefix.'region_g_id' => '',
        $prefix.'region_h_id' => '',
        $prefix.'region_i_id' => '',
    );

    if ($locations['tier'] == 3) {
        $criteria[$prefix.'province_id'] = $locations['grandparent_id'];
        $criteria[$prefix.'district_id'] = $locations['parent_id'];
        $criteria[$prefix.'region_c_id'] = $locations['child_id'];
    }
    elseif ($locations['tier'] == 2) {
        $criteria[$prefix.'province_id'] = $locations['parent_id'];
        $criteria[$prefix.'district_id'] = $locations['child_id'];
    }
    elseif ($locations['tier'] == 1) {
        $criteria[$prefix.'province_id'] = $locations['child_id'];
    }

    return $criteria;

}


function renderFacilityDropDown($facilities, $selected_index, $readonly)
{
  $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
  $sql = 'SELECT DISTINCT
       f.id as id,
       f.location_id AS "zone1",
       l2.id as "zone2",
       l2.parent_id AS "zone3"
      FROM facility f
      LEFT JOIN location l1 ON f.location_id = l1.id
      LEFT JOIN location l2 ON l1.parent_id = l2.id
      ';
  $rowArray = $db->fetchAll($sql); // get 3 tiers of parent ids
  $location_classes = array();
  foreach($rowArray as $row){
    // store parent location ids in a hash. hash[id] = "zone1id zone2id zone3id"
    $location_classes[$row['id']] = trim($row['zone1'].' '.$row['zone2'].' '.$row['zone3']);
  }
  $dupe = '';
  // lets build a visible <select> and also a display:none <select> with all locations
  $output = '<select id="facilityInput" name="facilityInput"';
  if ($readonly) 
  {
      $output .= ' disabled="disabled"';
  }
  $output .= '>';
  $output .= '<option class="defaultval" value="">--'.t('choose').'--</option>';
  foreach ( $facilities as $vals ) {
    $output .= '<option class="'.$location_classes[$vals['id']].'" value="'.$vals['id'].'" '.($selected_index == $vals['id']?'SELECTED':'').'>'.$vals['facility_name'].'</option>';
    $dupe .= '<option class="'.$location_classes[$vals['id']].'" value="'.$vals['id'].'" '.($selected_index == $vals['id']?'SELECTED':'').'>'.$vals['facility_name'].'</option>';
  }
  $output .= '</select>';
  $output .= '<div style="display:none;">';
  $output .= '<select id="facilityInputHidden" name="facilityInputHidden" style="display:none;visibility:hidden;">';
  $output .= '<option class="defaultval" value="">--'.t('choose').'--</option>';
  $output .= $dupe;
  $output .= '</select>';
  $output .= '</div>';

  // selects have a value attribute "region1_region2_region3", ie: 555_423_1
  // lets filter facility list by the last value when the user chooses something
  $js = '
      $(function () {
        regionSelectElements = $("#province_id,#district_id,#region_c_id,#region_d_id,#region_e_id,#region_f_id,#region_g_id,#region_h_id,#region_i_id")
        .change(function () {
            var compare_id = "";
            if ($(this).val() != ""){
              compare_id = $(this).val().split("_").pop();
            } else {
              for (i = regionSelectElements.length - 1 ; i >= 0; i--) {
                compare_id = $(regionSelectElements[i]).val().split("_").pop();
                if (compare_id != "")
                  break;
              }
            }

            allFacilities = $("#facilityInputHidden").children();
            cnt = allFacilities.length;
            facilityInput = $("#facilityInput");
            facilityInput.empty();

            for(i = 0; i < cnt; i++){
              row = $(allFacilities[i]);

              if(compare_id == "" || row.hasClass(compare_id) || row.hasClass("defaultval")){
                facilityInput.append(row.clone());
              }

            }
          });
      });
  ';
  $output .= '<script type="text/JavaScript">'.$js.'</script>';
  return $output;

}


/**
 * get the last drop down chosen by region filters
 *
 * @param string $prefix - prefix for html element names
 * @param $criteria - html form parameters
 * @return mixed|null
 */
function regionFiltersGetLastID($prefix, $criteria)
{
	if ($prefix)
		$prefix .= '_';
	$selectedID = null;

	if($criteria[$prefix.'province_id']) $selectedID = $criteria[$prefix.'province_id'];
	if($criteria[$prefix.'district_id']) $selectedID = $criteria[$prefix.'district_id'];
	if($criteria[$prefix.'region_c_id']) $selectedID = $criteria[$prefix.'region_c_id'];
	if($criteria[$prefix.'region_d_id']) $selectedID = $criteria[$prefix.'region_d_id'];
	if($criteria[$prefix.'region_e_id']) $selectedID = $criteria[$prefix.'region_e_id'];
	if($criteria[$prefix.'region_f_id']) $selectedID = $criteria[$prefix.'region_f_id'];
	if($criteria[$prefix.'region_g_id']) $selectedID = $criteria[$prefix.'region_g_id'];
	if($criteria[$prefix.'region_h_id']) $selectedID = $criteria[$prefix.'region_h_id'];
	if($criteria[$prefix.'region_i_id']) $selectedID = $criteria[$prefix.'region_i_id'];

	if (! $selectedID)
		return null;

	$o = explode('_', $selectedID);
	return array_pop($o);
}


/**
 * region filters (Dropdown style) - heavily uses renderFilter
 * @param $view
 * @param &$view                     - the view object, by reference
 * @param array &$locations          - reference to an array of location arrays, often from Location::getAll. passed to renderFilter
 * @param array &$criteria           - reference to an array returned by ReportFilterHelpers::getLocationCriteriaValues
 * @param bool $is_multiple = false  - is multiple select box
 * @param bool $middleColumn = false - has middle column
 * @param string $prefix = ''        - prefix to prepend to element id
 * @param bool $required = false     - mark as required
 */
function region_filters_dropdown(&$view, &$locations, &$criteria, $is_multiple = false, $middleColumn = false, $prefix = '', $required = false) {
    if ( $prefix )
        $prefix .= '_';

    $required = $required ? '<span class="required">*</span>' : '';
    $class = $is_multiple ? 'autoHeight' : '';
    ?>

    <div class="fieldLabel" id="<?php echo $prefix; ?>province_id_lbl"><?php echo $required . t('Region A (Province)'); ?></div>
    <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showProvince"    <?php  if ($criteria['showProvince']) echo 'checked="checked"';?> /></div><label for="showProvince" ></label></div> <?php } ?>
    <div class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 1, $prefix.'province_id', @$criteria[$prefix.'province_id'], ($view->setting['display_region_b']?$prefix.'district_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>

    <?php if ( $view->setting['display_region_b'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>district_id_lbl"><?php echo $required . t('Region B (Health District)'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showDistrict"   <?php  if (@$criteria['showDistrict']) echo 'checked="checked"';?> /></div><label for="showDistrict" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 2, $prefix.'district_id', @$criteria[$prefix.'district_id'], ($view->setting['display_region_c']?$prefix.'region_c_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_c'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_c_lbl"><?php echo $required . t('Region C (Local Region)'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionC"   <?php  if (@$criteria['showRegionC']) echo 'checked="checked"';?> /></div><label for="showRegionC" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 3, $prefix.'region_c_id', @$criteria[$prefix.'region_c_id'], ($view->setting['display_region_d']?$prefix.'region_d_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_d'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_d_lbl"><?php echo $required . t('Region D'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionD"   <?php  if (@$criteria['showRegionD']) echo 'checked="checked"';?> /></div><label for="showRegionD" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 4, $prefix.'region_d_id', @$criteria[$prefix.'region_d_id'], ($view->setting['display_region_e']?$prefix.'region_e_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_e'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_e_lbl"><?php echo $required . t('Region E'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionE"   <?php  if (@$criteria['showRegionE']) echo 'checked="checked"';?> /></div><label for="showRegionE" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 5, $prefix.'region_e_id', @$criteria[$prefix.'region_e_id'], ($view->setting['display_region_f']?$prefix.'region_f_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_f'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_f_lbl"><?php echo $required . t('Region F'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionF"   <?php  if (@$criteria['showRegionF']) echo 'checked="checked"';?> /></div><label for="showRegionF" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 6, $prefix.'region_f_id', @$criteria[$prefix.'region_f_id'], ($view->setting['display_region_g']?$prefix.'region_g_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_g'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_g_lbl"><?php echo $required . t('Region G'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionG"   <?php  if (@$criteria['showRegionG']) echo 'checked="checked"';?> /></div><label for="showRegionG" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 7, $prefix.'region_g_id', @$criteria[$prefix.'region_g_id'], ($view->setting['display_region_h']?$prefix.'region_h_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_h'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_h_lbl"><?php echo $required . t('Region H'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionH"   <?php  if (@$criteria['showRegionH']) echo 'checked="checked"';?> /></div><label for="showRegionH" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 8, $prefix.'region_h_id', @$criteria[$prefix.'region_h_id'], ($view->setting['display_region_i']?$prefix.'region_i_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php } ?>

    <?php if ( $view->setting['display_region_i'] ) { ?>
        <div class="fieldLabel" id="<?php echo $prefix; ?>region_i_lbl"><?php echo $required . t('Region I'); ?></div>
        <div class="fieldInput"><?php if ($middleColumn) { ?><div  class="leftBorderPad"><input type="checkbox" name="showRegionI"   <?php  if (@$criteria['showRegionI']) echo 'checked="checked"';?> /></div><label for="showRegionI" ></label></div> <?php } ?>
        <div  class="leftBorder <?php echo $class; ?>"><?php renderFilter($locations, 9, $prefix.'region_i_id', @$criteria[$prefix.'region_i_id'], ($view->setting['display_region_i']?'region_i_id':false), $is_multiple, $view->viewonly); ?></div><?php if (!$middleColumn) echo '</div>'; ?>
    <?php }

}

/**
 * region filters (Dropdown style)
 *
 * preservice uses a prefix-geo123 field name and a template / view name of prefixgeo123, call with prefix= local or permanent etc for field names local-geo1 2 3, or permanent-geo123 and assign default values to view->localgeo1 = 123; etc
 * this is just a helper function to render the same drop downs used in 4 pages.
 * ex: region_filters_dropdown_ps($this, 'local');
 * @param $view
 * @param string $prefix
 * @param bool $readonly
 */
function region_filters_dropdown_ps(&$view, $prefix = '', $readonly = false) {
  if ($prefix) $prefix2 = $prefix . '_';
	?>

	<label id="<?php echo ($prefix?$prefix.'_' : ''); ?>province_id_lbl"><?php echo (@$view->translation['Region A (Province)']); ?></label>
	<?php renderFilter($view->locations, 1, $prefix2.'geo1', $view->escape($view->{$prefix.'geo1'}), ($view->setting['display_region_b']?$prefix2.'geo2':false),'',$readonly);

	if ( $view->setting['display_region_b'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."district_id_lbl\">" . @$view->translation['Region B (Health District)'] . '</label>';
		renderFilter($view->locations, 2, $prefix2.'geo2', $view->escape( $view->{$prefix.'geo2'} ), ($view->setting['display_region_b']?$prefix2.'geo3':false),'',$readonly);
	}

	if ( $view->setting['display_region_c'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_c_id_lbl\">" . @$view->translation['Region C (Local Region)'] . '</label>';
		renderFilter($view->locations, 3, $prefix2.'geo3', $view->escape( $view->{$prefix.'geo3'} ), ($view->setting['display_region_c']?$prefix2.'geo4':false),'',$readonly);
	}

	if ( $view->setting['display_region_d'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_d_id_lbl\">" . @$view->translation['Region D'] . '</label>';
		renderFilter($view->locations, 4, $prefix2.'geo4', $view->escape( $view->{$prefix.'geo4'} ), ($view->setting['display_region_d']?$prefix2.'geo5':false),'',$readonly);
	}

	if ( $view->setting['display_region_e'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_e_id_lbl\">" . @$view->translation['Region E'] . '</label>';
		renderFilter($view->locations, 5, $prefix2.'geo5', $view->escape( $view->{$prefix.'geo5'} ), ($view->setting['display_region_e']?$prefix2.'geo6':false),'',$readonly);
	}

	if ( $view->setting['display_region_f'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_f_id_lbl\">" . @$view->translation['Region F'] . '</label>';
		renderFilter($view->locations, 6, $prefix2.'geo6', $view->escape( $view->{$prefix.'geo6'} ), ($view->setting['display_region_f']?$prefix2.'geo7':false),'',$readonly);
	}

	if ( $view->setting['display_region_g'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_g_id_lbl\">" . @$view->translation['Region G'] . '</label>';
		renderFilter($view->locations, 7, $prefix2.'geo7', $view->escape( $view->{$prefix.'geo7'} ), ($view->setting['display_region_g']?$prefix2.'geo8':false),'',$readonly);
	}

	if ( $view->setting['display_region_h'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_h_id_lbl\">" . @$view->translation['Region H'] . '</label>';
		renderFilter($view->locations, 8, $prefix2.'geo8', $view->escape( $view->{$prefix.'geo8'} ), ($view->setting['display_region_h']?$prefix2.'geo9':false),'',$readonly);
	}

	if ( $view->setting['display_region_i'] ) {
		echo "\t <label id=\"".($prefix?$prefix.'_' : '')."region_i_id_lbl\">" . @$view->translation['Region I'] . '</label>';
		renderFilter($view->locations, 9, $prefix2.'geo9', $view->escape( $view->{$prefix.'geo9'} ), false,'',$readonly);
	}

}

/**
 * @param $tlocations
 * @param $selectedValue
 * @param $selectContainerAttrs
 */
function training_location_dropdown(&$tlocations, $selectedValue, $selectContainerAttrs)
{
	?>

	<select <?php echo $selectContainerAttrs; ?>>
	<option value="">&mdash; <?php tp('select');?> &mdash;</option>
	<?php
	foreach($tlocations as $r) {
		if(!isset($lastProv) || $lastProv != $r['province_name']) {
			$rgns = $r;       // make copy of regions and join the regions as html for display as: province - district - region
			unset( $rgns['id'] );
			unset( $rgns['training_location_name'] );
			unset( $rgns['city_name'] );
				$rgnText = implode("&nbsp;&mdash;&nbsp;", $rgns);
				if ($rgnText != $lastProv)
					echo '<optgroup label="'.$rgnText.'">';
				$lastProv = $rgnText;
		}

      echo '<option value="'.$r['id'].'"'.(($selectedValue == $r['id']) ? ' selected' : '').'>';
      echo $r['training_location_name'];
      if ($r['city_name'] && $r['city_name'] != 'unknown')
        echo '&nbsp;&mdash;&nbsp;' . $r['city_name'];
      echo "</option>";
   }

		if (!isset($lastProv)) echo '</optgroup>';
  ?>

  <option value="0"><?php tp('unknown');?></option>
  </select>
  <?php
}

/**
 * @param $tlocations
 * @param $selectedValue
 * @param $selectContainerAttrs
 * @return string
 */
function training_location_dropdown_as_a_return_value(&$tlocations, $selectedValue, $selectContainerAttrs) // todo refactor these
{
  $opts = array ('<option value="">&mdash; ',t('select'),' &mdash;</option>');
  foreach($tlocations as $r) {
    if(!isset($lastProv) || $lastProv != $r['province_name']) {
      $rgns = $r;       // make copy of regions and join the regions as html for display as: province - district - region
      unset( $rgns['id'] );
      unset( $rgns['training_location_name'] );
      unset( $rgns['city_name'] );
        $rgnText = implode("&nbsp;&mdash;&nbsp;", $rgns);
        if ($rgnText != $lastProv)
          $opts [] = '<optgroup label="'.$rgnText.'">';
        $lastProv = $rgnText;
    }

      $opts[]   = '<option value="'.$r['id'].'"'.(($selectedValue == $r['id']) ? ' selected' : '').'>';
      $opts[]   = $r['training_location_name'];
      if ($r['city_name'] && $r['city_name'] != 'unknown')
        $opts[] = '&nbsp;&mdash;&nbsp;' . $r['city_name'];
      $opts[]   = "</option>";
   }

    if (!isset($lastProv)) $opts[] = '</optgroup>';

  $opts[] = '<option value="0">'.t('unknown').'</option>';
  $options = implode('', $opts);
  return "<select $selectContainerAttrs>$options</select>";
}


    // TODO: These github links no longer exist:
		<div class="fieldLabel" id="partner_lbl"><?php echo t('Subpartner'); ?></div>
		<div class="fieldLabel" id="mechanism_lbl"><?php echo t('Mechanism'); ?></div>
/**
 * 
 * @param unknown $fieldIndex
 * @param unknown $view
 * @param unknown $subPartner
 * @param unknown $partnerFunder
 * @param unknown $mechanism
 * @param string $val_employee
 * @param string $val_partner
 * @param string $val_subPartner
 * @param string $val_partnerFunder
 * @param string $val_mechanism
 * @param string $is_multiple
 * @param string $required
 * @param string $disabled
 */

function partner_sfm_dropdown($fieldIndex, &$view, &$subPartner, &$partnerFunder, &$mechanism, 
  $val_employee = null, $val_partner = null, $val_subPartner = null, $val_partnerFunder = null, $val_mechanism = null, 
  $is_multiple = false, $required = false, $disabled = '') {

  $required = $required ? '<span class="required">*</span>' : '';
  $class = $is_multiple ? 'autoHeight' : '';
  ?>
		<div class="fieldLabel" id="subPartner_lbl"><?php echo t('Partner'); ?></div>
		<div class="fieldInput">
	
		<?php partnerRenderFunder($subPartner, 'subPartner' . strval($fieldIndex), $val_subPartner, 'partnerFunder' . strval($fieldIndex), $is_multiple, $required, $disabled); ?>
		 
		<a href="
		<?php echo $view->base_url; ?>
		
		<?php 
		      $showDeleteLink = false;
		      if(!$val_employee && !$val_partner && !$val_subPartner && !$val_partnerFunder && !$val_mechanism) //empty
		      ;
		      else if($val_employee && $val_partner && $val_subPartner && $val_partnerFunder && $val_mechanism) { //employee
		          if ($view->hasACL("edit_employee"))
		          {
		              $showDeleteLink = true;
		              echo '/employee/delete_funder/id/' . $val_employee . '_' . $val_partner. '_' . $val_subPartner . '_' . $val_partnerFunder . '_' . $val_mechanism;
		          } 
			  }
		      else if(!$val_employee && $val_partner && $val_subPartner && $val_partnerFunder && $val_mechanism) { //partner
		      	  if ($view->hasACL("edit_partners"))
		      	  {
		              $showDeleteLink = true;
		      	      echo '/partner/delete_funder/id/' . $val_partner . '_' . $val_subPartner . '_' . $val_partnerFunder . '_' . $val_mechanism;
		      	  } 
		      }	
		      else if (!$val_employee && !$val_partner  && $val_subPartner && $val_partnerFunder && $val_mechanism) { //mechanism
		          if ($view->hasACL("edit_mechanisms"))
		          { 
		              $showDeleteLink = true;
		              echo '/mechanism/delete_funder/id/' . $val_subPartner . '_' . $val_partnerFunder . '_' . $val_mechanism;
		          }
		      }
		?>
		" >

		<?php 
		  if($val_subPartner && $val_partnerFunder && $val_mechanism && $showDeleteLink) { // not empty
		      		    echo t('Delete Funder'); 
		  }
		?>
		
		</a></div>

		<div class="fieldLabel" id="partnerFunder_lbl"><?php echo t('Funder'); ?></div>
		<div  class="fieldInput">
		<?php partnerRenderFunder($partnerFunder, 'partnerFunder' . strval($fieldIndex), $val_partnerFunder, 'mechanism' . strval($fieldIndex), $is_multiple, $required, $disabled); ?>
        </div>

		<div class="fieldLabel" id="mechanism_lbl"><?php echo 'Mechanism'; ?></div>
		<div  class="fieldInput">
		<?php partnerRenderFunder($mechanism, 'mechanism' . strval($fieldIndex), $val_mechanism, false, $is_multiple, $required, $disabled); ?>
        </div>

		<?php 

}

/**
 * TODO: This function calls a non-existent function gnrtestrenderFunder so doesn't work - find out what it was supposed to do
 * @param $view
 * @param $employee
 * @param $partner
 * @param $subPartner
 * @param $partnerFunder
 * @param $mechanism
 * @param null $val_employee
 * @param null $val_partner
 * @param null $val_subPartner
 * @param null $val_partnerFunder
 * @param null $val_mechanism
 * @param bool $is_multiple
 * @param bool $required
 */
function gnrtest_epsfm_dropdown(&$view, &$employee, &$partner, &$subPartner, &$partnerFunder, &$mechanism, $val_employee = null, $val_partner = null, $val_subPartner = null, $val_partnerFunder = null, $val_mechanism = null, $is_multiple = false, $required = false) {

	$required = $required ? '<span class="required">*</span>' : '';
	$class = $is_multiple ? 'autoHeight' : '';
	?>

		<div class="fieldLabel" id="employee_lbl"><?php echo t('Employee'); ?></div>
		<div class="fieldInput">
		<?php gnrtestrenderFunder($employee, 'employee', false, 'partner', $is_multiple); ?>
        </div>

		<div class="fieldLabel" id="partner_lbl"><?php echo 'Partner'; ?></div>
		<div class="fieldInput">
		<?php gnrtestrenderFunder($partner, 'partner', $val_employee, 'subPartner', $is_multiple); ?>
        </div>

		<div class="fieldLabel" id="subPartner_lbl"><?php echo t('Partner'); ?></div>
		<div  class="fieldInput">
		<?php gnrtestrenderFunder($subPartner, 'subPartner', $val_subPartner, 'partnerFunder', $is_multiple); ?>
        </div>

		<div class="fieldLabel" id="partnerFunder_lbl"><?php echo t('Funder'); ?></div>
		<div  class="fieldInput">
        <?php gnrtestrenderFunder($partnerFunder, 'partnerFunder', $val_partnerFunder, 'mechanism', $is_multiple); ?>
        </div>

		<div class="fieldLabel" id="mechanism_lbl"><?php echo 'Mechanism'; ?></div>
		<div  class="fieldInput">
		<?php gnrtestrenderFunder($mechanism, 'mechanism', $val_mechanism, false, $is_multiple); ?>
        </div>

		<?php 

}

/**
 * Output a dropdown select box in-line with option ids constructed from keys in $widget_array
 * 
 * @param array   $widget_array            - array of options for dropdown
 * @param string  $widget_id               - html id
 * @param string  $default_val_id  = false - id of the value selected by default
 * @param string  $child_widget_id = false - id of a dependent child object which is enabled/disabled by this selection
 * @param boolean $is_multiple     = false - is multiple selection?
 * @param boolean $required        = false - is a required element?
 * @param string  $disabled        = ''    - is read-only? 
 */
function partnerRenderFunder(&$widget_array, $widget_id, $default_val_id = false, $child_widget_id = false, $is_multiple = false, $required = false, $disabled = '') {

?>
  <select <?php echo $disabled;?> id="<?php echo $widget_id;?>" name="<?php echo $widget_id ;?>" <?php echo ' autocomplete="off" '?><?php if ($child_widget_id) { ?>onchange="setFunderStatus_<?php echo str_replace('-', '_', $widget_id); ?>()" <?php }?> >
    <option value="">--<?php tp('choose');?>--</option>
<?php

    foreach ( $widget_array as $val ) {

        $selected = '';
        if ( $default_val_id == $val['id']) {
           $selected = 'selected="selected"';
        }

        if ($val['employee_id']) {
            if ($val['partner_id']) {
                if ($val['subpartner_id']) {
                    if ($val['partner_funder_option_id']) {
                        if ($val['mechanism_option_id']) {
                            echo ('<option value="' . $val['employee_id'] . "_" . $val['partner_id'] . "_" . $val['subpartner_id'] . "_" . $val['partner_funder_option_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['mechanism_phrase'] . '</option>');
                        }
                        else {
                            echo ('<option value="' . $val['employee_id'] . "_" . $val['partner_id'] . "_" . $val['subpartner_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['funder_phrase'] . '</option>');
                        }
                    }
                    else if (!$val['partner_funder_option_id'] && !$val['mechanism_option_id']) {	 
                          echo ('<option value="' . $val['employee_id'] . "_" . $val['partner_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['partner'] . '</option>');
                    }
                }
                else if (!$val['subpartner_id'] && !$val['partner_funder_option_id'] && !$val['mechanism_option_id']) {
                    echo ('<option value="' . $val['employee_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['partner'] . '</option>');
                }
            }
            else if (!$val['partner_id'] && !$val['subpartner_id'] && !$val['partner_funder_option_id'] && !$val['mechanism_option_id']) {
                echo ('<option value="' . $val['id'] . '" ' . $selected . '>' . $val['employee_code'] . '</option>');
            }
        }
        else {
            
            if ($val['partner_id']) {
                if ($val['subpartner_id']) {
                    if ($val['partner_funder_option_id']) {
                        if ($val['mechanism_option_id']) {
                            echo ('<option value="' . $val['partner_id'] . "_" .
                                $val['subpartner_id'] . "_" . $val['partner_funder_option_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['mechanism_phrase'] . '</option>');
                        }
                        else {
                            echo ('<option value="' . $val['partner_id'] . "_" . $val['subpartner_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['funder_phrase'] . '</option>');
                        }
                    }
                    else if (!$val['partner_funder_option_id'] && !$val['mechanism_option_id']) {
                          echo ('<option value="' . $val['partner_id'] . "_" . $val['id'] . '" ' . $selected . '>' . $val['partner'] . '</option>');
                    }
                }
                else if (!$val['subpartner_id'] && !$val['partner_funder_option_id'] && !$val['mechanism_option_id']) {
                      echo ('<option value="' . $val['id'] . '" ' . $selected . '>' . $val['partner'] . '</option>');
                }
            }
            else {
                if ($val['subpartner_id']) {
                    if ($val['partner_funder_option_id']) {
                        if ($val['mechanism_option_id']) {
                            echo ('<option value="' . $val['subpartner_id'] . "_" . $val['partner_funder_option_id'] . "_" .$val['id'] . '" ' . $selected . ' >' . $val['mechanism_phrase'] . '</option>');
                        }
                        else {
                            echo ('<option value="' . $val['subpartner_id'] . "_" . $val['id'] . '" ' . $selected . ' >' . $val['funder_phrase'] . '</option>');
                        }
                    }
                    else if (!$val['partner_funder_option_id'] && !$val['mechanism_option_id']) {
                        echo ('<option value="' . $val['id'] . '" ' . $selected . ' >' . $val['partner'] . '</option>');
                    }
                    
                }
            }
        }
        

           	  
    }
    ?>
  </select>
  <?php
if ( $child_widget_id ) {?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--

function setFunderStatus_<?php echo str_replace('-', '_', $widget_id);?>() {
	var widgetObj = YAHOO.util.Dom.get('<?php echo $widget_id;?>');
	setFunderStatus(widgetObj.selectedIndex,'<?php echo $child_widget_id;?>','<?php echo $widget_id;?>');
}

YAHOO.util.Event.onDOMReady(function () {
	// set orig selected values
	//var widgetObj = YAHOO.util.Dom.get('<?php echo $widget_id;?>');
	//setFunderStatus(widgetObj.selectedIndex,'<?php echo $child_widget_id;?>','<?php echo $widget_id;?>');
});

//--><!]]>
</script>
<?php }
}
