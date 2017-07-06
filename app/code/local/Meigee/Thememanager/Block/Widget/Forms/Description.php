<?php

class Meigee_Thememanager_Block_Widget_Forms_Description extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $text = $element->getValues();
        $html = '<div id="' . $element->getHtmlId() . '">';
        $html .= '<h1>' . $text[0]['label'] . '</h1>';
        $html .= '<div>' . $text[0]['value'] . '</div>';
        $html .= '</div>';
        return $html;
    }
}
