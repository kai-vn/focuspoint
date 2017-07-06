<?php

class Meigee_Thememanager_Block_Adminhtml_Thems_AddConfigForm extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if(!Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/add_or_clone'))
        {
            return parent::_prepareLayout();
        }
        $helper = Mage::helper('thememanager');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'name' => 'edit_form',
            'action' => $this->getUrl('*/*/getNewConfig'),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));


        if ($this->getRequest()->getParam('saveConfig'))
        {
            $form->addField('saveConfig', 'hidden', array(
                'name' => 'saveConfig',
                'value' => 1,
                'after_element_html' => $this->getReloadFormJs(),
            ));
        }
        else
        {
            $name = '';
            $save_btn_name = 'Save';

            if (Mage::app()->getRequest()->getParam('clone'))
            {
                $theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
                if ($theme_id)
                {
                    $theme = Mage::getModel('thememanager/themes')->load($theme_id);
                    $name = $theme->getName() . " CLONE";
                    $save_btn_name = 'Clone';

                    $form->addField('cloneConfigId', 'hidden', array(
                        'name' => 'cloneConfigId',
                        'value' => $theme->getId()
                    ));
                }
            }

            $fieldset = $form->addFieldset('addConfigFormFieldset', array());
//            $fieldset = $form->addFieldset('addConfigFormFieldset', array('legend'=>Mage::helper('adminhtml')->__('Confirm Parameters')));

            $fieldset->addField('name', 'text', array(
                'label' => $helper->__('Name'),
                'required' => true,
                'name' => 'name',
                'value' => $name,
            ));

            $fieldset->addField('themenamespace', 'hidden', array(
                'name' => 'themenamespace',
                'value' => $helper-> getThemeNamespace()
            ));

            $stores = $helper->getStoresForm();
            $installed_thems = $helper->getUsedThems();

            foreach($stores AS $key => $store)
            {
                if (!isset($installed_thems[$store['value']]) || $helper-> getThemeNamespace() != $installed_thems[$store['value']])
                {
                    unset($stores[$key]);
                }
            }

            $fieldset->addField('store_id', 'select', array(
                'label' => $helper->__('Select Store'),
                'name' => 'store_id',
                'values' => $stores,
                'note' => 'If you do not see your store above, please activate theme for it by clicking Install demo button',
                'onchange' => 'showStoreTypes(this);',
            ));

            $all_types = Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->getAllTypes();
            $all_types_form = array();

            $selected_store = false;
            foreach ($stores AS $store)
            {
                $selected_store = (!$selected_store) ? $store['value'] : $selected_store;
                foreach ($all_types AS $type)
                {
                    $type['attributes'] = array('data-store'=>$store['value'], 'class'=>'data_store_option' . ($store['value']==$selected_store ? '' : ' hided_element'));
                    $all_types_form[$type['value'] . '-' . $store['value']] = $type;
                }
            }
            $collection_themes = Mage::getModel('thememanager/themes')->getThemesCollection()->load();

            foreach ($collection_themes AS $theme)
            {
                $key = $theme->getType() . '-' . $theme->getStoreId();
                if (isset($all_types_form[$key]) && ($all_types_form[$key]['is_single'] || !$all_types_form[$key]['visible']))
                {
                    unset($all_types_form[$key]);
                }
            }

            $fieldset->addType('custom_select','Meigee_Thememanager_Block_Adminhtml_Thems_AddConfigForm_Select');
            $fieldset->addField('type', 'custom_select', array(
                'label' => $helper->__('Type'),
                'name' => 'type',
                'values' => $all_types_form,
            ));

            $form->addField('saveConfig', 'hidden', array(
                'name' => 'saveConfig',
                'value' => 1
            ));

            $fieldset->addField('submit', 'submit', array(
                'name'  => 'confirm',
                'disabled' => false,
                'class' => 'form-button',
                'value'     => $helper->__($save_btn_name),
                'after_element_html' => $this->getFormJs(),
            ));
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }


    function getFormJs()
    {
        return '
        <script>
            function showStoreTypes(el)
            {
                var this_value = el.value;
                var elements = document.getElementsByClassName("data_store_option");
                var selected = true;
                for (e in elements)
                {
                    if (elements[e].value)
                    {
                        if (this_value == elements[e].getAttribute("data-store"))
                        {
                            elements[e].classList.remove("hided_element");
                            elements[e].selected = selected;
                            selected = false;
                        }
                        else
                        {
                            elements[e].classList.add("hided_element");
                            elements[e].selected = false;
                        }
                    }
                }
            }
        </script>
        ';
    }




    function getReloadFormJs()
    {
        $theme_config_id = Mage::registry('theme_config_id');
        if ($theme_config_id)
        {
            return '<script> window.parent.location.href="'.$this->getUrl("*/*/editConfig", array('theme_id'=>$theme_config_id)).'"; </script> ';
        }
        return '<script> window.parent.location.reload(); </script> ';
    }



}