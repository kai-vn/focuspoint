<?PHP

class Meigee_Thememanager_Helper_ThemeConfig extends Meigee_Thememanager_Helper_Data
{
    static $theme_id = false;
    static $theme_configs = array();
    static $configByAliases = false;
    static $page_theme_config_instance = null;

    function getPageThemeConfigInstance()
    {
        return Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance();
    }

    function getThemeConfigArray()
    {
        self::$theme_name = ucfirst($this->getThemeNamespace());

        $model = Mage::getModel('thememanager/configReader');
        $theme_config = $model->getThemeConfig(self::$theme_name);
        return json_decode(json_encode($theme_config), true);
    }

    function getThemeGuideArray($theme_name = false)
    {
        $model = Mage::getModel('thememanager/guideReader');
        $theme_name = $theme_name ? $theme_name : $this->getThemeNamespace();
        $theme_config = $model->getThemeGuide($theme_name);
        return json_decode(json_encode($theme_config), true);
    }

    public function getThemeConfig($alias, $full = false)
    {
        if (empty(self::$theme_configs))
        {
            self::$theme_configs = $this->getPageThemeConfigInstance()->getThemesInfo();
        }
        if ($full)
        {
            return isset(self::$theme_configs['configs'][$alias]) ? self::$theme_configs['configs'][$alias] : false;
        }
        return isset(self::$theme_configs['configs'][$alias]) ? self::$theme_configs['configs'][$alias]['value'] : false;
    }


    public function getThemeConfigTree()
    {
        $theme_config = $this->getThemeConfigArray();

        $configs_tree = array();
        $configs_by_blocks = array();
        foreach ($theme_config['configs'] AS $config_name =>$config)
        {
            $config = (array)$config;
            if (isset($config['block']))
            {
                $configs_by_blocks[$config['block']][$config_name] = $config;
            }
        }

        foreach ($theme_config['groups'] AS $group_name => $group)
        {
            $group = (array)$group;
            $configs_tree[$group_name] = $group;
        }

        foreach ($theme_config['blocks'] AS $block_name => $block)
        {
            $block = (array)$block;
            if (isset($configs_tree[$block['group']]))
            {
                foreach ($block['params'] AS $param_name => $param)
                {
                    if (isset($configs_by_blocks[$param_name]))
                    {
                        $param = $configs_by_blocks[$param_name];
                    }
                    $block['params'][$param_name]['elements'] = $param;
                }
                $configs_tree[$block['group']]['blocks'][$block_name]= $block;
            }
        }
        return $configs_tree;
    }

    public function getThemeConfigByAliases()
    {
        $theme_config = $this->getThemeConfigArray();
        return $theme_config['configs'];
    }

    public function getPredefined()
    {
        $theme_config = $this->getThemeConfigArray();
        return isset( $theme_config['predefined']) ?  $theme_config['predefined'] : array();
    }

    public function getThemeSettings()
    {
        $theme_config = $this->getThemeConfigArray();
        return isset( $theme_config['theme_settings']) ?  $theme_config['theme_settings'] : array();
    }



    public function getAdvancedStyling($full = false)
    {
        $model = Mage::getModel('thememanager/advancedStylingReader');
        $theme_name = $theme_name = ucfirst($this->getThemeNamespace());
        $theme_config = $model->getThemeAdvancedStyling($theme_name);
        $theme_config_arr = $theme_config;

        if ($full)
        {
            return $theme_config_arr;
        }
        return $theme_config_arr['advanced_styling'];
    }
    public function getStaticAdvancedStyling()
    {
        $as = $this->getAdvancedStyling(true);
        return $as['static_advanced_styling'];
    }


    function getThemeConfigByAliase($alias)
    {
        if (!self::$configByAliases)
        {
            self::$configByAliases = $this->getThemeConfigByAliases();
        }

        if (isset(self::$configByAliases[$alias]))
        {
            $result = $this->getThemeConfig($alias);
            if (isset(self::$configByAliases[$alias]['use_key']) && self::$configByAliases[$alias]['use_key'])
            {
                $result = isset(self::$configByAliases[$alias]['values'][$result]) ? self::$configByAliases[$alias]['values'][$result] : false;
            }

            if (isset(self::$configByAliases[$alias]['multi']) && self::$configByAliases[$alias]['multi'])
            {
                if (empty($result))
                {
                    $result = array();
                }
                else
                {
                    $result_unserialized = @unserialize($result);
                    $result = (array)(false !== $result_unserialized ? $result_unserialized : $result);
                }

                if (empty($result) && isset(self::$configByAliases[$alias]['default']))
                {
                    $result = (array)self::$configByAliases[$alias]['default'];
                }
            }
            self::$configByAliases[$alias]['result'] = '__empty__' == $result ? false : $result;
        }
        else
        {
            self::$configByAliases[$alias] = false;
        }
        return self::$configByAliases[$alias];
    }

    function getThemeConfigResultByAliase($alias)
    {
        $theme_config = $this->getThemeConfigByAliase($alias);
        if (is_array($theme_config['result']) && (!isset($theme_config['multi']) || !$theme_config['multi']))
        {
            return $theme_config['result']['value'];
        }
        return $theme_config['result'];
    }


}

