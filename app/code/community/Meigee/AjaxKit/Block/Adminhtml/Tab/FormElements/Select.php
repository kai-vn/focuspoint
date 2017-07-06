<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Select extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Form
{
    protected $opt = array();

    function getElementHtml()
    {
        $this->opt = array();
        foreach($this->element->values->value AS $value)
        {
            $this->opt[] = array('value'=>$value['data'], 'option'=>$value);
        }
        return $this->buildHtml();
    }

    function buildHtml()
    {
        $multi = "";
        $multi_class = "";

        $name = $this->name;
        if ($this->element->type['multiple'])
        {
            $multi = 'multiple size="'.$this->element->type['multiple'].'"';
            $multi_class = " multiple";
        }

        $html = '<select name="'.$name.'" id="'.$this->id.'" class="option-select'.$multi_class.'" '.$multi.'>';
       // foreach($this->element->values->value AS $value)
        foreach($this->opt AS $option)
        {
            if (is_array($this->value))
            {
                $selected =  in_array($option['value'],$this->value);
            }
            else
            {
                $selected = $option['value'] == $this->value;
            }
            $html .= '<option value="'.$option['value'].'" '.($selected ? 'selected="selected"' : '').'>'.(string)$option['option'].'</option>';
        }
        $html .= '</select>';
        return $html;
    }




}