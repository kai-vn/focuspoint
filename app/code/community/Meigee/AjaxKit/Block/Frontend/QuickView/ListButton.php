<?php

class Meigee_AjaxKit_Block_Frontend_QuickView_ListButton extends Mage_Core_Block_Template
{
    private $product_id;

    function isEnabled()
    {
        $is_enabled_submodule = (bool)Mage::getModel('ajaxKit/configsReader')->getConfigStatus('general_add_to_cart');
        $is_enabled = (bool)Mage::getModel('ajaxKit/configsReader')->getConfig('general_add_to_cart', 'enable_quick_view');
        $this->product_id = (int)$this->getProduct()->getId();
        $this->setProduct(null);
        return $this->product_id > 0 && $is_enabled && $is_enabled_submodule;
    }

    function getAttributes()
    {
        return 'data-id="'.$this->product_id.'"';
    }
    function getClass()
    {
        return 'btn-ajaxkit-quick-view';
    }
}
