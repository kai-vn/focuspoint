<?php
class Meigee_Thememanager_Model_AdvancedStylingReader
{
    const CONFIGURATION_FILENAME = 'theme-styling.xml';
    private $xml = null;

    public function getThemeAdvancedStyling($theme_name)
    {
        if (is_null($this->xml))
        {
            $file_path = Mage::getModuleDir('etc', 'Meigee_'.$theme_name) .DS . self::CONFIGURATION_FILENAME;
            $this->xml = false;

            if (file_exists($file_path))
            {
                $this->xml = simplexml_load_file($file_path, 'SimpleXMLElement', LIBXML_NOCDATA);
            }
        }
        return $this->xml;
    }
}


