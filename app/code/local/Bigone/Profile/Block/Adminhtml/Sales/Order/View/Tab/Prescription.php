<?php

class Bigone_Profile_Block_Adminhtml_Sales_Order_View_Tab_Prescription extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    public function _construct() {
        parent::_construct();
        $this->setTemplate('profile/sales/order/view/prescription.phtml');
    }

    public function getTabLabel() {
        return $this->__('Prescription');
    }

    public function getTabTitle() {
        return $this->__('Prescription');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder() {
        return Mage::registry('current_order');
    }

    protected function _getProfileDataByItem($item = null) {
        $data = array();
        if ($item->getId()) {
            $data = unserialize($item->getBigoneProfileData());
        }
        return $data;
    }

    public function getProfiledData() {
        $data = array();
        $order = $this->getOrder();
        if ($order->getId()) {
            foreach ($order->getAllVisibleItems() as $item) {
                if ($item->getBigoneProfileData()) {
                    $data[$item->getId()] = unserialize($item->getBigoneProfileData());
                }
            }
        }
        return $data;
    }

}
