<?php

class Meigee_Thememanager_Block_Adminhtml_Thems_ActivateTheme extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('thememanager');
        $theme = Mage::app()->getRequest()->getParam('theme');

        $form = new Varien_Data_Form(array(
            'method' => 'post',
            'name' => 'form_data',
            'action' => $this->getUrl('*/*/installSkin'),
            'onsubmit' => 'return InstallSkin();',
            'enctype' => 'multipart/form-data'
        ));

        $form->addField('activationNote', 'note', array(
          'text'     => $helper->__('Activation Options'),
          'class'   => 'activation-title'
        ));
        $form->addField('submit1', 'submit', array(
            'required'  => true,
//            'value'  => Mage::helper('thememanager')->__('Install'),
            'value'  => $this->__('Activate Theme'),
            'class'  => 'install-demo-btn',
            'tabindex' => 1
        ));

        $fieldset = $form->addFieldset('store_fieldset', array('legend'=>$helper->__('Select Store')));

        /**
         * Check is single store mode
         */

        $already_installed = array();
        $installed_thems = $helper->getUsedThems();

        if (!Mage::app()->isSingleStoreMode())
        {
            $values = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false);
            foreach ($values AS $f => &$v)
            {
                if (!empty($v['value']))
                {
                    foreach ($v['value'] AS $key => &$store)
                    {
                        if (isset($installed_thems[$store['value']]) && $theme == $installed_thems[$store['value']])
                        {
                            $already_installed[$store['value']] = trim($store['label']);
                            $store['label'] .= ' - ' . $helper->__('Already installed');
                        }
                    }
                }
            }

            $field = $fieldset->addField('ActivationStoreMultiselect', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => $helper->__('Store View'),
                'title'     => $helper->__('Store View'),
                'required'  => true,
                'values'    => $values,
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else
        {
            $id = Mage::app()->getStore(true)->getId();
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => $id
            ));
            if (isset($installed_thems[$id]) || $theme == $installed_thems[$id])
            {
                $already_installed[$id] = Mage::app()->getStore(true)->getName();;
            }
        }


        $delete_all_the_settings = '<p class="hided_element installed_store_action_note checked__delete_all" style="color:#f90000;">'.$helper->__('All configs (category/products/cms configs) created before for this store will be removed completely. Backup your data before theme activation!').'</p>';

        foreach ($already_installed AS $store_id => $store_name)
        {
            $name = 'installed_store_action_'.$store_id;
            $store_name = trim(preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', '', $store_name));
            $fieldset->addField($name, 'select', array(
                'name'      => $name,
                'label'     => $helper->__('There are several created settings for selected store "%s"', $store_name),
                'required'  => true,
                'class'  => 'installed_store_actions installed_store_action-'.$store_id,
                'values'    => array(
                                        '' => $helper->__('Please select')
                                        , 'delete_all' => $helper->__('Delete all the settings')
                                        , 'replace_default_only' => $helper->__('Replace default theme only.')
                                    ),
                'after_element_html' => $delete_all_the_settings
                                        . '<p class="hided_element installed_store_action_note checked__replace_default_only" style="color: #f90000;">'.$helper->__('Default config settings will be replaced. Keep in mind that created configs (category/products/cms configs) might affect on displaying of new theme.').'</p>'
            ));
        }

        $fieldset->addField('delete_all_settings', 'hidden', array(
            'name' => 'delete_all_settings',
            'value' => '1',
            'after_element_html'  =>  $delete_all_the_settings,
        ));



        $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();

        $fieldset2 = $form->addFieldset('skin_fieldset', array('legend'=>Mage::helper('thememanager')->__('Select Skin')));
        $fieldset2->addType('selectSkin','Meigee_Thememanager_Block_Adminhtml_Thems_ActivateTheme_SelectSkin');
        $fieldset2->addField('selectSkin', 'selectSkin', array(
            'text'     => $helper->__('Select Theme'),
            'skins' => $predefined_arr,
            'namesapce' => $theme
        ));



        $fieldset->addField('theme', 'hidden', array(
            'name'      => 'theme',
            'value'  =>  $theme,
        ));

        $form->addField('submit2', 'submit', array(
            'required'  => true,
            'value'  => $this->__('Activate Theme'),
            'class'  => 'install-demo-btn bottom',
            'tabindex' => 1
        ));

        if (1 == count($predefined_arr) && Mage::app()->isSingleStoreMode() && empty($already_installed))
        {
            $redirectUrl = $this->getUrl('*/*/installSkin', array(
                'stores' => $id
                , 'skin' => key($predefined_arr)
                , 'theme' => $theme
                , 'isAjax' => 'true'
            ));
            $this->setRedirectUrl ($redirectUrl);
        }

        $form->setUseContainer(true);
        $form->setId('install_form');
        $this->setForm($form);

        return parent::_prepareForm();
    }


}