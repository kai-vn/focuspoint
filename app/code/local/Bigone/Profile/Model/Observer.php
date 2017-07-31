<?php

class Bigone_Profile_Model_Observer {

    public function saveProductBrand($observer) {
        $include = Mage::app()->getRequest()->getPost('profile_include') ? 1 : 0;
        $productId = $observer->getEvent()->getProduct()->getId();
        $param_brand = Mage::app()->getRequest()->getPost('profile_brand');
        $brands = (!empty($param_brand)) ? implode(',', $param_brand) : '';
        $item = Mage::getModel('profile/brandassign')->getCollection()
                        ->addFieldToFilter('product_id', $productId)->getFirstItem();
        if ($item->getId()) {
            $item = $item->load($item->getId());
            if(!empty($brands) || $include) {
                $item->setBrands($brands)->setIncludeTest($include)->save();
            } else $item->delete();
        } else {
            if(!empty($brands) || $include) {
                $model = Mage::getModel('profile/brandassign')->setProductId($productId);
                $model->setBrands($brands)->setIncludeTest($include)->save();
            }
        }
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
