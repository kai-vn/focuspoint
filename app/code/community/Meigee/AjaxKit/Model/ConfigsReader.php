<?php
class Meigee_AjaxKit_Model_ConfigsReader
{
    const CONFIGURATION_FILES_DIR_NAME = 'submodules';
    const CONFIGURATION_STATUS_CNFPATH = '__module_enabled';
    private static $options_config = null;

    public function getConfigs($infoblock = false)
    {
        $path = Mage::getModuleDir('etc', 'Meigee_AjaxKit') .DS . self::CONFIGURATION_FILES_DIR_NAME .DS;
        $path_content = scandir($path);
        $xml_data_arr = false;

        foreach ($path_content AS $file_name)
        {
            $file_path = $path . $file_name;
            if(is_file($file_path) && '.xml' == substr($file_name, -4))
            {
                $xml = simplexml_load_file($file_path, 'SimpleXMLElement', LIBXML_NOCDATA);
                $version_type = (string)$xml->global['version_type'];
                $namespace = (string)$xml->global['namespace'];

                if ($infoblock)
                {
                    if($xml->$infoblock)
                    {
                        $xml_data_arr[$namespace][$version_type] = $xml->$infoblock;
                    }
                }
                else
                {
                    $xml_data_arr[$namespace][$version_type] = $xml;
                }
            }
        }
        return  $xml_data_arr;
    }


    function getConfigGroups()
    {
        return $this->getConfigs('groups');
    }
    function getConfigAdminhtml()
    {
        return $this->getConfigs('adminhtml');
    }

    function getConfigAddJs()
    {
        return $this->getConfigs('addJs');
    }

    function getConfigAddCss()
    {
        return $this->getConfigs('addCss');
    }

    function getConfigBlocks()
    {
        return $this->getConfigs('blocks');
    }

    function getConfigAdminhtmlInfo($submodule, $is_invert = false)
    {
        $config = $this->getConfigAdminhtml();
        $store = Mage::app()->getStore()->getId();

        if (isset($config[$submodule]))
        {
            $config = (array)$config[$submodule] + array('extended'=>array(), 'base'=>array());
            $config = (array)$config['extended'] + (array)$config['base'];

            $config_path = $this->getConfigPath(self::CONFIGURATION_STATUS_CNFPATH, $submodule);
            $config_value = Mage::getStoreConfig($config_path, 0);

            if (is_null($config_value))
            {
                Mage::getConfig()->saveConfig($config_path, 1, 'default', 0);
                $this->reload();
            }
            $config["enabled"] = Mage::getStoreConfig($config_path, $store);

            if ($is_invert)
            {
                $enabled = (int)!$config["enabled"];
                Mage::getConfig()->saveConfig(
                        $config_path
                        , $enabled
                        , (0==$store ? 'default' : 'stores')
                        , $store
                );
                $config["enabled"] = $enabled;
            }

            return $config;
        }
        return false;
    }

    function getConfigPath($tab_alias_name, $alias_name)
    {
        if ($alias_name)
        {
            $alias_name_arr = explode('__', $alias_name);

            if (count($alias_name_arr) > 2 && 'ajaxkit-system' == $alias_name_arr[0])
            {
                $path = '';
                switch ($alias_name_arr[1])
                {
                    case 'status':
                        $path = self::CONFIGURATION_STATUS_CNFPATH;
                        break;
                }

                 return 'meigee/ajaxkit/'.$path.'/'.$tab_alias_name;
            }
            else
            {
                return 'meigee/ajaxkit/'.$tab_alias_name.'/'.$alias_name;
            }
        }
        return 'meigee/ajaxkit/'.$tab_alias_name;
    }

    function getConfigOptions($set_default = false)
    {
        if (is_null(self::$options_config))
        {
            self::$options_config = $this->getConfigs('options');
            $is_reload = false;

            foreach (self::$options_config AS $option_tab_alias_name => $options_types)
            {
                foreach ($options_types AS $options_type_alias_name => $options)
                {
                    $options = (array)$options;
                    foreach ($options AS $option_alias_name => $option)
                    {
                        $option_arr = (array)$option;
                        $config_path = $this->getConfigPath($option_tab_alias_name, $option_alias_name);
                        $is_multiple = isset($option->type['multiple']) && $option->type['multiple'];
                        $config_value = Mage::getStoreConfig($config_path, 0);

                        if (is_null($config_value) || $set_default)
                        {
                            $is_reload = true;
                            if ($is_multiple)
                            {
                                $defaultConfigValue = !isset($option_arr['default']) ? array() : (array)$option_arr['default'];
                                $defaultConfigValue = serialize($defaultConfigValue);
                            }
                            else
                            {
                                $defaultConfigValue = (!isset($option_arr['default']) || (empty($option_arr['default']) && 0 != $option_arr['default'])) ? '' : $option_arr['default'];
                            }
                            Mage::getConfig()->saveConfig($config_path, $defaultConfigValue, 'default', 0);
                        }
                    }
                }
            }
            if($is_reload && !$set_default)
            {
                $this->reload();
            }
        }
        return self::$options_config;
    }

    function getSubmoduleConfigValues($namespace)
    {
        $options_config = $this->getConfigOptions();
        $options_config = (array)$options_config[$namespace]+array('extended'=>array(), 'base'=>array());
        $options_config = (array)$options_config['extended']+(array)$options_config['base'];

        $config_path = $this->getConfigPath($namespace, false);
        $config_data = Mage::getStoreConfig($config_path, Mage::app()->getStore());

        foreach ($config_data AS $f => $v)
        {
            if (isset($options_config[$f]) &&  $options_config[$f]->type['multiple'])
            {
                $config_data[$f] = unserialize($v);
            }
        }
        return $config_data;
    }

    function getConfigValue($tab_alias_name, $alias_name, $default = false)
    {
        $options_config = $this->getConfigOptions();

        foreach (array('extended', 'base') AS $type)
        {
            if (isset($options_config[$tab_alias_name]) && isset($options_config[$tab_alias_name][$type]) && !empty($options_config[$tab_alias_name][$type]->$alias_name))
            {
                $config_path = $this->getConfigPath($tab_alias_name, $alias_name);

                if ($default)
                {
                    $configValue = Mage::getStoreConfig($config_path, 0);
                }
                else
                {
                    $configValue = Mage::getStoreConfig($config_path, Mage::app()->getStore());
                }

                if (!empty($options_config[$tab_alias_name][$type]->$alias_name->type['multiple']))
                {
                    $configValue = unserialize($configValue);
                }
                return $configValue;
            }
        }
        return false;
    }

    function saveConfig($namespace, $data_arr)
    {
        $data_arr += array('use_default'=>array(), 'value'=>array());
        $store = (int)Mage::app()->getStore()->getId();
        $options_config = $this->getConfigOptions();

        if (0 < $store)
        {
            foreach ($data_arr['use_default'] AS $f=>$v)
            {
                if ($v > 0)
                {
                    $f = str_replace('default::', '', $f);
                    $config_path = $this->getConfigPath($namespace, $f);
                    Mage::getConfig()->deleteConfig($config_path, 'stores', $store);
                    if (isset($data_arr['value'][$f]))
                    {
                        unset($data_arr['value'][$f]);
                    }
                }
            }
        }

        foreach ($data_arr['value'] AS $f=>$v)
        {
            $config_path = $this->getConfigPath($namespace, $f);
            foreach (array('extended', 'base') AS $type)
            {
                if (!empty($options_config[$namespace][$type]->$f->type['multiple']))
                {
                    $v = serialize($v);
                    break;
                }
            }
            Mage::getConfig()->saveConfig($config_path, $v,  (0==$store ? 'default' : 'stores'), $store);
        }
        return true;
    }

    function reload()
    {
        Mage::app()->getResponse()->setRedirect(Mage::getUrl("*/*/*", (array)Mage::app()->getRequest()->getParams() + array('_forced_secure' => Mage::app()->getStore()->isCurrentlySecure())));
    }


    function getConfigStatus($namespace, $default = false)
    {
        $config_arr = $this->getConfigStatuses($default);
        return isset($config_arr[$namespace]) ? $config_arr[$namespace] : false;
    }

    function getConfigStatuses($default = false)
    {
        $config_path = $this->getConfigPath(self::CONFIGURATION_STATUS_CNFPATH, false);
        $store = $default ? 0 : Mage::app()->getStore();
        return Mage::getStoreConfig($config_path, $store);
    }


    function getConfig($submodule, $alias)
    {
        $config_path = $this->getConfigPath($submodule, $alias);
        return Mage::getStoreConfig($config_path, Mage::app()->getStore());
    }
}
