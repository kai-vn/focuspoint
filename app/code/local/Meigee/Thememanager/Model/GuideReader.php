<?php
class Meigee_Thememanager_Model_GuideReader
{
    const CONFIGURATION_FILENAME = 'theme-guide.xml';

    public function getThemeGuide($theme_name)
    {
        $file_path = Mage::getModuleDir('etc', 'Meigee_'.$theme_name) .DS . self::CONFIGURATION_FILENAME;
        $xml = false;

        if (file_exists($file_path))
        {
            $xml = simplexml_load_file($file_path, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
        return  $xml;
    }
}


