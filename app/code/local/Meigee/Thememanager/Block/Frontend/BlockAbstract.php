<?php
//abstract class Meigee_Thememanager_Block_Frontend_BlockAbstract extends Mage_Core_Block_Template
class Meigee_Thememanager_Block_Frontend_BlockAbstract extends Mage_Core_Block_Template
{
    protected $helper = null;
    function __construct()
    {
        $this->helper = Mage::helper('thememanager/themeConfig');
    }
}

