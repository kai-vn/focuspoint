<?php

class Bigone_Profile_Block_Profile extends Mage_Catalog_Block_Product_Abstract {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getProduct() {
        return Mage::registry('current_product');
    }

    public function getBrandsByProduct($productId) {
        $brands = array();
        $collection = Mage::getModel('profile/brandassign')->getCollection();
        $item = $collection->addFieldToFilter('product_id', $productId)->getFirstItem();
        $brandStr = $item->getBrands();
        if ($item->getId() && !empty($brandStr)) {
            $brands = explode(',', $brandStr);
        }
        return $brands;
    }

    public function getBrandData($productId) {
        $data = array();
        $collectionBrands = Mage::getModel('profile/brand')->getCollection()
                ->addFieldToFilter('brand_id', array('in' => $this->getBrandsByProduct($productId)));
        $collectionBrands->setOrder('sort_order','ASC');
        foreach ($collectionBrands as $brand) {
            $data[$brand->getId()] = $brand->getData();
        }
        return $data;
    }

    public function checkHasBrand() {
        $check = false;
        $productId = $this->getProduct()->getId();
        $brands = $this->getBrandsByProduct($productId);
        if (!empty($brands)) $check = true;
        return $check;
    }
}
