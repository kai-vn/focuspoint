<?php

class Bigone_Profile_Block_Adminhtml_Product_Brand extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/product/brand.phtml');
    }

    public function getTabLabel() {
        return $this->__('Assign Brand');
    }

    public function getTabTitle() {
        return $this->__('Title Tab');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getBrands() {
        $brands = Mage::getModel('profile/brand')->getCollection()->addFieldToFilter('status','1');
        return $brands;
    }

    public function isCheckBrand($brandId, $productId) {
        $item = Mage::getModel('profile/brandassign')->getCollection()
                        ->addFieldToFilter('product_id', $productId)->getFirstItem();
        if ($item->getId()) {
            $brands = explode(',', $item->getBrands());
            return in_array($brandId, $brands) ? true : false;
        }
        return false;
    }

    
}
