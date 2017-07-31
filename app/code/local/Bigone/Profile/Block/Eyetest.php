<?php

class Bigone_Profile_Block_Eyetest extends Mage_Catalog_Block_Product_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/eyetest.phtml');
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }
}
