<?php
class Meigee_Thememanager_Block_Adminhtml_Thems_InstalledThems_Message extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $html = '<tr>';
        $html .= '<td>' . $this->getMessage() . '</td>';
        $html .= '</tr>';
        return $html;
    }
}



