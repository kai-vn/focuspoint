<?php
class Meigee_Compo_Model_Widget_ReviewsPerRow extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    const theme = 'Compo';



    public function toOptionArray()
    {
        $grid_types = $this->getConfig(self::theme, 'reviews_per_row');
        return $grid_types['option'];
    }

}
