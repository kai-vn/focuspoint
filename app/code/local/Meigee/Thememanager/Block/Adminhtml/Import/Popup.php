<?php

class Meigee_Thememanager_Block_Adminhtml_Import_Popup extends Mage_Adminhtml_Block_Template
{
    function __construct()
    {
        parent::__construct();
        $this->setTemplate('thememanager/import_popup.phtml');
    }

    function getSelectedItems()
    {
        $SelectedEntityIdGrid = Mage::registry('SelectedEntityIdGrid');
        if ($SelectedEntityIdGrid)
        {
            return array_flip($SelectedEntityIdGrid);
        }
        return array();
    }


}