<?php

class Meigee_Thememanager_Block_Adminhtml_Options_GuideForm extends Mage_Adminhtml_Block_Widget_Form
{
    private $guide_block_name = '_module_guide';

    function __prepareLayout($guide_block_name)
    {
        $form = new Varien_Data_Form(array(
            'class' => 'hided_element'
        ));
        $form->addType('guide_html','Meigee_Thememanager_Block_Adminhtml_Forms_Guide');
        $form->addField('guide_html', 'guide_html', array(
            'group' => $this->guide_block_name,
            'block' => $guide_block_name,
        ));

        $form->setUseContainer(false);
        $this->setForm($form);
        return parent::_prepareLayout();
    }

    function setGuideBlockName($guide_block_name)
    {
        return $this->__prepareLayout($guide_block_name);
    }

    function setGuideGroupName($guide_group_name)
    {
        $this->guide_block_name = $guide_group_name;
    }



}