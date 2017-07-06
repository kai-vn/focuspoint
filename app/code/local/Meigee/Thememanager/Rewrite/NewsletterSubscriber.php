<?php
class Meigee_Thememanager_Rewrite_NewsletterSubscriber extends Mage_Newsletter_Block_Subscribe
{
    private $_customer = null;
    private function _getCustomer()
    {
        if (is_null($this->_customer))
        {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $this->_customer = ($customer->getId()) ? $customer : false;
        }
        return $this->_customer;
    }
    function getCustomerEmail()
    {
        return $this->_getCustomer() ? $this->_getCustomer()->getEmail() : '';
    }
    function getCustomerLastname()
    {
        return $this->_getCustomer() ? $this->_getCustomer()->getLastname() : '';
    }
    function getCustomerFirstname()
    {
        return $this->_getCustomer() ? $this->_getCustomer()->getFirstname() : '';
    }

}