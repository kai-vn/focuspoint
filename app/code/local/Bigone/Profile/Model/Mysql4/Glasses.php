<?php

class Bigone_Profile_Model_Mysql4_Glasses extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the profile_id refers to the key field in your database table.
        $this->_init('profile/glasses', 'glasses_id');
    }
}