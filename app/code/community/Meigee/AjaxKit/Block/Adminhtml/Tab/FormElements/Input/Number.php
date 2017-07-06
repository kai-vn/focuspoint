<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_Number extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'number';
    function getElementHtml()
    {
        $this->setAttributes('class', 'option-text');
        $this->setAttributes('value', $this->value);

        $element = (array)$this->element;

        if (isset($element['min']))
        {
            $this->setAttributes('min', (int)$element['min']);
        }

        if (isset($element['max']))
        {
            $this->setAttributes('max', (int)$element['max']);
        }
        return parent::getElementHtml();
    }
}