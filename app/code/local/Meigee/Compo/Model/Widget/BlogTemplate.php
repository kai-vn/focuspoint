<?php
class Meigee_Compo_Model_Widget_BlogTemplate extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'widget_post.phtml', 'label' => Mage::helper('adminhtml')->__('Horizontal List')),
            array('value'=>'widget_post_masonry.phtml', 'label' => Mage::helper('adminhtml')->__('Horizontal List Masonry')),
            array('value'=>'widget_post_slider.phtml', 'label' => Mage::helper('adminhtml')->__('Slider'))
        );
    }
}
