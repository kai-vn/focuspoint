<?php

class Meigee_Thememanager_Block_Adminhtml_Thems_DeactivateTheme extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('thememanager');
        $theme = Mage::app()->getRequest()->getParam('theme');

        $form = new Varien_Data_Form(array(
            'method' => 'post',
            'name' => 'form_data',
            'action' => $this->getUrl('*/*/deactivateSkin'),
            'onsubmit' => 'return checkDeactivateSkinForm();',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('store_fieldset', array('legend'=>$helper->__('Select Store')));

        $installed_thems = $helper->getUsedThems();

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode())
        {
            $values = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false);
            foreach ($values AS $f => &$v)
            {
                if (!empty($v['value']))
                {
                    foreach ($v['value'] AS $key => &$store)
                    {
                        if (!isset($installed_thems[$store['value']]) || $theme != $installed_thems[$store['value']])
                        {
                            unset($v['value'][$key]);
                        }
                    }
                    if (empty($v['value']))
                    {
                        unset($values[$f]);
                    }
                }
            }
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => $helper->__('Store View'),
                'title'     => $helper->__('Store View'),
                'required'  => true,
                'values'    => $values,
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }
        $this->setForm($form);

        $fieldset->addField('theme', 'hidden', array(
            'name'      => 'theme',
            'value'  =>  $theme,
        ));

        $fieldset->addField('areYouSure', 'hidden', array(
            'value'  =>  $this->__('All skin settings for the selected store/stores will be removed. Are you sure?'),
        ));

        $form->addField('submit', 'submit', array(
            'required'  => true,
            'value'  => Mage::helper('thememanager')->__('Deactivate'),
            'tabindex' => 1
        ));

        $form->setUseContainer(true);
        $form->setId('deactivate_form');
        $this->setForm($form);

        return parent::_prepareForm();
    }


}