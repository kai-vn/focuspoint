<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_Radio extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'radio';

    function getElementHtml()
    {
        $html = '';

        foreach($this->element->values->value AS $value)
        {
            $val = (string)$value['data'];
            $this->attributes['checked'] = $val == $this->value ? 'checked' : '';
            $this->attributes['value'] = $val;
            $this->attributes['id'] = $this->id . '-' . $val;


            $html .= '<div class="left a-center meigee-radio '.($this->attributes['checked'] ? "active" : '').'">
                            <label class="inline">
                                 '.parent::getElementHtml() . $val.'
                            </label>
                        </div>';
        }
        $html .= '</select>';
        return $html;
    }
}


/*




*/