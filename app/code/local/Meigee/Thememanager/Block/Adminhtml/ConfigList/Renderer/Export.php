<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Export extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $url = $this->getUrl('*/*/exportConfig', array('theme_id' => $row->getId()));
        return '<button  type="button" title="Export" href="javascript:void(0);" onclick="return exportConfig(\''.$url.'\');">'.Mage::helper('catalog')->__('Export').'</button>';
    }

}