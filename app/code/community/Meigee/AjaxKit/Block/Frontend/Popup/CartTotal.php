<?php
class Meigee_AjaxKit_Block_Frontend_Popup_CartTotal extends Mage_Core_Block_Template
{
    function __construct()
    {
        $this->setTemplate('ajaxkit/popup/cart_total.phtml');
        parent::__construct();
    }

    function getGrandTotalPrice()
    {
        return $this->helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());
    }

    function getTotalItems()
    {
        return (int)Mage::helper('checkout/cart')->getCart()->getItemsCount();
    }
}
