<?php

class Meigee_Thememanager_Model_Resource_Themes_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('thememanager/themes');
    }
}