<?php


class System extends Zend_Db_Table_Abstract
{
    protected $_name = '_system';
    protected $_primary = 'country';

    public static function getSetting($field)
    {
        $tableObj = new System();

        $select = $tableObj->select()->from($tableObj->_name, array($field));
        try {
            $row = $tableObj->fetchRow();
            return $row->__get($field);
        } catch(Zend_Exception $e) {
            error_log($e);
        }
    }

    public static function getAll() {
        $tableObj = new System();

        $select = $tableObj->select()->from($tableObj->_name);
        try {
            $row = $tableObj->fetchRow();
            $rtn = array();
            $info = $tableObj->info();
            foreach($info['cols'] as $col ) {
                $rtn[$col] = $row->$col;
            }

            $rtn['num_location_tiers'] = 2                    //including city
                + $rtn['display_region_b']
                + $rtn['display_region_c']
                + $rtn['display_region_d']
                + $rtn['display_region_e']
                + $rtn['display_region_f']
                + $rtn['display_region_g']
                + $rtn['display_region_h']
                + $rtn['display_region_i'];

            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $rows = $db->fetchAll("select * from file where parent_id = 0 and parent_table = '_system'");
            foreach($rows as $row) {
                $rtn['logo_id'] = $row['id'];
            }
            $select = $db->select()->from('site_styles', 'site_style_name')->where('id = ?', $rtn['site_style_id']);
            $rtn['site_style'] = $db->fetchOne($select);
            require_once('models/Session.php');
            Session::setSettings($rtn);
            return $rtn;
        } catch(Zend_Exception $e) {
            error_log($e);
        }
    }

    public function putSetting($field, $value) {
        if ( !$value ) //convert null to 0
            $value = 0;
        $this->update(array($field => $value),'');
    }

}
