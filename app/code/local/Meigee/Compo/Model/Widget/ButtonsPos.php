<?php
class Meigee_Compo_Model_Widget_ButtonsPos extends Meigee_Thememanager_Model_Widget_View_ThemeParametres
{
    const theme = 'Compo';



    public function toOptionArray()
    {
        $grid_types = $this->getConfig(self::theme, 'buttons_pos');
        return $grid_types['option'];
    }

}
