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
        foreach ($glasses as $item) {
            $data['glass'][] = $item->getData();
        }
        $lens = Mage::getModel('profile/lens')->getCollection()
                ->addFieldToFilter('brand', $brandId);
        foreach ($lens as $item) {
            $data['lens'][] = $item->getData();
        }
        $coatings = Mage::getModel('profile/coating')->getCollection()
                ->addFieldToFilter('brand', $brandId);
        foreach ($coatings as $item) {
            $data['coating'][] = $item->getData();
        }
        return $data;
    }
}
