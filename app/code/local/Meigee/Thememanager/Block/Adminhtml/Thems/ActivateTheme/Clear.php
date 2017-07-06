<?php

class Meigee_Thememanager_Block_Adminhtml_Thems_ActivateTheme_Clear extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('thememanager');
        $theme = Mage::app()->getRequest()->getParam('theme');

        $form = new Varien_Data_Form(array(
            'method' => 'post',
            'name' => 'form_data',
            'action' => $this->getUrl('*/*/flusheCacheAndLogOut'),
            'onsubmit' => 'return parentReload();',
            'enctype' => 'multipart/form-data'
        ));

        if ($this->getIsDeactivate())
        {
            $fieldset = $form->addFieldset('store_fieldset_', array('legend'=>$helper->__('Deactivation was finished')));
            $fieldset->addField('label1', 'label', array(
                'value'     => '',
                'after_element_html' => '<h2>'.$helper->__('Skin was deactivated. Remove static blocks and pages which related to this skin.').'</h2>'
            ));
        }
        else
        {
            $fieldset = $form->addFieldset('store_fieldset_', array('legend'=>$helper->__('Installation was finished')));
        }

        $fieldset->addField('label2', 'label', array(
            'value'     => '',
            'after_element_html' => 'Cache will be flushed and you will be automatically logged out in <span class="form-timer">8</span> seconds'
        ));

        $fieldset->addField('submit', 'submit', array(
            'required'  => true,
            'value'  => Mage::helper('thememanager')->__('Flush the cache and Logout'),
            'tabindex' => 1
        ));

        $fieldset->addField('theme', 'hidden', array(
            'name'      => 'theme',
            'value'  =>  $theme
        ));

        $fieldset->addField('parentUrl', 'hidden', array(
            'name'      => 'parentUrl',
            'value'  =>  $this->getUrl('*/*/flusheCacheAndLogOut', array('theme'=>$theme))
        ));

        $form->setUseContainer(true);
        $form->setId('form_data');
        $this->setForm($form);

        return parent::_prepareForm();
    }
}