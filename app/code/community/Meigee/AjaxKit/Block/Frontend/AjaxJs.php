<?php

class Meigee_AjaxKit_Block_Frontend_AjaxJs extends Mage_Core_Block_Abstract
{
    private $submodules_configs = array();
    private $submodules_html = '';
    private static $is_first = true;


    function __construct()
    {
        if(self::$is_first)
        {
            Mage::app()->getLayout()->getBlock('head')->addItem('js', 'meigee/ajaxkit/main.js');
            Mage::app()->getLayout()->getBlock('head')->addItem('skin_css', 'ajaxkit/main.css');
            $config_model  = Mage::getModel('ajaxKit/configsReader');
            $statuses = $config_model ->getConfigStatuses();
            $config_js_arr = $config_model->getConfigAddJs();
            $config_css_arr = $config_model->getConfigAddCss();
            $config_blocks_arr = $config_model->getConfigBlocks();
            foreach ($statuses AS $submodule => $status)
            {
                if ($status)
                {
                    $this->submodules_configs[$submodule] = $config_model ->getSubmoduleConfigValues($submodule);
                    $this->addHtmlElements('js', $config_js_arr, $submodule);
                    $this->addHtmlElements('css', $config_css_arr, $submodule);
                    $submodules_html_arr = $this->addHtmlElements('block', $config_blocks_arr, $submodule);
                    $this->submodules_html .= implode('', $submodules_html_arr);
                }
            }
        }
//        $this->submodules_configs['main']['url'] = $this->getUrl('ajaxKit', array('_forced_secure' => true));
        $this->submodules_configs['main']['url'] = $this->getUrl('ajaxKit', array('_forced_secure' => Mage::app()->getStore()->isCurrentlySecure()));
        $this->submodules_configs['main']['uenc'] = Mage::helper('core')->urlEncode($this->helper('core/url')->getCurrentUrl());
        $this->submodules_configs['main']['parent']['module'] = Mage::app()->getRequest()->getModuleName();
        $this->submodules_configs['main']['parent']['controller'] = Mage::app()->getRequest()->getControllerName();
    }

    function addHtmlElements($el_name, $config_arr, $submodule)
    {
        $result = array();
        if (isset($config_arr[$submodule]))
        {
            $_arr = (array)$config_arr[$submodule];
            foreach (array('base', 'extended') AS $type)
            {
                if (!isset($_arr[$type]))
                {
                    continue;
                }

                $type_arr = (array)$_arr[$type]->$el_name;
                foreach ($type_arr AS $el)
                {
                    switch ($el_name)
                    {
                        case'js':
                            Mage::app()->getLayout()->getBlock('head')->addItem('js', 'meigee/ajaxkit/'.$el);
                            break;

                        case'css':
                            Mage::app()->getLayout()->getBlock('head')->addItem('skin_css', 'ajaxkit/'.$el);
                            break;

                        case'block':
                            $result[] = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_'.$el)->setConfigs($this->submodules_configs[$submodule])->_toHtml();
                            break;
                    }
                }
            }
        }
        return $result;
    }

    function issetSubmodulesConfig($submodule)
    {
        if (isset($this->submodules_configs[$submodule]))
        {
            return $this->submodules_configs[$submodule];
        }
        return false;
    }

    protected function _toHtml()
    {
        if(self::$is_first)
        {
            self::$is_first = false;
            $used_js_css = array();
            Mage::getModel('ajaxKit/updateLayout')->getLayoutJsCss($used_js_css);
            $this->submodules_configs['main']['js_css'] = $used_js_css;
            return
                "<script type='text/javascript'>//<![CDATA[
                     var AjaxKitConfig = JSON.parse('" . Mage::helper('core')->jsonEncode($this->submodules_configs) . "');
                // ]]></script>
                " . $this->submodules_html ."
                <script type='text/javascript'>//<![CDATA[
                     AjaxKitMain.initSubmodules();
                // ]]></script>
                ";
        }
        return '';
    }
}