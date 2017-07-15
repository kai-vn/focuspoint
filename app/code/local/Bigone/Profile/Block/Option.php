<?php

class Bigone_Profile_Block_Option extends Bigone_Profile_Block_Profile {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/option.phtml');
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
        $this->setChild('question5', $this->getLayout()->createBlock('profile/question5'));
        return $this;
    }

    public function getButtonAddCartTitle() {
        return 'Add to cart';
    }

}
