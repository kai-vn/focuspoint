<?php

class Bigone_Profile_Block_Adminhtml_Brand_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        parent::_prepareForm();
        $form = new Varien_Data_Form();
        $helper = Mage::helper('profile');

        $fieldset = $form->addFieldset('brand_form', array('legend'=>$helper->__('Brand information')));
        
        $fieldset->addField('title', 'text', array(
            'label' => $helper->__('Brand Name'),
            'name' => 'title',
            'index' => 'title',
            'required' => true
        ));
        
        $fieldset->addField('logo', 'image', array(
            'label' => $helper->__('Brand Logo'),
            'name' => 'logo',
            'name' => 'logo',
            'required' => true
        ));
        
        
        $fieldset->addField('status', 'select', array(
            'label' => $helper->__('Status'),
            'name' => 'status',
            'index' => 'status',
            'values' => Mage::getSingleton('profile/status')->getOptionArray()
        ));
        
        $fieldset->addField('sort_order', 'text', array(
            'label' => $helper->__('Sort Order'),
            'name' => 'sort_order',
            'index' => 'sort_order',
        ));

        $session = Mage::getSingleton('adminhtml/session');
        if ($data = $session->getData('brand_data')) {
            $form->setValues($data);
        } elseif (Mage::registry('brand_data')) {
            $form->setValues(Mage::registry('brand_data')->getData());
        }
        $this->setForm($form);

        return $this;
    }
    
}