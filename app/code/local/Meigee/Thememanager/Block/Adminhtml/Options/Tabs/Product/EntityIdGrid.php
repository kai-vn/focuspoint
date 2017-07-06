<?php

class Meigee_Thememanager_Block_Adminhtml_Options_Tabs_Product_EntityIdGrid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $SelectedEntityIdGrid = Mage::registry('SelectedEntityIdGrid');
        if ($SelectedEntityIdGrid)
        {
            $is_checked = isset($SelectedEntityIdGrid[$row->getId()]);
        }
        else
        {
            $is_checked = (int)Mage::app()->getRequest()->getParam('theme_id') == (int)Mage::getModel('catalog/product')->load($row->getId())->getMeigeeProductThemeId();
        }
        return '<input class="massaction-checkbox" '.($is_checked ? 'checked="checked"' : '').' type="checkbox" value="'.$row->getId().'" name="__CheckedProduct[]">
                <input type="hidden" value="'.$row->getId().'" name="__CheckProductList[]">';
    }

}