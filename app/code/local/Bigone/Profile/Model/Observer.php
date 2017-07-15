<?php

class Bigone_Profile_Model_Observer {

    public function saveProductBrand($observer) {
        $productId = $observer->getEvent()->getProduct()->getId();
        $param_brand = Mage::app()->getRequest()->getPost('profile_brand');
        $brands = (!empty($param_brand)) ? implode(',', $param_brand) : '';
        $item = Mage::getModel('profile/brandassign')->getCollection()
                        ->addFieldToFilter('product_id', $productId)->getFirstItem();
        if ($item->getId()) {
            $item->load($item->getId())->setBrands($brands)->save();
        } else
            Mage::getModel('profile/brandassign')->setBrands($brands)->setProductId($productId)->save();
    }

    public function createBlockQuestionProfile($observer) {
        $block = $observer->getEvent()->getBlock();
        $transport = $observer->getEvent()->getTransport();
        $html = $transport->getHtml(); 
        if ($block instanceof Mage_Catalog_Block_Product_View_Tabs) {
            $html .= Mage::app()->getLayout()->createBlock('profile/option')->toHtml();
        }
        $transport->setHtml($html);
    }

    public function setProfileData(Varien_Event_Observer $obs) {
        $quote = $obs->getEvent()->getQuote();
        $item = $obs->getQuoteItem();
        $infoBuyRequest = unserialize($item->getOptionByCode('info_buyRequest')->getValue());
        if (!empty($infoBuyRequest['profile'])) {
            $additionalOptions = array();
            $profileData = serialize($infoBuyRequest['profile']);
            $item->setData('bigone_profile_data', $profileData);
            $item->getProduct()->setIsSuperMode(true);
        }
    }

}
