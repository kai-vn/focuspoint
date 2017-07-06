<?php
class Meigee_Thememanager_Model_Widget_View_Fbschemes  extends Meigee_Thememanager_Model_Widget_View
{
    public function toOptionArray()
    {
        return $this->getFbschemes();
    }

}