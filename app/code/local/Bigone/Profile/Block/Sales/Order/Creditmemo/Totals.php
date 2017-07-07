<?php

class Bigone_Profile_Block_Sales_Order_Creditmemo_Totals extends Mage_Sales_Block_Order_Creditmemo_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();
        $amount = $this->getSource()->getChargesAmount();
        $profiletax = $this->getSource()->getProfileTaxAmount();
        if ($amount != 0) {
            $this->addTotalBefore(new Varien_Object(array(
                'code'      => 'charges',
                'value'     => $amount,
                'base_value'=> $amount,
                'label'     => $this->helper('profile')->__('Profile Charges'),
            ), array('shipping', 'tax')));

            
        }

        if ($profiletax != 0) {
            $this->addTotalBefore(new Varien_Object(array(
                'code'      => 'profiletax',
                'value'     => $profiletax,
                'base_value'=> $profiletax,
                'label'     => $this->helper('profile')->__('Profile Tax'),
            ), array('charges', 'tax')));
        }

        return $this;
    }

}