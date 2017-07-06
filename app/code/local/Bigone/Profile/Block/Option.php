<?php

class Bigone_Profile_Block_Option extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/option.phtml');
    }

    public function _prepareLayout() {
        parent::_prepareLayout();
        $this->setChild('question5', $this->getLayout()->createBlock('profile/question5'));
        return $this;
    }

    public function getProduct() {
        return Mage::registry('current_product');
    }

    public function getProductId() {
        return $this->getProduct()->getId();
    }

    public function getBrandsByProduct($productId) {
        $brands = array();
        $collection = Mage::getModel('profile/brandassign')->getCollection();
        $item = $collection->addFieldToFilter('product_id', $productId)->getFirstItem();
        if ($item->getId()) {
            $brands = explode(',', $item->getBrands());
        }
        return $brands;
    }

    public function getBrandData($productId) {
        $data = array();
        $collectionBrands = Mage::getModel('profile/brand')->getCollection()
                ->addFieldToFilter('brand_id', array('in' => $this->getBrandsByProduct($productId)));
        foreach ($collectionBrands as $brand) {
            $data[$brand->getId()] = $brand->getData();
        }
        return $data;
    }

}
