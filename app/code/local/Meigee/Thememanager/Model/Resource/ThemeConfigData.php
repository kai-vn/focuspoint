<?php

class Meigee_Thememanager_Model_Resource_ThemeConfigData extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('thememanager/table_theme_config_data', 'theme_config_data_id');
    }
}