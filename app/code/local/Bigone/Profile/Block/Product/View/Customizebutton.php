<?php

class Bigone_Profile_Block_Product_View_Customizebutton extends Mage_Core_Block_Template
{
    public function getButtonTitle()
    {
        return $this->__('Customize and Add to Cart');
    }

    public function isCustomerLoggedIn()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }
}