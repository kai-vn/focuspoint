<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Remove extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $PageTypeConfigs = Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance();
//        if ($PageTypeConfigs::DefaultType == $row->getType())
        if (Meigee_Thememanager_Model_PageTypeConfigs_Basis::DefaultType == $row->getType())
        {
            return '<button class="scalable back" type="button" title="Remove"  onclick="removeConfig(false)"><i class="fa fa-times"></i>'.Mage::helper('catalog')->__('Remove').'</button>';
        }
        $helper = Mage::helper('thememanager');
        $url = $this->getUrl('*/*/removeConfig', array('theme'=>$helper->getThemeNamespace(), 'theme_id' => $row->getId()));
        return '<button class="scalable delete" type="button" title="Remove" onclick="removeConfig(\''.$url.'\')"><i class="fa fa-times"></i>'.Mage::helper('catalog')->__('Remove').'</button>';
    }

}