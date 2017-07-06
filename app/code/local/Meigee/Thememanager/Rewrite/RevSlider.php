<?php
require_once  Mage::getModuleDir('controllers', 'AM_RevSlider').DS.'SliderController.php';


class Meigee_Thememanager_Rewrite_RevSlider extends AM_RevSlider_SliderController
{
    public function __construct()
    {
        parent::__construct(Mage::app()->getRequest(), Mage::app()->getResponse());
    }


    function installFile($file_full_path)
    {
        if (file_exists($file_full_path))
        {
            $this->_processZipImport($file_full_path);
        }

    }
}