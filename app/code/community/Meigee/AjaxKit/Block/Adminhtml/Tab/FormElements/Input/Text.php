<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_Text extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'text';
    function getElementHtml()
    {
        $this->setAttributes('class', 'option-text');
        $this->setAttributes('value', $this->value);

        return parent::getElementHtml();
    }
}