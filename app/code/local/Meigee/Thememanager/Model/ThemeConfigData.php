<?php

class Meigee_Thememanager_Model_ThemeConfigData extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('thememanager/themeConfigData');
    }


    public function cloneConfig($from, $to)
    {
        $collection = $this->getCollection()->addFieldToFilter('theme_id', array( 'eq' => $from));
        foreach ($collection AS $collection_row)
        {
            $collection_row->setThemeId($to);
            $collection_row->setId(null);
            $collection_row->save();
        }
    }


    public function deleteDataByThemeId($config_id, $aliases=array())
    {
        $collection = $this->getCollection()->addFieldToFilter('theme_id', array( 'eq' => $config_id));

        if (!empty($aliases))
        {
            $collection->addFieldToFilter('alias', array( 'in' => $aliases));
        }

        foreach ($collection AS $collection_row)
        {
            $collection_row->delete();
        }
    }


    function saveThemeConfig($alias, $value, $theme_id)
    {
        if ( in_array($alias, array('advanced_styling_custom_css_file', 'advanced_styling_base_css_file')))
        {
            $this->parseCustomCssFile($value, $theme_id);
        }

        $config = Mage::helper('thememanager/themeConfig')->getThemeConfigByAliase($alias);

        if (empty($value) && !is_null($value) && 0 != $value)
        {
            $value = '';
        }

        if (isset($config['multi']) && $config['multi'])
        {
            if (empty($value))
            {
                $value = array();
            }

            $value = serialize((array)$value);
        }

        if (false === $config['result'])
        {
            $config['result'] = '__empty__';
        }

        if ($config['result'] != $value)
        {
            $saved = $this->getCollection()
                ->addFieldToFilter('theme_id', $theme_id)
                ->addFieldToFilter('alias', $alias)
                ->getFirstItem();

            if ($saved->getId())
            {
                if (is_null($value))
                {
                    $saved->delete();
                }
                else
                {
                    $save_array = array(
                        'theme_config_data_id' => $saved->getId()
                    , 'value' => $value
                    );
                    $this->setData($save_array)->save();
                }
            }
            else
            {
                $save_array = array(
                    'theme_id' => $theme_id
                , 'alias' => $alias
                , 'value' => $value
                , 'is_system' => '__' == substr($alias, 0, 2) ? 'Y' : 'N'
                );

                $this->setData($save_array)->save();
            }
            Mage::getModel('thememanager/themes')->setModifiedDate($theme_id);
        }
    }

    function addThemeConfig($alias, $value, $theme_id, $theme = false)
    {
        $save_array = array(
            'theme_id' => $theme_id
            , 'alias' => $alias
            , 'value' => $value
            , 'is_system' => '__' == substr($alias, 0, 2) ? 'Y' : 'N'
        );

        $this->setData($save_array)->save();
        Mage::getModel('thememanager/themes')->setModifiedDate($theme_id);
        if ('advanced_styling_base_css_file' == $alias)
        {
            $file_content = Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingSkinCssFileContent($value, $theme);

            $fonts_str = $this->parseCustomCssFileContent($file_content);
            if ($fonts_str)
            {
                $this-> addThemeConfig('advanced_styling_custom_css_google_fonts', $fonts_str, $theme_id);
            }
        }
    }


    function parseCustomCssFile($file_name, $theme_id)
    {
        $css_file_content = Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingCssFileContent($file_name);
        $fonts_str = $this->parseCustomCssFileContent($css_file_content);
        if ($fonts_str)
        {
            $this-> saveThemeConfig('advanced_styling_custom_css_google_fonts', $fonts_str, $theme_id);
        }
    }


    function parseCustomCssFileContent($css_file_content)
    {
        $regexp='/\* UsedGoogleFontFamily:(.*)\*/';
        preg_match_all($regexp,$css_file_content,$fonts_arr);

        $fonts_str = '';
        if (isset($fonts_arr[1]) && !empty($fonts_arr[1]))
        {
            $fonts_arr = array_unique(array_map('trim', $fonts_arr[1]));
            $fonts_str = implode(';', $fonts_arr);
            $fonts_str = str_replace(' ', '+', $fonts_str);
        }
        return $fonts_str;
    }



}