<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_Range extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'range';


    function getElementHtml()
    {
        if (!empty($this->element->element_property->min))
        {
            $this->setAttributes('min', (string)$this->element->element_property->min);
        }

        if (!empty($this->element->element_property->max))
        {
            $this->setAttributes('max', (string)$this->element->element_property->max);
        }

        if (!empty($this->element->element_property->step))
        {
            $this->setAttributes('step', (string)$this->element->element_property->step);
        }
        $this->setAttributes('class', 'option-range');

        return parent::getElementHtml();
    }
}