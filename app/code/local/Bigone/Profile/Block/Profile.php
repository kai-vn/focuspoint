<?php

class Bigone_Profile_Block_Profile extends Mage_Core_Block_Template {
    
    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/option.phtml');
    }
    
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

}
