<?php
class Meigee_Thememanager_Adminhtml_ThememanagerController extends Mage_Adminhtml_Controller_Action
{
    function _construct()
    {
        parent::_construct();
        Mage::getSingleton('core/session', array('name'=>'adminhtml'));

        if(Mage::app()->getRequest()->getParam('isAjax') && !Mage::getSingleton('admin/session')->isLoggedIn())
        {
            echo json_encode(array('ajaxExpired'=>1, 'ajaxRedirect'=>$this->getUrl("thememanager/adminhtml_thememanager/index")));
            die();
        }
    }

    private function getConfigArray()
    {
        return Mage::helper('thememanager/themeConfig')->getThemeConfigTree();
    }
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager');
    }


    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('meigee');

        $contentBlock = $this->getLayout()->createBlock('thememanager/adminhtml_thems_installedThems');
        $this->_addContent($contentBlock);

        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideBlockName('SelectInstalledThems');
        $this->_addContent($guideBlock);

        $this->addJsCss('thememanager-index-adm');
        $this->renderLayout();
    }

    public function activateThemeAction()
    {
        $theme = Mage::app()->getRequest()->getParam('theme');
        if ($theme)
        {
            $this->loadLayout();
            $this->addJsCss();
            $html = $this->getLayout()->getBlock('head')->toHtml();

            $activateTheme = $this->getLayout()->createBlock('thememanager/adminhtml_thems_activateTheme');
            $html .= $activateTheme->toHtml();

            if ($activateTheme->getRedirectUrl())
            {
                $this->getResponse()->setRedirect($activateTheme->getRedirectUrl());
            }
            else
            {
                $this->getResponse()->setBody($html);
            }
        }
    }

    public function installSkinAction()
    {
        $this->loadLayout();
        $this->addJsCss();

        $conflicts_fixed = Mage::app()->getRequest()->getParam('conflicts_fixed');
        $theme = Mage::app()->getRequest()->getParam('theme');
        $stores = $conflicts_fixed ? explode('|', Mage::app()->getRequest()->getParam('stores'))  : (array)Mage::app()->getRequest()->getParam('stores');
        $skin = Mage::app()->getRequest()->getParam('skin');
        $param_arr = Mage::app()->getRequest()->getParams();

        if ($theme && $stores && $skin)
        {
            $predefined_arr = Mage::helper('thememanager/themeConfig')->getPredefined();
            $static_conflicts = array();
            if (isset($predefined_arr[$skin]) && isset($predefined_arr[$skin]['install']))
            {
                $installed_store_actions = array();
                $installed_store_actions_post = array();
                foreach ($stores AS $store)
                {
                    $installed_store_action = Mage::app()->getRequest()->getParam('installed_store_action_'.$store);
                    if (Mage::app()->getRequest()->getParam('delete_all_settings'))
                    {
                        $installed_store_action = 'delete_all';
                    }
                    $installed_store_actions[$store] = $installed_store_action;
                    $installed_store_actions_post['installed_store_action_'.$store] = $installed_store_action;
                }

                $installSkinModel = Mage::getModel('thememanager/installSkin')
                        ->setSkin($skin)
                        ->setTheme($theme)
                        ->setInstalledStoreActions($installed_store_actions)
                        ->setParamArr($param_arr)
                        ->setStores($stores);
                $static_conflicts = $installSkinModel->getStaticConflicts();

                if (!$conflicts_fixed && !empty($static_conflicts))
                {
                    $html = $this->getLayout()->getBlock('head')->toHtml();
                    $html .= $this->getLayout()->createBlock('thememanager/adminhtml_thems_activateTheme_staticConflicts')
                                ->setStaticConflicts($static_conflicts)
                                ->setInstalledStoreActions($installed_store_actions_post)
                                ->toHtml();
                    $this->getResponse()->setBody($html);
                }
                else
                {
                    $installSkinModel->installStatics();
                    $installSkinModel->installBaseConfigs();
                    $installSkinModel->installSkinConfigs();
                    $html = $this->getLayout()->getBlock('head')->toHtml();
                    $html .= $this->getLayout()->createBlock('thememanager/adminhtml_thems_activateTheme_clear')->toHtml();
                    $this->getResponse()->setBody($html);
                }
            }
        }

    }

    public function deactivateThemeAction()
    {
        $theme = Mage::app()->getRequest()->getParam('theme');
        if ($theme)
        {
            $this->loadLayout();
            $this->addJsCss();
            $html = $this->getLayout()->getBlock('head')->toHtml();
            $activateTheme = $this->getLayout()->createBlock('thememanager/adminhtml_thems_deactivateTheme');
            $html .= $activateTheme->toHtml();
            if ($activateTheme->getRedirectUrl())
            {
                $this->getResponse()->setRedirect($activateTheme->getRedirectUrl());
            }
            else
            {
                $this->getResponse()->setBody($html);
            }
        }
    }

    public function deactivateSkinAction()
    {
        if (Mage::app()->getRequest()->getParam('theme'))
        {
            $this->loadLayout();
            $this->addJsCss();

            $installSkinModel = Mage::getModel('thememanager/installSkin')
                ->setSkin(Mage::app()->getRequest()->getParam('theme'))
                ->setStores(Mage::app()->getRequest()->getParam('stores'));

            $installSkinModel->uninstallBaseConfigs();
            $installSkinModel->installSkinConfigs(false, true);

            $html = $this->getLayout()->getBlock('head')->toHtml();
            $html .= $this->getLayout()->createBlock('thememanager/adminhtml_thems_activateTheme_clear')->setIsDeactivate(true)->toHtml();
            $this->getResponse()->setBody($html);
        }
    }

    public function flusheCacheAndLogOutAction()
    {
        Mage::app()->cleanCache();
        $adminSession = Mage::getSingleton('admin/session');
        $adminSession->unsetAll();
        $adminSession->getCookie()->delete($adminSession->getSessionName());
        $adminSession->addSuccess(Mage::helper('adminhtml')->__('You have logged out.'));

        $this->getResponse()->setRedirect($this->getUrl("*/*/themeConfig", array('theme'=>$this->getRequest()->getParam('theme'))));
    }

    public function removeModuleCheckingAction()
    {
        Mage::getModel('thememanager/installSkin')->cancelModuleChecking();
        $this->getResponse()->setRedirect($this->getUrl("*/*/themeConfig", array('theme'=>$this->getRequest()->getParam('theme'))));
    }

    public function themeConfigAction()
    {
        if (!Mage::app()->getRequest()->getParam('theme'))
        {
            $this->getResponse()->setRedirect($this->getUrl("*/*/index"));
        }
        Mage::getModel('thememanager/installSkin')->checkModulesForTheme();
        $this->loadLayout();
        $this->addJsCss('thememanager-themeconfig-adm');

        $this->_setActiveMenu('meigee');
        $contentBlockLeft = $this->getLayout()->createBlock('thememanager/adminhtml_configList');
        $this->_addContent($contentBlockLeft);

        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideBlockName('ConfigList');
        $this->_addContent($guideBlock);

        $this->renderLayout();
    }

    function getProductTableAction()
    {
        $contentBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_product');
        $contentBlock->setUseAjax(true);

        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideBlockName('Products');
        $guideBlock->setUseAjax(true);

        $this->getResponse()->setBody($contentBlock->toHtml() . $guideBlock->toHtml());
    }
    function getCategoryTableAction()
    {
        $contentBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_category');
        $contentBlock->setUseAjax(true);

        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideBlockName('Categories');
        $guideBlock->setUseAjax(true);

        $this->getResponse()->setBody($contentBlock->toHtml() . $guideBlock->toHtml());
    }
    public function categoriesJsonAction()
    {
        $categorytabId = Mage::app()->getRequest()->getParam('category');
        $categorytab = Mage::getModel('catalog/category')->load($categorytabId);
        Mage::register('categorytab_data', $categorytab);
        $this->getResponse()->setBody($this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_category')->getCategoryChildrenJson($this->getRequest()->getParam('category')));
    }

    function getCmsPageTableAction()
    {
        $contentBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_cmsPageTable');
        $contentBlock->setUseAjax(true);

        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideBlockName('CmsPages');
        $guideBlock->setUseAjax(true);


        $this->getResponse()->setBody($contentBlock->toHtml() . $guideBlock->toHtml());
    }

    public function getNewConfigAction()
    {
        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/add_or_clone'))
        {
            $this->loadLayout();
            $this->addJsCss();
            $html = $this->getLayout()->getBlock('head')->toHtml();
            $theme_config_id = 0;
            if ($this->getRequest()->getParam('saveConfig'))
            {
                $clone_config_id = $this->getRequest()->getParam('cloneConfigId');
                $theme_config_id = Mage::getModel('thememanager/themes')->setTheme($this->getRequest()->getParams());
                Mage::getModel('thememanager/themeConfigData')->cloneConfig($clone_config_id, $theme_config_id);
            }
            Mage::register('theme_config_id', $theme_config_id);
            $html .= $this->getLayout()->createBlock('thememanager/adminhtml_thems_addConfigForm')->toHtml();
            $this->getResponse()->setBody($html);
        }
    }

    function editConfigAction()
    {
        Mage::getModel('thememanager/installSkin')->checkModulesForTheme();
        $this->loadLayout();
        $this->_setActiveMenu('meigee');
        $config_arr = $this->getConfigArray();
        $contentBlockLeft = $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs');
        $type_tabs = $contentBlockLeft->getPageTypeTabs();

        if ($type_tabs)
        {
            $this->_addLeft($type_tabs);
        }
        foreach ($config_arr AS $group_namespace => $group)
        {
            $contentBlockLeft = $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs');
            $contentBlockLeft->getTabs($group, $group_namespace);
            $this->_addLeft($contentBlockLeft);
        }
        $this->_addContent($this->getLayout()->createBlock('thememanager/adminhtml_options'));
        $this->addJsCss('thememanager-editconfig-adm');
        $this->renderLayout();
    }

    function saveAction()
    {
        $theme_id = $this->getRequest()->getParam('theme_id');
        $config = $this->getRequest()->getParams();

        Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->setConfigsByAliases($config, $theme_id);
        if (Mage::app()->getRequest()->getParam('__currentPage'))
        {
            $this->getResponse()->setRedirect($this->getUrl("*/*/editConfig", array('theme_id'=>$theme_id, '__currentPage' => Mage::app()->getRequest()->getParam('__currentPage'))));
        }
        else
        {
            $theme=Mage::getModel('thememanager/themes')->load($theme_id)->getThemenamespace();
            $this->getResponse()->setRedirect($this->getUrl("*/*/themeConfig", array('theme'=>$theme)));
        }
    }

    function savePredefinedCollectionAction()
    {
        $theme_id = $this->getRequest()->getParam('theme_id');
        $collection = $this->getRequest()->getParam('collection');
        Mage::getModel('thememanager/themeConfigData')->deleteDataByThemeId($theme_id);
        $predefined_arr = Mage::helper('thememanager/themeConfig ')->getPredefined();

        if (isset($predefined_arr[$collection]))
        {
            $config = $predefined_arr[$collection]['values'];
            Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->setConfigsByAliases($config, $theme_id);
        }
        $this->getResponse()->setRedirect($this->getUrl("*/*/editConfig", array('theme_id'=>$theme_id)));
    }

    function removeConfigAction()
    {
        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/remove'))
        {
            $theme_id = $this->getRequest()->getParam('theme_id');
            $theme_namespace = Mage::helper('thememanager')->getThemeNamespace();
            Mage::getModel('thememanager/themes')->removeTheme($theme_id);
            $this->getResponse()->setRedirect($this->getUrl("*/*/themeConfig", array('theme'=>$theme_namespace)));
        }

    }

    function resetChangesAction()
    {
        $params = $this->getRequest()->getParams();
        if (!empty($params['aliases_to_delete']))
        {
            Mage::getModel('thememanager/themeConfigData')->deleteDataByThemeId($params['theme_id'], $params['aliases_to_delete']);
        }
        $this->getResponse()->setRedirect($this->getUrl("*/*/editConfig", array('theme_id'=>$params['theme_id'])));
    }

    function getDefaultAdvancedStylingAction()
    {
        $contentBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_defaultAdvancedStyling');
        $contentBlock->prepareLayout();
        $contentBlock->setUseAjax(true);

        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideBlockName('AdvancedStyling');
        $guideBlock->setUseAjax(true);

        $this->getResponse()->setBody($contentBlock->toHtml() . $guideBlock->toHtml());
    }

    function getAdvancedStylingCssFileAction()
    {
        $content_arr = array();
        switch ($this->getRequest()->getParam('action'))
        {
            case 'getCssFileContent':
                $file_name = $this->getRequest()->getParam('file_name');
                $content = '';
                if ($file_name)
                {
                    $content = Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingCssFileContent($file_name);
                }
                $content_arr = array('css_content' => $content);
                break;
            case 'generateCss':
                if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit'))
                {
                    $css_file_name = $this->getRequest()->getParam('css_file_name');
                    $css_content = $this->getRequest()->getParam('css_content');
                    if ($css_file_name && $css_content)
                    {
                        $is_saved = Mage::getModel('thememanager/advancedStyling')->saveAdvancedStylingCssFile($css_file_name, $css_content);
                        if ($is_saved && $this->getRequest()->getParam('is_applay'))
                        {
                            $theme_id = $this->getRequest()->getParam('theme_id');
                            Mage::getModel('thememanager/themeConfigData')->saveThemeConfig('advanced_styling_custom_css_file', $css_file_name, $theme_id);
                        }
                    }
                }
                break;
            case 'UploadPattern':
                $content_arr['file_url'] = Mage::getModel('thememanager/advancedStyling')->uploadPattern();
                break;
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($content_arr));
    }

    function ajaxAction()
    {
        $contentBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_ajaxForm');
        $config_arr = $this->getConfigArray();
        $contentBlock->setConfigArr($config_arr);
        $contentBlock->setUseAjax(true);

        $params = Mage::app()->getRequest()->getParams();
        $guideBlock = $this->getLayout()->createBlock('thememanager/adminhtml_options_guideForm');
        $guideBlock->setGuideGroupName($params['group_namespace']);
        $guideBlock->setGuideBlockName($params['block_namespace']);
        $guideBlock->setUseAjax(true);

        $this->getResponse()->setBody($contentBlock->toHtml() . $guideBlock->toHtml());
    }

    function adminResetDelayAction()
    {
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('ok'=>'ok')));
    }

    function exportConfigAction()
    {
        $export_import_model = Mage::getModel('thememanager/exportImport');
        $export_files = array();

        $files_dir = $export_import_model->is_writable();

        if ($files_dir)
        {
            $config_collection = Mage::getModel('thememanager/themes')->getThemesCollection();
            if ($this->getRequest()->getParam('theme'))
            {
                $config_collection->addFieldToFilter('themenamespace', array( 'eq' => $this->getRequest()->getParam('theme')));
            }

            if ($this->getRequest()->getParam('theme_id'))
            {
                $config_collection->addFieldToFilter('theme_id', array( 'eq' => $this->getRequest()->getParam('theme_id')));
            }
            $config_collection->load();
            foreach($config_collection AS $config)
            {
                $export_files[] = $export_import_model->getConfigFile($config);
            }
        }
        else
        {
            if ($this->getRequest()->getParam('theme'))
            {
                $this->getResponse()->setRedirect($this->getUrl("*/*/themeConfig", array('theme'=>$this->getRequest()->getParam('theme'))));
            }
            if ($this->getRequest()->getParam('theme_id'))
            {
                $this->getResponse()->setRedirect($this->getUrl("*/*/editConfig", array('theme_id'=>$this->getRequest()->getParam('theme_id'))));
            }
        }

        if(!empty($export_files))
        {
            if(count(scandir($files_dir)) >3)
            {
                $filepath = $export_import_model->createZip();
            }
            else
            {
                $filepath = end($export_files);
            }
            $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                ->setHeader('Pragma', 'public', true)
                ->setHeader('Content-type', 'application/force-download')
                ->setHeader('Content-Length', filesize($filepath))
                ->setHeader('Content-Disposition', 'attachment' . '; filename=' . basename($filepath));
            $this->getResponse()->clearBody();
            $this->getResponse()->sendHeaders();
            readfile($filepath);
        }
        $export_import_model->clear();
    }

    function importConfigAction()
    {
        $this->loadLayout();
        $this->addJsCss();
        $html = '';
        $html .= $this->getLayout()->getBlock('head')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_import')->toHtml();
        $this->getResponse()->setBody($html);
    }



    function uploadImportFilesAction()
    {
        $helper = Mage::helper('thememanager');
        $return_data = array();
        $export_import_model = Mage::getModel('thememanager/exportImport');
        if ($export_import_model->is_writable())
        {
            $export_import_model->setIsParseItemsIds(false);
            $export_import_model->setIsParseConfigValues(false);
            $return_data['files'] = $export_import_model->uploadConfigFile();
            $return_data['all_types'] = Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->getAllTypes(true);
            $stores = Mage::helper('thememanager')->getStoresForm();
            $stores_sorted = array();

            $installed_thems = $helper->getUsedThems();
            foreach($stores AS $store_key => $store)
            {
                if (isset($installed_thems[$store['value']]) && $this->getRequest()->getParam('theme') == $installed_thems[$store['value']])
                {
                    $stores_sorted[$store['value']] =   $store['store']['website']['code'].'{::}'.$store['store']['code'];
                }
                else
                {
                    unset($stores[$store_key]);
                }
            }
            $return_data['stores'] = array_values($stores);

            $themes_collection = Mage::getModel('thememanager/themes')->getThemesCollection();
            foreach ($themes_collection AS $theme)
            {
                $store_id = $theme->getStoreId();
                $return_data['themes'][] =array(
                                                'name' => $theme->getName()
                                                , 'store' => isset($stores_sorted[$store_id]) ? $stores_sorted[$store_id] : 'admin{::}admin'
                                                , 'type' => $theme->getType()
                                                , 'theme_id' => $theme->getThemeId()
                                            );
            }
        }
        else
        {
            $return_data['errors'] = $export_import_model->getErrorMsg();
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($return_data));
        return;
    }

    function checkFile()
    {
        $selected_items = $this->getRequest()->getParam('file_path');
        $export_import_model = Mage::getModel('thememanager/exportImport');
        if ($selected_items)
        {
            $export_import_model->setSelectedEntityIdGrid($selected_items);
        }
    }

    function importProductSelectorAction()
    {
        $this->loadLayout();
        $this->addJsCss();
        $this->checkFile();
        $html = $this->getLayout()->getBlock('head')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_product')->setUri('*/*/importProductSelector')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_import_popup')->setPopupType('list')->toHtml();
        $this->getResponse()->setBody($html);
    }

    function importCategorySelectorAction()
    {
        $this->loadLayout();
        $this->addJsCss();
        $params = array();

        $cat_list = Mage::app()->getRequest()->getParam('cat_list');
        if ($cat_list)
        {
            $items = array_flip(array_unique(explode(',', $cat_list)));
            Mage::register('SelectedEntityIdGrid', $items);
            $params = array('cat_list'=>$cat_list);
        }
        else
        {
            $this->checkFile();
        }

        $html = $this->getLayout()->getBlock('head')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_import_popup')->setPopupType('tree')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_category')->setUri('*/*/importCategoriesJson', $params)->toHtml();
        $this->getResponse()->setBody($html);
    }

    public function importCategoriesJsonAction()
    {
        $cat_list = Mage::app()->getRequest()->getParam('cat_list');
        $params = array();

        if ($cat_list)
        {
            $items = array_flip(array_unique(explode(',', $cat_list)));
            Mage::register('SelectedEntityIdGrid', $items);
            $params = array('cat_list'=>$cat_list);
        }
        else
        {
            $this->checkFile();
        }

        $categorytabId = Mage::app()->getRequest()->getParam('category');
        $categorytab = Mage::getModel('catalog/category')->load($categorytabId);
        Mage::register('categorytab_data', $categorytab);
        $this->getResponse()->setBody($this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_category')
                        ->setUri('*/*/importCategoriesJson', $params)
                        ->getCategoryChildrenJson($this->getRequest()->getParam('category')));
    }



    function importCmsPageSelectorAction()
    {
        $this->loadLayout();
        $this->addJsCss();
        $store_id = $this->getRequest()->getParam('store_id');
        $this->checkFile();
        $html = $this->getLayout()->getBlock('head')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_options_tabs_cmsPageTable')->setUsedStoreId($store_id)->setUri('*/*/importCmsPageSelector')->toHtml();
        $html .= $this->getLayout()->createBlock('thememanager/adminhtml_import_popup')->setPopupType('list')->toHtml();
        $this->getResponse()->setBody($html);
    }


    function importDataAction()
    {
        $this->loadLayout();

        $importData = Mage::app()->getRequest()->getParam('importData');
        $theme = Mage::app()->getRequest()->getParam('theme');
        $importData2 = Mage::app()->getRequest()->getParams();
        if (!$importData)
        {
            return false;
        }
        $importData = json_decode($importData, true);

        $export_import_model = Mage::getModel('thememanager/exportImport');

        foreach($importData AS $data)
        {
            $export_import_model->importData($data, $theme);
        }
    }


    private  function addJsCss($bodyClass = false)
    {
        if ($bodyClass && $root = $this->getLayout()->getBlock('root'))
        {
            $root->addBodyClass($bodyClass);
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->addItem('addJs', 'varien/form.js');
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'thememanager/adminhtml/scripts.js');
        $this->getLayout()->getBlock('head')->addItem('skin_css', 'thememanager/adminhtml/styles.css');
        $this->getLayout()->getBlock('head')->addItem('skin_css', 'thememanager/adminhtml/font-awesome-4.3.0/css/font-awesome.min.css');
        $this->getLayout()->getBlock('head')->addItem('addJs', 'prototype/window.js');
        $this->getLayout()->getBlock('head')->addItem('js_css', 'prototype/windows/themes/default.css');
        $this->getLayout()->getBlock('head')->addItem('skin_css', 'lib/prototype/windows/themes/magento.css');
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'thememanager/adminhtml/jquery-1.11.2.min.js');
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'thememanager/adminhtml/jquery-ui.js');
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'thememanager/adminhtml/advanced_styling.js');
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'thememanager/adminhtml/jqColorPicker.min.js');
        $theme_id = $this->getRequest()->getParam('theme_id');
        if($theme_id)
        {
            $theme=Mage::getModel('thememanager/themes')->load($theme_id)->getThemenamespace();
            $file_path =Mage::getDesign()->getSkinBaseDir() . DS . 'thememanager'.DS.'adminhtml'.DS.$theme.'-styles.css';
            if (file_exists($file_path))
            {
                $this->getLayout()->getBlock('head')->addItem('skin_css', 'thememanager/adminhtml/'.$theme.'-styles.css');
            }
        }
    }









}
