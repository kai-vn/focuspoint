<?php
class Meigee_AjaxKit_Block_Frontend_Popup_Button extends Mage_Core_Block_Template
{
    function __construct()
    {
        $this->setTemplate('ajaxkit/popup/button.phtml');
        parent::__construct();
    }

    function getAttributes()
    {
        $result = array('class'=>'', 'system'=>'');
        switch($this->getType())
        {
            case "to_cart_button":
                $result['class'] = 'rewrite-to-url';
                $result['system'] = 'data-url="' . $this->getUrl('checkout/cart', array('_forced_secure' => Mage::app()->getStore()->isCurrentlySecure())) . '"';
                break;

            case "to_checkout_button":
                $result['class'] = 'rewrite-to-url';
                $result['system'] = 'data-url="' . $this->getUrl('checkout/onepage', array('_forced_secure' => Mage::app()->getStore()->isCurrentlySecure())) . '"';
                break;

            case "continue_shopping_button":
                $result['class'] = 'close-popup';
                break;
        }
        return $result;
    }

}
