<?php 

class Meigee_Thememanager_Model_Widget_View_Templates extends Meigee_Thememanager_Model_Widget_View
{
    public function toOptionArray()
    {
        return $this->getTemplates();
    }

}