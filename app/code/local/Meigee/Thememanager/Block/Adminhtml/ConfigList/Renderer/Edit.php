<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Edit extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $url = $this->getUrl('*/*/editConfig', array('theme_id' => $row->getId()));
        $tr_class = 'ConfigType-'.$row->getType();

        return '<button class="edit-btn" data-parent-tr-class="'.$tr_class.'" type="button" title="Edit" onclick="return reloadTo(\''.$url.'\');"><i class="fa fa-pencil-square-o"></i>
                        '.((Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit')) ? Mage::helper('catalog')->__('Edit') : Mage::helper('catalog')->__('View') ).'</button>';
    }

}