<?php
class Meigee_Thememanager_Rewrite_Core_Layout extends Mage_Core_Model_Layout
{
    protected static $checked_versions = array();
    private static $logo_data_info = false;

    function __construct()
    {
        if (Mage::app()->getRequest()->getParam('isAjax') && Mage::registry('meigee_ajax'))
        {
            $meigee_ajax = Mage::registry('meigee_ajax');
            $module = $meigee_ajax['module'];
            $controller = $meigee_ajax['controller'];
        }
        else
        {
            $module = Mage::app()->getRequest()->getModuleName();
            $controller = Mage::app()->getRequest()->getControllerName();
        }
//        if ('admin' == $module && 'thememanager' == $controller)
        if ('thememanager' == $controller)
        {
            $module = 'thememanager';
            $controller = 'adminhtml_thememanager';
        }

        $controller_arr = explode('_', $controller);
        $controller_arr = array_map('ucfirst', $controller_arr);
        $imploded_controller_arr = implode('_', $controller_arr);
        $model_file = Mage::getConfig()->getModuleDir('', 'Meigee_Thememanager').DS . 'Model'.DS .'PageTypeConfigs'.DS .ucfirst($module).DS .implode(DS, $controller_arr) .'.php';
        $model_file_compiled = __DIR__.DS . 'Meigee_Thememanager_Model_PageTypeConfigs_'.ucfirst($module).'_'.$imploded_controller_arr .'.php';

        if ((is_file($model_file) || is_file($model_file_compiled)) && class_exists('Meigee_Thememanager_Model_PageTypeConfigs_'.ucfirst($module).'_'.$imploded_controller_arr))
        {
            $model = Mage::getModel('thememanager/pageTypeConfigs_'.$module.'_'.$controller);
        }
        else
        {
            if ('cms' == strtolower($module))
            {
                $model = Mage::getModel('thememanager/pageTypeConfigs_'.$module.'_Page');
            }
            else
            {
                $model = Mage::getModel('thememanager/pageTypeConfigs_default');
            }
        }
        $model->getInstance();
        parent::__construct();
    }

    function checkVersion($versions)
    {
        if (!is_array($versions))
        {
            $versions = (array)$versions;
        }

        foreach ($versions AS $version)
        {
            if (!isset(self::$checked_versions[$version]))
            {
                $magento_version =  preg_replace('/[^0-9,]/', '', Mage::getVersion());
                $version_to_check = preg_replace('/[^0-9,]/', '', (string)$version);
                self::$checked_versions[$version] = ($version_to_check == substr($magento_version, 0, strlen($version_to_check)));
            }
            if (self::$checked_versions[$version])
            {
                return self::$checked_versions[$version];
            }
        }
        return false;
    }

    function checkVersionUse($node)
    {
        if (isset($node['if_version']))
        {
            $if_version = (string)$node['if_version'];
            if (!$this->checkVersion($if_version))
            {
                return array('is_return'=>true, 'node'=>$this);
            }
        }

        if (isset($node['check_version']) && $node['check_version'] && !empty($node->if_version))
        {
            $is_use_default = true;
            foreach ($node->if_version AS $version_node)
            {
                if ($this->checkVersion((string)$version_node['is']))
                {
                    $node = $version_node;
                    $is_use_default = false;
                    break;
                }
            }
            if ($is_use_default && !empty($node->default))
            {
                $node = $node->default;
            }
        }
        return array('is_return'=>false, 'node'=>$node);
    }

    protected function _generateAction($node, $parent)
    {
        $version_checker = $this->checkVersionUse($node);
        if ($version_checker['is_return'])
        {
            return $version_checker['node'];
        }
        $node = $version_checker['node'];

        if (isset($node['mconfig']))
        {
            $configAlias = (string)$node['mconfig'];
            $config_node = Mage::helper('thememanager/themeConfig')->getThemeConfigByAliase($configAlias);

            if ($config_node)
            {
				if (is_array($config_node['result']))
                {
                    if (isset($node['mconfig_key']))
                    {
                        $mconfig_key = (string)$node['mconfig_key'];
                        if (isset($config_node['result']['value'][$mconfig_key]) && $config_node['result']['value'][$mconfig_key]['value'])
                        {
                            $node_clone = clone $node;
                            $parent_clone = clone $parent;
                            return $this->processingGenerateAction($node_clone, $parent_clone, $config_node['result']['value'][$mconfig_key]['type_frontend'], $config_node['result']['value'][$mconfig_key]['value']);
                        }
                        return $this;
                    }

                    $generated = array();

                    foreach($config_node['result']['value'] AS $result)
                    {
                        $node_clone = clone $node;
                        $parent_clone = clone $parent;
                        $generated[] = $this->processingGenerateAction($node_clone, $parent_clone, $result['type_frontend'], $result['value']);
                    }
                    return $this;
                }
                else
                {
                    return $this->processingGenerateAction($node, $parent, $config_node['type_frontend'], $config_node['result']);
                }
            }
            else
            {
                return $this;
            }
        }

        if (isset($node['method']) && 'addCustomCss' == $node['method'])
        {
            $file_names = Mage::getModel('thememanager/advancedStyling')->getHttpCssFilePath();
            foreach ((array)$file_names AS $file_name)
            {
                if(!empty($file_name))
                {
                    $this->getBlock('head')->addItem('link_rel', trim($file_name), "rel='stylesheet' type='text/css'");
                }
            }
            foreach ($this->getBlock('head')->getItems() AS $item)
            {
                if (empty($item['type']) || empty($item['name']))
                {
                    $this->getBlock('head')->removeItem($item['type'], $item['name']);
                }
            }
            return $this;
        }
        return $this->processingGenerateAction($node, $parent);
    }

    private function processingGenerateAction($node, $parent, $type = null, $result = null)
    {
        $result = '__empty__' == $result ? false : $result;
		if (!is_null($type))
        {
            switch($type)
            {
                case 'setTemplate':
                    if (!$result) { return $this; }
                    $node['method'] = 'setTemplate';
                    $node->template = $result;
                    break;
                case 'Bool':
                    if (!$result) { return $this; }
                    break;
                case 'invertBool':
                    if ($result)
                    {
                        return $this;
                    }
                    break;
                case 'addItem':
                    if (!$result) { return $this; }
                    $node['method'] = 'addItem';
                    $node->type = 'skin_js';
                    $node->name = $result;
                    break;
                case 'addJs':
                    if (!$result) { return $this; }
                    $node['method'] = 'addJs';
                    $node->script = $result;
                    break;
                case 'addCss':
                    if (!$result) { return $this; }
                    $node['method'] = 'addCss';
                    $node->stylesheet = $result;
                    break;
                case 'addBodyClass':
                    if (!$result) { return $this; }
                    $node['method'] = 'addBodyClass';
                    $node->classname = $result;
                    break;
                default :
                    return $this;
            }
        }

        return parent::_generateAction($node, $parent);
    }


    protected function _generateBlock($node, $parent)
    {
        $version_checker = $this->checkVersionUse($node);
        if ($version_checker['is_return'])
        {
            return $version_checker['node'];
        }
        $node = $version_checker['node'];
        if (isset($node['mconfig']))
        {
            $configAlias = (string)$node['mconfig'];
            $config_node = Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase($configAlias);
            $mconfig_key = '';

            if (isset($node['mconfig_key']))
            {
                $mconfig_key = (string)$node['mconfig_key'];
                if (!isset($config_node[$mconfig_key]) || !is_array($config_node[$mconfig_key]) )
                {
                    return $this;
                }
                $config_node = $config_node[$mconfig_key]['value'];
            }
            if($config_node)
            {
                if (is_array($config_node))
                {
                    $generated = array();
                    foreach($config_node AS $cn)
                    {
                        $node_clone = clone $node;
                        $parent_clone = clone $parent;
                        $generated[] = $this->processingGenerateBlock($node_clone, $parent_clone, $cn['value']);
                    }
                    return $generated;
                }
                else
                {
                    return $this->processingGenerateBlock($node, $parent, $config_node);
                }
            }
            else
            {
                return $this;
            }
        }
        return parent::_generateBlock($node, $parent);
    }

    private function processingGenerateBlock($node, $parent, $config_node)
    {
        $node['template'] = $config_node;
        return parent::_generateBlock($node, $parent);
    }


    function getMConfigResultByAlias($alias, $params = array())
    {
        $mconfig = Mage::helper('thememanager/themeConfig')->getThemeConfigByAliase($alias);
        if ($mconfig)
        {
            $mconfig['result'] = '__empty__' == $mconfig['result'] ? false : $mconfig['result'];
			if(isset($mconfig['type_frontend'])){
				switch($mconfig['type_frontend'])
				{
					case 'getBlockHtml':
						$return = '';
						if ($mconfig['result'])
						{
							$return = $this->createBlock('cms/block')->setBlockId($mconfig['result'])->toHtml();
						}
						return $return;
						break;
					case 'getPageHtml':
						$return = '';
						if ($mconfig['result'])
						{
							$return = $this->createBlock('cms/page')->setBlockId($mconfig['result'])->toHtml();
						}
						return $return;
						break;
					case 'getData':
						$return = $mconfig['result'];
						return $return;
						break;
					case 'getMediaImage':
						$return = $mconfig['result'];
						if ($return)
						{
							$baseUrl = Mage::helper('thememanager/themeConfig')->getBaseUrlS();
							$return = $baseUrl.$mconfig['result'];
						}
						return $return;
						break;
					case 'Bool':
						if (!$mconfig['result'])
						{
							return $this;
						}
						break;
					case 'invertBool':
						if ($mconfig['result'])
						{
							return $this;
						}
						break;
					case 'defaultFrontendBlock':
						if ($mconfig['result'])
						{
							$frontend_block = $mconfig['result'];
							$return = $this->createBlock('thememanager/frontend_'.$frontend_block)->_getHtml($params);
							return $return;
						}
						break;

					case 'retinaImage':
						$mconfig_x2 = Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase($alias."_X2");
						$baseUrl = Mage::helper('thememanager/themeConfig')->getBaseUrlS();
						// cdn fix //
						$mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
						$mediaUrlLength = strlen($mediaUrl);
						$strim_val = mb_strimwidth($mconfig['result'], 0, $mediaUrlLength);

						$mediaUrl == $strim_val ? $baseUrl = '' : $baseUrl = $baseUrl;
						$skin_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
						$is_cdn = strpos($skin_url, 'cdn-');
						// !cdn fix //
						if ($mconfig['result'] && $mconfig_x2)
						{
							$value = $mconfig['result'];
							if($is_cdn == true) {
								$baseUrl = $skin_url;
								$mediaUrl == $strim_val ? $baseUrl = '' : $baseUrl = $baseUrl;
								$value = substr($value, 5);
							} else {
								$baseUrl = $baseUrl;
							}
							 $return = Mage::helper('thememanager/images')->setPresetImages($baseUrl.$value, $baseUrl.$mconfig_x2);
							 // $return = Mage::helper('thememanager/images')->setPresetImages($baseUrl.$mconfig['result'], $baseUrl.$mconfig_x2);
							return $return;
						}
						return false;
						break;
				}
			}
        }
    }

    function isHomePage()
    {
        return Mage::getBlockSingleton('page/html_header')->getIsHomePage();
    }

    function getLogoDataInfo($is_alt = false)
    {
        if (false === self::$logo_data_info)
        {
            if (!$this->isHomePage())
            {
                $second_logo = $this->getMConfigResultByAlias('second_logo');
                if ($second_logo)
                {
                    self::$logo_data_info = array(
                        'logo' => $second_logo->getImageHtmlAttributte()
                    , 'logo_alt' => Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('second_logo_alt')
                    );
                    return;
                }
            }

            $base_logo = $this->getMConfigResultByAlias('logo');
            if ($base_logo)
            {
                self::$logo_data_info = array(
                    'logo' => $base_logo->getImageHtmlAttributte()
                , 'logo_alt' => Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('logo_alt')
                );
                return;
            } 
            self::$logo_data_info = array(
//                                'logo' => $this->getSkinUrl('images/logo.png')
                                'logo' => Mage::getDesign()->getSkinUrl('images/logo.png')
//                            , 'logo_alt' =>  $this->getLogoAlt()
                            , 'logo_alt' =>   Mage::getStoreConfig('design/header/logo_alt')
                            );
        }
    }

    function getLogoData($is_alt = false)
    {
        $this->getLogoDataInfo();
        if ($is_alt)
        {
            return self::$logo_data_info['logo_alt'];
        }
        return self::$logo_data_info['logo'];
    }
}




