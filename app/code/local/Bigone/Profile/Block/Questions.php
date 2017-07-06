<?php

class Bigone_Profile_Block_Questions extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/questions.phtml');
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

}
