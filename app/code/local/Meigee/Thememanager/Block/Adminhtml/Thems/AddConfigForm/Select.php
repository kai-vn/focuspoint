<?php
class Meigee_Thememanager_Block_Adminhtml_Thems_AddConfigForm_Select extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $html = '<select id="'.$this->getId().'" onclick="'.$this->getOnclick().'" name="'.$this->getName().'">';

        foreach ($this->getValues() AS $value)
        {
            $attributes = '';
            if (isset($value['attributes']) && is_array($value['attributes']))
            {
                foreach ($value['attributes'] AS $name=>$attribute)
                {
                    $attributes .= ' '.$name . '="'.$attribute.'"';
                }
            }

            $html .= '<option value="'.$value['value'].'"'.$attributes.'>'.$value['label'].'</option>';
        }
        $html .= '<select>';
        return $html;
    }
}



