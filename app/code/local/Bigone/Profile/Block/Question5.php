<?php

class Bigone_Profile_Block_Question5 extends Mage_Catalog_Block_Product_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/question5.phtml');
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getOptions($start = 25, $step = 0.5, $end = 40) {
        $option = array();
        while ($start <= $end) {
            $option[] = $start;
            $start += $step;
        }
        return $option;
    }

    public function getSavedPrescription() {
        $data = array();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $_customer = Mage::getSingleton('customer/session')->getCustomer();
            $orders = Mage::getResourceModel('sales/order_collection')
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('customer_id', $_customer->getId())
                    ->addAttributeToSort('created_at', 'DESC')
                    ->setPageSize(1);
            $lastOrder = $orders->getFirstItem();
            foreach ($lastOrder->getAllVisibleItems() as $item) {
                $profileData = $item->getBigoneProfileData();
                if ($profileData) {
                    $data = unserialize($profileData);
                    break;
                }
            }
        }
        return $data;
    }

    public function getOptionText() {
        return '<option value="00">00 plano</option><option value="" selected="selected">None</option><option value="SPH">SPH</option><option value="DS">DS</option><option value="Balance">Balance</option><option value="INFINITY">INFINITY</option>';
    }

    public function getOrders() {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $orderCollection = Mage::getResourceModel('sales/order_collection')
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('state', 'complete')
                ->setOrder('created_at', 'desc');
        return $orderCollection;
    }
    
    public function getViewUrl($order) {
        return $this->getUrl('sales/order/view', array('order_id' => $order->getId()));
    }
    
    public function getAjaxUrlOrder() {
        return Mage::getUrl('profile/ajax/changeOrder');
    }

}
