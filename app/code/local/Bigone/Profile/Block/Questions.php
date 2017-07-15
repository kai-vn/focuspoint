<?php

class Bigone_Profile_Block_Questions extends Mage_Catalog_Block_Product_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/questions.phtml');
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

}
