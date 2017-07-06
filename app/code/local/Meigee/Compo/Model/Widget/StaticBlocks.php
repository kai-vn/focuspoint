<?php
class Meigee_Compo_Model_Widget_StaticBlocks extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    const theme = 'Compo';



    public function toOptionArray()
    {
        return Mage::getModel('cms/block')->getCollection()->toOptionArray();
    }

}
