<?php

class Meigee_Thememanager_Block_Adminhtml_Forms_Radio_VerticalIco extends Meigee_Thememanager_Block_Adminhtml_Forms_Radio_Ico
{
    public function getFormElement()
    {
        $this->add_class = 'vertical-radio';
        return parent::getFormElement();
    }
}