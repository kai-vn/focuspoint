<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Input_FileArray extends Meigee_Thememanager_Block_Adminhtml_Forms_Input_File
{
    public function getFormElement()
    {
        $values = $this->getConfigValue();
        $values = (array)(empty($values) ? array() : @unserialize($values));

        $this->setConfigId($this->getConfigId().'[]');

        $html = '<div class="multifile-element-content">';
        $html .= '<div class="multifile-element-inputs">';
        foreach ($values AS $value)
        {
            $this->setConfigValue($value);
            $html .= '<div class="multifile-element">' . parent::getFormElement() . '</div>';
        }
        $this->setConfigValue('');

        $this->params['element_property']['disabled'] = 'disabled';
        $this->params['element_property']['class'] = 'disabled_element';

        $html .= '<div class="multifile-element hided_element multifile-element-example">' . parent::getFormElement() . '</div>';
        $html .= '</div>';
        $html .= '<div><button class="multifile-element-add" style="float:right;" onclick="cloneMultifileElement(this); return false;" ><span>Add</span></button></div>';
        $html .= '</div>';
        return $html;
    }
}





