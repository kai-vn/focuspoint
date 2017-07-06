<?php

class Meigee_Thememanager_Model_InstallSkin extends Mage_Core_Model_Abstract
{
    const SkinConfigPath = 'meigee/thememanager/installed/skin';
    const NoCheckModuleConfigPath = 'meigee/thememanager/no_check_module';
    const ConfigBackupPostfix = '_backup';

    private $_static_conflicts = false;

    public function getStaticConflicts()
    {
        if ($this->_static_conflicts)
        {
            return $this->_static_conflicts;
        }

        $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();
        $skin_install_data = $predefined_arr[$this->getSkin()]['install'];
        if (isset($skin_install_data['blocks']))
        {
            foreach ($skin_install_data['blocks'] AS $identifier => $block_data)
            {
                foreach ($this->getStores() AS $store_id)
                {
                    $block  = Mage::getModel('cms/block')->setStoreId($store_id)->load($identifier);
                    if ($block->getId())
                    {
                        $store_ids = Mage::getResourceModel('cms/block')->lookupStoreIds($block->getId());
                        if (in_array($store_id ,$store_ids))
                        {
                            $this->_static_conflicts[$store_id]['block-store'][$identifier] = $block->getTitle();
                        }
                        elseif (in_array(0 ,$store_ids))
                        {
                            $this->_static_conflicts[$store_id]['block-all'][$identifier] = $block->getTitle();
                        }
                    }
                }
            }
        }
        if (isset($skin_install_data['pages']))
        {
            foreach ($skin_install_data['pages'] AS $identifier => $page_data)
            {
                foreach ($this->getStores() AS $store_id)
                {
                    $page  = Mage::getModel('cms/page')->setStoreId($store_id)->load($identifier);
                    if ($page->getId())
                    {
                        $store_ids = Mage::getResourceModel('cms/page')->lookupStoreIds($page->getId());
                        if (in_array($store_id ,$store_ids))
                        {
                            $this->_static_conflicts[$store_id]['page'][$identifier] = $page->getTitle();
                        }
                    }
                }
            }
        }
        return $this->_static_conflicts;
    }


    public function installStatics()
    {
        $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();
        $skin_install_data = $predefined_arr[$this->getSkin()]['install'];

        $param_arr = $this->getParamArr();

        if (isset($skin_install_data['blocks']))
        {
            $static_blocks_dir = Mage::getModuleDir('sql', 'Meigee_'.ucfirst($this->getTheme())) .DS. 'static' . DS . 'blocks'.DS;
            foreach ($skin_install_data['blocks'] AS $identifier => $block_data)
            {
                $file = $static_blocks_dir . $block_data['content_file'];
                $skin_install_data['blocks'][$identifier]['content'] = (file_exists($file) && is_file($file)) ? file_get_contents($file) : '';
            }

            foreach ($skin_install_data['blocks'] AS $identifier => $block_data)
            {
                foreach ($this->getStores() AS $store_id)
                {
                    $block  = Mage::getModel('cms/block')->setStoreId($store_id)->load($identifier);
                    if ($block->getId())
                    {
                        $conflict_key = $store_id . "::block::" .$identifier;
                        if (isset($param_arr[$conflict_key]))
                        {
                            switch ($param_arr[$conflict_key])
                            {
                                case 'Overwrite':
                                    $store_ids = Mage::getResourceModel('cms/block')->lookupStoreIds($block->getId());
                                    if(count($store_ids) == 1)
                                    {
                                        $block->setTitle($block_data['name'])->setContent($block_data['content'])->save();
                                        break;
                                    }
                                    else
                                    {
                                        $key = array_search($block->getId(), $store_ids);
                                        unset($store_ids[$key]);
                                        $block->setStores()->save($store_ids);
                                    }
                                case 'Create':
                                    $staticBlock = array(
                                        'title' => $block_data['name'],
                                        'identifier' => $identifier,
                                        'content' => $block_data['content'],
                                        'is_active' => 1,
                                        'stores' => array($store_id)
                                    );
                                    Mage::getModel('cms/block')->setData($staticBlock)->save();

                                    break;
                                case 'LeaveAsIs':
                                    continue;
                                    break;
                            }
                        }
                    }
                    else
                    {
                        $staticBlock = array(
                            'title' => $block_data['name'],
                            'identifier' => $identifier,
                            'content' => $block_data['content'],
                            'is_active' => 1,
                            'stores' => array($store_id)
                        );
                        Mage::getModel('cms/block')->setData($staticBlock)->save();
                    }
                }
            }
        }
        if (isset($skin_install_data['pages']))
        {
            $static_pages_dir = Mage::getModuleDir('sql', 'Meigee_'.ucfirst($this->getTheme())) .DS. 'static' . DS . 'pages'.DS;
            foreach ($skin_install_data['pages'] AS $identifier => $page_data)
            {
                $file = $static_pages_dir . $page_data['content_file'];
                $skin_install_data['pages'][$identifier]['content'] = '';
                if (file_exists($file) && is_file($file))
                {
                    $skin_install_data['pages'][$identifier]['content'] = file_get_contents($file);
                }

                $file = $static_pages_dir . (isset($page_data['content_layout_xml_file']) ? $page_data['content_layout_xml_file'] : '');
                $skin_install_data['pages'][$identifier]['content_layout_xml'] = '';
                if (file_exists($file) && is_file($file))
                {
                    $skin_install_data['pages'][$identifier]['content_layout_xml'] = file_get_contents($file);
                }
            }

            foreach ($skin_install_data['pages'] AS $identifier => $page_data)
            {
                $page_data['layout'] = isset($page_data['layout']) ? $page_data['layout'] : '';
                foreach ($this->getStores() AS $store_id)
                {
                    $page  = Mage::getModel('cms/page')->setStoreId($store_id)->load($identifier);
                    $store_ids = array();
                    if ($page->getId()   )
                    {
                        $store_ids = Mage::getResourceModel('cms/page')->lookupStoreIds($page->getId());
                    }

                    if ($page->getId() && in_array($store_id, $store_ids))
                    {
                        $conflict_key = $store_id . "::page::" .$identifier;
                        if (isset($param_arr[$conflict_key]))
                        {
                            switch ($param_arr[$conflict_key])
                            {
                                case 'Overwrite':
                                    $store_ids = Mage::getResourceModel('cms/page')->lookupStoreIds($page->getId());
                                    if(count($store_ids) == 1)
                                    {
                                        $page->delete();
                                    }
                                    else
                                    {
                                        $key = array_search($store_id, $store_ids);
                                        unset($store_ids[$key]);
                                        $page->setStores($store_ids)->save();
                                    }

                                    Mage::getModel('cms/page')
                                        ->setTitle($page_data['name'])
                                        ->setContent($page_data['content'])
                                        ->setIdentifier($identifier)
                                        ->setLayoutUpdateXml($page_data['content_layout_xml'])
                                        ->setRootTemplate($page_data['layout'])
                                        ->setIsActive(1)
                                        ->setStores(array($store_id))
                                        ->save();

                                    break;
                                case 'LeaveAsIs':
                                    continue;
                                    break;
                                    break;
                            }
                        }
                    }
                    else
                    {
                        $staticPage = array(
                                    'title' => $page_data['name'],
                                    'identifier' => $identifier,
                                    'content' => $page_data['content'],
                                    'root_template' => $page_data['layout'],
                                    'layout_update_xml' => $page_data['content_layout_xml'],
                                    'is_active' => 1,
                                    'stores' => array($store_id)
                                );
                        Mage::getModel('cms/page')->setData($staticPage)->save();
                    }
                    $page->clearInstance();
                }
            }
        }
    }


    public function setVariablePermissions($variableName, $is_allowed)
    {
        $adminVersion = Mage::getConfig()->getModuleConfig('Mage_Admin')->version;
        if(version_compare($adminVersion, '1.6.1.2', '>=') && class_exists('Mage_Admin_Model_Variable'))
        {
            $whitelistVar = Mage::getModel('admin/variable')->load($variableName, 'variable_name');
            $whitelistVar->setData('variable_name', $variableName);
            $whitelistVar->setData('is_allowed', $is_allowed);
            $whitelistVar->save();
        }
    }

    public function installBaseConfigs()
    {
        $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();
        $skin_install_data = $predefined_arr[$this->getSkin()]['install'];
        $skin_install_data['config']['_design_package_name'] = array('key'=>'design/package/name', 'value'=>$this->getTheme());
        $skin_install_data['config']['_meigee_design_package_name'] = array('key'=>'design/package/meigee_package_name', 'value'=>$this->getTheme());
        $skin_install_data['config']['_design_package_skin'] = array('key'=>self::SkinConfigPath, 'value'=>$this->getSkin());
        if (isset($skin_install_data['config']))
        {
            foreach ($this->getStores() AS $store_id)
            {
                foreach ($skin_install_data['config'] AS $data)
                {
                    $real = Mage::getStoreConfig($data['key'], $store_id);
                    $backup = Mage::getStoreConfig($data['key'].self::ConfigBackupPostfix, $store_id);
                    if (!$backup && $real)
                    {
                        Mage::getConfig()->saveConfig($data['key'].self::ConfigBackupPostfix, $real, 'stores', $store_id);
                    }
                    Mage::getConfig()->saveConfig($data['key'], $data['value'], 'stores', $store_id);

                    if (isset($data['is_allowed']))
                    {
                        $this->setVariablePermissions($data['key'], $data['is_allowed']);
                    }
                }
            }
        }
    }

    public function uninstallBaseConfigs()
    {
        $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();
        foreach ($this->getStores() AS $store_id)
        {
            $skin = Mage::getStoreConfig(self::SkinConfigPath, $store_id);
            $predefined_arr[$skin]['install']['config']['_design_package_name'] = array('key'=>'design/package/name', 'value'=>$this->getTheme());
            $predefined_arr[$skin]['install']['config']['_design_package_name'] = array('key'=>'design/package/meigee_package_name', 'value'=>'');

            foreach ($predefined_arr[$skin]['install']['config'] AS $data)
            {
                $backup = Mage::getStoreConfig($data['key'].self::ConfigBackupPostfix, $store_id);
                if ($backup)
                {
                    Mage::getConfig()->saveConfig($data['key'], $backup, 'stores', $store_id);
                    Mage::getConfig()->saveConfig($data['key'].self::ConfigBackupPostfix, null, 'stores', $store_id);
                }
            }
            Mage::getConfig()->saveConfig(self::SkinConfigPath, null, 'stores', $store_id);
        }
    }

    public function installSkinConfigs($is_install = true, $is_remove = true)
    {
        $installed_store_actions = $this->getInstalledStoreActions();

        foreach ($this->getStores() AS $store_id)
        {
            $themesModel = Mage::getModel('thememanager/themes');
            $themesCollection = $themesModel->getThemesCollection();
            $action = isset($installed_store_actions[$store_id]) ? $installed_store_actions[$store_id] : false;

            foreach ($themesCollection AS $elTheme)
            {
                if ($is_remove && $elTheme->getStoreId() == $store_id)
                {
                    switch($action)
                    {
                        case 'replace_default_only':
                            if (Meigee_Thememanager_Model_PageTypeConfigs_Basis::StoreType == $elTheme->getType())
                            {
                                $elTheme->delete();
                            }
                            break;
                        case 'delete_all':
                        default:
                            $elTheme->delete();
                    }
                }
            }

            if ($is_install)
            {
                $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();
                $skin_name = $predefined_arr[$this->getSkin()]['name'];
                $theme_id = $themesModel->setTheme(array(
                                                            'themenamespace'=>$this->getTheme()
                                                            , 'type'=>Meigee_Thememanager_Model_PageTypeConfigs_Basis::StoreType
                                                            , 'store_id'=>$store_id
                                                            , 'name'=>$skin_name
                                                        ));

                if (isset($predefined_arr[$this->getSkin()]))
                {
                    $config = $predefined_arr[$this->getSkin()]['values'];
                    foreach ($config AS $alias => $value)
                    {
                        Mage::getModel('thememanager/themeConfigData')->addThemeConfig($alias, $value, $theme_id);
                    }
                    if (isset($predefined_arr[$this->getSkin()]) and isset($predefined_arr[$this->getSkin()]['install']['store_extension_configs']))
                    {
                        foreach($predefined_arr[$this->getSkin()]['install']['store_extension_configs'] AS $extensionData)
                        {
                            $this->installStoreExtensionConfigs($store_id, $extensionData);
                        }
                    }
                }
            }
        }
    }

    function installStoreExtensionConfigs($store_id, $extensionData)
    {
        $helper = Mage::helper('thememanager/themeConfig');
        $FllPageTypes = Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->getAllTypes();
        if (!isset($FllPageTypes[$extensionData['type']]))
        {
            return;
        }
        $themesModel = Mage::getModel('thememanager/themes');
        $theme_id = $themesModel->setTheme(array(
                                                'themenamespace'=>$this->getTheme()
                                            , 'type'=>$extensionData['type']
                                            , 'store_id'=>$store_id
                                            , 'name'=> $extensionData['name']
                                        ));

        if (isset($extensionData['values']))
        {
            foreach ((array)$extensionData['values'] AS $alias => $value)
            {
                Mage::getModel('thememanager/themeConfigData')->addThemeConfig($alias, $value, $theme_id, $this->getTheme());
            }
        }

        switch ($extensionData['type'])
        {
            case 'all_product':
            case 'product':
            case 'all_category':
            case 'category':
            case 'all_cms_page':
                break;
            case 'cms_page':
                foreach ((array)$extensionData['page'] AS $identifier)
                {
                    $page  = Mage::getModel('cms/page')->setStoreId($store_id)->load($identifier);
                    $helper->setCmsPageConfigByEntity($page, $theme_id);
                }
                break;
        }
    }

    function cancelModuleChecking()
    {
        Mage::getConfig()->saveConfig(self::NoCheckModuleConfigPath, 1, 'default', 0);
    }

    function checkModulesForTheme()
    {
        $cnf = Mage::getModel('core/config_data')->getCollection()->addFieldToFilter('path',self::NoCheckModuleConfigPath)->addFieldToFilter('scope_id',0)->getFirstItem();;
        if ((bool)$cnf->getValue())
        {
            return;
        }

        $theme = Mage::app()->getRequest()->getParam('theme');
        $helper = Mage::helper('thememanager/themeConfig');

        if ($theme)
        {
            $settings_arr = $helper->getThemeSettings();
            $predefined_arr = $helper->getPredefined();
            $installed_thems = $helper->getUsedThems();
            $modules = isset($settings_arr['_modules']) ? $settings_arr['_modules'] : array();
            foreach ($installed_thems AS $store_id => $theme_name)
            {
                if ($theme == $theme_name)
                {
                    $skin = Mage::getStoreConfig(self::SkinConfigPath, $store_id);
                    $modules += isset($predefined_arr[$skin]) && isset($predefined_arr[$skin]['_modules']) ? $predefined_arr[$skin]['_modules'] : array();
                }
            }

            $no_installed_modules = array();
            foreach (array_keys($modules) AS $module)
            {
                if (!Mage::helper('core')->isModuleEnabled($module))
                {
                    $no_installed_modules[] = $module;
                }
            }
            $msg = '<div class="installation-notes">';
            if (!empty($no_installed_modules))
            {
                $msg .= $helper->__('- We recommend you get installed following extensions: %s <br />', implode(', ', $no_installed_modules));
            }
            $msg .= $helper->__('- Revolution slider must be configured separately');
            if (isset($settings_arr['installation_instructions_url']))
            {
                $msg .= '<br/>'.$helper->__('More info about it you can find in our')
                    .' <a target="_blank" href="'.$settings_arr['installation_instructions_url'].'">' . $helper->__('User Guide') . '</a>';
            }
            $msg .= '</div>';
            Mage::getSingleton('core/session')->addNotice($msg);
        }
    }









}
