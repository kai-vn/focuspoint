<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Datadescription extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $html = '';
        if ($this->getText())
        {
            $html = '<h3>'.$this->getText().'</h3>';
        }
        return $html;
    }
}
