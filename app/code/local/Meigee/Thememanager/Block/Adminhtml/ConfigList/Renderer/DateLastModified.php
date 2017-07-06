<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_DateLastModified extends Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Date
{
    function getDate($row)
    {
        return (int)$row->getLastModifiedDate();
    }
}