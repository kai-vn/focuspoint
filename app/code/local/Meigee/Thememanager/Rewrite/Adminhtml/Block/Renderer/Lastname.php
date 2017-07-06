<?php
class Meigee_Thememanager_Rewrite_Adminhtml_Block_Renderer_Lastname extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if ($row->getType() != 2)
        {
            $value = $row->getThememanagerSubscriberLastname();
        }
        else
        {
            $value = $row->getCustomerLastname() ? $row->getCustomerLastname() : $row->getThememanagerSubscriberLastname();
        }
        return $value ? $value : '----';
    }
}









