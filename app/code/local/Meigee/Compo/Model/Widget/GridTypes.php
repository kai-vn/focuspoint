<?php
class Meigee_Compo_Model_Widget_GridTypes extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    const theme = 'Compo';



    public function toOptionArray()
    {
        $grid_types = $this->getConfig(self::theme, 'grid_types');
        return $grid_types['option'];
    }

}
