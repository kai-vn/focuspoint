<?php 

class Meigee_Thememanager_Model_Widget_View_Number extends Meigee_Thememanager_Model_Widget_View
{
    public function toOptionArray()
    {
        return $this->getNumber();
    }

}