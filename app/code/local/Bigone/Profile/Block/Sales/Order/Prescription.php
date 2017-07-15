<?php

class Bigone_Profile_Block_Sales_Order_Prescription extends Mage_Sales_Block_Order_Info {

    public function getProfileData() {
        $_order = $this->getOrder();
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

}
