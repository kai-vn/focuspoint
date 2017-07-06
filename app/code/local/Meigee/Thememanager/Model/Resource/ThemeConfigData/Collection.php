<?php

class Meigee_Thememanager_Model_Resource_ThemeConfigData_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('thememanager/themeConfigData');
        //$this->_init('thememanager/theme_config_data');
    }
}