<?php

class Bigone_Profile_Model_Mysql4_Brandassign extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the profile_id refers to the key field in your database table.
        $this->_init('profile/brandassign', 'assign_id');
    }
}