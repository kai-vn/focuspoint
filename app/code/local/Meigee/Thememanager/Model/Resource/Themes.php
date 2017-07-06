<?php

class Meigee_Thememanager_Model_Resource_Themes extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('thememanager/table_themes', 'theme_id');
    }
}