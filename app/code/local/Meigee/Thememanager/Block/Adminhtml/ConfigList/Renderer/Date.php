<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Date extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $date = $this->getDate($row);
        return date("Y.m.d", $date);
    }
}