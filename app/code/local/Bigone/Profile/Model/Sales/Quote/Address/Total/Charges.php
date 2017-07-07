<?php

class Bigone_Profile_Model_Sales_Quote_Address_Total_Charges extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    protected $_code = 'charges';
    protected $_calculator = null;

    public function __construct() {
        $this->_calculator = Mage::getSingleton('tax/calculation');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        parent::collect($address);

        $this->_setAmount(0);
        $this->_setBaseAmount(0);
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


        $profileCharges = 0;
        foreach ($items as $item) {
            $profileData = unserialize($item->getBigoneProfileData());
            if (!empty($profileData['profile_cost'])) {
                $profileCharges = $profileCharges + ($profileData['profile_cost'] * (int) $item->getQty());
            }
        }

        if ($profileCharges != 0) {

            $taxAmount = $calc->calcTaxAmount($profileCharges, $rate, true, true);
            $baseTaxAmount = $calc->calcTaxAmount($profileCharges, $rate, true, true);
            $address->setProfileTaxAmount($taxAmount);
            $address->setBaseProfileTaxAmount($baseTaxAmount);

            $address->setChargesAmount($profileCharges - $address->getProfileTaxAmount());
            $address->setBaseChargesAmount($profileCharges - $address->getProfileTaxAmount());
            $quote->setChargesAmount($profileCharges - $baseTaxAmount);
            $address->setGrandTotal($address->getGrandTotal() + $profileCharges);
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $profileCharges);
        }

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $amt = $address->getChargesAmount();
        if ($amt != 0) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('profile')->__('Profile Charges'),
                'value' => $amt
            ));
        }
        return $this;
    }

}
