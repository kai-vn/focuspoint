<?php

class Meigee_Thememanager_Block_Adminhtml_Thems_InstalledThems extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('thememanager');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'name' => 'edit_form',
            'action' => $this->getUrl('*/*/themeConfig'),
            'method' => 'get',
            'enctype' => 'multipart/form-data',
        ));

        $form->addField('help_guide_button', 'hidden', array(
            'after_element_html' => '<div class="content-header">
                                                <button class="help" onclick="showHelpGuide()" type="button" title="Help" name="help_guide"><i class="fa fa-life-ring"></i>'.$helper->__('Help').'</button>
                                     </div>',
        ));

        $fieldset = $form->addFieldset('fieldset', array('legend'=>Mage::helper('adminhtml')->__('Available Themes')));

        $installed_thems = $helper->getInstalledThems();

        if (!empty($installed_thems))
        {
            $fieldset->addType('selectTheme','Meigee_Thememanager_Block_Adminhtml_Thems_InstalledThems_SelectTheme');
            $fieldset->addField('selectTheme', 'selectTheme', array(
                'text'     => '',
                'thems' => $installed_thems,
                'url' => $this->getUrl('*/*/themeConfig'),
                'activate_url' => $this->getUrl('*/*/activateTheme'),
                'deactivate_url' => $this->getUrl('*/*/deactivateTheme'),
                'used_thems' => $helper->getUsedThems()
            ));
        }
        else
        {
            $fieldset->addType('message','Meigee_Thememanager_Block_Adminhtml_Thems_InstalledThems_Message');
            $fieldset->addField('message', 'message', array(
                'message'     => $helper->__('No theme Selected'),
            ));
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }


    function redirect()
    {
        $helper = Mage::helper('thememanager');
        $installed_thems = $helper->getInstalledThems();
        if (count($installed_thems) == 1 )
        {
            return (string)key($installed_thems);

        }
        return false;
    }






}