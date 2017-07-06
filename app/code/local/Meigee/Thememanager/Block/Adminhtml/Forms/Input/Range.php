<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Input_Range extends Meigee_Thememanager_Block_Adminhtml_Forms_Input
{
    public function getFormElement()
    {
        $this->type = 'range';
        $this->element_params = isset($this->params['element_property'])? (array)$this->params['element_property'] : array();
        return parent::getFormElement();
    }
}
