<?php

class Bigone_Profile_Model_Sales_Quote_Address_Total_Profiletax extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    protected $_code = 'profiletax';
    protected $_calculator = null;
    protected $_label = null;

    public function __construct() {
        $this->_calculator = Mage::getSingleton('tax/calculation');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        parent::collect($address);
        $quote = $address->getQuote();
        $store = $address->getQuote()->getStore();
        $items = $address->getAllItems();
        if (!count($items)) {
            return $this;
        }

        $calc = $this->_calculator;
        $addressTaxRequest = $calc->getRateRequest(
                $quote->getShippingAddress(), $quote->getBillingAddress(), $quote->getCustomerTaxClassId(), $store
        );
        $shippingTaxClass = Mage::getStoreConfig('tax/classes/shipping_tax_class', $store);
        $addressTaxRequest->setProductClassId($shippingTaxClass);
        $rate = $calc->getRate($addressTaxRequest);
        $this->_label = 'Profile GST(' . $rate . '%)';
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $amt = $address->getProfileTaxAmount();
        if ($address->getChargesAmount() != 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $this->_label,
                'value' => $amt
            ));
        }



        return $this;
    }

}
