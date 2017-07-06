<?php
//class Meigee_Thememanager_Model_ConfigReader extends Mage_Core_Model_Config_Base
class Meigee_Thememanager_Model_ConfigReader
{
    const CONFIGURATION_FILENAME = 'theme-config.xml';
    const CONFIGURATION_TEMPLATE = '<?xml version="1.0"?><config></config>';

    private static $configuration_file_path;


//    public function __construct()
//    {
//
//    }

    public function getThemeConfig($theme_name)
    {
        self::$configuration_file_path = Mage::getModuleDir('etc', 'Meigee_'.$theme_name) .DS . self::CONFIGURATION_FILENAME;

        $xml = false;

        if (file_exists(self::$configuration_file_path))
        {
            //parent::__construct(self::$configuration_file_path);
            $xml = simplexml_load_file(self::$configuration_file_path, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return  $xml;
        //return  $this->getNode();
    }
}