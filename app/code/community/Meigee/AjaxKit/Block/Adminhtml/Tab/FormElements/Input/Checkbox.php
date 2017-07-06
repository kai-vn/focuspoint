<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_Checkbox extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'checkbox';

    function getElementHtml()
    {
        $html = '';
        $this->setAttributes('name', (string)$this->element->element_property->max);

        foreach($this->element->values->value AS $value)
        {
            $val = (string)$value['data'];
            $this->attributes['checked'] = $val == $this->value ? 'checked' : '';
            $this->attributes['value'] = $val;
            $this->attributes['name'] = $this->name . '[]';
            $this->attributes['id'] = $this->id . '-' . $val;

            $html .= '<div class="left a-center meigee-radio ">
                            <label class="inline">
                                 '.parent::getElementHtml() . (string)$value.'
                            </label>
                        </div>';
        }
        $html .= '</select>';
        return $html;
    }
}


/*




*/