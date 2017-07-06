<?php
class Meigee_Compo_Model_Widget_VisibleReviews extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    const theme = 'Compo';



    public function toOptionArray()
    {
        $grid_types = $this->getConfig(self::theme, 'visible_reviews');
        return $grid_types['option'];
    }

}
