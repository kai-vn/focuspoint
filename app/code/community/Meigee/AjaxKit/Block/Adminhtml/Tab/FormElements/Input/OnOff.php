<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_OnOff extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'checkbox';

    function getElementHtml()
    {
        $this->setAttributes('class', 'on-off-selector');

        if($this->value > 0)
        {
            $this->setAttributes('checked', 'checked');
        }
        $this->setAttributes('value', (int)$this->value);

        $display_on = !empty($this->element->label_on) ? $this->element->label_on : 'Display';
        $display_off = !empty($this->element->label_off) ? $this->element->label_off : 'Hide';

        return '<div class="input_checkbox">
                    <input class="_input_checkbox_value no-save" value="'.$this->value.'" type="hidden" >
                    <div class="slide pull-right">
                        '.parent::getElementHtml().'
                        <label for="'.$this->id.'" class="slider">
                            <label for="'.$this->id.'" class="display on">'.$display_on.'</label>
                            <span class="switch"></span>
                            <label for="'.$this->id.'" class="display off">'.$display_off.'</label>
                        </label>
                    </div>
                </div>';
    }
}


