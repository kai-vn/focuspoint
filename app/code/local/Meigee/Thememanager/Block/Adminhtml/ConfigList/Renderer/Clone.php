<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Clone extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $helper = Mage::helper('thememanager');
        $url = $this->getUrl('*/*/getNewConfig', array('theme'=>$helper->getThemeNamespace(), 'clone'=>1, 'theme_id' => $row->getId()));
        return '<button type="button" title="Clone" onclick="cloneConfig(\''.$url.'\')"><i class="fa fa-files-o"></i>Clone</button>';
    }

}//