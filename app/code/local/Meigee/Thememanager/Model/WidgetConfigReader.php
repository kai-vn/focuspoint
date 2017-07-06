<?php
class Meigee_Thememanager_Model_WidgetConfigReader
{
    const CONFIGURATION_FILENAME = 'theme-widget-config.xml';
    const CONFIGURATION_TEMPLATE = '<?xml version="1.0"?><config></config>';

    private static $configuration_file_path;
    private static $widget_config = array();


    public function getConfig($theme_name, $parametres_name)
    {
        if (!isset(self::$widget_config[$theme_name]))
        {
            self::$configuration_file_path = Mage::getModuleDir('etc', 'Meigee_'.$theme_name) .DS . self::CONFIGURATION_FILENAME;
            $xml = false;
            if (file_exists(self::$configuration_file_path))
            {
                $xml = simplexml_load_file(self::$configuration_file_path, 'SimpleXMLElement', LIBXML_NOCDATA);
            }
            self::$widget_config[$theme_name] = json_decode(json_encode($xml), true);
        }
        return  isset(self::$widget_config[$theme_name][$parametres_name]) ? self::$widget_config[$theme_name][$parametres_name] : false;
    }
}