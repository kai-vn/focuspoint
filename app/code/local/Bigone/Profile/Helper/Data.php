<?php

class Bigone_Profile_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getGlassIds() {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $select = $connection->select('glasses_id')->from('profile_glasses');
        return $connection->fetchCol($select);
    }

    public function getMaxGlassId() {
        $maxId = max($this->getGlassIds()) ?: 0;
        return $maxId;
    }

    public function getLenIds() {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $select = $connection->select('lens_id')->from('profile_lens');
        return $connection->fetchCol($select);
    }

    public function getMaxLenId() {
        $maxId = max($this->getLenIds()) ?: 0;
        return $maxId;
    }

    public function getCoatingIds() {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $select = $connection->select('coating_id')->from('profile_coating');
        return $connection->fetchCol($select);
    }

    public function getMaxCoatingId() {
        $maxId = max($this->getCoatingIds()) ?: 0;
        return $maxId;
    }

    public function getLoginUrl() {
        return Mage::getUrl('customer/account/login');
    }

    public function getAjaxUrlBrand() {
        return Mage::getUrl('profile/ajax/index');
    }

    public function getAjaxUrlPres() {
        return Mage::getUrl('profile/ajax/changePres');
    }

    public function getDataByBrand($brandId) {

        $data = array();
        $glasses = Mage::getModel('profile/glasses')->getCollection()
                ->addFieldToFilter('brand', $brandId);
        $glasses->setOrder('glasses_id','DESC');
        foreach ($glasses as $item) {
            $data['glass'][$item->getId()] = $item->getData();
        }
        $lens = Mage::getModel('profile/lens')->getCollection()
                ->addFieldToFilter('brand', $brandId);
        $lens->setOrder('lens_id','DESC');
        foreach ($lens as $item) {
            $data['lens'][$item->getId()] = $item->getData();
        }
        $coatings = Mage::getModel('profile/coating')->getCollection()
                ->addFieldToFilter('brand', $brandId);
        $coatings->setOrder('coating_id','DESC');
        foreach ($coatings as $item) {
            $data['coating'][$item->getId()] = $item->getData();
        }
        if (!empty($data['coating'])) {
            foreach ($data['coating'] as $key => &$coating) {
                $coating['subprice'] = unserialize($coating['subprice']);
            }
        }
        return $data;
    }

    public function isCustomerLoggedIn() {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function getSavedPrescription($orderId) {
        $_order = Mage::getModel('sales/order')->load($orderId);
        $data = array();
        $items = array();
        foreach ($_order->getAllVisibleItems() as $item) {
            if ($item->getBigoneProfileData()) {
                $items[] = $item->getId();
            }
        }
        if (!empty($items)) {
            $id = max($items);
            $data = unserialize(Mage::getModel('sales/order_item')->load($id)->getBigoneProfileData());
        }
        return $data;
    }

    public function getLogoBrandById($id = null) {
        $logo = '';
        if ($id) {
            $brand = Mage::getModel('profile/brand')->load($id);
            if ($brand->getId()) {
                $logo = $brand->getLogo();
            }
        }
        return $logo;
    }

    public function getDataGlassById($glass_id = null) {
        $data = array();
        if ($glass_id) {
            $glass = Mage::getModel('profile/glasses')->load($glass_id);
            if ($glass->getId()) {
                $data = array(
                    'title' => $glass->getTitle(),
                    'subtitle' => $glass->getSubtitle(),
                    'price' => $glass->getPrice()
                );
            }
        }
        return $data;
    }

    public function getDataLensById($len_id = null) {
        $data = array();
        if ($len_id) {
            $lens = Mage::getModel('profile/lens')->load($len_id);
            if ($lens->getId()) {
                $data = array(
                    'title' => $lens->getTitle(),
                    'subtitle' => $lens->getSubtitle(),
                    'price' => $lens->getPrice()
                );
            }
        }
        return $data;
    }

    public function getDataCoatingById($coating_id = null) {
        $data = array();
        if ($coating_id) {
            $coating = Mage::getModel('profile/coating')->load($coating_id);
            if ($coating->getId()) {
                $data = array(
                    'title' => $coating->getTitle(),
                    'subtitle' => $coating->getSubtitle(),
                    'price' => $coating->getPrice()
                );
            }
        }
        return $data;
    }
    
    public function formatPrice($str) {
        $price = (!empty($str) && floatval($str) != 0) ? 'RM'.$str : 'Free';
        return $price;
    }
    
    public function getMethodPresTitle($id) {
        $title = '';
        switch ($id) {
            case '1':
                $title = 'Fill it out online';
                break;
            case '2':
                $title = 'Send later';
                break;
            case '3':
                $title = 'Use my saved prescription';
                break;
        }
        return $title;
    }

    public function getUrlEditOrder() {
        return Mage::getUrl('profile/ajax/editOrder');
    }

    public function getExtensionFile($fileName) {
        $ex = '';
        if ($fileName) {
            $arr = explode('.', $fileName);
            $ex = end($arr);
        }
        return $ex;
    }

    public function isImage($fileName) {
        $ex = $this->getExtensionFile($fileName);
        $extension_img = array('jpg', 'jpeg', 'gif', 'png');
        return (in_array($ex, $extension_img)) ? true : false;
    }

    public function isIncludeTest($productId)
    {
        $item = Mage::getModel('profile/brandassign')->getCollection()
                        ->addFieldToFilter('product_id', $productId)->getFirstItem();
        if ($item->getId()) {
            return $item->getIncludeTest() ? true : false;
        }
        return false;
    }

}
