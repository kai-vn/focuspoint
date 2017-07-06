<?php
class Bigone_Profile_Block_Brand extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getProfile()     
     { 
        if (!$this->hasData('profile')) {
            $this->setData('profile', Mage::registry('profile'));
        }
        return $this->getData('profile');
        
    }
}