<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_DateAdd extends Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Date
{
    function getDate($row)
    {
        return (int)$row->getAddDate();
    }
}