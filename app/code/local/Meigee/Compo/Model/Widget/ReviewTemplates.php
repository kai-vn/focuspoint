<?php
class Meigee_Compo_Model_Widget_ReviewTemplates extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    const theme = 'Compo';



    public function toOptionArray()
    {
        $grid_types = $this->getConfig(self::theme, 'review_templates');
        return $grid_types['option'];
    }

}
