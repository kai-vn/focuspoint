<?php

class Bigone_Profile_Block_Option extends Bigone_Profile_Block_Profile {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/option.phtml');
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
        $this->setChild('question5', $this->getLayout()->createBlock('profile/question5'));
        $childPrice = $this->getLayout()->createBlock('catalog/product_view', 'custom_price')->setTemplate('catalog/product/view/price_clone.phtml');
        $this->setChild('custom_price',$childPrice);
        return $this;
    }

    public function getButtonAddCartTitle() {
        return 'Add to cart';
    }

}
