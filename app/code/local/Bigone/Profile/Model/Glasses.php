<?php

class Bigone_Profile_Model_Glasses extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('profile/glasses');
    }
}