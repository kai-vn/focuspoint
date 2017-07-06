<?php

class Meigee_Thememanager_Block_Adminhtml_Options_Form extends Mage_Adminhtml_Block_Widget_Form
{
    function _prepareLayout()
    {
        $theme_id = $this->getRequest()->getParam('theme_id');
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('theme_id'=>$theme_id)),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
			'class' => 'meigee-thememanager-form'
        ));

        $form->addField('__currentPage', 'hidden', array(
            'name' => '__currentPage',
            'value' => '',
        ));


        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareLayout();
    }
}