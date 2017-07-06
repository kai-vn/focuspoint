<?php

class Bigone_Profile_Model_Mysql4_Lens_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('profile/lens');
    }
}