<?php 

class Meigee_Thememanager_Model_Widget_View_ReviewTemplates extends Meigee_Thememanager_Model_Widget_View
{
    public function toOptionArray()
    {
        return $this->getReviewTemplates();
    }

}