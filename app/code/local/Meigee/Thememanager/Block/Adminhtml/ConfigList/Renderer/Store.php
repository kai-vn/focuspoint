<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Store extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    static $stores = array();
    public function render(Varien_Object $row)
    {
        $helper = Mage::helper('thememanager');
        $storeId = $row->getStoreId();
        if (!isset(self::$stores[$storeId]))
        {
            self::$stores[$storeId] = $helper->getStoreTitleNameById($storeId);
        }
        return self::$stores[$storeId] ? self::$stores[$storeId] : '<div class="removed-store">'.$helper->__('This store was removed and theme settings for this store are not available to edit. Please export or clone this subtheme to use it for another available store') . '</div>';
    }
}