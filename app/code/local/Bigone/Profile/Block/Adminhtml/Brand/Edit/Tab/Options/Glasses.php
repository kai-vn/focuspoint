<?php

class Bigone_Profile_Block_Adminhtml_Brand_Edit_Tab_Options_Glasses extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/options/glasses.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Delete Column'),
                    'class' => 'delete delete-product-option'
                ))
        );

        return parent::_prepareLayout();
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    public function getOptionValue() 
    {
    	$data = Mage::registry('glasses_data');
    	$values = array();
    	if ($data) {
    		foreach ($data as $value) {
    			$values[] = new Varien_Object($value);
    		}
    	}
    	return $values;
    }

    public function getFieldId()
    {
    	return 'glasses_column';
    }

    public function getFieldName()
    {
        return 'options[glasses]';
    }
    
    public function getAddButtonId() {
        return 'add_new_glasses';
    }

}
