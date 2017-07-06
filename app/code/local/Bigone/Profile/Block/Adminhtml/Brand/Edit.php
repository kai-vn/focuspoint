<?php

class Bigone_Profile_Block_Adminhtml_Brand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        $this->_objectId = 'brand_id';
        $this->_blockGroup = 'profile';
        $this->_controller = 'adminhtml_brand';
        $helper = Mage::helper('profile');

        parent::__construct();
        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
              editForm.submit($('edit_form').action+'back/edit/');
            }
        ";      
    }

    public function getHeaderText() {
        if (Mage::registry('brand_data') && Mage::registry('brand_data')->getId()) {
            return Mage::helper('profile')->__("Edit Brand '%s'", $this->htmlEscape(Mage::registry('brand_data')->getTitle()));
        } else {
            return Mage::helper('profile')->__('New Brand');
        }
    }

}
